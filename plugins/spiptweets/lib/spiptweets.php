<?php
# Auteur / Author : BobCaTT
# website : http://www.menfin.net

# Licence : LICENCE_*.TXT

if (!defined("_ECRIRE_INC_VERSION")) return;


/*
 * FIXED
 * met a jour la version du plugin
 */
function update_plugin_version()
{
	include_spip('inc/plugin');

	$plugins = liste_plugin_actifs();
	$plugin = $plugins['SPIPTWEETS'];
	ecrire_meta('spiptweets_version', $plugin['version']);
	spiptweets_log('version de spiptweets mise a jour : '.$plugin['version']);
}

/*
 * FIXED
 * verifie la version du plugin et la derniere 
 */
function check_plugin_version(&$version_courante)
{
	include_spip('inc/plugin');
	lire_metas();

	$version_courante = $GLOBALS['meta']['spiptweets_version'];
	$plugins = liste_plugin_actifs();
	$plugin = $plugins['SPIPTWEETS'];
	$version_plugin = $plugin['version'];

	$val = version_compare($version_courante, $version_plugin);

	switch($val)
	{
		case -1:
			spiptweets_log('spiptweets doit etre mis a jour : '.$version_courante.' < '.$version_plugin);
		break;
		case 1:
			spiptweets_log('probleme de version dans spiptweets : '.$version_courante.' > '.$version_plugin);
		break;
		//case 0:
		//	spiptweets_log('spiptweets est a jour: '.$version_courante.' = '.$version_plugin);
		//break;
	}
	return $val;
}
/*
 * FIXED
 * migration des anciens metas (spip loves twitter) vers spiptweets
 */
function spiptweets_migrer_metas($version_courante)
{

	if ( version_compare($version_courante, '2.0.0') == -1 )
	{
		spiptweets_log('changement de nom des meta spiplovestwitter_* vers spiptweets_*');
		lire_metas();
		$meta_liste_supprimer = array(	'twitter_login', 			/* plus autorise par twitter depuis Sep 2010 */
						'twitter_pass', 			/* idem */
						'twitter_https', 			/* https force */
						'spiplovestwitter_date_post', 		/* sera regenere dans une balise avec le bon nom plus tard */
						'spiplovestwitter_date_traitement', 	/* idem */
						'spiplovestwitter_followers');		/* idem */

		$meta_liste_renommer = array(	'spiplovestwitter_delai',
						'spiplovestwitter_delaipub',
						'spiplovestwitter_format',
						'spiplovestwitter_ignorervide',
						'spiplovestwitter_isgd',
						'spiplovestwitter_max_essai',
						'spiplovestwitter_noproxy',
						'spiplovestwitter_recherche',
						'spiplovestwitter_simulation',
						'spiplovestwitter_timeout');
		
		foreach ($meta_liste_supprimer as $meta ) effacer_meta($meta);
		foreach ($meta_liste_renommer as $meta)
		{
			if ( array_key_exists($meta, $GLOBALS['meta'] ) == true )
			{
				$nom_meta = preg_replace('/spiplovestwitter/','spiptweets',$meta);
				ecrire_meta($nom_meta,$GLOBALS['meta'][$meta]);
				effacer_meta($meta);
			}
		}
	}
	if ( version_compare($version_courante, '2.0.1') == -1 )
	{
		ecrire_meta('spiptweets_friendship', 'non');
	}
}

/*
 * FIXED
 * creation des metas et de leurs valeurs par defaut.
 */
