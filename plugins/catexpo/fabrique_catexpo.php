<?php

/**
 *  Fichier généré par la Fabrique de plugin v5
 *   le 2014-03-20 16:02:30
 *
 *  Ce fichier de sauvegarde peut servir à recréer
 *  votre plugin avec le plugin «Fabrique» qui a servi à le créer.
 *
 *  Bien évidemment, les modifications apportées ultérieurement
 *  par vos soins dans le code de ce plugin généré
 *  NE SERONT PAS connues du plugin «Fabrique» et ne pourront pas
 *  être recréées par lui !
 *
 *  La «Fabrique» ne pourra que régénerer le code de base du plugin
 *  avec les informations dont il dispose.
 *
**/

if (!defined("_ECRIRE_INC_VERSION")) return;

$data = array (
  'fabrique' => 
  array (
    'version' => 5,
  ),
  'paquet' => 
  array (
    'nom' => 'Galerie d\'art',
    'slogan' => '',
    'description' => 'Constituer des catalogues et présenter les œuvres dans des expositions virtuelles',
    'prefixe' => 'catexpo',
    'version' => '0.1.0',
    'auteur' => 'Sylvain Breil',
    'auteur_lien' => '',
    'licence' => 'GNU/GPL',
    'categorie' => 'divers',
    'etat' => 'dev',
    'compatibilite' => '[3.0.16;3.0.*]',
    'documentation' => '',
    'administrations' => 'on',
    'schema' => '0.1.0',
    'formulaire_config' => 'on',
    'formulaire_config_titre' => 'Options des catalogues',
    'fichiers' => 
    array (
      0 => 'fonctions',
      1 => 'options',
      2 => 'pipelines',
    ),
    'inserer' => 
    array (
      'paquet' => '',
      'administrations' => 
      array (
        'maj' => '',
        'desinstallation' => '',
        'fin' => '',
      ),
      'base' => 
      array (
        'tables' => 
        array (
          'fin' => '',
        ),
      ),
    ),
    'scripts' => 
    array (
      'pre_copie' => '',
      'post_creation' => '',
    ),
    'exemples' => 'on',
  ),
  'objets' => 
  array (
    0 => 
    array (
      'nom' => '&#338;uvres',
      'nom_singulier' => '&#338;uvre',
      'genre' => 'feminin',
      'logo_variantes' => 'on',
      'table' => 'spip_oeuvres',
      'cle_primaire' => 'id_oeuvre',
      'cle_primaire_sql' => 'bigint(21) NOT NULL',
      'table_type' => 'oeuvre',
      'champs' => 
      array (
        0 => 
        array (
          'nom' => 'Numero inventaire',
          'champ' => 'numero_inventaire',
          'sql' => 'varchar(35) NOT NULL',
          'recherche' => '',
          'saisie' => '',
          'explication' => '',
          'saisie_options' => '',
        ),
        1 => 
        array (
          'nom' => 'Thematique',
          'champ' => 'thematique',
          'sql' => 'varchar(64) NOT NULL DEFAULT \'\'\'\'\'\'',
          'recherche' => '',
          'saisie' => '',
          'explication' => '',
          'saisie_options' => '',
        ),
        2 => 
        array (
          'nom' => 'Titre',
          'champ' => 'titre',
          'sql' => 'varchar(255) NOT NULL DEFAULT \'\'\'\'\'\'',
          'recherche' => '',
          'saisie' => '',
          'explication' => '',
          'saisie_options' => '',
        ),
        3 => 
        array (
          'nom' => 'Titre secret',
          'champ' => 'titre_secret',
          'sql' => 'varchar(255) NOT NULL DEFAULT \'\'\'\'\'\'',
          'recherche' => '',
          'saisie' => '',
          'explication' => '',
          'saisie_options' => '',
        ),
        4 => 
        array (
          'nom' => 'Signature mention',
          'champ' => 'signature_mention',
          'sql' => 'varchar(64) NOT NULL DEFAULT \'\'\'\'\'\'',
          'recherche' => '',
          'saisie' => '',
          'explication' => '',
          'saisie_options' => '',
        ),
        5 => 
        array (
          'nom' => 'Signature placement',
          'champ' => 'signature_placement',
          'sql' => 'enum(\'HG\',\'HM\',\'HD\',\'MG\',\'MM\',\'MD\',\'BG\',\'BM\',\'BD\',\'non\') NOT NULL DEFAULT \'non\'',
          'recherche' => '',
          'saisie' => '',
          'explication' => '',
          'saisie_options' => '',
        ),
        6 => 
        array (
          'nom' => 'Date estimee debut',
          'champ' => 'date_estimee_debut',
          'sql' => 'varchar(4) NOT NULL DEFAULT \'\'\'\'\'\'',
          'recherche' => '',
          'saisie' => '',
          'explication' => '',
          'saisie_options' => '',
        ),
        7 => 
        array (
          'nom' => 'Date estimee fin',
          'champ' => 'date_estimee_fin',
          'sql' => 'varchar(4) NOT NULL DEFAULT \'\'\'\'\'\'',
          'recherche' => '',
          'saisie' => '',
          'explication' => '',
          'saisie_options' => '',
        ),
        8 => 
        array (
          'nom' => 'Inachevee',
          'champ' => 'inachevee',
          'sql' => 'enum(\'oui\',\'\') NOT NULL DEFAULT \'\'',
          'recherche' => '',
          'saisie' => '',
          'explication' => '',
          'saisie_options' => '',
        ),
        9 => 
        array (
          'nom' => 'Support',
          'champ' => 'support',
          'sql' => 'varchar(255) NOT NULL DEFAULT \'\'\'\'\'\'',
          'recherche' => '',
          'saisie' => '',
          'explication' => '',
          'saisie_options' => '',
        ),
        10 => 
        array (
          'nom' => 'Hauteur',
          'champ' => 'hauteur',
          'sql' => 'float NOT NULL',
          'recherche' => '',
          'saisie' => '',
          'explication' => '',
          'saisie_options' => '',
        ),
        11 => 
        array (
          'nom' => 'Largeur',
          'champ' => 'largeur',
          'sql' => 'float NOT NULL',
          'recherche' => '',
          'saisie' => '',
          'explication' => '',
          'saisie_options' => '',
        ),
        12 => 
        array (
          'nom' => 'Chassis',
          'champ' => 'chassis',
          'sql' => 'enum(\'oui\',\'non\') NOT NULL DEFAULT \'non\'',
          'recherche' => '',
          'saisie' => '',
          'explication' => '',
          'saisie_options' => '',
        ),
        13 => 
        array (
          'nom' => 'Cadre',
          'champ' => 'cadre',
          'sql' => 'enum(\'oui\',\'non\') NOT NULL DEFAULT \'non\'',
          'recherche' => '',
          'saisie' => '',
          'explication' => '',
          'saisie_options' => '',
        ),
        14 => 
        array (
          'nom' => 'Emplacement',
          'champ' => 'emplacement',
          'sql' => 'varchar(64) NOT NULL DEFAULT \'\'\'\'\'\'',
          'recherche' => '',
          'saisie' => '',
          'explication' => '',
          'saisie_options' => '',
        ),
        15 => 
        array (
          'nom' => 'Diapo existe',
          'champ' => 'diapo_existe',
          'sql' => 'tinyint(4) NOT NULL',
          'recherche' => '',
          'saisie' => '',
          'explication' => '',
          'saisie_options' => '',
        ),
        16 => 
        array (
          'nom' => 'Negatif existe',
          'champ' => 'negatif_existe',
          'sql' => 'tinyint(4) NOT NULL',
          'recherche' => '',
          'saisie' => '',
          'explication' => '',
          'saisie_options' => '',
        ),
      ),
      'champ_titre' => 'titre',
      'langues' => 
      array (
        0 => 'lang',
        1 => 'id_trad',
      ),
      'champ_date' => 'date',
      'statut' => '',
      'chaines' => 
      array (
        'titre_objets' => '&#338;uvres',
        'titre_objet' => '&#338;uvre',
        'info_aucun_objet' => 'Aucune oeuvre',
        'info_1_objet' => 'Une oeuvre',
        'info_nb_objets' => '@nb@ oeuvres',
        'icone_creer_objet' => 'Créer une oeuvre',
        'icone_modifier_objet' => 'Modifier cette oeuvre',
        'titre_logo_objet' => 'Logo de cette oeuvre',
        'titre_langue_objet' => 'Langue de cette oeuvre',
        'titre_objets_rubrique' => '&#338;uvres de la rubrique',
        'info_objets_auteur' => 'Les oeuvres de cet auteur',
        'retirer_lien_objet' => 'Retirer cette oeuvre',
        'retirer_tous_liens_objets' => 'Retirer toutes les oeuvres',
        'ajouter_lien_objet' => 'Ajouter cette oeuvre',
        'texte_ajouter_objet' => 'Ajouter une oeuvre',
        'texte_creer_associer_objet' => 'Créer et associer une oeuvre',
        'texte_changer_statut_objet' => 'Cette oeuvre est :',
      ),
      'table_liens' => 'on',
      'vue_liens' => 
      array (
        0 => 'spip_expositions',
      ),
      'roles' => '',
      'auteurs_liens' => '',
      'vue_auteurs_liens' => '',
      'echafaudages' => 
      array (
        0 => 'prive/squelettes/contenu/objets.html',
        1 => 'prive/squelettes/contenu/objet.html',
      ),
      'autorisations' => 
      array (
        'objet_creer' => '',
        'objet_voir' => '',
        'objet_modifier' => '',
        'objet_supprimer' => '',
        'associerobjet' => 'administrateur_restreint',
      ),
      'boutons' => 
      array (
        0 => 'menu_edition',
        1 => 'outils_rapides',
      ),
    ),
    1 => 
    array (
      'nom' => 'Expositions',
      'nom_singulier' => 'Exposition',
      'genre' => 'feminin',
      'logo_variantes' => 'on',
      'table' => 'spip_expositions',
      'cle_primaire' => 'id_exposition',
      'cle_primaire_sql' => 'bigint(21) NOT NULL',
      'table_type' => 'exposition',
      'champs' => 
      array (
        0 => 
        array (
          'nom' => 'Titre',
          'champ' => 'titre',
          'sql' => 'varchar(255) NOT NULL',
          'recherche' => '',
          'saisie' => '',
          'explication' => '',
          'saisie_options' => '',
        ),
      ),
      'champ_titre' => 'titre',
      'champ_date' => '',
      'statut' => '',
      'chaines' => 
      array (
        'titre_objets' => 'Expositions',
        'titre_objet' => 'Exposition',
        'info_aucun_objet' => 'Aucun exposition',
        'info_1_objet' => 'Un exposition',
        'info_nb_objets' => '@nb@ expositions',
        'icone_creer_objet' => 'Créer un exposition',
        'icone_modifier_objet' => 'Modifier ce exposition',
        'titre_logo_objet' => 'Logo de ce exposition',
        'titre_langue_objet' => 'Langue de ce exposition',
        'titre_objets_rubrique' => 'Expositions de la rubrique',
        'info_objets_auteur' => 'Les expositions de cet auteur',
        'retirer_lien_objet' => 'Retirer ce exposition',
        'retirer_tous_liens_objets' => 'Retirer tous les expositions',
        'ajouter_lien_objet' => 'Ajouter ce exposition',
        'texte_ajouter_objet' => 'Ajouter un exposition',
        'texte_creer_associer_objet' => 'Créer et associer un exposition',
        'texte_changer_statut_objet' => 'Ce exposition est :',
      ),
      'table_liens' => '',
      'roles' => '',
      'auteurs_liens' => '',
      'vue_auteurs_liens' => '',
      'autorisations' => 
      array (
        'objet_creer' => '',
        'objet_voir' => '',
        'objet_modifier' => '',
        'objet_supprimer' => '',
        'associerobjet' => '',
      ),
      'boutons' => 
      array (
        0 => 'menu_edition',
        1 => 'outils_rapides',
      ),
    ),
    2 => 
    array (
      'nom' => 'Catalogues',
      'nom_singulier' => 'Catalogue',
      'genre' => 'masculin',
      'logo_variantes' => 'on',
      'table' => 'spip_catalogues',
      'cle_primaire' => 'id_catalogue',
      'cle_primaire_sql' => 'bigint(21) NOT NULL',
      'table_type' => 'catalogue',
      'champs' => 
      array (
        0 => 
        array (
          'nom' => 'Titre',
          'champ' => 'titre',
          'sql' => 'varchar(255) NOT NULL DEFAULT \'\'\'\'\'\'',
          'recherche' => '',
          'saisie' => '',
          'explication' => '',
          'saisie_options' => '',
        ),
        1 => 
        array (
          'nom' => 'Nomenclature stricte',
          'champ' => 'nomenclature_stricte',
          'sql' => 'varchar(64) NOT NULL DEFAULT \'\'\'\'\'\'',
          'recherche' => '',
          'saisie' => '',
          'explication' => '',
          'saisie_options' => '',
        ),
        2 => 
        array (
          'nom' => 'Nomenclature existante',
          'champ' => 'nomenclature_existante',
          'sql' => 'varchar(64) NOT NULL DEFAULT \'\'\'\'\'\'',
          'recherche' => '',
          'saisie' => '',
          'explication' => '',
          'saisie_options' => '',
        ),
      ),
      'champ_titre' => 'titre',
      'champ_date' => '',
      'statut' => '',
      'chaines' => 
      array (
        'titre_objets' => 'Catalogues',
        'titre_objet' => 'Catalogue',
        'info_aucun_objet' => 'Aucun catalogue',
        'info_1_objet' => 'Un catalogue',
        'info_nb_objets' => '@nb@ catalogues',
        'icone_creer_objet' => 'Créer un catalogue',
        'icone_modifier_objet' => 'Modifier ce catalogue',
        'titre_logo_objet' => 'Logo de ce catalogue',
        'titre_langue_objet' => 'Langue de ce catalogue',
        'titre_objets_rubrique' => 'Catalogues de la rubrique',
        'info_objets_auteur' => 'Les catalogues de cet auteur',
        'retirer_lien_objet' => 'Retirer ce catalogue',
        'retirer_tous_liens_objets' => 'Retirer tous les catalogues',
        'ajouter_lien_objet' => 'Ajouter ce catalogue',
        'texte_ajouter_objet' => 'Ajouter un catalogue',
        'texte_creer_associer_objet' => 'Créer et associer un catalogue',
        'texte_changer_statut_objet' => 'Ce catalogue est :',
      ),
      'table_liens' => '',
      'roles' => '',
      'auteurs_liens' => '',
      'vue_auteurs_liens' => '',
      'autorisations' => 
      array (
        'objet_creer' => '',
        'objet_voir' => '',
        'objet_modifier' => '',
        'objet_supprimer' => '',
        'associerobjet' => '',
      ),
      'boutons' => 
      array (
        0 => 'menu_edition',
        1 => 'outils_rapides',
      ),
    ),
  ),
  'images' => 
  array (
    'paquet' => 
    array (
      'logo' => 
      array (
        0 => 
        array (
          'extension' => '',
          'contenu' => '',
        ),
      ),
    ),
    'objets' => 
    array (
      0 => 
      array (
        'logo' => 
        array (
          0 => 
          array (
            'extension' => 'png',
            'contenu' => 'iVBORw0KGgoAAAANSUhEUgAAACAAAAAgCAYAAABzenr0AAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAyBpVFh0WE1MOmNvbS5hZG9iZS54bXAAAAAAADw/eHBhY2tldCBiZWdpbj0i77u/IiBpZD0iVzVNME1wQ2VoaUh6cmVTek5UY3prYzlkIj8+IDx4OnhtcG1ldGEgeG1sbnM6eD0iYWRvYmU6bnM6bWV0YS8iIHg6eG1wdGs9IkFkb2JlIFhNUCBDb3JlIDUuMC1jMDYwIDYxLjEzNDc3NywgMjAxMC8wMi8xMi0xNzozMjowMCAgICAgICAgIj4gPHJkZjpSREYgeG1sbnM6cmRmPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5LzAyLzIyLXJkZi1zeW50YXgtbnMjIj4gPHJkZjpEZXNjcmlwdGlvbiByZGY6YWJvdXQ9IiIgeG1sbnM6eG1wPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvIiB4bWxuczp4bXBNTT0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wL21tLyIgeG1sbnM6c3RSZWY9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9zVHlwZS9SZXNvdXJjZVJlZiMiIHhtcDpDcmVhdG9yVG9vbD0iQWRvYmUgUGhvdG9zaG9wIENTNSBXaW5kb3dzIiB4bXBNTTpJbnN0YW5jZUlEPSJ4bXAuaWlkOkJENkNEN0M2QjAzNTExRTNCODlBOUJDMzZBREZDNkJCIiB4bXBNTTpEb2N1bWVudElEPSJ4bXAuZGlkOkJENkNEN0M3QjAzNTExRTNCODlBOUJDMzZBREZDNkJCIj4gPHhtcE1NOkRlcml2ZWRGcm9tIHN0UmVmOmluc3RhbmNlSUQ9InhtcC5paWQ6QkQ2Q0Q3QzRCMDM1MTFFM0I4OUE5QkMzNkFERkM2QkIiIHN0UmVmOmRvY3VtZW50SUQ9InhtcC5kaWQ6QkQ2Q0Q3QzVCMDM1MTFFM0I4OUE5QkMzNkFERkM2QkIiLz4gPC9yZGY6RGVzY3JpcHRpb24+IDwvcmRmOlJERj4gPC94OnhtcG1ldGE+IDw/eHBhY2tldCBlbmQ9InIiPz6UzQwvAAAJzUlEQVR42uyXe3BcdRXHP3f37t27702yu81uN0lJQhJCQxugvFpogSKgAoK8R2Ycx1FAZZRxUGZ4jYqO01HAWsuIgyNUCigP5SEDIxTa0CctpE3bQJpsms17N5t9P++9/jZNawWl/Mc/3pnf3N/dvfee7znfc77nXMkwDD7Pw8TnfPwfgJxKpU2ZTNoSCoXs4loql8uWfD7vKRaLlbKhuzAq9nIxb+TyijuZGJOSEzHlo7FpR/9An7G0s9XW0NhiMoyKLJmUWq1Q0JPZlKtQLDsr5aKeyWRqZ5Oz5lgsblYssi0SiRhnnXVO7bJly9avXr362TkAGzY8cavP57tX7K3CsOF2u80W2ezQtaIWSxSsDvcCvLYiusvNlGYiOR2ls72VZUs7iSfTjE8Ng2bBZrPQ1XUyLncbQ0MjdLS1oahWPG4vuXwOu83Dvffew9133004HH6ralssXd648akFDz30cH0wFGT7uz20NDQQ6jgZm9MrD773NyK9G7C2PoC2aSuNbzxL8YZrWX3xagHkIP0HDuGsW4psN6PNJNh3KIks5ampsaIrKrOZHLP5MonkLHZpgpl4fC7sXq83G41GbWJblv0L/PlCNk2mXEMxUM9wwcvoS3vIHM5jaThICQ/rnnyL859fy01DvfRfsJyZUomZ2G5K2R0UxhagpKMc8jXyza+thYrCk7+/gQu6WhmaKWCULMiZJIbfi24+knKSJFnEyS1WTi5XJBKVCuw+hPbiE5z8YDfE/0kp9zop5UbGFi7jFzcGMMl2en/tQvE2MS5o+MPaQfbZu7h/yTD7d27HZO7hV44+8DmJP/JbnvzNn1Ac01jsWVKzZoJ33EWmos8B0DWtmm9OsTRZxSSdftGF2J55g+gTT5G85E3K4WZKl69Aj9dhPuBiZHI/3su+h362AuoMucH3qSmJMO99mfqrBgh1ulHTWUK3CafsRVbd2UTPjjr+sfZDlp49Ss+uy1m0ZDneLZvnAFQ0vRoBpZoHsi6UsK9nK36vCdf9P2HHgIef3dfPVCnMeZcVeWLfGh4fCtD2+h/xcZDieC+ppJdeUze3Ks/QGtAEKEn4IxyqvlaVUevc1Lf4iHE7Dz9+gJbmpeIWDQ1pvviM6mZuySaBo9HrZtGK86m/9ELCA8MEew5hShosCEwzfaCfLoG+0ycyurabTdMGHzz/AqMfjjAhi5CKZEMX3EpC0quqbobJyQzRSAS39+tcdP4KND3Ngvo6VNVyNAeOIZEN8XAilUEdGiA2OUV0coyp8TixhJdyZBL9wetpP/U7nLZwYZUyfJEQiTPtPPajZQxta2Fk+M+oDjOKuYJFMVESmX/dqkWsWhxEy0dwBRvo2/sR/f0uhC4csWoY+jEh0g1N1GmGbKGGWKZMMNzG+p9eR0l7CIvvUgq+U7BaqiVbFqtEtr+Pl97M8uKOURY6ZJE3ZnyeFGNxH0MjU2gph4jmBDc+aEPKRnF6ziPcVKC7+3T8/sBRANoxABVBR7Gkk04kiI1sx++5hNPPP4/hVzcysWE7u2srLLncTj7cKu6bpX/NI+wrnEYulKP23Ca2dl1DZ0NBaEAjUnteCE8v+w0noYEwgTovEx/uJZ2M896uXYKaqU9KsSzYsAluVJed2ZQAkhxn9qB4UfN9JDdvYNPOYd4vFtHqamlx19F1z52EHnsLk8j2ylsvc+1Xl+P60i3Enn6F2267GFX7O707nfgafCzqOIs9u3soWVXCDWG8NTWfBGAWvJ1ySjv19Q0k4nmWLOkgNjXOye2ddK74&#338;d+cAWO/F/YtD7BGT+8m6/ccjP+szpxLP8G0fgeOn9wDc3d7WjZSQa3HGR8Yz3W5c3sPbwHZU+ErvZGYvEEs7MJctnMJwFUirB3bz+jo5PEpkcYHhaRmJ2hVKkmqot0pp6paADHo79j8OyVRC3nUBA1X3LKZBIwVKzgKSSZHIlg1VS0M+pRVrZw+K99eN0WHGe6RTX5sNud5HKFT7Zjs9kQoXFitVoJLAiKRhHEbFZwuqwiKiq94zdzw49XUX3U4TbPVcJCVwfB166is2cVb8fKvPTcK1jNQnLrnDRf+WUir73H2+/nmBEG04nDuF0uIXcmZMux7n9sDBPprRPw1+FwelAUBZ+vXtRskqbGcFVVCHn7sXnNGGvW0HTBOWQNEyOxPA7Fgm4o6AWDYG0tTq+Nsijpk2xO+l7Zxr79LQRq3yd4s4OK74sE6m0i19SjVVA+DoCEySRTlYaJ8XEBwE9S8DUjdMDjNEhOxUmIkLdffT12n4/pgQi5zCSS4yKhH6fS1jJFrpDicN9h0XJtJIUittz/fX75wRDSoMHIIZXxR++l5oG7SKbmc0A6JonImqZzaDCCxaIwKNRLsVoYGYmSEeETMwSp6V18+2oHlkJRcD4rhE+la3G119sZ+MiNV3i/6KRWdu7YimpX8VSr5ZQuVn4hyfhogrEDQ+Qt+2gKhoRzdfP2j0vCqveChWpYB&#338;huZVO5fDX+YSEGjR1LOf0ZeKRUoqZtAWTUUHXrRRFn69GwuVtwC+i5vM3YbJ4CQnF9MrinXIAq0u0ulCc4O3fmpfg/5jAjSNVoGuUSgXcHg9OMVg0LqwT7TJF86JGoet2HFKU0bER0nnBZdlJtaXYzB/icDiRdJmx2VfJDGQ5kAsT9k5iKtZQLgZFlzGI6h7StjqMSD+ZxgZRhnn4WAxkqywr4aYgnR3drFv/NC+8NohTaHsgMIWY85gc2MbCziBtK88mMP2aoGuGO9bswVfnIunwc2V0I8tj20Wj6absHSNu14mKjFdseYpKB9O1V1KzajGDg4MkZmbmjGqa9u8qqBT04tatB0jECjz3yhY2bRX9PeijWK7yohOT/Xy3doyW57eh37oFU9lC79AVLJjNczgwy02bFVYUPYwxKrqFQhYxPwoPdTHw2NiFiB9nrInOjT9i+pozmkollaMApHPO6/ZNTCdOqmQrte0drQFDUtzpdMah6ZKomYpdU2y2uqlpm2ViXA1c0ayGm9tsu/Yoqugn1rhsti2ORqxLZhJqTtxokQ2rXVVla7Gs2Au64silLEmXjdoNDxC0hVi3bi3vvLNZF4PvHb29ve8I+wnpSAenOiB6qvPi/F7+mGAdn7iS0+m0WFXVapaFesmSaJZmxaxLit1pU1tbWxQ1X1ZJF+xSqajmjIx90m6x2c0uVYz6Tr/fv7+n5903EomZ6oQ6I81/nFjnZzTnnPocAXW84ePPn7aXTvDM0euqEGWrEZDny6FSnVDndPbI2fQpL/9fy3SC6+NBVe1U1b0sHfeJZpr33PQZPOcExk70exWAaIPkpU/j+jPuT0TJf/tPnwdR+ZcAAwDbfQ+khvADIwAAAABJRU5ErkJggg==',
          ),
        ),
      ),
      1 => 
      array (
        'logo' => 
        array (
          0 => 
          array (
            'extension' => 'png',
            'contenu' => 'iVBORw0KGgoAAAANSUhEUgAAACAAAAAgCAYAAABzenr0AAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAA4tpVFh0WE1MOmNvbS5hZG9iZS54bXAAAAAAADw/eHBhY2tldCBiZWdpbj0i77u/IiBpZD0iVzVNME1wQ2VoaUh6cmVTek5UY3prYzlkIj8+IDx4OnhtcG1ldGEgeG1sbnM6eD0iYWRvYmU6bnM6bWV0YS8iIHg6eG1wdGs9IkFkb2JlIFhNUCBDb3JlIDUuMC1jMDYwIDYxLjEzNDc3NywgMjAxMC8wMi8xMi0xNzozMjowMCAgICAgICAgIj4gPHJkZjpSREYgeG1sbnM6cmRmPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5LzAyLzIyLXJkZi1zeW50YXgtbnMjIj4gPHJkZjpEZXNjcmlwdGlvbiByZGY6YWJvdXQ9IiIgeG1sbnM6eG1wTU09Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9tbS8iIHhtbG5zOnN0UmVmPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvc1R5cGUvUmVzb3VyY2VSZWYjIiB4bWxuczpkYz0iaHR0cDovL3B1cmwub3JnL2RjL2VsZW1lbnRzLzEuMS8iIHhtbG5zOnhtcD0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wLyIgeG1wTU06RG9jdW1lbnRJRD0ieG1wLmRpZDozQzRGNDVBQUIwMzgxMUUzOTJBQkZDRjJGMTdCMDk5RCIgeG1wTU06SW5zdGFuY2VJRD0ieG1wLmlpZDozQzRGNDVBOUIwMzgxMUUzOTJBQkZDRjJGMTdCMDk5RCIgeG1wOkNyZWF0b3JUb29sPSJBZG9iZSBQaG90b3Nob3AgQ1M1IFdpbmRvd3MiPiA8eG1wTU06RGVyaXZlZEZyb20gc3RSZWY6aW5zdGFuY2VJRD0idXVpZDpmYWY1YmRkNS1iYTNkLTExZGEtYWQzMS1kMzNkNzUxODJmMWIiIHN0UmVmOmRvY3VtZW50SUQ9IjAxRTRFNTY2RDU4ODk0MzRDRTIxQTBCMUVGNTkxMDNEIi8+IDxkYzpjcmVhdG9yPiA8cmRmOlNlcT4gPHJkZjpsaT5TdWx2PC9yZGY6bGk+IDwvcmRmOlNlcT4gPC9kYzpjcmVhdG9yPiA8L3JkZjpEZXNjcmlwdGlvbj4gPC9yZGY6UkRGPiA8L3g6eG1wbWV0YT4gPD94cGFja2V0IGVuZD0iciI/PtCHSlIAAAlCSURBVHjaxFdtjB1VGX7OOfN5b3cL222XNqVQaGkBQRQCCSaIYO0ipWIxxJioURKCCTHRqImaGiP/hB8Y/1Sj8sM0QVDLh4TPUrABEiMgIghCwdJyt7t79/be3fsxd2bOOT7nzO62NfjPhNvO3rkz857zfjzv+zwjrLX4MD+B+1PqEhoW2oiNsEKA5/6fPXEYw2+jhQrUQqzUTBSGEO5RfnRJayu4DjbyrLJ3cfEwS/aWd7QVSomFMBAzaRJ7e+/A+82ZsfZ7b+3N329cTSNZbWZgSoOyLJEXJYpsiMGwK9qi1pnYvHn35OT2PUop78D0dGtsrvHO3rz9ztXWFtJtrK1BxqN0tkWBcthH&#338;iKznCkk646a/eNn//cniiKKgdkYW4d7n98MjrwR6RpACUCBMoiFAUCG6DkPoGLBAatsDb+stl1V/9T1+wbqalpZ59r+43h1GOTp7XuQxrnkCpEGGgkqoBVGogSHgZq1KCbrxx/anrnXf3BZ/fRgWnvANO5UXSPIy27SE2MmFuF3Ji5AMQQI7nkt4RkGiWzU4OOtdGjLnhnr2R4bmrnUTcLCFiAGAVC&#338;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',
          ),
        ),
      ),
      2 => 
      array (
        'logo' => 
        array (
          0 => 
          array (
            'extension' => 'png',
            'contenu' => 'iVBORw0KGgoAAAANSUhEUgAAAEAAAABACAYAAACqaXHeAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAyBpVFh0WE1MOmNvbS5hZG9iZS54bXAAAAAAADw/eHBhY2tldCBiZWdpbj0i77u/IiBpZD0iVzVNME1wQ2VoaUh6cmVTek5UY3prYzlkIj8+IDx4OnhtcG1ldGEgeG1sbnM6eD0iYWRvYmU6bnM6bWV0YS8iIHg6eG1wdGs9IkFkb2JlIFhNUCBDb3JlIDUuMC1jMDYwIDYxLjEzNDc3NywgMjAxMC8wMi8xMi0xNzozMjowMCAgICAgICAgIj4gPHJkZjpSREYgeG1sbnM6cmRmPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5LzAyLzIyLXJkZi1zeW50YXgtbnMjIj4gPHJkZjpEZXNjcmlwdGlvbiByZGY6YWJvdXQ9IiIgeG1sbnM6eG1wPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvIiB4bWxuczp4bXBNTT0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wL21tLyIgeG1sbnM6c3RSZWY9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9zVHlwZS9SZXNvdXJjZVJlZiMiIHhtcDpDcmVhdG9yVG9vbD0iQWRvYmUgUGhvdG9zaG9wIENTNSBXaW5kb3dzIiB4bXBNTTpJbnN0YW5jZUlEPSJ4bXAuaWlkOjY0QzA3RUIyQjAzODExRTNBQ0M1RURENkJBQTRCRTY1IiB4bXBNTTpEb2N1bWVudElEPSJ4bXAuZGlkOjY0QzA3RUIzQjAzODExRTNBQ0M1RURENkJBQTRCRTY1Ij4gPHhtcE1NOkRlcml2ZWRGcm9tIHN0UmVmOmluc3RhbmNlSUQ9InhtcC5paWQ6NjRDMDdFQjBCMDM4MTFFM0FDQzVFREQ2QkFBNEJFNjUiIHN0UmVmOmRvY3VtZW50SUQ9InhtcC5kaWQ6NjRDMDdFQjFCMDM4MTFFM0FDQzVFREQ2QkFBNEJFNjUiLz4gPC9yZGY6RGVzY3JpcHRpb24+IDwvcmRmOlJERj4gPC94OnhtcG1ldGE+IDw/eHBhY2tldCBlbmQ9InIiPz4NBDCdAAAoSklEQVR42uR7eZxlV13n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&#338;xMpnCsdk1vHL2ObJBC2FNRrBsYWecpB1mp9ec+iNFCgGgVGQ6i3jbLXEMXh7Flq38PjeL6lwZBauKNNVgtSyhRL5retrQHumAQio2dBGNpoH06go82nlmwBtfCrGg3ipicWGBA6QR0GxemP6AKciUQK5eRjIQdunML9iuivX7vGgQMx58+hBeHZtDW38fisUSZqZnoLM+A0TxZNjnYougNVFsxmC/THGzTMSPr/K6RHyKKnT3Q2so2JYoAudywPEpVGt1zObnkCPYNij2JOLRt79xP5Ldw/AnIvAwU01dx0p2RT1vDHi9EMjrLFUEPE1kcqtsdDf8VHF1pnputQVf3A+FKd7KLSPa9GCisxNDfZvRIm2GVBPbBvuxlCtg4fBLFEoyhZGXhokGSpJcueuArZH0I2V6iB0VbPUwC9h/ZCvAoMYsmGPHK2QWYg4dZqPVwKpRhV4UUTQDWKMy5TBgfHkGoycP4aa9uwi2intt1aNyTNQfPQOIShyNMGRPlG6QZohqrNmgE6NKE4+8iG2HVnBoqIA5ycJquROr45NQuvopSlT0xRV0R7yoktpyxZarDk2&#338;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&#338;DQGvWmsb8ukVn30+MRdu/wDnztZ9f1MZmrpjqWvfr8r0vF4wBuMkgrrSK056wHh8YmF/Ml0VrtHDCafDPf0NgqraUT9cbeU3zQAzvqYQIXlCdroCXZQcy8jV6i4e3xV2UcODzID2GA6s3YCV9MsQO4aQiAqw5Muoa+nF4deGcW3PnUPuvu60GB9NqQQRopfY2q/gJJ6JepSHEvJFxAIxXBWNFw0VrwySs086rE7IanMKnL73OwM5mZmXUP02u4EmcCsaNqiqMY/H9p84VGa4X3nnnuw1zT0kW0d/uh4VnuhrA1/ZyX/0oStehdp+AhydXc+8Yd5ybIlaiFvLNjfM+gSw5/cPY+//Lt7sGmw0/Xj7l5eSkzn4UfdCUKnio9+KIsSYrDW/grhPnrusyr+4fEIBnvHGCAqMr8f/+NoCzsqfVjEDKFqioAVcjc3OasAJi044QNRZlu79KeYaYtj8313MwMiHLE6/u0pN8GgNLY+88Lh19r7zPr7L17vwNVv2cX4mwfAssX86bOTi//zL+4PhlXNKpdynu0jSSkaViTdcB5Xk+CApmDpaASiaK0U4fnor6LC1E/+UQ2mp4S4fy+S7SPo7WRdmirEkB/xMQXtBNIqA+VxygiO5hTXH8HgKJk6A6qrCKLMzw3svviS15saDAZee2LNs7qaFvCf+JJtuXzfucnD//TckafDmi6Zl199IHLDZV2e5eU1jUwQarVMudlqJEh3UgBqTFFr2j+2bF+5VQ7K98eUPWM7I5lSUNzdPRtSBVOh8fLGq6L/aEKVW1JnpGb7BMEyvM6srjNZZ5vOlgsepBPUWS5hw481mp1Xj3wfg9FNcJ47rVWdx26C2Lp1uNrV1dX4Tw0A3yWifSkcDhCRRRcIXRXoKkFp/elM6BzDGhSCpaO3j2/vd5kgkZWxz3uJu7fPtOcEEohcqdY1sVH3vjjgl14Y9AUlwxSklhXQBUO5+PKLtYioBVqpFZUImii36qJYKcXzRlGd+4PfC8p6wC+LikKN0XnzzTe/2taWvCeVWqz+ZwZAeKunx/9ff4n4//z1rwIMAMtdxlxf78QAAAAAAElFTkSuQmCC',
          ),
        ),
      ),
    ),
  ),
);

?>