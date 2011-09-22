<?php
# Auteur / Author : BobCaTT
# website : http://www.menfin.net

# Licence : LICENCE_*.TXT

if (!defined("_ECRIRE_INC_VERSION")) return;

/*
 * FIXED
 * declaration de la balise
 */
function balise_SPIPTWEETS($p) 
{
	spiptweets_log('entree balise_SPIPTWEETS()');
	return calculer_balise_dynamique($p,'SPIPTWEETS', array());
}

/*
 * FIXED
 * fonction se chargeant de parser les arguments mis dans le skel
 * et de les mettre en ordre pour l'appel de la balise dynamique
 */
function balise_SPIPTWEETS_stat($skelargs, $filtres)
{
	/*
		les arguments sont a indiquer dans le tableau ci-dessous
		l'index correspond a la position de l'argument dans l'appel
		de la balise dynamique.
	*/
	$args = array ( 'process' => 0,
			'item' => 1
			);
	
	$finalargs = array();
	foreach( $skelargs as $skelarg )
	{
		foreach ($args as $karg => $varg )
		{
			if ( strstr($skelarg,$karg) != false )
			{
				// extraction argument
				$finalargs[$varg] = substr($skelarg,strpos($skelarg,'=')+1);
			}
		}
	}

	return $finalargs;
}

/*
 * FIXED
 * balise dynamique. Les arguments sont envoyes via balise_SPIPTWEETS_stat()
 */
function balise_SPIPTWEETS_dyn($process = 'oui', $item = '') 
{
	include_spip('inc/meta');
	include_spip('lib/spiptweets');
	lire_metas();

	$version_courante = '';
	if ( check_plugin_version($version_courante) != 0 )
	{
		spiptweets_migrer_metas($version_courante);
		spiptweets_creer_metas($version_courante);
		spiptweets_migrer_table($version_courante);
		spiptweets_creer_table($version_courante);
		update_plugin_version();
	}

	if ( $process == 'oui' )
	{
		publication_twitter();
	}
	
	switch($item)
	{
		case "followers":
			return (string)$GLOBALS['meta']['spiptweets_tw_followers'];
		break;
		case "logo":
			return (string)$GLOBALS['meta']['spiptweets_tw_logo'];
		break;
		case "nom":
			return (string)$GLOBALS['meta']['spiptweets_tw_nom'];
		break;
		case "date_creation":
			return (string)$GLOBALS['meta']['spiptweets_tw_creation'];
		break;
		case "login":
			return (string)$GLOBALS['meta']['spiptweets_tw_login'];
		break;
		case "url":
			return "http://twitter.com/".$GLOBALS['meta']['spiptweets_tw_login'];
		break;
		case "":
			return;
		break;
		default:
			spiptweets_log('item "'.$item.'" inconnu.');
		break;
	}
}

/*
 * FIXED
 * fonction principale de traitement.
 */
