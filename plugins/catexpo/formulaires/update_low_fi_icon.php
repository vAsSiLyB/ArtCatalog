<?php

function formulaires_update_low_fi_icon_charger ($id_document, $dispositif_annee) {
	$valeurs=array();
	$doc_data=sql_fetsel('dispositif','spip_documents', 'id_document='.$id_document);
	$doc_data['dispositif']=str_pad($doc_data['dispositif'],8,' ');
	$valeurs['dispositif_annee']=str_replace(substr($dispositif_annee,0,8),$doc_data['dispositif'],$dispositif_annee);
	return $valeurs;
}

function formulaires_update_low_fi_icon_verifier ($id_document, $dispositif_annee) {
	$erreurs=array();
	return $erreurs;
}

function formulaires_update_low_fi_icon_traiter ($id_document, $dispositif_annee) {
	$resultat=array();
	if (_request('action_form')=='uncover_doc') {
		sql_updateq('spip_documents',array('dispositif'=>'compact_'),'id_document='.$id_document);
	}
	else if (_request('action_form')=='cover_doc') {
		sql_updateq('spip_documents',array('dispositif'=>'compact'),'id_document='.$id_document);
	}
	$doc_data=sql_fetsel('vig.fichier', 'spip_documents as doc join spip_documents as vig', 'doc.id_document='.$id_document.' AND vig.id_document=doc.id_vignette');
	$resultat['message_ok']=$doc_data['fichier'];
	return $resultat;
}
?>