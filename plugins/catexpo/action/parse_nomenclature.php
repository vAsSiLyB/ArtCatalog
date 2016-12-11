<?php
/**
 * peut être appelée comme un filtre sur : #ID_CATALOGUE|parse_nomenclature ou #VAL{''}|parse_nomenclature{#ID_OEUVRE}
 * 
 * @return Array :
 * un tableau indexé par id_oeuvre : 
 * la nomenclature existante et une proposition de normalisation
 */
function parse_nomenclature($id_catalogue='', $id_oeuvre='') {
	$resultat='';
	$check=0;
	
	if (intval($id_oeuvre)) {
		$oeuvres=sql_allfetsel('id_oeuvre, numero_inventaire, titre, titre_secret, date_real, date_estimee_debut, periode, thematique, technique, support, id_catalogue, numero_ordre','spip_oeuvres','id_oeuvre='.$id_oeuvre);
		$id_catalogue=$oeuvres[0]['id_catalogue'];
	}
	else if (intval($id_catalogue)) {
		$oeuvres=sql_allfetsel('id_oeuvre, numero_inventaire, titre, titre_secret, date_real, date_estimee_debut, periode, thematique, technique, support, numero_ordre','spip_oeuvres','id_catalogue='.$id_catalogue, '', 'CASE WHEN date_real = "0000-00-00 00:00:00" THEN 1 ELSE 0 END, date_real');
	}
	$infos_cat=sql_fetsel('nomenclature_existante, nomenclature_stricte','spip_catalogues','id_catalogue='.$id_catalogue);
	$nomenclature_existante=unserialize($infos_cat['nomenclature_existante']);
	$nomenclature_stricte=unserialize($infos_cat['nomenclature_stricte']);
	
	foreach ($oeuvres as $oeuvre) {
		
		// reconnaissance mode "souple" : test séquentiel à mesure qu'on assemble la RegExp
		$oeuvre['match']=0; 
		$regexp_construct='';
		$i=1;
		foreach ($nomenclature_existante as $key=>$nomenc) {
			if ($nomenc) {
				$regexp_construct.='('.$nomenc.')[-/_:]?';
				preg_match('@'.$regexp_construct.'@', $oeuvre['numero_inventaire'], $matches);
				if (count($matches)>$i) {
					$oeuvre['match']++;
					$oeuvre['piece'][$key]=$matches[$i];
				}
				$i++;
			}
		}
		
		// validation mode souple : les deux premiers critères définis et reconnus (préfixe et numéro) suffisent à un "match" valide 
		if ($oeuvre['match']>=2) $oeuvre['parsed']=true;
		
		//	Le parsing en mode souple a réussi : on construit la proposition pour la normalisation mode strict
		
		if ($nomenclature_stricte['utiliser']=='Oui' && $oeuvre['parsed']==true) {
			$separateur=$nomenclature_stricte['Séparateur'];
			$replacement_carA='A';
			$replacement_cara='a';
			$replacement_digit='0';

			foreach ($nomenclature_stricte as $key=>$nomenc) {
				if (!empty($nomenc) && $key!=='utiliser' && $key!=='auto' && $key!=='Séparateur') {
					$parse=true;
					// On compare -pièce à pièce- : si une pièce n'est pas reconnue en mode "souple" elle ne sera pas traitée en mode "strict"
					$piece=$oeuvre['piece'][$key];
						
						// Cas particuliers : on ne parse pas
						if ($key == 'Préfixe') { $parse=false;
							$oeuvre['nomenc_stricte'][$key].=$nomenc;
						}
						else if ($key == 'Constante1') { $parse=false;
							if ($oeuvre[$nomenc])
								$oeuvre['nomenc_stricte'][$key].=strtoupper(substr(nettoyerChaine($oeuvre[$nomenc]),0,$nomenclature_stricte['nbr_lettres1']));
							else $oeuvre['nomenc_stricte'][$key].='?';
						}
						else if ($key == 'Constante2') { $parse=false;
							if ($oeuvre[$nomenc])
								$oeuvre['nomenc_stricte'][$key].=strtoupper(substr(nettoyerChaine($oeuvre[$nomenc]),0,$nomenclature_stricte['nbr_lettres2']));
							else $oeuvre['nomenc_stricte'][$key].='?';
						}
						else if ($key == 'nbr_lettres1' || $key == 'nbr_lettres2') $parse=false;
						
						//Cas général: on définit l'index auquel le parsing donnera une valeur
						else $oeuvre['nomenc_stricte'][$key]='';

					while ($parse==true && $nomenc) {
						// problème en php5, substring ne renvoie jamais false pour un $start en dehors de la chaine (il renvoie le dernier caractère valide)
						// => on prend un $start statique
						$sample=substr($nomenc,-1,1);
						$sub_piece=substr($piece,-1,1);
						// => et on shift les string
						$nomenc=substr($nomenc,0,-1);
						$piece=substr($piece,0,-1);
						
						if ($sub_piece) {
							switch ($sample) {
								case 'A': if (ctype_alpha($sub_piece)) $oeuvre['nomenc_stricte'][$key].=strtoupper($sub_piece);
											else $oeuvre['nomenc_stricte'][$key].= $replacement_carA;
											break;
								case 'a': if (ctype_alpha($sub_piece)) $oeuvre['nomenc_stricte'][$key].=strtolower($sub_piece);
											else $oeuvre['nomenc_stricte'][$key].= $replacement_cara;
											break;
								case '1': if (intval($sub_piece)) $oeuvre['nomenc_stricte'][$key].=$sub_piece;
											else $oeuvre['nomenc_stricte'][$key].= $replacement_digit;
											break;
							}
						}
						else $oeuvre['nomenc_stricte'][$key].= $replacement_digit;
					}
					// On a parsé à rebours => on renverse la piece enregistrée avant de passer à la suivante 
					if ($parse==true) $oeuvre['nomenc_stricte'][$key]=strrev($oeuvre['nomenc_stricte'][$key]);
				}
			}
			// Au moins un des masques a produit un résultat : on considère qu'on a une proposition valide pour la normalisation
			if (count($oeuvre['nomenc_stricte'])) {
				// Concaténation
				if ($separateur) {
					$oeuvre['normalized']=implode($separateur,$oeuvre['nomenc_stricte']);
				}
				else $oeuvre['normalized']=implode($oeuvre['nomenc_stricte']);
			}
		}
		// Le parsing en mode souple a échoué : c'est peut-être que le numéro d'inventaire est déjà conforme dans le mode strict
		else if ($nomenclature_stricte['utiliser']=='Oui' && ($oeuvre['parsed']!=true || $nomenclature_stricte['auto']=='Oui')) {
			$separateur=$nomenclature_stricte['Séparateur'];
			// On créé un masque au format RegExp à partir de la def de la nomenclature stricte
			$masque='';
			foreach ($nomenclature_stricte as $key=>$nomenc) {
				if (!empty($nomenc) && $key!=='utiliser' && $key!=='auto' && $key!=='Séparateur') {
					$nom=$nomenc;
					if ($key == 'Préfixe') {
						$masque.=$nom.$separateur;
					}
					else if ($key == 'Constante1') {
						if ($nomenc) {
							$masque_part=strtoupper(substr(nettoyerChaine($oeuvre[$nomenc]),0,$nomenclature_stricte['nbr_lettres1'])).$separateur;
							$masque.=$masque_part;
							
							// Permettre un tri selon le critère constante
							$oeuvre['nomenc_stricte']['masque_constante']=$masque_part;
							$oeuvre['nomenc_stricte']['constantes'][]=$oeuvre[$nomenc];
						}
					}
					else if ($key == 'Constante2') {
						if ($nomenc) {
							$masque_part=strtoupper(substr(nettoyerChaine($oeuvre[$nomenc]),0,$nomenclature_stricte['nbr_lettres2'])).$separateur;
							$masque.=$masque_part;
							
							// Permettre un tri selon le critère constante
							$oeuvre['nomenc_stricte']['masque_constante'].=$masque_part;
							$oeuvre['nomenc_stricte']['constantes'][]=$oeuvre[$nomenc];
						}
					}
					else if ($key == 'Numéro') {
						$long=strlen($nomenc);
						//  L'utilsateur a choisi le mode "inventaire auto" : on lui fait une proposition
						if ($nomenclature_stricte['auto']=='Oui') {
							//  basée sur le numéro_d'ordre
							$oeuvre['generation']=$masque.str_pad($oeuvre['numero_ordre'],$long,'0',STR_PAD_LEFT);
							$oeuvre['auto']=true;
						}
						$oeuvre['nomenc_stricte']['numero_long']=$long;
						$masque.='[0-9]{'.$long.'}';
						if ($nomenclature_stricte['Partie_optionnelle']) $masque.=$separateur;
					} 
					else if ($key == 'Partie_optionnelle') {
						while ($nom) {
							$sample=substr($nom,0,1);
							$nom=substr($nom,1);
							switch ($sample) {
								case 'A' : $masque.='[A-Z]'; break;
								case 'a' : $masque.='[a-z]'; break;
								case '1' : $masque.='[0-9]'; break;
							}
						}
					}
				} 
			}
			// On test le masque sur le numéro d'inventaire
			if (preg_match('@'.$masque.'@',$oeuvre['numero_inventaire'])) {
				$oeuvre['conforme']=true;
				$check++;
			}
		}
		$resultat[$oeuvre['id_oeuvre']]=$oeuvre;
		if ($check==count($oeuvres)) $resultat[$oeuvre['id_oeuvre']]['structure_inventaire_valide']=true;
	}
	return $resultat;
}

