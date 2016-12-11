<?php

if (!defined('_ECRIRE_INC_VERSION')) return;


function formulaires_numeroter_oeuvres_charger_dist($id_catalogue){
	include_spip('action/attribuer_numeros_ordre');
	$valeurs['data_numeros_ordre']=trouver_numeros_ordre($id_catalogue);
	$valeurs['nouvelle_num'] = '';
	$valeurs['id_catalogue'] = $id_catalogue;
	$valeurs['recherche']=$_GET['recherche'];
	$valeurs['lowFi_mask'] = $_GET['lowFi_mask'];
	return $valeurs;
}


function formulaires_numeroter_oeuvres_verifier_dist($id_catalogue){
	return array();
}


function formulaires_numeroter_oeuvres_traiter_dist($id_catalogue){
	$retour['message_erreur']='';
	$retour['message_ok']='';
	$nouvelle_num=_request('nouvelle_num');
	foreach ($nouvelle_num as $num => $id) {
		$id=explode('_',$id);
		$num_donnee[$id[1]]=$num;
	}
	
	include_spip('action/attribuer_numeros_ordre');
	$res=attribuer_numeros_inventaire($id_catalogue,'',true,array($num_donnee));

	if (is_array($res) && count($res)) {
		// Pour limiter le versionning des fichiers ( (1) (2) etc), la liste des oeuvres est parcourue dans l'ordre inverse des numeros d'ordre
		// TODO : verifier à l'usage si ça marche assez bien, ou parfaitement bien
		arsort($res);
		// Mise à jour des documents liés
		include_spip('action/controler_fichiers_catalogue');
		foreach ($res as $id => $num) {
			$controler_documents=actualiser_noms_fichiers_catalogue($id);
			$resultat['message_info']=$controler_documents['message_erreur']?$controler_documents['message_erreur']:$controler_documents['message_ok'];
		}
		if ($resultat['message_ok'])
			$retour['message_ok']='Mise à jour enregistrée';
		include_spip('inc/invalideur');
		suivre_invalideur('');
	}
	else {
		$retour['message_erreur']='Erreur lors de l\'enregistrement';
	}
	return $retour;
}


?>