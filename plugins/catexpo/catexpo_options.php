<?php
/**
 * Options du plugin Galerie d&#039;artau chargement
 *
 * @plugin     Galerie d&#039;art
 * @copyright  2014
 * @author     Sylvain Breil
 * @licence    GNU/GPL
 * @package    SPIP\Catexpo\Options
 */

if (!defined('_ECRIRE_INC_VERSION')) return;


/*
 * Un fichier d'options permet de définir des éléments
 * systématiquement chargés à chaque hit sur SPIP.
 *
 * Il vaut donc mieux limiter au maximum son usage
 * tout comme son volume !
 * 
 */
//$GLOBALS['dossier_squelettes'] .= ':plugins/catexpo/public';
$table_des_traitements['NOMENCLATURE_EXISTANTE'][]= '';
$table_des_traitements['NOMENCLATURE_STRICTE'][]= '';
$table_des_traitements['VERSION'][] = 'supprimer_numero(%s)';
?>