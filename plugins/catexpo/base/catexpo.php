<?php
/**
 * Déclarations relatives à la base de données
 *
 * @plugin     Galerie d&#039;art
 * @copyright  2014
 * @author     Sylvain Breil
 * @licence    GNU/GPL
 * @package    SPIP\Catexpo\Pipelines
 */

if (!defined('_ECRIRE_INC_VERSION')) return;


/**
 * Déclaration des alias de tables et filtres automatiques de champs
 *
 * @pipeline declarer_tables_interfaces
 * @param array $interfaces
 *     Déclarations d'interface pour le compilateur
 * @return array
 *     Déclarations d'interface pour le compilateur
 */
function catexpo_declarer_tables_interfaces($interfaces) {

	$interfaces['table_des_tables']['oeuvres'] = 'oeuvres';
	$interfaces['table_des_tables']['expositions'] = 'expositions';
	$interfaces['table_des_tables']['catalogues'] = 'catalogues';

	return $interfaces;
}


/**
 * Déclaration des objets éditoriaux
 *
 * @pipeline declarer_tables_objets_sql
 * @param array $tables
 *     Description des tables
 * @return array
 *     Description complétée des tables
 */
function catexpo_declarer_tables_objets_sql($tables) {

	$tables['spip_oeuvres'] = array(
		'type' => 'oeuvre',
		'principale' => "oui",
		'field'=> array(
			"id_oeuvre"          => "bigint(21) NOT NULL",
			"id_catalogue"       => "bigint(21) NOT NULL",
			"numero_inventaire"  => "varchar(35) NOT NULL",
			"titre"              => "varchar(255) NOT NULL DEFAULT ''",
			"titre_secret"       => "varchar(255) NOT NULL DEFAULT ''",
			"signature_placement" => "enum('HG','HM','HD','MG','MM','MD','BG','BM','BD','Dos','Non') NOT NULL DEFAULT 'non'",
			"signature_mention"  => "varchar(64) NOT NULL DEFAULT ''",
			"date_real"          => "datetime NOT NULL DEFAULT '1000-01-01 00:00:00'", 
			"date_estimee_debut" => "datetime NOT NULL DEFAULT '1000-01-01 00:00:00'", 
			"date_estimee_fin"   => "datetime NOT NULL DEFAULT '1000-01-01 00:00:00'", 
			"periode"         => "varchar(64) NOT NULL DEFAULT ''",
			"thematique"         => "varchar(64) NOT NULL DEFAULT ''",
			"notice_courte"		=> "mediumtext NOT NULL DEFAULT ''",
			"id_notice_longue"	=> "varchar(255) NOT NULL ",
			"notice_longue"		=> "longtext NOT NULL",
			"technique"            => "varchar(255) NOT NULL DEFAULT ''",
			"support"            => "varchar(255) NOT NULL DEFAULT ''",
			"support_old"            => "varchar(255) NOT NULL DEFAULT ''",
			"hauteur"            => "float NOT NULL DEFAULT 0",
			"largeur"            => "float NOT NULL DEFAULT 0",
			"inachevee"          => "enum('Oui','') NOT NULL DEFAULT ''",
			"chassis"            => "enum('Oui','Non') NOT NULL DEFAULT 'Non'",
			"cadre"              => "enum('Oui','Non') NOT NULL DEFAULT 'Non'",
			"localisation"        => "varchar(64) NOT NULL DEFAULT ''",
			"diapo_existe"       => "tinyint(4) ",
			"negatif_existe"     => "tinyint(4) ",
			"id_oeuvres_liees"	=> "varchar(255)",
			"publication"		=> "longtext NOT NULL ",
			"numero_ordre"		=> "bigint(21) NOT NULL",
			"lang"               => "VARCHAR(10) NOT NULL DEFAULT ''",
			"langue_choisie"     => "VARCHAR(3) DEFAULT 'non'", 
			"id_trad"            => "bigint(21) NOT NULL DEFAULT 0", 
			"maj"                => "TIMESTAMP"
		),
		'key' => array(
			"PRIMARY KEY"        => "id_oeuvre",
			"KEY lang"           => "lang", 
			"KEY id_trad"        => "id_trad", 
		),
		'titre' => "titre AS titre, lang AS lang",
		'champs_editables'  => array(
			"id_catalogue",
			"numero_inventaire", 
			"thematique", 
			"titre", 
			"titre_secret", 
			"signature_mention", 
			"signature_placement",
			"date_real",
			"date_estimee_debut", 
			"date_estimee_fin",
			"periode",
			"notice_courte",
			"id_notice_longue",
			"notice_longue",
			"id_oeuvres_liees",
			"inachevee",
			"technique",
			"support",
			"support_old",
			"hauteur",
			"largeur",
			"chassis",
			"cadre",
			"localisation",
			"diapo_existe",
			"negatif_existe",
			"publication",
			"numero_ordre"
		),
		'champs_versionnes' => array(),
		'rechercher_champs' => array('titre'=>3, 'titre_secret'=>3, 'notice_courte'=>2, 'notice_longue'=>1, 'thematique'=>1),
		'tables_jointures'  => array('spip_oeuvres_liens','spip_catalogues'),
		

	);

	$tables['spip_expositions'] = array(
		'type' => 'exposition',
		'principale' => "oui",
		'field'=> array(
			"id_exposition"      => "bigint(21) NOT NULL",
			"titre"              => "varchar(255) NOT NULL",
			"type_expo"			=> "enum('réelle','en ligne', 'show en ligne') NOT NULL DEFAULT 'en ligne'",
			"date_expo"              => "varchar(255) NOT NULL DEFAULT ''",
			"lieu"              => "varchar(255) NOT NULL DEFAULT ''",
			"maj"                => "TIMESTAMP"
		),
		'key' => array(
			"PRIMARY KEY"        => "id_exposition",
		),
		'titre' => "titre AS titre, '' AS lang",
		'champs_editables'  => array(
			"titre",
			"type_expo",
			"date_expo",
			"lieu"
		),
		'champs_versionnes' => array(),
		'rechercher_champs' => array(),
		'tables_jointures'  => array('spip_oeuvres_liens','spip_documents_liens'),
		

	);

	$tables['spip_catalogues'] = array(
		'type' => 'catalogue',
		'principale' => "oui",
		'field'=> array(
			"id_catalogue"       => "bigint(21) NOT NULL",
			"titre"              => "varchar(255) NOT NULL DEFAULT ''",
			"media"              => "varchar(255) NOT NULL DEFAULT ''",
			"versions"              => "varchar(255) NOT NULL DEFAULT ''",
			"nomenclature_stricte" => "TEXT NOT NULL DEFAULT ''",
			"nomenclature_existante" => "TEXT NOT NULL DEFAULT ''",
			"maj"                => "TIMESTAMP"
		),
		'key' => array(
			"PRIMARY KEY"        => "id_catalogue",
		),
		'titre' => "titre AS titre, '' AS lang",
		 #'date' => "",
		'champs_editables'  => array(
			"titre",
			"versions",
			"media",
			"nomenclature_stricte",
			"nomenclature_existante"
		),
		'champs_versionnes' => array(),
		'rechercher_champs' => array(),
		'tables_jointures'  => array(),
		

	);

	return $tables;
}


