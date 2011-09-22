<?php
# Auteur / Author : BobCaTT
# website : http://www.menfin.net

# Licence : LICENCE_*.TXT

if (!defined("_ECRIRE_INC_VERSION")) return;

/* fonctions utilisees pour l'administration du site */
/* traite les infos saisies, et appelle les fonctions necessaire */
function exec_Spiptweets()
{
	global $table_prefix;
	$action = '';

	include_spip('lib/twitteroauth/twitteroauth');
	include_spip('lib/spiptweets');
	include_spip('lib/isgd.class');
	include_spip('public/assembler');
	include_spip('inc/distant');
	include_spip('base/abstract_sql');

	$interface  = _request('interface');


	if ( _request('associer') ) $action = 'associer';
	if ( _request('associer2') ) $action = 'associer2';	/* config: callback twitter */
	if ( _request('dissocier') ) $action = 'dissocier';	/* config: dissocie le plugin et le compte twitter */
	if ( _request('verifier') ) $action = 'verifier';	/* config: verification association du plugin et du compte twitter */
	if ( _request('valider') ) $action = 'valider';		/* config: enregistrement des parametres */
	if ( _request('accepterlic') ) $action = 'accepterlic';	/* config: acceptation de la licence */
	if ( _request('refuserlic') ) $action = 'refuserlic';	/* config: refus de la licence */
	if ( _request('envoyer') ) $action = 'envoyer';		/* main: poster message */
	if ( _request('raccourcir') ) $action = 'raccourcir';	/* main: raccourcir url */
	if ( _request('twitid') ) $action = 'reinit';		/* main: remet a zero l'etat d'un tweet */

	$version_courante = '';

	if ( check_plugin_version($version_courante) != 0 )
	{
		spiptweets_migrer_metas($version_courante);
		spiptweets_creer_metas($version_courante);
		spiptweets_migrer_table($version_courante);
		spiptweets_creer_table($version_courante);
		update_plugin_version();
	}

	switch($interface)
	{
		case 'config':
			twitter_config($action);
		break;
		default:
			twitter_gestion($action);
		break;
	}
}

function twitter_gestion($action)
{
	$valeurs = array();
	$error = 0;

	if ( twitter_verifier_status($valeurs, false) == true )
	{
		$valeurs['statut_style'] = 'none;';
		switch( $action )
		{
			case 'envoyer':
				if ( twitter_publie_message($_POST['message']) == true )
				{
					$valeurs['publier'] = 'oui';
					$valeurs['publier_style'] = 'none;';
					$valeurs['publier_couleur'] = '#00C020;';
					$valeurs['publier_message'] = _T('spiptweets:publier_ok');
				}
				else
				{
					$valeurs['publier'] = 'non';
					$valeurs['publier_style'] = 'block;';
					$valeurs['publier_couleur'] = '#C00020;';
					$valeurs['publier_message'] = _T('spiptweets:publier_nok');
					$valeurs['publier_contenu_message'] = $_POST['message'];
				}
			break;

			case 'raccourcir':
				$valeurs['raccourcir_style'] = 'block;';
				$valeurs['raccourcir_contenu_url'] = $_POST['url'];
				$shorturl = '';
				if ( isgd_raccourcir_url($_POST['url'], $shorturl, $timeout) == true )
				{
					$valeurs['raccourcir'] = 'oui';
					$valeurs['raccourcir_couleur'] = '#00C020;';
					$valeurs['raccourcir_message'] = _T('spiptweets:raccourcir_ok');
					$valeurs['raccourcir_petite_url'] = $shorturl;
				}
				else
				{
					$valeurs['raccourcir'] = 'non';
					$valeurs['raccourcir_couleur'] = '#C00020;';
					$valeurs['raccourcir_message'] = _T('spiptweets:raccourcir_nok');
				}
			break;
			case 'reinit':
				$twitid = _request('twitid');
				if ( $twitid != NULL )
				{
					twitter_reinit_mesage($twitid);
					spiptweets_log('report : '.$twitid);
				}
			break;
		}


	}

	$valeurs['followers'] = $GLOBALS['meta']['spiptweets_tw_followers'];
	if ( $GLOBALS['meta']['spiptweets_date_traitement'] ) $valeurs['date_traitement'] = strftime('%d/%m/%G %H:%M:%S',$GLOBALS['meta']['spiptweets_date_traitement']);
	if ( $GLOBALS['meta']['spiptweets_date_post'] ) $valeurs['date_post'] = strftime('%d/%m/%G %H:%M:%S',$GLOBALS['meta']['spiptweets_date_post']);

	$commencer_page = charger_fonction('commencer_page', 'inc');

	echo $commencer_page($titre=_T('spiptweets:titre'));

	echo debut_gauche('', true);
	echo debut_droite('', true);
	echo debut_cadre_formulaire('', true);
	echo gros_titre(_T('spiptweets:titre'), '', false);

	echo recuperer_fond('formulaire/spiptweets_aide',$valeurs);
	echo recuperer_fond('formulaire/spiptweets_top',$valeurs);
	if ( twitter_verifier_status($valeurs, false) == true )
	{
		echo recuperer_fond('formulaire/spiptweets',$valeurs);
	}
	echo fin_cadre_formulaire(true);

	echo fin_gauche();
	echo fin_page();
}