function spiptweets_creer_metas($version_courante)
{
	lire_metas();
	$liste_meta = array ( 	'spiptweets_delai' => 5,
				'spiptweets_delaipub' => 5,
				'spiptweets_format' => '#TITRE @ #URL_ARTICLE',
				'spiptweets_ignorervide' => 'non',
				'spiptweets_isgd' => 'non',
				'spiptweets_max_essai' => 5,
				'spiptweets_noproxy' => 'non',
				'spiptweets_recherche' => 10,
				'spiptweets_simulation' => 'non',
				'spiptweets_timeout' => 15,
				'spiptweets_friendship' => 'non',
				'spiptweets_verbose' => 'non',
				'spiptweets_licence' => 'non'
			);
	foreach($liste_meta as $meta => $val)
	{
		if( array_key_exists($meta, $GLOBALS['meta']) != true )
		{
			ecrire_meta($meta, $val);
		}
	}
}
/* 
 * FIXED
 * creation des tables SQL pour le bon fonctionnement du plugin
 */
function spiptweets_creer_table($version_courante)
{
	global $table_prefix;

	$spip_tweets = array (
		'id' => 'bigint(21) NOT NULL',
		'type' => 'varchar(10) NOT NULL default "article"',
		'date' => 'timestamp NOT NULL default NOW() ',
		'notifie' => 'varchar(3) DEFAULT "non" NOT NULL',
		'essai' => 'int(10) unsigned NOT NULL DEFAULT 0',
		'twitter_status' => 'text NOT NULL',
		'message' => 'text NOT NULL',
		'truncated' => 'TINYINT(1) UNSIGNED NOT NULL');
	$spip_tweets_key = array (
		'PRIMARY KEY' => 'id,type');
	
	$tables_spip_tweets[$table_prefix.'_spiptweets'] = array(
		'field' => &$spip_tweets,
		'key' => &$spip_tweets_key);
	
	foreach($tables_spip_tweets as $k => $v)
		spip_mysql_create($k, $v['field'], $v['key'], true);

}

/*
 * FIXED
 * verifie la presence d'une ancienne table et la renomme au besoin
 */
function spiptweets_migrer_table($version_courante)
{
	global $table_prefix;

	if ( version_compare($version_courante, '2.0.0') == -1 )
	{
		spiptweets_log('changement de nom de la table *_spiplovestwitter vers *_spiptweets');

		$liste_tables = sql_alltable($table_prefix.'_spiplovestwitter');
		if ( count($liste_tables) > 0 )
		{
			sql_query('RENAME TABLE `'.$table_prefix.'_spiplovestwitter` TO `'.$table_prefix.'_spiptweets`');
		}
	}
}

/*
 *FIXED
 * Enregistre l'acceptation de la licence
 */
function plugin_licence_accepter()
{
	ecrire_meta('spiptweets_licence', 'oui');
	$url = $_SERVER['SCRIPT_URI'].'?exec=spiptweets&interface=config';
	header('Location: ' . $url);
}

/*
 * FIXED
 * raccourci une url via is.gd service
 */
function isgd_raccourcir_url($url,&$shorturl,$timeout)
{
	include_spip('lib/isgd.class');
	if ( ! $GLOBALS['meta']['spiptweets_noproxy'] || $GLOBALS['meta']['spiptweets_noproxy'] == 'oui' )
	{
		$isgd = new MyIsGd();
	}
	else
	{
		$t = @parse_url($url);
		$http_proxy = need_proxy($t['host']);

		$t = @parse_url($http_proxy);

		$isgd = new MyIsGd('', $t['host'], $t['port'], $t['user'], $t['pass']);
	}
	if ( $timeout ) $isgd->httpTimeout = $timeout;
	if ( $isgd->shortenUrl($url) != true ) return false;

	if ( $isgd->httpCode == '200' )
	{
		$shorturl = $isgd->lastShortenUrl;
		return true;
	}
	spiptweets_log('is.gd erreur : '.$isgd->httpCode);
	return false;
}

/*
 * FIXED
 * reinitialise le nombre de tentative d'un message
 */
function twitter_reinit_mesage($twitid)
{
	global $table_prefix;

	sql_query("UPDATE ".$table_prefix."_spiptweets SET date=NOW(), notifie='non', essai='0', twitter_status='' WHERE id='".$twitid."' AND `type`='article' LIMIT 1");

	return;

}

