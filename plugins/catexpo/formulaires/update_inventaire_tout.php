<?php
function formulaires_update_inventaire_tout_charger_dist($id_catalogue, $oeuvres_catalogue, $retour=''){
	$valeurs=array(
		'id_catalogue'=>$id_catalogue
	);
	return $valeurs;
}

function formulaires_update_inventaire_tout_verifier_dist($id_catalogue, $oeuvres_catalogue, $retour=''){
	$valeurs=array();
	return $valeurs;
}

function formulaires_update_inventaire_tout_traiter_dist($id_catalogue, $oeuvres_catalogue, $retour=''){
	include_spip('action/attribuer_numeros_ordre');
	$res['message_ok']='';
	
	if (_request('corriger_numeros')=='numeros' 
				&& $id_catalogue 
				&& $nums_ordre=attribuer_numeros_ordre($id_catalogue,'',true)) {
		$res['message_ok']='OK';
	}
	else if (_request('corriger_numeros')=='tout' 
				&& $id_catalogue 
				&& $nums_inventaire=attribuer_numeros_inventaire($id_catalogue,'',true)) {
		$res['message_ok']='OK';
	}
	if ($res['message_ok']) {
		$res['message_ok']='<span style="color:#900;">Première étape validée </span>: Il faut maintenant renommer les documents représentant chaque oeuvre';
	}
	else if (_request('reformer_docs')=='true') {
		spip_log('reforme des numeros d\'inventaire pour les documents démarrée');
		include_spip('action/controler_fichiers_catalogue');
		foreach ($oeuvres_catalogue as $id=>$oeuvre) {
			$retour=actualiser_noms_fichiers_catalogue($id);
			$res['message_ok']=$retour['message_ok'];
		}
		spip_log('reforme des numeros d\'inventaire pour les documents terminée');
	}
	
// 	$res['redirect']=$retour;
   	include_spip('inc/invalideur');
    suivre_invalideur('');
	return $res;
}