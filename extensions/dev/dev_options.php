<?php

if (!defined('_ECRIRE_INC_VERSION')) return;

define('_LOG_FILTRE_GRAVITE',8);
$GLOBALS['test_i18n'] = true; // signaler les trads manquantes

function affiche_usage_memoire(){
	chdir(_ROOT_CWD); // precaution
	// dans l'espace prive uniquement, et si la fonction taille_en_octets est deja chargee
	if (test_espace_prive()
	    AND function_exists('taille_en_octets')
			AND !_request('action'))
		echo "<div style='position:fixed;top:0;right:0;color:#fff;background:#666;padding:5px;z-index:1000;'>"
		 . taille_en_octets(memory_get_usage())
		 . '</div>';
	if (isset($GLOBALS['_debug']))
		echo var_export($GLOBALS['_debug'],true);
}
register_shutdown_function('affiche_usage_memoire');

// Une fonction pour joliment afficher les variables de #ENV, #GET, #SESSION...
// à utiliser avec [(#ENV|bel_env)], [(#GET|bel_env)], [(#SESSION|bel_env)]
function bel_env($env) {
  $env = str_replace(array('&quot;', '&#039;'), array('"', '\''), $env);
  if (is_array($env_tab = @unserialize($env))) $env = $env_tab;
  if (!$env) return '';
  $res = "\n";
  foreach ($env as $nom => $valeur) {
    if (is_array($valeur)) $valeur = bel_env($valeur);
    else $valeur = entites_html($valeur);
    $res .= "|". entites_html($nom). "&nbsp;:&nbsp;|{" .$valeur. "}|\n";
  }
  return "\n<fieldset>\n" .propre($res). "</fieldset>\n";
}
