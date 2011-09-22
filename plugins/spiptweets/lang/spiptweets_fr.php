<?php
# Auteur / Author : BobCaTT
# website : http://www.menfin.net

# Licence : LICENCE_*.TXT

// This is a SPIP language file  --  Ceci est un fichier langue de SPIP
$GLOBALS[$GLOBALS['idx_lang']] = array(
'adresse_courte'		=> 'Adresse courte',
'aide'				=> 'Aide',
'bouton_accepter'		=> 'Accepter',
'bouton_enregistrer'		=> 'Enregistrer',
'bouton_refuser'		=> 'Refuse',
'callback_url'			=> 'Adresse de retour <small>(Callback url)</small>',
'conf_av_plugin'		=> 'Configuration avanc&eacute;e',
'conf_enregistree'		=> 'Configuration enregistree.',
'conf_plugin'			=> 'Configuration plugin',
'conf_twitter'			=> 'Configuration twitter',
'consumer_key'			=> 'Cl&eacute; client <small>(Consumer Key)</small>',
'consumer_secret'		=> 'Secret client <small>(Consumer Secret)</small>',
'php_nok'			=> 'Le support PHP 5.2 n\'est pas disponible',
'php_ok'			=> 'Le support PHP 5.2 est disponible',
'curl_nok'			=> 'Le support cURL n\'est pas disponible',
'curl_ok'			=> 'Le support cURL est disponible',
'delai'				=> 'Interval de traitement (minute)',
'date'				=> 'Date',
'date_dernier_post'		=> 'Date de la derni&egrave;re publication',
'date_dernier_traitement'	=> 'Date du dernier traitement de la balise',
'delaipub'			=> 'D&eacute;lai de publication (minute)',
'documentation'			=> '<br/><strong>Raccourcir une url:</strong>Raccourci une url via le service isgd.
<br/><strong>Publier un message:</strong>Mets &agrave; jour votre status twitter avec le message fourni.
<br/><hr/><strong>A propos du plugin:</strong> Ce plugin est d&eacute;velopp&eacute; par BobCaTT (moi) pour les besoins du site <a href="http://www.menfin.net/" target="_blank">menfin.net</a>. Ce plugin est fourni gratuitement pour une utilisation non commerciale. Merci de me laisser un message si vous utilisez et que vous aimez ce plugin spip.  ',
'documentation_config'		=> '<br/>
<br/><strong>Configuration Twitter</strong> Pour configurer ce plugin, vous devez renseigner la <strong>Cl&eacute; client <small>(Consumer Key)</small></strong> et le <strong>Secret client <small>(Consumer Secret)</small></strong>. Ceux-ci sont obtenus en enregistrant une <a href="https://dev.twitter.com/apps" target="_blank">application twitter</a>. L\'<strong>adresse de retour <small>(Callback url)</small></strong> est &agrave; pr&eacute;ciser. Il faut aussi choisir <strong>Read &amp; Write</strong> pour les permissions. Sans cela, vous ne pourrez pas publier. Une fois votre application cr&eacute;&eacute;e, remplissez les informations <strong>Cl&eacute; client</strong> et <strong>Secret client</strong>, puis associez les &agrave votre compte twitter en cliquant sur <strong>Associer</strong>. Un nouveau jeton sera fournit par twitter pour les communications futures.
<br/><strong>V&eacute;rifier:</strong> V&eacute;rifie la validit&eacute; des jetons de connexion, mais n\'enregistre pas la configuration.
<br/><strong>Tentatives twitter:</strong> Nombre d\'essai de publication avant d\'ignorer l\'article. Evite un embouteillage dans la file de publication.
<br/><strong>Nombre de recherches:</strong> Nombre d\'articles &agrave; inclure dans la recherche pour la publication twitter.
<br/><strong>D&eacute;lai de publication:</strong> Nombre de minutes entre la mise en ligne de celui-ci et sa publication sur twitter. Principalement utilis&eacute; pour le passage d\'article en post-publication. 0 permet de d&eacute;sactiver cette fonctionnalit&eacute;.
<br/><strong>Interval de traitement:</strong> D&eacute;lai entre 2 recherches d\'article pour la balise. 0 pour un traitement sans d&eacute;lai (Attention &agrave; twitter.com)
<br/><strong>D&eacute;lai de connexion:</strong> D&eacute;lai maximum pour une tentative de connexion &agrave; un service distant. 0 correspond &agrave; une attente infinie de connexion.
<br/><strong>Format:</strong> Vous pouvez utiliser #LANG, #TITRE, #SOUSTITRE, #SURTITRE, #URL_ARTICLE. Vous ne pouvez pas utiliser les filtres ou les conditions. La balise #LANG renvoie le nom de la langue sur 2 caract&egrave;res uniquement.
<br/><strong>Ignorer si vide:</strong> Ne publie pas le message si une balise renvoi vide.
<br/><strong>Simulation:</strong> Le plugin fonctionne normalement mais la balise ne publie aucun message sur twitter.
<br/><strong>is.gd:</strong> l\'url contenu dans le message twitter est raccourci via le service <a href="http://is.gd" target="_blank">is.gd</a> au lieu de laisser twitter raccourcir l\'url. Si l\'url courte n\'est pas obtenue, alors l\'url par d&eacute;faut est utilis&eacute;e.
<br/><strong>Proxy:</strong> Indique au plugin de ne pas utiliser le proxy pour toutes les requ&ecirc;tes vers twitter.com et is.gd. Il ne devrait pas &ecirc;tre n&eagrave;cessaire d\'activer cette option.
<br/><strong>Proxy:</strong> Indique au plugin de ne pas utiliser le proxy pour toutes les requ&ecirc;tes vers twitter.com et is.gd. Il ne devrait pas &ecirc;tre n&eagrave;cessaire d\'activer cette option.
<br/><strong>Mode verbeux:</strong> Permettre de rendre le plugin plus parlant dans les logs de spip. Cela aide a comprendre pourquoi le plugin ne fonctionne pas. 
<br/><strong>Note:</strong> Si le message calcul&eacute; est vide (mauvaise balises, champs vides...) celui-ci n\'est pas publi&eacute;. Le message suivant est ensuite analys&eacute;.
<br/><hr/><strong>A propos du plugin:</strong> Ce plugin est d&eacute;velopp&eacute; par BobCaTT (moi) pour les besoins du site <a href="http://www.menfin.net/" target="_blank">menfin.net</a>. Ce plugin est fourni gratuitement pour une utilisation non commerciale. Merci de me laisser un message si vous utilisez et que vous aimez ce plugin spip.  ',
'explicatif'			=> 'Interface permettant d\'administrer et de configurer les notifications de publication sur <a href="http://twitter.com/" />twitter.com</a>.',
'envoyer'			=> 'Envoyer',
'followers'			=> 'abonn&eacute;s',
'follower'			=> 'abonn&eacute;',
'fonctions_manquantes'		=> 'Vous devez avoir le support cURL et SimpleXML pour ce plugin',
'format'			=> 'Formatage du message twitter',
'ignorer_vide'			=> 'Ignorer si vide',
'isgd'				=> 'Utiliser <a href="http://is.gd" target="_blank">is.gd</a> pour raccourcir les urls',
'isgdclass_nok'			=> 'Class MyIsGd absente',
'isgdclass_ok'			=> 'Class MyIsGd disponible',
'json_nok'			=> 'Le support json n\'est pas disponible',
'json_ok'			=> 'Le support json est disponible',
'langue'			=> 'Langue',
'licence'			=> 'LICENCE_fr.TXT',
'link'				=> 'Associer',
'liste_publication'		=> 'Liste des derni&egrave;res publications',
'login'				=> 'Login',
'max_essai'			=> 'Tentatives de publication',
'message'			=> 'Message',
'nom'				=> 'Nom',
'pas_post'			=> 'Inconnue',
'pas_traitement'		=> 'Inconnue',
'noproxy'			=> 'Ne pas utiliser le proxy Spip',
'publier'			=> 'Publier un message',
'publier_nok'			=> 'Echec de l\'envoi du message',
'publier_ok'			=> 'Message mis en ligne',
'raccourcir'			=> 'Raccourcir une url',
'raccourcir_ok'			=> 'Adresse raccouci',
'raccourcir_nok'		=> 'Echec d\'obtention de l\'adresse courte',
'recherche'			=> 'Nombre de recherches',
'simulation'			=> 'Mode simulation',
'statut'			=> 'Statut fonctionnel du plugin',
'timeout'			=> 'D&eacute;lai de connexion (sec)',
'titre'				=> 'SpipTweets',
'titre_config'			=> 'SpipTweets',
'token'				=> 'Jeton',
'token_secret'			=> 'Secret',
'tronque'			=> 'Tronqu&eacute;',
'twit_abonnez_vous'		=> 'Suivre le flux twitter du site',
'twitter_consumer_nok'		=> 'Cl&eacute;/Secret Twitter manquant. (Consumer Key/Secret)',
'twitter_ok'			=> 'Jetons Twitter pr&egrave;sent',
'twitter_token_nok'		=> 'Jeton d\'acc&egrave;s Twitter manquant. (Access token)',
'twitteroauthclass_nok'		=> 'Class TwitterOAuth absente',
'twitteroauthclass_ok'		=> 'Class TwitterOAuth disponible',
'unlink'			=> 'Dissocier',
'url'				=> 'Adresse',
'verbose'			=> 'Mode verbeux',
'verifier'			=> 'V&eacute;rifier',
'verifier_nok'			=> 'Echec de l\'authentification twitter.com',
'verifier_ok'			=> 'Authentification twitter.com valide'
);

?>
