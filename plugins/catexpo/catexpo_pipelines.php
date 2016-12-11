<?php
/**
 * Utilisations de pipelines par Galerie d&#039;art
 *
 * @plugin     Galerie d&#039;art
 * @copyright  2014
 * @author     Sylvain Breil
 * @licence    GNU/GPL
 * @package    SPIP\Catexpo\Pipelines
 */

if (!defined('_ECRIRE_INC_VERSION')) return;
	

/*
 * Un fichier de pipelines permet de regrouper
 * les fonctions de branchement de votre plugin
 * sur des pipelines existants.
 */

function catexpo_header_prive($flux) {
	$flux.='<link rel="stylesheet" href="'.find_in_path('catexpo_style.css').'">';
	// Ici l'utilisation de produire_fond_statique est criticable : le css est toujour mis en cache, peu compatible avec la logique du privé
	// Gardé pour illustration, mais il faudrait lui préférer href="'.generer_url_ecrire('catexpo_prive.css',parametres_css_prive()).'"
	$flux.='<link rel="stylesheet" href="'.produire_fond_statique(substr(find_in_theme('catexpo_prive.css.html'),strlen(_DIR_RACINE),-5),array('ltr'=>'left', 'couleur_claire'=>$GLOBALS["couleur_claire"], 'couleur_foncee'=>$GLOBALS["couleur_foncee"])).'">';
// 	$flux.='<!-- elevateZoom -->';
	$flux.='<script type="text/javascript" src="'.find_in_path('js/jquery.elevatezoom_tweak.js').'"></script>';
	$flux.='<script type="text/javascript" src="'.find_in_path('js/art_analyser.js').'"></script>';
	return $flux;
}

function catexpo_insert_head_css($flux) {
	$flux.='
	<link rel="stylesheet" href="'.find_in_path('catexpo_style.css').'">
	<link rel="stylesheet" href="'.produire_fond_statique(substr(find_in_theme('style_prive.css.html'),strlen(_DIR_RACINE),-5),array('ltr'=>'left')).'">
	';
//		<link rel="stylesheet" href="'.produire_fond_statique(substr(find_in_theme('forms.css.html'),strlen(_DIR_RACINE),-5)).'">
//	';
	return $flux;
}

function catexpo_insert_head($flux) {	
// 	$flux.='<script type="text/javascript">'.recuperer_fond('js/expo_show.js',array('ltr'=>'left')).'</script>';
	if ($_GET['page']=='catalogue_public') {
		$flux.='<!-- elevateZoom -->';
		$flux.='<script type="text/javascript" src="'.find_in_path('js/jquery.elevatezoom_tweak.js').'"></script>';
		$flux.='<script type="text/javascript" src="'.find_in_path('js/art_analyser.js').'"></script>';
	}
	return $flux;
}

function catexpo_jqueryui_plugins($flux) {
	$flux[]='jquery.ui.sortable';
	return $flux;
}

/**
 * Ajout de contenu sur certaines pages,
 * notamment des formulaires de liaisons entre objets
 *
 * @pipeline affiche_milieu
 * @param  array $flux Données du pipeline
 * @return array       Données du pipeline
 */
function catexpo_affiche_milieu($flux) {
	$texte = "";
	$e = trouver_objet_exec($flux['args']['exec']);

	// oeuvres sur les expositions
	if (!$e['edition'] AND in_array($e['type'], array('exposition'))) {
		$texte .= recuperer_fond('prive/objets/editer/liens', array(
			'table_source' => 'oeuvres',
			'objet' => $e['type'],
			'id_objet' => $flux['args'][$e['id_table_objet']]
		));
	}

	if ($texte) {
		if ($p=strpos($flux['data'],"<!--affiche_milieu-->"))
			$flux['data'] = substr_replace($flux['data'],$texte,$p,0);
		else
			$flux['data'] .= $texte;
	}

	return $flux;
}

function catexpo_affiche_gauche($flux) {
	$e = $flux['args']['exec'];
	if (strpos($e,'_edit')) $e=substr($e,0,-5);

	if ($e=='oeuvre') {
		$contexte=$flux['args'];
		$contexte['id_objet']=$flux['args']['id_oeuvre'];
		$contexte['objet']=$e;
		$flux['data'].=recuperer_fond('prive/squelettes/inclure/colonne-documents',$contexte);
	}
	return $flux;
}