/**
 * Déclaration des tables secondaires (liaisons)
 *
 * @pipeline declarer_tables_auxiliaires
 * @param array $tables
 *     Description des tables
 * @return array
 *     Description complétée des tables
 */
function catexpo_declarer_tables_auxiliaires($tables) {

	$tables['spip_oeuvres_liens'] = array(
		'field' => array(
			"id_oeuvre"          => "bigint(21) DEFAULT '0' NOT NULL",
			"id_objet"           => "bigint(21) DEFAULT '0' NOT NULL",
			"objet"              => "VARCHAR(25) DEFAULT '' NOT NULL",
			"rang"          	 => "bigint(21) DEFAULT '0' NOT NULL"
		),
		'key' => array(
			"PRIMARY KEY"        => "id_oeuvre,id_objet,objet",
			"KEY id_oeuvre"      => "id_oeuvre"
		)
	);

	return $tables;
}

function catexpo_declarer_champs_extras($champs = array()) {
	$champs['spip_documents']['version'] = array(
		'saisie' => 'input',//Type du champ (voir plugin Saisies)
		'options' => array(
			'nom' => 'version',
			'label' => _T('catexpo:label_version_doc'),
			//'readonly'=>true,
			'sql' => 'varchar(30) NOT NULL DEFAULT ""',
			'defaut' => '',// Valeur par défaut
			'restrictions'=>array('voir' => array('auteur' => ''),//Tout le monde peut voir
									'modifier' => array('auteur' => 'comite') //Seuls les rédacteurs peuvent modifier
							)
			)
	);
	return $champs;
}
?>