function publication_twitter()
{
	include_spip('lib/twitteroauth/twitteroauth');
	include_spip('lib/spiptweets');
	include_spip('lib/isgd.class');
	$valeurs = array();
	if ( !twitter_verifier_status($valeurs) )
	{
		spiptweets_log('le statut fonctionnel du plugin est incomplet.');
		return;
	}

	// verifier la saisie des params

	if ( 	strlen($GLOBALS['meta']['spiptweets_recherche']) == 0  || strlen($GLOBALS['meta']['spiptweets_max_essai']) == 0  ||
		strlen($GLOBALS['meta']['spiptweets_delai']) == 0  || strlen($GLOBALS['meta']['spiptweets_delaipub']) == 0  ||
		strlen($GLOBALS['meta']['spiptweets_timeout']) == 0  || strlen($GLOBALS['meta']['spiptweets_ignorervide']) == 0  ||
		strlen($GLOBALS['meta']['spiptweets_simulation']) == 0  || strlen($GLOBALS['meta']['spiptweets_format']) == 0  )
	{
		spiptweets_log('parametres manquant');
		return;
	}

	// verification du delai entre 2 traitements
	$maintenant = date('U');
	if ( strlen($GLOBALS['meta']['spiptweets_date_traitement']) )
	{
		$avant = $GLOBALS['meta']['spiptweets_date_traitement'];
		$delai = $GLOBALS['meta']['spiptweets_delai'];
		if ( $maintenant < (($delai*60)+$avant) )
		{
			// trop tot
			// spiptweets_log('trop tot '.$maintenant.' -- '.$avant.' -- '.$delai);
			return;
		}
	}
	// a la bonne heure

	include_spip('inc/utils');
	generer_url_entite();
	include_spip('base/abstract_sql');
	include_spip('inc/filtres');
	include_spip('inc/distant');


	ecrire_meta('spiptweets_date_traitement',$maintenant);

	$recherche = $GLOBALS['meta']['spiptweets_recherche'];
	$max_essai = $GLOBALS['meta']['spiptweets_max_essai'];
	$delaipub = $GLOBALS['meta']['spiptweets_delaipub'];
	$timeout = $GLOBALS['meta']['spiptweets_timeout'];
	$format = $GLOBALS['meta']['spiptweets_format'];
	$ignorervide = $GLOBALS['meta']['spiptweets_ignorervide'];
	$simulation = $GLOBALS['meta']['spiptweets_simulation'];
	$isgd = $GLOBALS['meta']['spiptweets_isgd'];

	if ( $recherche == '' || $recherche <= 0 ) $recherche = 10;
	if ( $max_essai == '' || $max_essai <= 0 ) $max_essai = 5;
	if ( $timeout == '' || $timeout < 0 ) $timeout = 15;
	if ( $delaipub == '' || $delaipub < 0 ) $delaipub = 0;
	$delaipub = $delaipub*60;	// passage de minutes en secondes

	global $table_prefix;
	$objets = sql_allfetsel (
			array('id_article','surtitre','titre','soustitre','lang'),	// select what
			array($table_prefix.'_articles'),				// from what
			array("statut='publie'","date <= (NOW()-".$delaipub.")"),	// where
			null,								// group by
			array('date desc'),						// order by
			$recherche							// limit
			);

	$objets = array_reverse($objets);

	foreach ($objets as $objet )
	{
		$row = null;
		$result = sql_select (
			array('notifie,essai'),						// select what
			array($table_prefix.'_spiptweets'),				// from what
			array('id="'.$objet['id_article'].'"','type="article"')		// where ...
			);

		if ( sql_count($result) > 0 ) 
		{
			$row = sql_fetch($result);
			if ( $row['notifie'] == 'oui' )
			{
				// deja publie sur twitter
				continue;
			}
			if ( $row['essai'] >= $max_essai )
			{
				// nombre de tentatives epuise
				continue;
			}
		}

		// a ce point, l'article en cours peut etre emis vers twitter.com
		if ( $row != null && is_array($row) )
		{
			$objet['essai'] = $row['essai'];
			$objet['in_db'] = 1;
		}
		else $objet['in_db'] = 0;

		$objet['type'] = 'article';
		$objet['notifie'] = 'non';
		$objet['twitter_status'] = '';
		$objet['format'] = $format;
		$objet['ignorervide'] = $ignorervide;
		$objet['simulation'] = $simulation;
		$objet['timeout'] = $timeout;
		$objet['isgd'] = $isgd;
		$objet['noproxy'] = $noproxy;
		$objet['url'] =  url_de_base() . generer_url_entite($objet['id_article'], 'article');

		// spiptweets_log(print_r($objet,true));

		if ( generer_message_twitter($objet) != true ) 
		{
			spiptweets_log('la generation du message a renvoye une erreur');
			continue;
		}
		$objet['essai'] = $objet['essai']+1;

		// spiptweets_log(print_r($objet,true));

		if ( twitter_publie_message($objet['message'], 'article', $objet['id_article'], $objet['essai']) == true )
		{
			ecrire_meta('spiptweets_date_post',$maintenant);
		}
		spiptweets_log("id: ".$objet['id_article']." envoi: ".$objet['notifie']." msg: ".$objet['message']);


		if ( $objet['simulation'] == 'non' )
		{
			twitter_profile();
		}
		// une et une seule notification par cron pour ne pas surcharger twitter.com
		break;
	}

	return;
}
