<?php
/**
 * Fonctions utiles au plugin Galerie d&#039;art
 *
 * @plugin     Galerie d&#039;art
 * @copyright  2014
 * @author     Sylvain Breil
 * @licence    GNU/GPL
 * @package    SPIP\Catexpo\Fonctions
 */

if (!defined('_ECRIRE_INC_VERSION')) return;


/*
 * Un fichier de fonctions permet de définir des éléments
 * systématiquement chargés lors du calcul des squelettes.
 *
 * Il peut par exemple définir des filtres, critères, balises, …
 * 
 */

// Fonctions déjà définies dans le plugin "jQuery_upload"
// Il faudrait trouver un script commun où définir ces fonctions
if (!test_plugin_actif('jQuery_upload')) {
	function date2mysql($user_date, $user_time='00:00:00', $mode='fr') {
		
		if (count($user_date_ex=explode(' ', $user_date)) >= 2) {
			$user_date=$user_date_ex[0];
			$user_time=$user_date_ex[1];
		}
		
		if ($mode=='fr'
			&& (count($user_date_ex=explode('/', $user_date)) == 3
			|| count($user_date_ex=explode('-', $user_date)) == 3)) {
			$mysql_date=$user_date_ex[2].'-'.$user_date_ex[1].'-'.$user_date_ex[0].' '.$user_time;
		}
		else if ($mode=='exif'
			&& (count($user_date_ex=explode(':', $user_date)) == 3
			|| count($user_date_ex=explode('-', $user_date)) == 3)) {
			$mysql_date=$user_date_ex[0].'-'.$user_date_ex[1].'-'.$user_date_ex[2].' '.$user_time;
		}
		else if ( strlen($user_date)==8 ) {
			$mysql_date=substr($user_date,-4).'-'.substr($user_date,2,2).'-'.substr($user_date,0,2).' '.$user_time;
		}
		else if (strlen($user_date)==6) {
			$mysql_date=substr($user_date,-2).'-'.substr($user_date,2,2).'-'.substr($user_date,0,2).' '.$user_time;
		}
		else if (strlen($user_date)==4) {
			$mysql_date=$user_date.'-01-01 '.$user_time;
		}
		else if (strlen($user_date)==2) {
			$mysql_date_test='20'.$user_date;
			if ($mysql_date_test>=date('Y')) {	// cette syntaxe interdit de traiter une date dans le futur
				$mysql_date='19'.$user_date.'-01-01 '.$user_time;
			}
			else {$mysql_date=$mysql_date_test;}
		}

		if (validateMysqlDate($mysql_date)){
			return $mysql_date;
		}
		return '0000-00-00 00:00:00';
	}
	
	function validateMysqlDate( $date ){ 
	    if (preg_match("/^(\d{4})-(\d{2})-(\d{2}) ([01][0-9]|2[0-3]):([0-5][0-9]):([0-5][0-9])$/", $date, $matches)) { 
	        if (checkdate($matches[2], $matches[3], $matches[1])) { 
	            return true; 
	        } 
	    } 
	    return false; 
	}
}
	
?>