function nettoyerChaine($chaine) {
	$caracteres = array(
			'À' => 'a', 'Á' => 'a', 'Â' => 'a', 'Ä' => 'a', 'à' => 'a', 'á' => 'a', 'â' => 'a', 'ä' => 'a', '@' => 'a',
			'È' => 'e', 'É' => 'e', 'Ê' => 'e', 'Ë' => 'e', 'è' => 'e', 'é' => 'e', 'ê' => 'e', 'ë' => 'e', '€' => 'e',
			'Ì' => 'i', 'Í' => 'i', 'Î' => 'i', 'Ï' => 'i', 'ì' => 'i', 'í' => 'i', 'î' => 'i', 'ï' => 'i',
			'Ò' => 'o', 'Ó' => 'o', 'Ô' => 'o', 'Ö' => 'o', 'ò' => 'o', 'ó' => 'o', 'ô' => 'o', 'ö' => 'o',
			'Ù' => 'u', 'Ú' => 'u', 'Û' => 'u', 'Ü' => 'u', 'ù' => 'u', 'ú' => 'u', 'û' => 'u', 'ü' => 'u', 'µ' => 'u',
			'Œ' => 'oe', 'œ' => 'oe',
			'$' => 's');
	$chaine = strtr($chaine, $caracteres);
	$chaine = preg_replace('#\s#', '_', $chaine);
	return $chaine;
}
?>