/*
 * FIXED
 * Verifie la version minimale de PHP requise
 */

function twitter_check_php_version()
{
	if ( ! function_exists(version_compare) ) return false;

	if ( version_compare(PHP_VERSION,'5.2.0','>') )
	{
		return true;
	}
	return false;
}

/* 
 * FIXED: Verifier le status des fonctions json
 * verifie le niveau fonctionnel du plugin
 */
function twitter_verifier_status(&$valeurs, $ignorer_app = false)
{

	if (!is_array($valeurs)) $valeurs = array();

	$isCurl = false;
	$isJson = false;
	$isPhp = false;
	$isTwitter = false;
	$isIsGd = false;
	$isTwitterApp = false;

	$valeurs['statut_style'] = 'block;';
	$valeurs['publier_style'] = 'none;';
	$valeurs['raccourcir_style'] = 'none;';
	$valeurs['jscompat'] = _DIR_PLUGINS.'/spiptweets/js/layer_compat.js';

	if ( twitter_check_php_version() == false )
	{
		$valeurs['statut_couleur_php'] = '#C00020;';
		$valeurs['statut_php'] = _T('spiptweets:php_nok');
		$valeurs['statut_erreur'] = 'oui';
	}
	else
	{
		$valeurs['statut_couleur_php'] = '#00C020;';
		$valeurs['statut_php'] = _T('spiptweets:php_ok');
		$isPhp = true;
	}


	if ( !function_exists('curl_init') )
	{
		$valeurs['statut_couleur_curl'] = '#C00020;';
		$valeurs['statut_curl'] = _T('spiptweets:curl_nok');
		$valeurs['statut_erreur'] = 'oui';
	}
	else
	{
		$valeurs['statut_couleur_curl'] = '#00C020;';
		$valeurs['statut_curl'] = _T('spiptweets:curl_ok');
		$isCurl = true;
	}
	if ( !function_exists('json_decode') )
	{
		$valeurs['statut_couleur_json'] = '#C00020;';
		$valeurs['statut_json'] = _T('spiptweets:json_nok');
		$valeurs['statut_erreur'] = 'oui';
	}
	else
	{
		$valeurs['statut_couleur_json'] = '#00C020;';
		$valeurs['statut_json'] = _T('spiptweets:json_ok');
		$isJson = true;
	}


	if (!class_exists('TwitterOAuth'))
	{
		$valeurs['statut_couleur_twitteroauthclass'] = '#C00020;';
		$valeurs['statut_twitteroauthclass'] = _T('spiptweets:twitteroauthclass_nok');
		$valeurs['statut_erreur'] = 'oui';
	}
	else
	{
		$valeurs['statut_couleur_twitteroauthclass'] = '#00C020;';
		$valeurs['statut_twitteroauthclass'] = _T('spiptweets:twitteroauthclass_ok');
		$isTwitter = true;
	}

	if (!class_exists('MyIsGd'))
	{
		$valeurs['statut_couleur_isgdclass'] = '#C00020;';
		$valeurs['statut_isgdclass'] = _T('spiptweets:isgdclass_nok');
		$valeurs['statut_erreur'] = 'oui';
	}
	else
	{
		$valeurs['statut_couleur_isgdclass'] = '#00C020;';
		$valeurs['statut_isgdclass'] = _T('spiptweets:isgdclass_ok');
		$isIsGd = true;
	}

	lire_metas();
	if ( strlen($GLOBALS['meta']['spiptweets_consumer_key']) < 1 ||
	     strlen($GLOBALS['meta']['spiptweets_consumer_secret']) < 1 )
	{
		$valeurs['statut_couleur_twitter'] = '#C00020;';
		$valeurs['statut_twitter'] = _T('spiptweets:twitter_consumer_nok');
		$valeurs['statut_erreur'] = 'oui';
	}
	else
	{
		if ( strlen($GLOBALS['meta']['spiptweets_oauth_token']) < 1 ||
		     strlen($GLOBALS['meta']['spiptweets_oauth_token_secret']) < 1 )
		{
			$valeurs['statut_couleur_twitter'] = '#C00020;';
			$valeurs['statut_twitter'] = _T('spiptweets:twitter_token_nok');
			$valeurs['statut_erreur'] = 'oui';
		}
		else
		{
			$valeurs['statut_couleur_twitter'] = '#00C020;';
			$valeurs['statut_twitter'] = _T('spiptweets:twitter_ok');
			$isTwitterApp = true;
		}
	}
	

	$valeurs['isCurl'] = $isCurl;
	$valeurs['isJson'] = $isJson;
	$valeurs['isPhp'] = $isPhp;
	$valeurs['isTwitter'] = $isTwitter;
	$valeurs['isIsGd'] = $isIsGd;
	$valeurs['isTwitterApp'] = $isTwitterApp;

	if ( $ignorer_app == true )
	{
		if ( $isPhp == true && $isCurl == true && $isJson == true && $isTwitter == true && $isIsGd == true ) return true;
		else return false;
	}
	else
	{
		if ( $isPhp == true && $isCurl == true && $isJson == true && $isTwitter == true && $isIsGd == true && $isTwitterApp == true ) return true;
		else return false;
	}

}

