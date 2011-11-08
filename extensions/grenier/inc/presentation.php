<?php

/***************************************************************************\
 *  SPIP, Systeme de publication pour l'internet                           *
 *                                                                         *
 *  Copyright (c) 2001-2011                                                *
 *  Arnaud Martin, Antoine Pitrou, Philippe Riviere, Emmanuel Saint-James  *
 *                                                                         *
 *  Ce programme est un logiciel libre distribue sous licence GNU/GPL.     *
 *  Pour plus de details voir le fichier COPYING.txt ou l'aide en ligne.   *
\***************************************************************************/

if (!defined('_ECRIRE_INC_VERSION')) return;

define('_SIGNALER_ECHOS', true);
define('_INTERFACE_ONGLETS', false);

include_once(_ROOT_RESTREINT."inc/presentation.php");

// Faux HR, avec controle de couleur
// http://doc.spip.org/@hr
/*
function hr($color, $retour = false) {
	$ret = "\n<div style='height: 1px; margin-top: 5px; padding-top: 5px; border-top: 1px solid $color;'></div>";

	if ($retour) return $ret; else echo_log('hr',$ret);
}
*/
//
// Cadres
//
// http://doc.spip.org/@afficher_onglets_pages
function afficher_onglets_pages($ordre,$onglets){
	static $onglet_compteur = 0;
	$res = "";
	$corps = "";
	$cpt = 0;
	$actif = 0;
	// ordre des onglets
	foreach($ordre as $id => $label) {
		$cpt++;
		$disabled = strlen(trim($onglets[$id]))?"":" class='tabs-disabled'";
		if (!$actif && !$disabled) $actif = $cpt;
		$res .= "<li$disabled><a rel='$cpt' href='#$id'><span>" . $label . "</span></a></li>";
	}
	$res = "<ul class='tabs-nav'>$res</ul>";
	foreach((_INTERFACE_ONGLETS ? array_keys($ordre):array_keys($onglets)) as $id){
		$res .= "<div id='$id' class='tabs-container'>" . $onglets[$id] . "<br class='nettoyeur' /></div>";
	}
	$onglet_compteur++;
	return "<div class='boite_onglets' id='boite_onglet_$onglet_compteur'>$res</div>"
	. (_INTERFACE_ONGLETS ?
	   http_script("$('#boite_onglet_$onglet_compteur').tabs(".($actif?"$actif,":"")."{ fxAutoHeight: true });
	 if (!$.browser.safari)
	 $('ul.tabs-nav li').hover(
	 	function(){
	 		\$('#boite_onglet_$onglet_compteur').triggerTab(parseInt(\$(this).attr('rel')));
	 		return false;
	 	}
	 	,
	 	function(){}
	 	);")
	   :"");
}


// Voir en ligne, ou apercu, ou rien (renvoie tout le bloc)
// http://doc.spip.org/@voir_en_ligne
function voir_en_ligne ($type, $id, $statut=false, $image='racine-24.png', $af = true, $inline=true) {

	$en_ligne = $message = '';
	switch ($type) {
	case 'article':
			if ($statut == "publie" AND $GLOBALS['meta']["post_dates"] == 'non') {
				$n = sql_fetsel("id_article", "spip_articles", "id_article=$id AND date<=NOW()");
				if (!$n) $statut = 'prop';
			}
			if ($statut == 'publie')
				$en_ligne = 'calcul';
			else if ($statut == 'prop')
				$en_ligne = 'preview';
			break;
	case 'rubrique':
			if ($id > 0)
				if ($statut == 'publie')
					$en_ligne = 'calcul';
				else
					$en_ligne = 'preview';
			break;
	case 'breve':
	case 'site':
			if ($statut == 'publie')
				$en_ligne = 'calcul';
			else if ($statut == 'prop')
				$en_ligne = 'preview';
			break;
	case 'mot':
			$en_ligne = 'calcul';
			break;
	case 'auteur':
			$n = sql_fetsel('A.id_article', 'spip_auteurs_liens AS L LEFT JOIN spip_articles AS A ON (L.objet=\'article\' AND L.id_objet=A.id_article)', "A.statut='publie' AND L.id_auteur=".intval($id));
			if ($n) $en_ligne = 'calcul';
			else $en_ligne = 'preview';
			break;
	default: return '';
	}

	if ($en_ligne == 'calcul')
		$message = _T('icone_voir_en_ligne');
	else if ($en_ligne == 'preview'
	AND autoriser('previsualiser'))
		$message = _T('previsualiser');
	else
		return '';

	$h = generer_url_action('redirect', "type=$type&id=$id&var_mode=$en_ligne");

	return $inline
	  ? icone_inline($message, $h, $image, "", $GLOBALS['spip_lang_left'])
	: icone_horizontale($message, $h, $image, "",$af);

}

// http://doc.spip.org/@icone_inline
function icone_inline($texte, $lien, $fond, $fonction="", $align="", $ajax=false, $javascript=''){
	// cas d'ajax_action_auteur: faut defaire le boulot
	// (il faudrait fusionner avec le cas $javascript)
	if (preg_match(",^<a\shref='([^']*)'([^>]*)>(.*)</a>$,i",$lien,$r)) {
		list($x,$lien,$atts,$texte)= $r;
		$javascript .= $atts;
	}

	// l'ajax de l'espace prive made in php
	if ($ajax)
		$javascript .= ' onclick=' . ajax_action_declencheur($lien,$ajax);

	return icone_base($lien, $texte, $fond, $fonction,"verticale $align",$javascript);
}


// http://doc.spip.org/@navigation_pagination
function navigation_pagination($num_rows, $nb_aff=10, $href=null, $debut, $tmp_var=null, $on='') {

	$texte = '';
	$self = parametre_url(self(), 'date', '');
	$deb_aff = intval($debut);

	for ($i = 0; $i < $num_rows; $i += $nb_aff){
		$deb = $i + 1;

		// Pagination : si on est trop loin, on met des '...'
		if (abs($deb-$deb_aff)>101) {
			if ($deb<$deb_aff) {
				if (!isset($premiere)) {
					$premiere = '0 ... ';
					$texte .= $premiere;
				}
			} else {
				$derniere = ' | ... '.$num_rows;
				$texte .= $derniere;
				break;
			}
		} else {

			$fin = $i + $nb_aff;
			if ($fin > $num_rows)
				$fin = $num_rows;

			if ($deb > 1)
				$texte .= " |\n";
			if ($deb_aff + 1 >= $deb AND $deb_aff + 1 <= $fin) {
				$texte .= "<b>$deb</b>";
			}
			else {
				$script = parametre_url($self, $tmp_var, $deb-1);
				if ($on) $on = generer_onclic_ajax($href, $tmp_var, $deb-1);
				$texte .= "<a href=\"$script\"$on>$deb</a>";
			}
		}
	}

	return $texte;
}


// http://doc.spip.org/@generer_onclic_ajax
function generer_onclic_ajax($url, $idom, $val)
{
	return "\nonclick=\"return charger_id_url('"
	  . parametre_url($url, $idom, $val)
	  . "','"
	  . $idom
	  . '\');"';
}

//
// Afficher la hierarchie des rubriques
//

// http://doc.spip.org/@afficher_hierarchie
function afficher_hierarchie($id_parent, $editable=true,$id_objet=0,$type='',$id_secteur=0,$restreint='') {
	$out = recuperer_fond('prive/squelettes/hierarchie/objet',
					array(
						'id_parent'=>$id_parent,
						'objet'=>$type,
						'id_objet'=>$id_objet,
						'deplacer'=>_request('deplacer')?'oui':'',
						'id_secteur'=>$id_secteur,
						'restreint'=>$restreint,
						'editable'=>$editable?' ':'',
					),array('ajax'=>true));
	$out = pipeline('affiche_hierarchie',array('args'=>array(
			'id_parent'=>$id_parent,
			'id_objet'=>$id_objet,
			'objet'=>$type,
			'id_secteur'=>$id_secteur,
			'restreint'=>$restreint,
			'editable'=>$editable?' ':'',
			),
			'data'=>$out));

 	return $out;
}
// Cadre formulaires

// http://doc.spip.org/@debut_cadre_formulaire
function debut_cadre_formulaire($style=''){return "\n<div class='cadre-formulaire'" .(!$style ? "" : " style='$style'") .">";}
// http://doc.spip.org/@fin_cadre_formulaire
function fin_cadre_formulaire($return=false){return "</div>\n";}

// Pour construire des menu avec SELECTED
// http://doc.spip.org/@mySel
function mySel($varaut,$variable, $option = NULL) {
	$res = ' value="'.$varaut.'"' . (($variable==$varaut) ? ' selected="selected"' : '');
	return  (!isset($option) ? $res : "<option".$res.">$option</option>\n");
}


// http://doc.spip.org/@bonhomme_statut
function bonhomme_statut($row) {
	$puce_statut = charger_fonction('puce_statut', 'inc');
	return $puce_statut(0, $row['statut'], 0, 'auteur');
}


// http://doc.spip.org/@bouton_radio
function bouton_radio($nom, $valeur, $titre, $actif = false, $onClick="") {
	static $id_label = 0;

	if (strlen($onClick) > 0) $onClick = " onclick=\"$onClick\"";
	$texte = "<input type='radio' name='$nom' value='$valeur' id='label_${nom}_${id_label}'$onClick";
	if ($actif) {
		$texte .= ' checked="checked"';
		$titre = '<b>'.$titre.'</b>';
	}
	$texte .= " /> <label for='label_${nom}_${id_label}'>$titre</label>\n";
	$id_label++;
	return $texte;
}


// http://doc.spip.org/@afficher_choix
function afficher_choix($nom, $valeur_actuelle, $valeurs, $sep = "<br />") {
	$choix = array();
	while (list($valeur, $titre) = each($valeurs)) {
		$choix[] = bouton_radio($nom, $valeur, $titre, $valeur == $valeur_actuelle);
	}
	return "\n".join($sep, $choix);
}
?>