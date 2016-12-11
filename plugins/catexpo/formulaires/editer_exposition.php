<?php
/**
 * Gestion du formulaire de d'édition de exposition
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
 * @param int|string $id_exposition
 *     Identifiant du exposition. 'new' pour un nouveau exposition.
 * @param string $retour
 *     URL de redirection après le traitement
 * @param int $lier_trad
 *     Identifiant éventuel d'un exposition source d'une traduction
 * @param string $config_fonc
 *     Nom de la fonction ajoutant des configurations particulières au formulaire
 * @param array $row
 *     Valeurs de la ligne SQL du exposition, si connu
 * @param string $hidden
 *     Contenu HTML ajouté en même temps que les champs cachés du formulaire.
 * @return string
 *     Hash du formulaire
 */
function formulaires_editer_exposition_identifier_dist($id_exposition='new', $retour='', $lier_trad=0, $config_fonc='', $row=array(), $hidden=''){
	return serialize(array(intval($id_exposition)));
}

/**
 * Chargement du formulaire d'édition de exposition
 *
 * Déclarer les champs postés et y intégrer les valeurs par défaut
 *
 * @uses formulaires_editer_objet_charger()
 *
 * @param int|string $id_exposition
 *     Identifiant du exposition. 'new' pour un nouveau exposition.
 * @param string $retour
 *     URL de redirection après le traitement
 * @param int $lier_trad
 *     Identifiant éventuel d'un exposition source d'une traduction
 * @param string $config_fonc
 *     Nom de la fonction ajoutant des configurations particulières au formulaire
 * @param array $row
 *     Valeurs de la ligne SQL du exposition, si connu
 * @param string $hidden
 *     Contenu HTML ajouté en même temps que les champs cachés du formulaire.
 * @return array
 *     Environnement du formulaire
 */
function formulaires_editer_exposition_charger_dist($id_exposition='new', $retour='', $lier_trad=0, $config_fonc='', $row=array(), $hidden=''){
	$valeurs = formulaires_editer_objet_charger('exposition',$id_exposition,'',$lier_trad,$retour,$config_fonc,$row,$hidden);
	
	// Définir les options d'un select à partir de la def d'un champ de type ENUM
	$trouver_table = charger_fonction('trouver_table', 'base');
	$def=$trouver_table('expositions');
	$field_type=$def['field']['type_expo'];
	preg_match('@\((.*)\)@',$field_type,$matches);	// mémo : On pourrait utiliser pour la regexp une class de caractère niée : ([^)]*)
	$valeurs['type_expo_options']=explode(',',str_replace("'", "", $matches[1]));
	
	return $valeurs;
}

/**
 * Vérifications du formulaire d'édition de exposition
 *
 * Vérifier les champs postés et signaler d'éventuelles erreurs
 *
 * @uses formulaires_editer_objet_verifier()
 *
 * @param int|string $id_exposition
 *     Identifiant du exposition. 'new' pour un nouveau exposition.
 * @param string $retour
 *     URL de redirection après le traitement
 * @param int $lier_trad
 *     Identifiant éventuel d'un exposition source d'une traduction
 * @param string $config_fonc
 *     Nom de la fonction ajoutant des configurations particulières au formulaire
 * @param array $row
 *     Valeurs de la ligne SQL du exposition, si connu
 * @param string $hidden
 *     Contenu HTML ajouté en même temps que les champs cachés du formulaire.
 * @return array
 *     Tableau des erreurs
 */
function formulaires_editer_exposition_verifier_dist($id_exposition='new', $retour='', $lier_trad=0, $config_fonc='', $row=array(), $hidden=''){

	return formulaires_editer_objet_verifier('exposition',$id_exposition);

}

/**
 * Traitement du formulaire d'édition de exposition
 *
 * Traiter les champs postés
 *
 * @uses formulaires_editer_objet_traiter()
 *
 * @param int|string $id_exposition
 *     Identifiant du exposition. 'new' pour un nouveau exposition.
 * @param string $retour
 *     URL de redirection après le traitement
 * @param int $lier_trad
 *     Identifiant éventuel d'un exposition source d'une traduction
 * @param string $config_fonc
 *     Nom de la fonction ajoutant des configurations particulières au formulaire
 * @param array $row
 *     Valeurs de la ligne SQL du exposition, si connu
 * @param string $hidden
 *     Contenu HTML ajouté en même temps que les champs cachés du formulaire.
 * @return array
 *     Retours des traitements
 */
function formulaires_editer_exposition_traiter_dist($id_exposition='new', $retour='', $lier_trad=0, $config_fonc='', $row=array(), $hidden=''){
	return formulaires_editer_objet_traiter('exposition',$id_exposition,'',$lier_trad,$retour,$config_fonc,$row,$hidden);
}


?>