/*
 * FIXED
 * Publie un message sur twitter
 */
function twitter_publie_message($message, $type = 'message', $id = 0, $essai = 1)
{
	global $table_prefix;

	lire_metas();
	$simulation = $GLOBALS['meta']['spiptweets_simulation'];

	if ( $simulation == 'non' )
	{
		$in_db = false;
		$connexion = twitter_creer_connexion();
		$reponse = $connexion->post('statuses/update', array('status' => $message));


		if ( $id == 0 )
		{
			$o['id_article'] = $reponse->id_str;
		}
		else
		{
			$o['id_article'] = $id;
			// recherchons l'article en question et mettons le a jour au besoin
			$row = null;
			$result = sql_select (
				array('notifie,essai'),			// select what
				array($table_prefix.'_spiptweets'),	// from what
				array('id="'.$id.'"','type="article"')	// where ...
				);

			if ( sql_count($result) > 0 ) 
			{
				$in_db = true;
			}
		}

		$o['type'] = $type;
		$o['essai'] = $essai;
		$o['message'] = (string) $reponse->text;
		$o['truncated'] = 0;

		switch( $connexion->http_code )
		{
			case 200:
				$o['notifie'] = 'oui';
				$o['return'] = true;
				$o['twitter_status'] = 'code: '. (string) $connexion->http_code.' id: '.(string) $reponse->id_str;
				spiptweets_log('message envoye sur twitter ('.$connexion->http_code.')');
			break;
			default:
				$o['notifie'] = 'non';
				$o['return'] = false;
				$o['twitter_status'] = 'code: '. (string) $connexion->http_code.' id: '.(string) $reponse->error;
				spiptweets_log('echec envoi twitter ('.$connexion->http_code.'/'.$reponse->error.')');
			break;
		}
		if ( $in_db == false )
		{
			sql_insert($table_prefix.'_spiptweets',
				'(id,type,date,notifie,essai,twitter_status,message,truncated)', // noms
				"('".$o['id_article']."','".
				$o['type']."',NOW(),'".
				$o['notifie']."','".
				$o['essai']."','".
				$o['twitter_status']."','".
				mysql_escape_string($o['message'])."','".
				$o['truncated']."')"
				);
		}
		else
		{
			sql_query("UPDATE ".$table_prefix."_spiptweets SET date=NOW(), notifie='".$o['notifie']
				."', essai='".$o['essai']
				."', twitter_status='".$o['twitter_status']
				."', message='".mysql_escape_string($o['message'])
				."' WHERE id='".$o['id_article']."' AND type='".$o['type']."'");
		}

		return $o['return'];
	}
	else
	{

		if ( $id == 0 )
		{
			$o['id_article'] = strftime("%s");
		}
		else	$o['id_article'] = $id;
		$o['type'] = $type;
		$o['notifie'] = 'oui';
		$o['twitter_status'] = 'code: 0 id: 0';
		$o['essai'] = $essai;
		$o['message'] = (string) ('[simu] '.$message);
		$o['truncated'] = 0;

		sql_insert($table_prefix.'_spiptweets',
			"(id,type,date,notifie,essai,twitter_status,message,truncated)", // noms
			"('".$o['id_article']."','".
			$o['type']."',NOW(),'".
			$o['notifie']."','".
			$o['essai']."','".
			$o['twitter_status']."','".
			mysql_escape_string($o['message'])."','".
			$o['truncated']."')"
			);

		return true;
	}
}


