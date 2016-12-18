<?php
/**
 * Gestion du formulaire de d'édition de oeuvre
 *
 * @plugin     Galerie d&#039;art
 * @copyright  2014
 * @author     Sylvain Breil
 * @licence    GNU/GPL
 * @package    SPIP\Catexpo\Formulaires
 */

if (!defined('_ECRIRE_INC_VERSION')) return;

include_spip('inc/actions');
include_spip('inc/editer');

function in_array_r($needle, $haystack, $strict = false) {
    foreach ($haystack as $item) {
        if (($strict ? $item === $needle : $item == $needle) || (is_array($item) && in_array_r($needle, $item, $strict))) {
            return true;
        }
    }

    return false;
}

/**
 * Identifier le formulaire en faisant abstraction des paramètres qui ne représentent pas l'objet edité
 *
 * @param int|string $id_oeuvre
 *     Identifiant du oeuvre. 'new' pour un nouveau oeuvre.
 * @param string $retour
 *     URL de redirection après le traitement
 * @param string $associer_objet
 *     Éventuel `objet|x` indiquant de lier le oeuvre créé à cet objet,
 *     tel que `article|3`
 * @param int $lier_trad
 *     Identifiant éventuel d'un oeuvre source d'une traduction
 * @param string $config_fonc
 *     Nom de la fonction ajoutant des configurations particulières au formulaire
 * @param array $row
 *     Valeurs de la ligne SQL du oeuvre, si connu
 * @param string $hidden
 *     Contenu HTML ajouté en même temps que les champs cachés du formulaire.
 * @return string
 *     Hash du formulaire
 */
// function formulaires_editer_oeuvre_identifier_dist($id_oeuvre='oui', $retour='', $associer_objet='', $lier_trad=0, $config_fonc='', $row=array(), $hidden=''){
// 	return serialize(array(intval($id_oeuvre), $associer_objet));
// }

/**
 * Chargement du formulaire d'édition de oeuvre
 *
 * Déclarer les champs postés et y intégrer les valeurs par défaut
 *
 * @uses formulaires_editer_objet_charger()
 *
 * @param int|string $id_oeuvre
 *     Identifiant du oeuvre. 'new' pour un nouveau oeuvre.
 * @param string $retour
 *     URL de redirection après le traitement
 * @param string $associer_objet
 *     Éventuel `objet|x` indiquant de lier le oeuvre créé à cet objet,
 *     tel que `article|3`
 * @param int $lier_trad
 *     Identifiant éventuel d'un oeuvre source d'une traduction
 * @param string $config_fonc
 *     Nom de la fonction ajoutant des configurations particulières au formulaire
 * @param array $row
 *     Valeurs de la ligne SQL du oeuvre, si connu
 * @param string $hidden
 *     Contenu HTML ajouté en même temps que les champs cachés du formulaire.
 * @return array
 *     Environnement du formulaire
 */
function formulaires_editer_oeuvre_charger_dist($id_oeuvre='oui', $id_catalogue='', $retour='', $associer_objet='', $lier_trad=0, $config_fonc='', $row=array(), $hidden=''){
	$valeurs=array();
	$valeurs = formulaires_editer_objet_charger('oeuvre',$id_oeuvre,'',$lier_trad,$retour,$config_fonc,$row,$hidden);
	
	// Définir les options d'un select à partir de la def d'un champ de type ENUM
	$trouver_table = charger_fonction('trouver_table', 'base');
	$def=$trouver_table('oeuvres');
	$field_type=$def['field']['signature_placement'];
	preg_match('@\((.*)\)@',$field_type,$matches);	// mémo : On pourrait utiliser pour la regexp une class de caractère niée : ([^)]*)
	$valeurs['signature_placement_opt']=explode(',',str_replace("'", "", $matches[1]));
	
	if (!empty($id_catalogue)) {
		$valeurs['id_catalogue']=$id_catalogue;
	}
// 	$valeurs['redirect']=$retour;
// 	$valeurs['message_erreur']='';
// 	$valeurs['message_ok']='';
// 	$valeurs['message_info']='';

	return $valeurs;
}

/**
 * Vérifications du formulaire d'édition de oeuvre
 *
 * Vérifier les champs postés et signaler d'éventuelles erreurs
 *
 * @uses formulaires_editer_objet_verifier()
 *
 * @param int|string $id_oeuvre
 *     Identifiant du oeuvre. 'oui' pour un nouveau oeuvre.
 * @param string $retour
 *     URL de redirection après le traitement
 * @param string $associer_objet
 *     Éventuel `objet|x` indiquant de lier le oeuvre créé à cet objet,
 *     tel que `article|3`
 * @param int $lier_trad
 *     Identifiant éventuel d'un oeuvre source d'une traduction
 * @param string $config_fonc
 *     Nom de la fonction ajoutant des configurations particulières au formulaire
 * @param array $row
 *     Valeurs de la ligne SQL du oeuvre, si connu
 * @param string $hidden
 *     Contenu HTML ajouté en même temps que les champs cachés du formulaire.
 * @return array
 *     Tableau des erreurs
 */
