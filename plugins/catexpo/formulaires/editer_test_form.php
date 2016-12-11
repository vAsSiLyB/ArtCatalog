<?php
function formulaires_editer_test_form_charger_dist() {
	$valeurs=array(
	'id_notice1'=>'article|33',
	'id_notice2'=>'article33',
	'id_notice3'=>'33',
	'id_notice4'=>'article|33',
	'id_notice5'=>'article33',
	'id_notice6'=>'33'
	);
	return $valeurs;
}

function formulaires_editer_test_form_verifier_dist() {
		$req=fopen('php://input','r');
//		spip_log(stream_get_contents($req));
		$i=1;
		while (isset($_REQUEST['id_notice'.$i])) {
//			spip_log('$_POST');
//			spip_log(print_r($_POST['id_notice'.$i],true));
	
//			spip_log( '$_REQUEST : ');
//			spip_log(print_r($_REQUEST['id_notice'.$i],true));
	
			$i++;
		}
		return array();
}

function formulaires_editer_test_form_traiter_dist() {}