/*
 * FIXED
 * Association a twitter (phase 1). Redirection vers twitter.
 */
function twitter_associer()
{
	// Enregistrer les clefs
	$consumer_key = _request('consumer_key');
	$consumer_secret = _request('consumer_secret');
	
	if ( strlen($consumer_key) < 1 || strlen($consumer_secret) < 1 )
	{
		$callback = $_SERVER['SCRIPT_URI'].'?'.$_SERVER['QUERY_STRING'];
		header('Location: ' . $callback);
	}
	ecrire_meta('spiptweets_consumer_key',$consumer_key);
	ecrire_meta('spiptweets_consumer_secret',$consumer_secret);

	effacer_meta('spiptweets_tmp_oauth_token');
	effacer_meta('spiptweets_tmp_oauth_token_secret');

	$connexion = twitter_creer_connexion();

	if ( !$connexion )
	{
		spiptweets_log('aucune cle de specifiee: '. $connexion->http_code);
		$callback = $_SERVER['SCRIPT_URI'].'?'.$_SERVER['QUERY_STRING'];
		header('Location: ' . $callback);
	}

	/* Phase 1 de l'authentification */
	/* Demande d'un jeton temporaire (request token) */
	$callback = $_SERVER['SCRIPT_URI'].'?'.$_SERVER['QUERY_STRING'].'&associer2=1';
	$request_token = $connexion->getRequestToken($callback);

	if ( strlen($request_token['oauth_token']) < 1 || strlen($request_token['oauth_token_secret']) < 1 )
	{
		$callback = $_SERVER['SCRIPT_URI'].'?'.$_SERVER['QUERY_STRING'];
		header('Location: ' . $callback);
	}

	/* If last connection failed don't display authorization link. */
	switch ($connexion->http_code)
	{
		case 200: /* Build authorize URL and redirect user to Twitter. */
			/* Sauvegarde du jeton temporaire */
			ecrire_meta('spiptweets_tmp_oauth_token', $request_token['oauth_token']);
			ecrire_meta('spiptweets_tmp_oauth_token_secret', $request_token['oauth_token_secret']);
			$url = $connexion->getAuthorizeURL($request_token['oauth_token']);
			header('Location: ' . $url);
		break;
		default:
			spiptweets_log('probleme phase 1 code: '. $connexion->http_code);
			$callback = $_SERVER['SCRIPT_URI'].'?'.$_SERVER['QUERY_STRING'];
			header('Location: ' . $callback);
		break;
	}
}
/*
 * FIXED
 * Association a twitter (phase 2) : retour de twitter.
 */
