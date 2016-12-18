<?php
/**
 * Gestion du formulaire de d'édition de catalogue
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

/**
 * Identifier le formulaire en faisant abstraction des paramètres qui ne représentent pas l'objet edité
 *
 * @param int|string $id_catalogue
 *     Identifiant du catalogue. 'new' pour un nouveau catalogue.
 * @param string $retour
 *     URL de redirection après le traitement
 * @param int $lier_trad
 *     Identifiant éventuel d'un catalogue source d'une traduction
 * @param string $config_fonc
 *     Nom de la fonction ajoutant des configurations particulières au formulaire
 * @param array $row
 *     Valeurs de la ligne SQL du catalogue, si connu
 * @param string $hidden
 *     Contenu HTML ajouté en même temps que les champs cachés du formulaire.
 * @return string
 *     Hash du formulaire
 */
function formulaires_editer_catalogue_identifier_dist($id_catalogue='new', $retour='', $lier_trad=0, $config_fonc='', $row=array(), $hidden=''){
	return serialize(array(intval($id_catalogue)));
}

/**
 * Chargement du formulaire d'édition de catalogue
 *
 * Déclarer les champs postés et y intégrer les valeurs par défaut
 *
 * @uses formulaires_editer_objet_charger()
 *
 * @param int|string $id_catalogue
 *     Identifiant du catalogue. 'new' pour un nouveau catalogue.
 * @param string $retour
 *     URL de redirection après le traitement
 * @param int $lier_trad
 *     Identifiant éventuel d'un catalogue source d'une traduction
 * @param string $config_fonc
 *     Nom de la fonction ajoutant des configurations particulières au formulaire
 * @param array $row
 *     Valeurs de la ligne SQL du catalogue, si connu
 * @param string $hidden
 *     Contenu HTML ajouté en même temps que les champs cachés du formulaire.
 * @return array
 *     Environnement du formulaire
 */
function formulaires_editer_catalogue_charger_dist($id_catalogue='new', $retour='', $lier_trad=0, $config_fonc='', $row=array(), $hidden=''){
	$valeurs = formulaires_editer_objet_charger('catalogue',$id_catalogue,'',$lier_trad,$retour,$config_fonc,$row,$hidden);
	
	if (is_array($nomenc_array1=unserialize($valeurs['nomenclature_existante']))) {
		foreach ($nomenc_array1 as $key => $value) {
			$valeurs['nomenclature_existante['.$key.']']=$value;
		}
	}
	if (is_array($nomenc_array2=unserialize($valeurs['nomenclature_stricte']))) {
		foreach ($nomenc_array2 as $key => $value) {
			$valeurs['nomenclature_stricte['.$key.']']=$value;
		}
	}
	return $valeurs;
}

/**
 * Vérifications du formulaire d'édition de catalogue
 *
 * Vérifier les champs postés et signaler d'éventuelles erreurs
 *
 * @uses formulaires_editer_objet_verifier()
 *
 * @param int|string $id_catalogue
 *     Identifiant du catalogue. 'new' pour un nouveau catalogue.
 * @param string $retour
 *     URL de redirection après le traitement
 * @param int $lier_trad
 *     Identifiant éventuel d'un catalogue source d'une traduction
 * @param string $config_fonc
 *     Nom de la fonction ajoutant des configurations particulières au formulaire
 * @param array $row
 *     Valeurs de la ligne SQL du catalogue, si connu
 * @param string $hidden
 *     Contenu HTML ajouté en même temps que les champs cachés du formulaire.
 * @return array
 *     Tableau des erreurs
 */
function formulaires_editer_catalogue_verifier_dist($id_catalogue='new', $retour='', $lier_trad=0, $config_fonc='', $row=array(), $hidden=''){
	$erreurs=array();
	if (is_array($n_stricte=_request('nomenclature_stricte'))) {
		set_request('nomenclature_stricte', serialize($n_stricte));
		
		// Attention si nomenc_stricte : préciser valeurs
		if ($n_stricte['utiliser']=='Oui') {
			if (empty($n_stricte['Préfixe']) && empty($n_stricte['Numéro']))
				$erreurs['nomenclature_stricte']='Il est préférable de définir au moins un préfixe et un numéro';
		}
		
		// mode auto doit être complet
		if ($n_stricte['utiliser']=='Non' && $n_stricte['auto']=='Oui')
			$erreurs['nomenclature_stricte']='Vous devez activer et paramétrer la nomenclature stricte pour utiliser l\'inventaire automatique';
	}
	
	if (is_array($n_existante=_request('nomenclature_existante'))) {
		set_request('nomenclature_existante', serialize($n_existante));
	}
	
	// On ne peut pas utiliser la fonction générique si l'un des champs postés est un tableau
	$erreurs_std=formulaires_editer_objet_verifier('catalogue',$id_catalogue,array('titre'));
	
	$erreurs=!empty($erreurs_std)?array_merge($erreurs,$erreurs_std):$erreurs;
	
	return $erreurs; 
}

/**
 * Traitement du formulaire d'édition de catalogue
 *
 * Traiter les champs postés
 *
 * @uses formulaires_editer_objet_traiter()
 *
 * @param int|string $id_catalogue
 *     Identifiant du catalogue. 'new' pour un nouveau catalogue.
 * @param string $retour
 *     URL de redirection après le traitement
 * @param int $lier_trad
 *     Identifiant éventuel d'un catalogue source d'une traduction
 * @param string $config_fonc
 *     Nom de la fonction ajoutant des configurations particulières au formulaire
 * @param array $row
 *     Valeurs de la ligne SQL du catalogue, si connu
 * @param string $hidden
 *     Contenu HTML ajouté en même temps que les champs cachés du formulaire.
 * @return array
 *     Retours des traitements
 */
function formulaires_editer_catalogue_traiter_dist($id_catalogue='new', $retour='', $lier_trad=0, $config_fonc='', $row=array(), $hidden=''){
//	refuser_traiter_formulaire_ajax();

	$res = formulaires_editer_objet_traiter('catalogue',$id_catalogue,'',$lier_trad,$retour,$config_fonc,$row,$hidden);
   	include_spip('inc/invalideur');
    suivre_invalideur('');
	return $res;
}


?>