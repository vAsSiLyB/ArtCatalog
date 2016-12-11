<?php
if (!defined("_ECRIRE_INC_VERSION")) return;

function formulaires_configurer_docmeta_charger_dist(){
	foreach(array(
		"activer_breves",
		) as $m)
		$valeurs[$m] = $GLOBALS['meta'][$m];

	return $valeurs;
}


function formulaires_configurer_docmeta_traiter_dist(){
	$res = array('editable'=>true);
	foreach(array(
		"activer_breves",
		) as $m)
		if (!is_null($v=_request($m)))
			ecrire_meta($m, $v=='oui'?'oui':'non');

	$res['message_ok'] = _T('config_info_enregistree');
	return $res;
}
?>