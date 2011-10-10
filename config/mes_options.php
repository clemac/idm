<?php

$idm_team_relecture = array (327,633,637);
$idm_team_billets   = array (63,285,286,7,50);

define ('_ID_WEBMESTRES', '1');

//ini_set('display_errors', '1');

$type_urls = "propres2";

function prenom_nom ($texte) {
  $texte = preg_replace ('/([^,]+), ([^,]+)/s', '\2 \1', $texte);
  return $texte;
}

// Perdre le cookie d'admin a la daconnection :

include_spip('inc/cookie');

$table_des_traitements['TITRE'][]= 'supprimer_numero(%s)';
$table_des_traitements['NOM'][]= 'prenom_nom(%s)';

function action_logout()
{
  global $auteur_session, $ignore_auth_http;
  $logout =_request('logout');
  $url = _request('url');
  spip_log("logout $logout $url" . $auteur_session['id_auteur']);
  // cas particulier, logout dans l'espace public
  if ($logout == 'public' AND !$url)
    $url = url_de_base();

  // seul le loge peut se deloger (mais id_auteur peut valoir 0 apres une restauration avortee)
  if (is_numeric($auteur_session['id_auteur'])) {
    spip_query("UPDATE spip_auteurs SET en_ligne = DATE_SUB(NOW(),INTERVAL 15 MINUTE) WHERE id_auteur = ".$auteur_session['id_auteur']);
    // le logout explicite vaut destruction de toutes les sessions
    if ($_COOKIE['spip_session']) {
      $session = charger_fonction('session', 'inc');
      $session($auteur_session['id_auteur']);
      preg_match(',^[^/]*//[^/]*(.*)/$,',
        url_de_base(),
        $r);
      spip_setcookie('spip_session', '', -1,$r[1]);
      spip_setcookie('spip_session', '', -1);
      spip_setcookie('spip_admin', '', time() - 3600 * 24);
    }
    if ($_SERVER['PHP_AUTH_USER'] AND !$ignore_auth_http) {
      include_spip('inc/actions');
      if (verifier_php_auth()) {
        ask_php_auth(_T('login_deconnexion_ok'),
          _T('login_verifiez_navigateur'),
          _T('login_retour_public'),
          "redirect=". _DIR_RESTREINT_ABS,
          _T('login_test_navigateur'),
          true);
        exit;
      }
    }
  }
  redirige_par_entete(url_de_base());
}

include_spip ('inc/envoyer_mail');

function inc_envoyer_mail ($destinataire, $sujet, $corps, $from = "", $headers = "") {

  if (!email_valide($destinataire)) return false;
  if ($destinataire == _T('info_mail_fournisseur')) return false; // tres fort

  // Fournir si possible un Message-Id: conforme au RFC1036,
  // sinon SpamAssassin denoncera un MSGID_FROM_MTA_HEADER

  $email_envoi = $GLOBALS['meta']["email_envoi"];
  if (!email_valide($email_envoi)) {
    spip_log("Meta email_envoi invalide. Le mail sera probablement vu comme spam.");
    $email_envoi = $destinataire;
  }

  if (is_array($corps)){
    $texte = $corps['texte'];
    $from = (isset($corps['from'])?$corps['from']:$from);
    $headers = (isset($corps['headers'])?$corps['headers']:$headers);
    if (is_array($headers))
      $headers = implode("\n",$headers);
    $parts = "";
    if ($corps['pieces_jointes'] AND function_exists('mail_embarquer_pieces_jointes'))
      $parts = mail_embarquer_pieces_jointes($corps['pieces_jointes']);
  } else
    $texte = $corps;

  if (!$from) $from = $email_envoi;

  // ceci est la RegExp NO_REAL_NAME faisant hurler SpamAssassin
  if (preg_match('/^["\s]*\<?\S+\@\S+\>?\s*$/', $from))
    $from .= ' (' . str_replace(')','', translitteration(str_replace('@', ' at ', $from))) . ')';

  // nettoyer les &eacute; &#8217, &emdash; etc...
  // les 'cliquer ici' etc sont a eviter;  voir:
  // http://mta.org.ua/spamassassin-2.55/stuff/wiki.CustomRulesets/20050914/rules/french_rules.cf
  $texte = nettoyer_caracteres_mail($texte);
  $sujet = nettoyer_caracteres_mail($sujet);

  // encoder le sujet si possible selon la RFC
  if (init_mb_string()) {
    # un bug de mb_string casse mb_encode_mimeheader si l'encoding interne
    # est UTF-8 et le charset iso-8859-1 (constate php5-mac ; php4.3-debian)
    $charset = $GLOBALS['meta']['charset'];
    mb_internal_encoding($charset);
    $sujet = mb_encode_mimeheader($sujet, $charset, 'Q', "\n");
    mb_internal_encoding('utf-8');
  }

  if (function_exists('wordwrap') && (preg_match(',multipart/mixed,',$headers) == 0))
    $texte = wordwrap($texte);

  list($headers, $texte) = mail_normaliser_headers($headers, $from, $destinataire, $texte, $parts);

  if (_OS_SERVEUR == 'windows') {
    $texte = preg_replace ("@\r*\n@","\r\n", $texte);
    $headers = preg_replace ("@\r*\n@","\r\n", $headers);
    $sujet = preg_replace ("@\r*\n@","\r\n", $sujet);
  }

  spip_log("mail $destinataire\n$sujet\n$headers",'mails');
  // mode TEST : forcer l'email
  if (defined('_TEST_EMAIL_DEST')) {
    if (!_TEST_EMAIL_DEST)
      return false;
    else
      $destinataire = _TEST_EMAIL_DEST;
  }

  return @mail($destinataire, $sujet, $texte, $headers, "-f noreply@images.math.cnrs.fr");
}
?>