function formulaires_editer_oeuvre_verifier_dist($id_oeuvre='oui', $id_catalogue='', $retour='', $associer_objet='', $lier_trad=0, $config_fonc='', $row=array(), $hidden=''){
	
	// Spip n'accepte pas qu'on se repose sur la valeur par défaut de MySQL : il compare ce qu'on lui a fourni et ce qui a été écrit...
	// Vérifier en prod que la valeur par défaut de date2mysql() fonctionne bien à '0000-00-00 00:00:00'
	$date_real=_request('date_real');
	$date_estimee_debut=_request('date_estimee_debut');
	$date_estimee_fin=_request('date_estimee_fin');
	set_request('date_real',date2mysql($date_real));
	set_request('date_estimee_debut',date2mysql($date_estimee_debut));
	set_request('date_estimee_fin',date2mysql($date_estimee_fin));

	// Validation du form : Champs obligatoires
	// Nomenclature auto
	$obligatoire=array();
	if (intval($id_catalogue)) {
		$nomenc=sql_fetsel('nomenclature_stricte', 'spip_catalogues', 'id_catalogue='.$id_catalogue);
		$nomenc=unserialize($nomenc['nomenclature_stricte']);
		if ($nomenc[auto]==='Oui') {
			if ($nomenc['Constante1']) {
				$obligatoire[]=$nomenc['Constante1'];
			}
			if ($nomenc['Constante2']) {
				$obligatoire[]=$nomenc['Constante2'];
			}
		}
	}
	
	$res=formulaires_editer_objet_verifier('oeuvre',$id_oeuvre,$obligatoire);

	// Validation du form : incohérences de la saisie des dates
	if ($date_real && ($date_estimee_debut || $date_estimee_fin)) {
		$res['date_real']='Vous ne pouvez saisir simultanément une date de réalisation et une date approximative';
	}
	
	// Validation du form : numéro_d'inventaire doit être unique
	$nums_inventaire=intval($id_oeuvre)?sql_allfetsel('numero_inventaire','spip_oeuvres','id_oeuvre<>'.$id_oeuvre):sql_allfetsel('numero_inventaire','spip_oeuvres');
	if (_request('numero_inventaire') && in_array_r(_request('numero_inventaire'),$nums_inventaire))
		$res['numero_inventaire']='Ce numero d\'inventaire est déjà attribué';

	// Validation du form : id_catalogue, id_notice_longue et id_oeuvres_liees
	// si le picker génère un <input> : champs multiples reçus en tant qu'array 
	// si le picker supprime l'<input> : set_request(id__,'') pour effacement en base
	if (is_array($id_catalogue=$_REQUEST['catalogue_parent'])) {
		$id_catalogue=explode('|',$id_catalogue[0]);
		set_request('id_catalogue',$id_catalogue[1]);
	}
	else if (!$id_catalogue) {
		set_request('id_catalogue','');
	}
	// On utilise $_REQUEST car $_POST pose problème avec champ id_xx dont la valeur n'est pas un entier : @ http://forum.spip.net/fr_256961.html
	$id_notice_longue=$_REQUEST['id_notice_longue'];
	if (is_array($id_notice_longue)) {
		set_request('id_notice_longue',serialize($id_notice_longue));
	}
	else if (!$id_notice_longue) {
		set_request('id_notice_longue', '');
	}

	$id_oeuvres_liees=$_REQUEST['id_oeuvres_liees'];
	if (is_array($id_oeuvres_liees)) {
		set_request('id_oeuvres_liees',serialize($id_oeuvres_liees));
	}
	else if (!$id_oeuvres_liees) {
		set_request('id_oeuvres_liees','');
	}
	
	return $res;
}

/**
 * Traitement du formulaire d'édition de oeuvre
 *
 * Traiter les champs postés
 *
 * @uses formulaires_editer_objet_traiter()
 *
 * @param int|string $id_oeuvre
 *     Identifiant du oeuvre. 'oui' pour un nouveau oeuvre.
 * @param string $retour
 *     URL de redirection après le traitement
 * @param string $associer_objet
 *     Éventuel `objet|x` indiquant de lier le oeuvre créé à cet objet,
 *     tel que `article|3`
 * @param int $lier_trad
 *     Identifiant éventuel d'un oeuvre source d'une traduction
 * @param string $config_fonc
 *     Nom de la fonction ajoutant des configurations particulières au formulaire
 * @param array $row
 *     Valeurs de la ligne SQL du oeuvre, si connu
 * @param string $hidden
 *     Contenu HTML ajouté en même temps que les champs cachés du formulaire.
 * @return array
 *     Retours des traitements
 */