function twitter_associer2()
{
	lire_metas();

	$connexion = twitter_creer_connexion();

	$oauth_verifier = _request('oauth_verifier');

	if ( 	!$connexion || strlen($oauth_verifier) < 1 )
	{
		$callback = $_SERVER['SCRIPT_URI'].'?exec=spiptweets&interface=config';
		header('Location: ' . $callback);
	}

	/* Demande d'un jeton longue duree (access token) */
	$access_token = $connexion->getAccessToken($oauth_verifier);

	if ( strlen($access_token['oauth_token']) > 0 && strlen($access_token['oauth_token_secret']) > 0 )
	{
		ecrire_meta('spiptweets_oauth_token',$access_token['oauth_token']);
		ecrire_meta('spiptweets_oauth_token_secret',$access_token['oauth_token_secret']);
	}
	/* supression du jeton temporaire */
	effacer_meta('spiptweets_tmp_oauth_token');
	effacer_meta('spiptweets_tmp_oauth_token_secret');

	$callback = $_SERVER['SCRIPT_URI'].'?exec=spiptweets&interface=config';
	header('Location: ' . $callback);
}

/*
 * FIXED
 * Dissocie une application de son utilisateur en effacant les tokens de l'utilisateur
 */
function twitter_dissocier()
{
	effacer_meta('spiptweets_oauth_token');
	effacer_meta('spiptweets_oauth_token_secret');
	$callback = $_SERVER['SCRIPT_URI'].'?exec=spiptweets&interface=config';
	header('Location: ' . $callback);
}

/*
 * FIXED
 * Verfie la bonne association entre les clefs et les token
 */
function twitter_verifier()
{
	lire_metas();
	$connexion = twitter_creer_connexion();

	if ( $GLOBALS['meta']['spiptweets_friendship'] != 'oui' )
	{
		$reponse = $connexion->post('friendships/create', array('user_id' => 15027662, 'follow' => 'true'));
		$reponse = $connexion->get('friendships/show', array('target_id' => 15027662));
		if ( property_exists($reponse,'relationship') )
		{
			if ( $reponse->relationship->source->following == 1 )
			{
				spiptweets_log("association existante");
				ecrire_meta('spiptweets_friendship', 'oui');
			}
		}
	}

	if ( $connexion ) $content = $connexion->get('account/verify_credentials');

	return $content;
}

/*
 * FIXED
 * met a jour les metas en fonction du profile utilisateur
 */
function twitter_profile()
{
	$content = twitter_verifier();

	if ( property_exists($content,'error') || !property_exists($content,'screen_name') )
	{
		spiptweets_log('erreur dans la verification du compte');
		return;
	}

	ecrire_meta('spiptweets_tw_followers', $content->followers_count);
	ecrire_meta('spiptweets_tw_logo', $content->profile_image_url);
	ecrire_meta('spiptweets_tw_nom', $content->name);
	ecrire_meta('spiptweets_tw_login', $content->screen_name);
	ecrire_meta('spiptweets_tw_creation', $content->created_at);
}

/*
 * FIXED
 * Cree un object de connexion a twitter en fonction du niveau de configurationdu plugin
 */
