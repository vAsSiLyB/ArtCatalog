<?php
// This is a SPIP language file  --  Ceci est un fichier langue de SPIP

if (!defined('_ECRIRE_INC_VERSION')) return;


$GLOBALS[$GLOBALS['idx_lang']] = array(

	// A
	'ajouter_lien_catalogue' => 'Ajouter ce catalogue',

	//B
	'bouton_mettre_a_jour'=> 'Mettre à jour',

	// E
	'explication_catalogue_oeuvre' => 'Créez ou modifiez un catalogue pour y ajouter des œuvres.',
	'explication_nomenclature_existante' => 'Permet une simple vérification du numéro d\'inventaire d\'une œuvre.(RegExp)',
	'explication_nomenclature_existante2' => 'Exemple : Préfixe:^[A-Z] Numéro:[0-9]{1,4} Partie_optionnelle:\w?<br/>
		La présence d\'un séparateur [-/_:] entre les modules sera ignorée.',
	'explication_nomenclature' =>  'C\'est ici que vous définissez le masque permettant à Spip de proposer une numérotation "stricte" de l\'inventaire.</p>
		<p class="explication">Nota : Si vous activez cette fonction, les fichiers images uploadés pour une œuvre seront renommés selon le numéro d\'inventaire strict de cette oeuvre (s\'il existe, ou s\'il est possible de le déterminer à partir de la nomenclature souple).</p>',
	'explication_inventaire_auto' => 'Une fois l\'<i>inventaire automatique</i> activé, Spip se charge de maintenir une numérotation continue.',
	'explication_nomenclature_optionnelle' =>  'Tous les champs sont optionnels.',
	'explication_nomenclature_stricte2' =>  'Le préfixe peut être défini par les initiales de l\'auteur du catalogue, les initiales de l\'artiste, etc.',
	'explication_nomenclature_stricte3' =>  'Préciser ici la constante qui sera extraite des caractéristiques de chaque œuvre, ainsi que le nombre de lettres à partir du début du mot.<br/>
		Exemple : choissisez Thématique:2 <br/>
		une oeuvre référencée sous la -thématique- : Nature aura un numéro d\'inventaire comportant les lettres "NA"',
	'explication_nomenclature_stricte4' =>  'Format du Suffixe : A pour une majuscule, a pour une minuscule, 1 pour un chiffre, 1111 pour un nombre à 4 chiffres',
	'explication_nomenclature_stricte5' =>  'Le séparateur sera ajouté entre chaque motif défini',
	'explication_versions' => 'S\'il est possible qu\'il existe plusieurs versions d\'une oeuvre sous un même numéro d\'inventaire, nommez-les ici, séparées par une virgule.',
	'explication_media'=>'Permet de distinguer les images téléversées pour ce catalogue en leur attribuant un type particulier (au singulier minuscule, exemple : oeuvre, archive, peinture, etc.)',
	'explication_media2'=>'Pour gérer ce cas de figure, ainsi que les <i>Versions</i> ci-dessous, les plugins "Doc Pneumatic" et "Métadonnées pour les documents" sont nécessaires.',

	// I
	'icone_creer_catalogue' => 'Créer un catalogue',
	'icone_modifier_catalogue' => 'Paramétrer le catalogue',
	'icone_verifier_inventaire' => 'Vérifier l\'inventaire',
	'icone_ordonner_inventaire' => 'Vue Synthétique',
	'info_1_catalogue' => '1 catalogue',
	'info_aucun_catalogue' => 'Aucun catalogue',
	'info_catalogues_auteur' => 'Les catalogues de cet auteur',
	'info_nb_catalogues' => '@nb@ catalogues',
	'intertitre_identification'=> 'Identification',
	'intertitre_nomenclature' => '<b>Nomenclature (facultatif, un outil puissant mais complexe) </b>: Cette section permet l\'attribution systématique des numéros d\'inventaire. Elle permet également, par exemple, d\'effectuer une réforme de l\'inventaire, ou vérifier les erreurs de saisie.',
	'intertitre_versions'=> 'Versions (facultatif)',

	// L
	'label_utiliser_nomenclature' => 'Utiliser la nomenclature stricte',
	'label_utiliser_inventaire_auto'=> 'Utiliser l\'inventaire automatique',
	'label_nomenclature_existante' => 'Nomenclature souple',
	'label_nomenclature_stricte' => 'Nomenclature stricte',
	'label_nomenclature_utiliser' => '[utiliser]',
	'label_nomenclature_auto' => '[auto]',
	'label_nomenclature_part1' => '[Préfixe]',
	'label_nomenclature_part11' => '[Constante1]',
	'label_nomenclature_part12' => '[Constante2]',
	'label_nomenclature_parta' => 'thematique',
	'label_nomenclature_partb' => 'technique',
	'label_nomenclature_partc' => 'support',
	'label_nomenclature_partd' => 'periode',
	'label_nomenclature_parte' => 'date_real',
	'label_nomenclature_partn1' => '[nbr_lettres1]',
	'label_nomenclature_partn2' => '[nbr_lettres2]',
	'label_nomenclature_part2' => '[Numéro]',
	'label_nomenclature_part3' => '[Partie_optionnelle]',
	'label_nomenclature_part4' => '[Suffixe]',
	'label_nomenclature_part5' => '[Séparateur]',
	'label_titre' => 'Titre',
	'label_versions' => 'Versions',
	'label_media'=>'Type de media',

	// R
	'retirer_lien_catalogue' => 'Retirer ce catalogue',
	'retirer_tous_liens_catalogues' => 'Retirer tous les catalogues',

	// T
	'texte_ajouter_catalogue' => 'Ajouter un catalogue',
	'texte_changer_statut_catalogue' => 'Ce catalogue est :',
	'texte_creer_associer_catalogue' => 'Créer et associer un catalogue',
	'titre_catalogue' => 'Catalogue',
	'titre_catalogue_court' => 'Cat.',
	'titre_catalogues' => 'Catalogues',
	'titre_catalogues_rubrique' => 'Catalogues de la rubrique',
	'titre_langue_catalogue' => 'Langue de ce catalogue',
	'titre_logo_catalogue' => 'Logo de ce catalogue',
	'titre_les_catalogues' => 'Les catalogues',
	'titre_verifier_inventaire' => 'Vérification de l\'inventaire',
);

?>