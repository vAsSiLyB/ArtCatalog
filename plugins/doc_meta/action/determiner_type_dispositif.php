<?php
function determiner_type_dispositif($modele) {
	$type_image='';
	include(find_in_path('lang/dispositifs_modeles.php'));
	foreach ($patterns as $pattern => $type) {
		if (strpos($modele,$pattern)!==false) $type_image=$type;
	}
	return $type_image;
}
?>