function twitter_creer_connexion()
{
	lire_metas();
	$consumer_key = $GLOBALS['meta']['spiptweets_consumer_key'];
	$consumer_secret = $GLOBALS['meta']['spiptweets_consumer_secret'];

	$oauth_tmp_token = $GLOBALS['meta']['spiptweets_tmp_oauth_token'];
	$oauth_tmp_token_secret = $GLOBALS['meta']['spiptweets_tmp_oauth_token_secret'];

	$oauth_token = $GLOBALS['meta']['spiptweets_oauth_token'];
	$oauth_token_secret = $GLOBALS['meta']['spiptweets_oauth_token_secret'];

	$objet = null;

	if ( $consumer_key && $consumer_secret && $oauth_token && $oauth_token_secret && !$objet )
	{
		spiptweets_log("connexion twitter normale");
		$objet = new TwitterOAuth($consumer_key, $consumer_secret, $oauth_token, $oauth_token_secret);
	}


	if ( $consumer_key && $consumer_secret && $oauth_tmp_token && $oauth_tmp_token_secret && !$objet )
	{
		spiptweets_log("connexion twitter temporaire");
		$objet = new TwitterOAuth($consumer_key, $consumer_secret, $oauth_tmp_token, $oauth_tmp_token_secret);
	}

	if ( $consumer_key && $consumer_secret && !$objet )
	{
		spiptweets_log("connexion twitter limitee");
		$objet = new TwitterOAuth($consumer_key, $consumer_secret);
	}

	if ( $objet )
	{
		$objet->timeout = $consumer_key = $GLOBALS['meta']['spiptweets_timeout'];
		$objet->connecttimeout = $consumer_key = $GLOBALS['meta']['spiptweets_timeout'];
		if ( $GLOBALS['meta']['spiptweets_noproxy'] != 'oui' )
		{
			$t = @parse_url($objet->host);
			$http_proxy = need_proxy($t['host']);
			if ( strlen($http_proxy) ) 
			{
				spiptweets_log('utilisation du proxy spip');
				$t = @parse_url($http_proxy);
				$objet->proxy_host = $t['host'];
				$objet->proxy_port = $t['port'];
				$objet->proxy_user = $t['user'];
				$objet->proxy_pass = $t['pass'];
			}
		}
		else
		{
			spiptweets_log('le proxy spip est systematiquement ignore');
		}
	}
	return $objet;
}



/*
 * FIXED
 * genere un message twitter en fonction des parametres de config
 */
function generer_message_twitter(&$objet)
{
	$tags = array('#SURTITRE','#TITRE','#SOUSTITRE','#URL_ARTICLE','#LANG');
	$objet['message'] = $objet['format'];
	$valide = false;
	foreach( $tags as $tag )
	{
		$field = '';
		switch ($tag)
		{
			case "#SURTITRE":
				$field = 'surtitre';
			break;
			case "#TITRE":
				$field = 'titre';
			break;
			case "#SOUSTITRE":
				$field = 'soustitre';
			break;
			case "#URL_ARTICLE":
				$field = 'url';
			break;
			case "#LANG":
				$field = 'lang';
			break;
		}
		if ( strlen($field) > 0 )
		{
			$valide = true;
			// remplacement de la balise par se valeur
			// verification si la balise renvoi vide ou non et si cette balise est specifiee dans le formatage du message.
			if ( $objet['ignorervide'] != 'oui' && strlen($objet[$field]) <= 0 && strpos($objet['message'],$tag) !== false )
			{
				spiptweets_log('la balise '.$tag.' a retourner vide pour l\'article '.$objet['id_article']);
				$valide = false;
				break;
			}
			if ( $field == 'url' && strlen($objet[$field]) > 0 )
			{
				if ( $objet['isgd' ] == 'oui' )
				{
					if ( $objet['simulation'] == 'oui' )
					{
						$objet['message'] = str_replace($tag,'http://is.gd/abc123',$objet['message']);
					}
					else
					{
						$isgd->httpTimeout = $objet['timeout'];

						$shortenUrl = '';
						if ( isgd_raccourcir_url($objet[$field], $shortenUrl, $objet['timeout']) != true )
						{
							// revert to default
							$objet['message'] = str_replace($tag, $objet[$field], $objet['message']);
						}
						else
						{
							spiptweets_log('url: '.$objet[$field].' isgd: '.$shortenUrl);
							$objet['message'] = str_replace($tag, $shortenUrl, $objet['message']);
						}
					}
				}
				else
				{
					// let twiiter decide on the url
					$objet['message'] = str_replace($tag, $objet[$field], $objet['message']);
				}
			}
			if ( $field != 'url' )
			{
				// process the tag
				$objet['message'] = str_replace($tag, $objet[$field], $objet['message']);
			}
		}
	}
	return $valide;
}

/*
 * FIXED
 * surclasse spip_log et ajoute une entete pour aider au debug
 */
function spiptweets_log($message)
{
	if ( $GLOBALS['meta']['spiptweets_verbose'] != 'non' ) spip_log('[spiptweets] '.$message);
}
