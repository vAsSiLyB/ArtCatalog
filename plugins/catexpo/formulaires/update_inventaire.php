<?php
function formulaires_update_inventaire_charger_dist($id_oeuvre, $numero_inventaire, $inventaire_correction){
	$valeurs=array(
		'id_oeuvre'=>$id_oeuvre,
		'numero_inventaire' => $numero_inventaire, 
		'inventaire_correction' => $inventaire_correction
	);
	return $valeurs;
}

function formulaires_update_inventaire_verifier_dist($id_oeuvre, $numero_inventaire, $inventaire_correction){
	$valeurs=array();
	return $valeurs;
}

function formulaires_update_inventaire_traiter_dist($id_oeuvre, $numero_inventaire, $inventaire_correction){
	$res['message_ok']='';
	if ($id_oeuvre && $num_inventaire=$inventaire_correction) {
		$bool=sql_updateq(spip_oeuvres, array('numero_inventaire'=>$num_inventaire), 'id_oeuvre="'.$id_oeuvre.'"');
		if ($bool) 
		$res['message_ok']='Le nouveau numéro d\'inventaire est : '.$num_inventaire;
	}
   	include_spip('inc/invalideur');
    suivre_invalideur('');
	return $res;
}