function formulaires_editer_oeuvre_traiter_dist($id_oeuvre='oui', $id_catalogue='', $retour='', $associer_objet='', $lier_trad=0, $config_fonc='', $row=array(), $hidden=''){
	$old_value=sql_fetsel('id_oeuvres_liees','spip_oeuvres','id_oeuvre='.$id_oeuvre);
	$res = formulaires_editer_objet_traiter('oeuvre',$id_oeuvre,'',$lier_trad,$retour,$config_fonc,$row,$hidden);

	// mise à jour des numéros d'ordre (y compris sur création : numero_ordre prend alors la valeur supérieure à la valeur la plus haute)
	$id=$res['id_oeuvre']?$res['id_oeuvre']:$id_oeuvre;
	include_spip('action/attribuer_numeros_ordre');
	if ($numero_inventaire=attribuer_numeros_inventaire('',$id)) {// après mise à jour de l'ordre, $num_inventaire n'est retourné que si le mode auto est actif
		sql_update('spip_oeuvres',array('numero_inventaire'=>sql_quote($numero_inventaire)),'id_oeuvre='.$id);
	}

	// Mise à jour des documents liés (Si nomenclature stricte : on doit faire correspondre le nom de fichier au numero d'inventaire)
	if (intval($id_oeuvre))	{ 	// on n'actualise pas les noms de fichier sur une création : pas de docs liés
		include_spip('action/controler_fichiers_catalogue');
		$controler_documents=actualiser_noms_fichiers_catalogue($id_oeuvre);
		$res['message_info']=$controler_documents['message_erreur']?$controler_documents['message_erreur']:$controler_documents['message_ok'];
	}
	
	// Gestion bidirectionnelle des liens entre oeuvres sans passer par table lien (archaïque, mais permet de rester dans le contexte 'oeuvre' par rapport à elle-même)
	$id_oeuvres_liees=_request('id_oeuvres_liees');
	if (!empty($id_oeuvres_liees) || !empty($old_value)) {
		foreach(unserialize($id_oeuvres_liees) as $key => $value) {
			$update_id=substr($value,strpos($value,'|')+1);
			$oeuvres_to_update[]=$update_id;
		}
		// Lien à supprimer
		foreach (unserialize($old_value['id_oeuvres_liees']) as $key=>$value) {
			$old_id=substr($value,strpos($value,'|')+1);
			if (!in_array($old_id,$oeuvres_to_update)) {
				$current_value=sql_fetsel('id_oeuvres_liees','spip_oeuvres','id_oeuvre='.$old_id);
				$current_value=unserialize($current_value['id_oeuvres_liees']);
				$key_to_delete=array_search('oeuvre|'.$id,$current_value);
				if ($key_to_delete!==false) unset($current_value[$key_to_delete]);
				if (!empty($current_value)) {
					sql_updateq('spip_oeuvres',array('id_oeuvres_liees'=>serialize($current_value)),'id_oeuvre='.$old_id);
				}
				else {
					sql_updateq('spip_oeuvres',array('id_oeuvres_liees'=>''),'id_oeuvre='.$old_id);
				}
			}
		}
		
		// Nouveau lien
		foreach($oeuvres_to_update as $key=>$value) {
			$current_value=sql_fetsel('id_oeuvres_liees','spip_oeuvres','id_oeuvre='.$value);
			$current_value=unserialize($current_value['id_oeuvres_liees']);
			if (!in_array($new_val='oeuvre|'.$id, $current_value)) {
				$current_value[]=$new_val;
			}
			sql_updateq('spip_oeuvres',array('id_oeuvres_liees'=>serialize($current_value)),'id_oeuvre='.$value);
		}
	}

	
	// Un lien a prendre en compte ?
//	$associer_objet=$champs['id_notice_longue'];
//	if ($associer_objet AND $id_oeuvre = $res['id_oeuvre']) {
//		list($objet, $id_objet) = explode('|', $associer_objet);
//
//		if ($objet AND $id_objet AND autoriser('modifier', $objet, $id_objet)) {
			
//			include_spip('action/editer_liens');
//			objet_associer(array('oeuvre' => $id_oeuvre), array($objet => $id_objet));
//			if (isset($res['redirect'])) {
//				$res['redirect'] = parametre_url ($res['redirect'], "id_lien_ajoute", $id_oeuvre, '&');
//			}
//		}
//	}

	// Offrir la possiblilité d'ajouter des docs immédiatement après la création
	$retour=trim($retour);
	if (!intval($id_oeuvre) AND intval($id)) { // C'est un retour de création
		$res['redirect']=generer_url_ecrire('oeuvre_edit', 'id_oeuvre='.$id.'&id_catalogue='.$id_catalogue.'&ajouter_documents=oui&redirect='.$retour);
	}
	else {
		$res['redirect']=$retour?$retour.'&message_info='.$res['message_info']:generer_url_entite($id,'oeuvre');
	}
   	include_spip('inc/invalideur');
    suivre_invalideur('');

	return $res;
}


?>