/*
 * FIXED
 * Affiche l'interface de configuration du plugin
 */
function twitter_config($action)
{
	$valeurs = array();

	$type_admin = $GLOBALS['liste_des_statuts']['info_administrateurs'];
	$type_user  = $GLOBALS['connect_statut'];

	if ( twitter_verifier_status($valeurs, true) == true )
	{
		$valeurs['statut_style'] = 'none;';

		switch($action)
		{
			case 'accepterlic':
				plugin_licence_accepter();
				return;
			break;
			case 'associer':
				twitter_associer();
				return;
			break;
			case 'associer2':
				twitter_associer2();
				twitter_profile();
				return;
			break;
			case 'dissocier':
				twitter_dissocier();
				return;
			break;
			case 'verifier':
				$content = twitter_verifier();
				twitter_profile();
				if ( ! $content->error )
				{
					$valeurs['verifier'] = 'oui';
					$valeurs['verifier_couleur'] = '#00C020;';
					$valeurs['verifier_message'] = _T('spiptweets:verifier_ok');
					$valeurs['screen_name'] = $content->screen_name;
					$valeurs['profile_image_url'] = $content->profile_image_url;
					$valeurs['name'] = $content->name;
				}
				else
				{
					$valeurs['verifier'] = 'non';
					$valeurs['verifier_couleur'] = '#C00020;';
					$valeurs['verifier_message'] = _T('spiptweets:verifier_nok');
					$valeurs['error'] = $content->error;
				}
				$valeurs['statut_style'] = 'block;';
			break;
			case 'valider':
				if ( $_POST['recherche'] == '' || $_POST['recherche'] <= 0 ) { $valeurs['recherche'] = $recherche = 10; } else { $valeurs['recherche'] = $recherche = $_POST['recherche']; }
				if ( $_POST['max_essai'] == '' || $_POST['max_essai'] <= 0 ) { $valeurs['max_essai'] = $max_essai = 5; } else { $valeurs['max_essai'] = $max_essai = $_POST['max_essai']; }
				if ( $_POST['delai'] == '' || $_POST['delai']  < 0 ) { $valeurs['delai'] = $delai = 5; } else { $valeurs['delai'] = $delai = $_POST['delai']; }
				if ( $_POST['delaipub'] == '' || $_POST['delaipub'] < 0 ) { $valeurs['delaipub'] = $delaipub = 0; } else { $valeurs['delaipub'] = $delaipub = $_POST['delaipub']; }
				if ( $_POST['timeout'] == '' || $_POST['timeout'] < 0 ) { $valeurs['timeout'] = $timeout = 15; } else { $valeurs['timeout'] = $timeout = $_POST['timeout']; }
				if ( $_POST['format'] == '' ) { $valeurs['format'] = $format = '#TITRE @ #URL_ARTICLE'; } else { $valeurs['format'] = $format = $_POST['format']; }
				if ( ! array_key_exists('ignorervide',$_POST) ) { $valeurs['ignorervide'] = $ignorervide = 'non'; } else { $valeurs['ignorervide'] = $ignorervide = $_POST['ignorervide']; }
				if ( ! array_key_exists('simulation',$_POST) ) { $valeurs['simulation'] = $simulation = 'non'; } else { $valeurs['simulation'] = $simulation = $_POST['simulation']; }
				if ( ! array_key_exists('isgd',$_POST) ) { $valeurs['isgd'] = $isgd = 'non'; } else { $valeurs['isgd'] = $isgd = $_POST['isgd']; }
				if ( ! array_key_exists('noproxy',$_POST) ) { $valeurs['noproxy'] = $noproxy = 'non'; } else { $valeurs['noproxy'] = $noproxy = $_POST['noproxy']; }
				if ( ! array_key_exists('verbose',$_POST) ) { $valeurs['verbose'] = $verbose = 'non'; } else { $valeurs['verbose'] = $verbose = $_POST['verbose']; }

				ecrire_meta('spiptweets_recherche',$recherche);
				ecrire_meta('spiptweets_max_essai',$max_essai);
				ecrire_meta('spiptweets_delai',$delai);
				ecrire_meta('spiptweets_delaipub',$delaipub);
				ecrire_meta('spiptweets_timeout',$timeout);
				ecrire_meta('spiptweets_format',$format);
				ecrire_meta('spiptweets_ignorervide',$ignorervide);
				ecrire_meta('spiptweets_simulation',$simulation);
				ecrire_meta('spiptweets_isgd',$isgd);
				ecrire_meta('spiptweets_noproxy',$noproxy);
				ecrire_meta('spiptweets_verbose',$verbose);

				$valeurs['message'] = 'oui';
				$valeurs['message_txt'] = _T('spiptweets:conf_enregistree');
				$valeurs['message_couleur'] = '#00C020';
			break;
		}
	}

	lire_metas();
	if ( ! $valeurs['consumer_key'] ) $valeurs['consumer_key'] = $GLOBALS['meta']['spiptweets_consumer_key'];
	if ( ! $valeurs['consumer_secret'] ) $valeurs['consumer_secret'] = $GLOBALS['meta']['spiptweets_consumer_secret'];
	if ( ! $valeurs['oauth_token'] ) $valeurs['oauth_token'] = $GLOBALS['meta']['spiptweets_oauth_token'];
	if ( ! $valeurs['oauth_token_secret'] ) $valeurs['oauth_token_secret'] = $GLOBALS['meta']['spiptweets_oauth_token_secret'];

	if ( ! $GLOBALS['meta']['spiptweets_oauth_token'] )
	{
		$valeurs['bouton_multi1_label'] = _T('spiptweets:link');
		$valeurs['bouton_multi1_value'] = 'associer';
		$valeurs['bouton_multi1_style'] = 'visibility:visible;';
		$valeurs['bouton_multi2_style'] = 'visibility:hidden;';
	}
	else
	{
		$valeurs['bouton_multi1_label'] = _T('spiptweets:verifier');
		$valeurs['bouton_multi1_value'] = 'verifier';
		$valeurs['bouton_multi1_style'] = 'visibility:visible;';
		$valeurs['bouton_multi2_label'] = _T('spiptweets:unlink');
		$valeurs['bouton_multi2_value'] = _T('dissocier');
	}

	if ( ! $valeurs['recherche'] ) $valeurs['recherche'] = $GLOBALS['meta']['spiptweets_recherche'];
	if ( ! $valeurs['max_essai'] ) $valeurs['max_essai'] = $GLOBALS['meta']['spiptweets_max_essai'];
	if ( ! $valeurs['delai'] ) $valeurs['delai'] = $GLOBALS['meta']['spiptweets_delai'];
	if ( ! $valeurs['timeout'] ) $valeurs['timeout'] = $GLOBALS['meta']['spiptweets_timeout'];
	if ( ! $valeurs['delaipub'] ) $valeurs['delaipub'] = $GLOBALS['meta']['spiptweets_delaipub'];
	if ( ! $valeurs['format'] ) $valeurs['format'] = $GLOBALS['meta']['spiptweets_format'];
	if ( ! $valeurs['ignorervide'] ) 
	{
		if ( strlen($GLOBALS['meta']['spiptweets_ignorervide']) == 0 )
		{
			$valeurs['ignorervide'] = 'oui'; // valeur par defaut
		}
		else $valeurs['ignorervide'] = $GLOBALS['meta']['spiptweets_ignorervide'];
	}
	if ( ! $valeurs['simulation'] ) 
	{
		if ( strlen($GLOBALS['meta']['spiptweets_simulation']) == 0 )
		{
			$valeurs['simulation'] = 'non'; // valeur par defaut
		}
		else $valeurs['simulation'] = $GLOBALS['meta']['spiptweets_simulation'];
	}

	if ( ! $valeurs['isgd'] ) 
	{
		if ( strlen($GLOBALS['meta']['spiptweets_isgd']) == 0 )
		{
			$valeurs['isgd'] = 'non'; // valeur par defaut
		}
		else $valeurs['isgd'] = $GLOBALS['meta']['spiptweets_isgd'];
	}

	if ( ! $valeurs['noproxy'] ) 
	{
		if ( strlen($GLOBALS['meta']['spiptweets_noproxy']) == 0 )
		{
			$valeurs['noproxy'] = 'non'; // valeur par defaut
		}
		else $valeurs['noproxy'] = $GLOBALS['meta']['spiptweets_noproxy'];
	}

	if ( ! $valeurs['verbose'] ) 
	{
		if ( strlen($GLOBALS['meta']['spiptweets_verbose']) == 0 )
		{
			$valeurs['verbose'] = 'non'; // valeur par defaut
		}
		else $valeurs['verbose'] = $GLOBALS['meta']['spiptweets_verbose'];
	}

	$valeurs['spiptweets_callback_url'] = $_SERVER['SCRIPT_URI'].'?'.$_SERVER['QUERY_STRING'].'&associer2=1';

	$commencer_page = charger_fonction('commencer_page', 'inc');

	echo $commencer_page($titre=_T('spiptweets:titre'));

	echo debut_gauche('', true);
	echo debut_droite('', true);
	echo debut_cadre_formulaire('', true);
	echo gros_titre(_T('spiptweets:titre_config'), '', false);

	if ( $GLOBALS['meta']['spiptweets_licence'] == 'oui' )
	{
		echo recuperer_fond('formulaire/spiptweets_aide_config',$valeurs);
		echo recuperer_fond('formulaire/spiptweets_top',$valeurs);
		/* On affiche l'interface d'admin si un administrateur est connecte uniquement */
		if ( strlen($type_admin) > 0 && strlen($type_user) > 0 && strcmp($type_admin,$type_user) == 0)
		{
			echo recuperer_fond('formulaire/spiptweets_config',$valeurs);
		}
	}
	else
	{
		$licence = file_get_contents(find_in_path(_T('spiptweets:licence')));
		$valeurs['licence'] = $licence;
		echo recuperer_fond('formulaire/spiptweets_licence',$valeurs);
	}

	echo fin_cadre_formulaire(true);

	echo fin_gauche();
	echo fin_page();
}

