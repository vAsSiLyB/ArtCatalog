<?php
/**
 * Fichier gérant l'installation et désinstallation du plugin Galerie d&#039;art
 *
 * @plugin     Galerie d&#039;art
 * @copyright  2014
 * @author     Sylvain Breil
 * @licence    GNU/GPL
 * @package    SPIP\Catexpo\Installation
 */

if (!defined('_ECRIRE_INC_VERSION')) return;


/**
 * Fonction d'installation et de mise à jour du plugin Galerie d&#039;art.
 *
 * Vous pouvez :
 *
 * - créer la structure SQL,
 * - insérer du pre-contenu,
 * - installer des valeurs de configuration,
 * - mettre à jour la structure SQL 
 *
 * @param string $nom_meta_base_version
 *     Nom de la meta informant de la version du schéma de données du plugin installé dans SPIP
 * @param string $version_cible
 *     Version du schéma de données dans ce plugin (déclaré dans paquet.xml)
 * @return void
**/
function catexpo_upgrade($nom_meta_base_version, $version_cible) {
	$maj = array();
	
	// Si ce n'est pas déjà fait, ajouter automatiquement le formulaire d'upload de documents sur les oeuvres
	// Sauf si le plugin jQuery_upload est présent : on l'appelera par un Pipeline
	if (!test_plugin_actif('jQuery_upload')) {
		$config_doc_assoc=lire_config('documents_objets');
		function not_empty($var) {return !empty($var);}
		$config_doc_assoc=array_filter(explode(',',$config_doc_assoc),'not_empty');
		if (!in_array('spip_oeuvres',$config_doc_assoc)) {
			$config_doc_assoc[]='spip_oeuvres';
		}
		$config_doc_assoc=implode(',',$config_doc_assoc);
		$maj['create'][]=array('ecrire_config', 'documents_objets',$config_doc_assoc);
	}
	
	// Ajouter une fonction de nettoyage pour elevateZoom adapté pour colorbox
	include_spip('inc/meta');
	$mediabox_meta=unserialize(lire_meta('mediabox'));
	if (!is_array($mediabox_meta)) {
		$mediabox_meta=[];
	}
	$mediabox_meta['skin'] = 'bootstrap';
	$mediabox_meta['onClosed']='function() {$(".zoomContainer").remove();}';
	ecrire_meta('mediabox',serialize($mediabox_meta));

	
	# quelques exemples
	# (que vous pouvez supprimer !)
	# 
	# $maj['create'] = array(array('creer_base'));
	#
	# include_spip('inc/config')
	# $maj['create'] = array(
	#	array('maj_tables', array('spip_xx', 'spip_xx_liens')),
	#	array('ecrire_config', array('catexpo', array('exemple' => "Texte de l'exemple")))
	#);
	#
	# $maj['1.1.0']  = array(array('sql_alter','TABLE spip_xx RENAME TO spip_yy'));
	# $maj['1.2.0']  = array(array('sql_alter','TABLE spip_xx DROP COLUMN id_auteur'));
	# $maj['1.3.0']  = array(
	#	array('sql_alter','TABLE spip_xx CHANGE numero numero int(11) default 0 NOT NULL'),
	#	array('sql_alter','TABLE spip_xx CHANGE texte petit_texte mediumtext NOT NULL default \'\''),
	# );
	# ...
	include_spip('inc/cextras');
	include_spip('catexpo_pipelines');
	if (!cextras_api_upgrade(catexpo_declarer_champs_extras(),$maj['create']))
		spip_log('Catexpo : Erreur de création des champs extra');
	if (!cextras_api_upgrade(catexpo_declarer_champs_extras(),$maj['0.1.30']))
		spip_log('Catexpo : Erreur de création des champs extra');

	$maj['create'][]= array('maj_tables', array('spip_oeuvres', 'spip_oeuvres_liens', 'spip_expositions', 'spip_catalogues'));
	$maj['0.1.45'][]= array('maj_tables', array('spip_oeuvres', 'spip_oeuvres_liens', 'spip_expositions', 'spip_catalogues'));

	include_spip('base/upgrade');
	maj_plugin($nom_meta_base_version, $version_cible, $maj);
}


/**
 * Fonction de désinstallation du plugin Galerie d&#039;art.
 * 
 * Vous devez :
 *
 * - nettoyer toutes les données ajoutées par le plugin et son utilisation
 * - supprimer les tables et les champs créés par le plugin. 
 *
 * @param string $nom_meta_base_version
 *     Nom de la meta informant de la version du schéma de données du plugin installé dans SPIP
 * @return void
**/
function catexpo_vider_tables($nom_meta_base_version) {
	# quelques exemples
	# (que vous pouvez supprimer !)
	# sql_drop_table("spip_xx");
	# sql_drop_table("spip_xx_liens");

	sql_drop_table("spip_oeuvres");
	sql_drop_table("spip_oeuvres_liens");
	sql_drop_table("spip_expositions");
	sql_drop_table("spip_catalogues");
//
//	# Nettoyer les versionnages et forums
//	sql_delete("spip_versions",              sql_in("objet", array('oeuvre', 'exposition', 'catalogue')));
//	sql_delete("spip_versions_fragments",    sql_in("objet", array('oeuvre', 'exposition', 'catalogue')));
//	sql_delete("spip_forum",                 sql_in("objet", array('oeuvre', 'exposition', 'catalogue')));
	
	effacer_meta($nom_meta_base_version);
}

?>