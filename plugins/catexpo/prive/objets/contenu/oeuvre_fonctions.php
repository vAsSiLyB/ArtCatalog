<?php

function nettoyer_array_picker($objets_lies) {
	$retour=array_map('mapping_function',$objets_lies);
	return $retour;
}

function mapping_function($chaine) {
	$retour=preg_replace('/oeuvre\||article\|/','',$chaine);
	return $retour;
}
?>