function catexpo_affiche_droite($flux) {
	// Si le Plugin jQuery_upload (Doc Pneumatic) est installé, on ajoute le formulaire : (permet d'uploader/associer rapidement des images à une oeuvre)
	// on affiche autant de formulaires d'upload qu'il y a de versions définies pour le catalogue
	if (test_plugin_actif('jquery_upload')) {
		if ($flux['args']['exec']=='oeuvre_edit' || $flux['args']['exec']=='oeuvre') {
			if (intval($id_oeuvre=$flux['args']['id_oeuvre'])){
				$objet=strpos($flux['args']['exec'],'_edit')?substr($flux['args']['exec'],0,-5):$flux['args']['exec'];
			
				$infos_oeuvre_cat=sql_fetsel('oe.titre, oe.titre_secret, versions','spip_catalogues AS cat JOIN spip_oeuvres AS oe ON cat.id_catalogue=oe.id_catalogue','id_oeuvre="'.$id_oeuvre.'"');
				$versions=explode(',',$infos_oeuvre_cat['versions']);
				
				// Il n'y a parfois pas de titre
				$infos_oeuvre_cat['titre']=$infos_oeuvre_cat['titre']?$infos_oeuvre_cat['titre']:$infos_oeuvre_cat['titre_secret'];
				$contexte=array('id_objet'=>$id_oeuvre,'objet'=>$objet,'titre'=>$infos_oeuvre_cat['titre']);
				$flux['args']=array_merge($flux['args'],$contexte);
				
				$flux['data'].='<!--jQuery_upload-->';
				foreach ($versions as $version) {
					$flux['args']['id_formulaire']=supprimer_numero($version);
					$flux['args']['version']=$version;
					$flux['data'].=recuperer_fond('prive/squelettes/inc/inc-formulaires_upload', $flux['args']);
				}
				$flux['data'].='<!--/jQuery_upload-->';
			}
		}
	}
	return $flux;
}

function catexpo_renseigner_document_catalogue($flux) {
	// on fournit au plugin jQuery_upload le moyen de créer un nom de fichier à partir du numéro d'inventaire strict
	include_spip('action/parse_nomenclature');
	if ($flux['args']['objet']=='oeuvre') {
	    if ($id_oeuvre=$flux['args']['id_objet']) {
	    	$catalogue_infos=sql_fetsel('nomenclature_stricte,media','spip_catalogues AS cat JOIN spip_oeuvres AS oe ON cat.id_catalogue=oe.id_catalogue','oe.id_oeuvre='.$id_oeuvre);
	    	$flux['data']['media']=$catalogue_infos['media']?$catalogue_infos['media']:'';
	    	
	    	$nomenclature=unserialize($catalogue_infos['nomenclature_stricte']);
	    	if ($nomenclature['utiliser']=='Oui') {
		    	$inventaire_data=parse_nomenclature('',$id_oeuvre);
		    	if ($inventaire_data[$id_oeuvre]['conforme']) $filename_nomenc=$inventaire_data[$id_oeuvre]['numero_inventaire'];
		    	else $filename_nomenc=$inventaire_data[$id_oeuvre]['parsed']?$inventaire_data[$id_oeuvre]['normalized']:'';
		    	$flux['data']['nomenclature']=$filename_nomenc;
		    	$flux['data']['titre']=$inventaire_data[$id_oeuvre]['titre'];
	    	}
	    }
	}
	return $flux;
}

function catexpo_affichage_final($texte) {
	include_spip('inc/pipelines_ecrire');
	$texte=affichage_final_prive_title_auto($texte);
	return $texte;
}

/**
 * Optimiser la base de données en supprimant les liens orphelins
 * de l'objet vers quelqu'un et de quelqu'un vers l'objet.
 *
 * @pipeline optimiser_base_disparus
 * @param  array $flux Données du pipeline
 * @return array       Données du pipeline
 */
function catexpo_optimiser_base_disparus($flux){
	include_spip('action/editer_liens');
	$flux['data'] += objet_optimiser_liens(array('oeuvre'=>'*'),'*');
	return $flux;
}

?>