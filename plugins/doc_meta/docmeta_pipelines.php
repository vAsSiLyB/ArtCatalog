<?php
/**
 * Utilisations de pipelines par Documents avancés
 *
 * @plugin     Documents avancés
 * @copyright  2014
 * @author     Sylvain Breil
 * @licence    GNU/GPL
 * @package    SPIP\docmeta\Pipelines
 */

if (!defined('_ECRIRE_INC_VERSION')) return;
	

/*
 * Un fichier de pipelines permet de regrouper
 * les fonctions de branchement de votre plugin
 * sur des pipelines existants.
 */

     
function docmeta_declarer_champs_extras($champs = array()) {
	$champs['spip_documents']['dispositif'] =  array(
		'saisie' => 'selection',
		'options' => array(
			'nom' => 'dispositif',
			'datas' => array('compact'=>'compact',"DSLR 40D"=>'Reflex 40D',"DSLR 60D"=>'Reflex 60D',"DSLR 5D"=>'Reflex 5D',"DSLR NIKON"=>'Reflex Nikon',"argentique"=>'Scan Diapo/Nega',"video"=>'Capture Vidéo'),
			'label' => _T('docmeta:dispositif'),
			'sql' => 'ENUM("","compact","compact_","DSLR 40D","DSLR 50D","DSLR 60D","DSLR 5D","DSLR NIKON","argentique","composite") NOT NULL DEFAULT "" ',
			'restrictions' =>array ('voir'=> array('auteur' => ''),
									'modifier' => array('auteur' => 'comite')
				
			)
		)
	);
	$champs['spip_documents']['date_prise_de_vue'] = array(
		'saisie' => 'date',//Type du champ (voir plugin Saisies)
		'options' => array(
			'nom' => 'date_prise_de_vue',
			'label' => _T('docmeta:date_digitized'),
			'sql' => "DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00'",
			'defaut' => '0000-00-00 00:00:00',// Valeur par défaut
			'restrictions'=>array('voir' => array('auteur' => ''),//Tout le monde peut voir
									'modifier' => array('auteur' => 'comite') //Seuls les rédacteurs peuvent modifier
							),
			),
	);
	$champs['spip_documents']['marque'] = array(
		'saisie' => 'input',//Type du champ (voir plugin Saisies)
		'options' => array(
			'nom' => 'marque',
			'label' => _T('docmeta:marque'),
			'sql' => "VARCHAR(30) NOT NULL DEFAULT ''",
			'defaut' => '',// Valeur par défaut
			'restrictions'=>array('voir' => array('auteur' => ''),//Tout le monde peut voir
									'modifier' => array('auteur' => 'comite') //Seuls les rédacteurs peuvent modifier
							),
			),
	);
	$champs['spip_documents']['modele_cam'] = array(
		'saisie' => 'input',//Type du champ (voir plugin Saisies)
		'options' => array(
			'nom' => 'modele_cam',
			'label' => _T('docmeta:modele'),
			'sql' => "VARCHAR(255) NOT NULL DEFAULT ''",
			'defaut' => '',// Valeur par défaut
			'restrictions'=>array('voir' => array('auteur' => ''),//Tout le monde peut voir
									'modifier' => array('auteur' => 'comite') //Seuls les rédacteurs peuvent modifier
							),
			),
	);
	$champs['spip_documents']['focale'] = array(
		'saisie' => 'input',//Type du champ (voir plugin Saisies)
		'options' => array(
			'nom' => 'focale',
			'label' => _T('docmeta:focale'),
			'sql' => "FLOAT",
			'defaut' => '',// Valeur par défaut
			'restrictions'=>array('voir' => array('auteur' => ''),//Tout le monde peut voir
									'modifier' => array('auteur' => 'comite') //Seuls les rédacteurs peuvent modifier
							),
			),
	);
	
	return $champs;	
}

function docmeta_renseigner_document_catalogue($flux) {
	// on fournit au plugin jQuery_upload le moyen d'intégrer le type de dispositif dans le nom de fichier et dans la base
	if ($flux['args']['objet']=='oeuvre') {
		include_spip('action/determiner_type_dispositif');
    	if ($dispositif=determiner_type_dispositif($flux['data']['modele'])) {
    		$flux['data']['dispositif']=$dispositif;
    	}
	}
	return $flux;
}

function docmeta_header_prive($flux) {
	$flux.=	'
	<!-- Generic page styles including some bootstrap styles-->
	<link rel="stylesheet" href="'.find_in_path('css/doc_meta_style.css').'">';
	
	return $flux;
}
?>