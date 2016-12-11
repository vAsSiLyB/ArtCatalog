<?php
function actualiser_noms_fichiers_catalogue($id_oeuvre) {
	$retour['message_erreur']='';
	$retour['message_ok']='';
	
	if (!intval($id_oeuvre)) return array('message_erreur'=>'id_oeuvre n\'est pas un entier'); 
	$documents=trouver_fichiers_documents_objet($id_oeuvre,'oeuvre');
	
	// Méta définies par l'utilisateur (influent sur le comportement de fixer_fichier)
	include_spip('inc/config');
	$inscrire_type=lire_config('jQuery_upload/inscrire_type')=='Oui'?true:false;
	$inscrire_date=lire_config('jQuery_upload/inscrire_date')=='Oui'?true:false;
	
	include_spip('action/parse_nomenclature');
	$nomenclature=parse_nomenclature('',$id_oeuvre);
	// En mode souple et stricte : on fait ce qu'on peut (on prend le num actuel s'il est conforme, ou le normalisé pour préparer un passage en mode auto)
	$numero_inventaire=$nomenclature[$id_oeuvre]['conforme']?$nomenclature[$id_oeuvre]['numero_inventaire']:$nomenclature[$id_oeuvre]['normalized'];
	// En mode auto : il faudra toujours corriger les numeros d'ordre (attribuer_numeros_ordre() ) avant de corriger les noms de fichier

	if (isset($nomenclature[$id_oeuvre]['auto'])) {
		$numero_inventaire=$nomenclature[$id_oeuvre]['generation'];
	}
	
	$versions_fichier=array(
			array(
				'orig_dir'=>'001_originaux',
				'sub_dir'=>'001_originaux',
			),
			array(
				'orig_dir'=>'',
				'sub_dir'=>'',
			),
			array(
				'orig_dir'=>'002_vignettes',
				'sub_dir'=>'002_vignettes',
			)
	);
	
	// on aura besoin de filtrer les chaines
	include_spip('inc/filtres');
	
	// fonctions natives Spip
	include_spip('inc/documents');
	// fonctions du plugin medias
	include_spip('action/editer_document');
	// gestionnaire spécifique du plugin jQuery_upload
	include_spip('action/fixer_fichier');
	
	if (!empty($documents) && is_string($numero_inventaire) && !empty($numero_inventaire)) {
		foreach ($documents as $fichier) {
			// On cherche le sous-dossier (IMG/(?)) qui était défini lors de l'upload
			preg_match('@^[^/]+@',$fichier['document']['fichier'],$matches);
			$user_dir=$matches[0];
			
			// nom de fichier actuel du document
			preg_match('@/([^/]+)/([^/]+)$@',$fichier['document']['fichier'],$matches);
			$filename=$matches[2];
			$actuel_dossier=$matches[1].'/';
			
			// Si on detecte un document dont le nom de fichier n'est pas conforme, on traite
			if ($numero_inventaire != substr($actuel_dossier,0,-1)) {
				// les metas qui servent à construire le nom de fichier
				$date_annee=$fichier['document']['date_prise_de_vue']?substr($fichier['document']['date_prise_de_vue'],0,4):'';
				$modele_cam=$fichier['document']['modele_cam']?$fichier['document']['modele_cam']:'';
				// Supprimer numero version
				$version_doc=$fichier['document']['version']?supprimer_numero($fichier['document']['version']):'';
				// idem que dans ajouter_document_catalogue, prendre soin de remplacer les espaces dans les noms de fichier (gd, etc...)
				// une fonction plus dure est désormais appliquée dans fixer_fichier 
				$dispositif=strtr($fichier['document']['dispositif'],' ','_');
				
				$resultats=array();
				foreach ($versions_fichier as $version_fichier) {
					$fixeur= new fixer_fichier(array(
						'action'=>'rename',
						'orig_path_prefix'=>'../'._NOM_PERMANENTS_ACCESSIBLES.$user_dir.'/'.$actuel_dossier,
						'dest_path_prefix'=>'../'._NOM_PERMANENTS_ACCESSIBLES,
						'user_defined_folder'=>$user_dir,
						'orig_subfolder'=>$version_fichier['orig_dir'],
						'dest_subfolder'=>$version_fichier['sub_dir'],
						'filename'=>$filename,
						'extension'=>$fichier['document']['extension'],
						'nomenclature'=>$numero_inventaire,
						'nom_version'=>$version_doc,
						'dispositif'=>$dispositif,
						'date_annee'=>$date_annee,
						'inscrire_type'=>$inscrire_type,
						'inscrire_date'=>$inscrire_date
						)
					);
					$resultats[]=$fixeur->handle_file();
					unset ($fixeur);
				}
				$nouveaux_fichiers[$fichier['document']['id_document']]=set_spip_doc($resultats[1]['fichier']);
				$nouveaux_fichiers[$fichier['vignette']['id_document']]=set_spip_doc($resultats[2]['fichier']);
			}
		}
		if (count($nouveaux_fichiers)) {
			// On inscrit les nouveaux chemins en base
			foreach ($nouveaux_fichiers as $id => $fichier) {
				document_modifier($id,array('fichier'=>$fichier));
			}
		}
	}
	else $retour['message_erreur']='Pas de documents liés';

	if (!is_string($numero_inventaire) || empty($numero_inventaire)) $retour['message_erreur']='Impossible de générer un numéro d\'inventaire conforme à la nomenclature';
		else $retour['message_ok']='Numéro d\'inventaire conforme';

	if ($nouveaux_fichiers)
		$retour=array_merge($retour,$nouveaux_fichiers);
	return $retour;
}

/**
 * Il y a un début de généricisation, mais il faudrait développer pour que cela fonctionne pour différents objets (pour l'instant : "oeuvre" uniquement)
 * 
 * @param $id_objet
 * @param $objet
 */
function trouver_fichiers_documents_objet($id_objet,$objet) {
	
	// recuperer la liste des champs extras existants
	$c_extras='';
	include_spip('cextras_pipelines');
	if (function_exists('champs_extras_objet')) {
		if ($c_extras=champs_extras_objet('spip_documents')) {
			$c_extras=', '.implode(', ',array_keys($c_extras));
		}
	}
	$fichiers=array();
	$prefix=$GLOBALS['connexions'][0][prefixe];
	$documents=sql_allfetsel(
		'doc.id_document,id_vignette,fichier,extension,dispositif,date_prise_de_vue'.$c_extras,
		'(('.$prefix.'_documents_liens AS lien JOIN '.$prefix.'_documents AS doc ON lien.id_document=doc.id_document) JOIN '.$prefix.'_'.$objet.'s AS obj ON lien.id_objet=obj.id_'.$objet.')',
		'lien.id_objet='.$id_objet.' AND objet="'.$objet.'" AND id_vignette<>0'
		);
	
	$i=0;
	foreach($documents as $document) {
		$fichiers[$i]['document']=$document;
		$fichiers[$i]['vignette']=sql_fetsel('id_document, fichier, extension','spip_documents','id_document='.$document['id_vignette']);
		$i++;
	}
	return $fichiers;
}
?>