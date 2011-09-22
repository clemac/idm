<?php
# Auteur / Author : BobCaTT
# website : http://www.menfin.net

# Licence : LICENCE_*.TXT

// This is a SPIP language file  --  Ceci est un fichier langue de SPIP
$GLOBALS[$GLOBALS['idx_lang']] = array(
'adresse_courte'		=> 'Short address',
'aide'				=> 'Help',
'bouton_accepter'		=> 'Accept',
'bouton_enregistrer'		=> 'Save',
'bouton_refuser'		=> 'Refuse',
'callback_url'			=> 'Callback url',
'conf_av_plugin'		=> 'Advanced Configuration',
'conf_enregistree'		=> 'Configuration saved.',
'conf_plugin'			=> 'Plugin configuration',
'conf_twitter'			=> 'Twitter configuration',
'consumer_key'			=> 'Consumer Key',
'consumer_secret'		=> 'Consumer Secret',
'php_nok'			=> 'PHP 5.2 support not available',
'php_ok'			=> 'PHP 5.2 support available',
'curl_nok'			=> 'cURL support not available',
'curl_ok'			=> 'cURL support available',
'delai'				=> 'Processing delay (minute)',
'date'				=> 'Date',
'date_dernier_post'		=> 'Last date post',
'date_dernier_traitement'	=> 'Last processing date',
'delaipub'			=> 'Post delay (minute)',
'documentation'			=> '<br/><strong>Shorten an url:</strong> Make an url shorter using isgd service.
<br/><strong>Post a message:</strong>Update your twitter status.
<br/><hr/><strong>About this  plugin:</strong> This plugin is developped by BobCaTT (me) for <a href="http://www.enfin.net/" target="_blank">menfin.net</a> needs. This plugin is free for non-commercial use. Thanks to drop me a message if you like and/or use this plugin.',
'documentation_config'		=> '<br/>
<br/><strong>Verify:</strong> Verify your if the plugin is able to connect to twitter and check your tokens validity. The configuration is NOT saved.
<br/><strong>Twitter configuration</strong> To configure this plugin, you must feel <strong>Consumer Key</strong> and <strong>Consumer Secret</strong>. Thee can be obtained by registering a new twitter app <a href="https://dev.twitter.com/apps" target="_blank">here</a>. The <strong>callback url</strong> must be specified. You must grant <strong>Read &amp; Write</strong> permission to be able to publish. When created, fill <strong>Consumer key</strong> and <strong>Consumer secret</strong> and then click <strong>Link</strong>. A token will be returned by twitter for all communications.
<br/><strong>Twitter publication retries:</strong> Number of attempts before discarding the message. This prevent publication jam.
<br/><strong>Back search:</strong> Maximum number of articles to include for publication list.
<br/><strong>Post delay:</strong> Time (minutes) between the article online date and its publication on twitter.com. This feature should be used if you post-publish your articles. A value of 0 disable the feature.
<br/><strong>Intervalssing delay:</strong> Time (minutes) between two twitter publication. This is to prevent spamming twitter.
<br/><strong>Connection timeout:</strong> Timeout (seconds) to declare a connection failure for a remote service. 0 means infinite wait.
<br/><strong>Message format:</strong> You can use #LANG, #TITRE, #SOUSTITRE, #SURTITRE, #URL_ARTICLE but you can\'t use filters or conditions. #LANG returns the 2 charaters language name.
<br/><strong>Ignore if empty:</strong> If one or more tag returns an empty string, the message is not published.
<br/><strong>Simulation mode:</strong> The plugin behave normally but messages are not published online.
<br/><strong>is.gd:</strong> If #URL_ARTICLE is used, is.gd service will be used to shorten the url. If not checked, twitter will decided to shorten or not the submitted url.
<br/><strong>Proxy:</strong> If set, the plugin won\'t use the proxy difined in Spip configuration for all requests to twitter.com and is.gd. It should be safe to leave this option unchecked.
<br/><strong>Verbose mode:</strong> If set, the plugin will output some of its actions in tmp/*.log. This is usually useful when you need to uderstand why the plugin isn\'t working.
<br/><strong>Note:</strong> If the computed message is empty (bad tag, empty field...) it won\'t be published online. The next one in the list will be processed.
<br/><hr/><strong>About this  plugin:</strong> This plugin is developped by BobCaTT (me) for the <a href="http://www.menfin.net/" target="_blank">menfin.net</a> needs. This plugin is free for non-commercial use. Thanks to drop me a message if you like and/or use this plugin.',
'explicatif'			=> 'Interface to configure and manage notifications on <a href="http://twitter.com/" />twitter.com</a>.',
'envoyer'			=> 'Send',
'followers'			=> 'Followers',
'follower'			=> 'Follower',
'fonctions_manquantes'		=> 'You must have cURL and SimpleXML for this plugin',
'format'			=> 'Message format',
'ignorer_vide'			=> 'Ignore if empty',
'isgd'				=> 'Use <a href="http://is.gd" target="_blank">is.gd</a> to shorten urls',
'isgdclass_nok'			=> 'Class MyIsGd is missing',
'isgdclass_ok'			=> 'Class MyIsGd is available',
'json_nok'			=> 'json support not available',
'json_ok'			=> 'json support available',
'langue'			=> 'Lang',
'licence'			=> 'LICENCE_en.TXT',
'link'				=> 'Link',
'liste_publication'		=> 'Latest publications',
'login'				=> 'Login',
'max_essai'			=> 'Twitter publication retries',
'message'			=> 'Message',
'nom'				=> 'Name',
'pas_post'			=> 'Unknown',
'pas_traitement'		=> 'Unknown',
'noproxy'			=> 'Do not use Spip proxy',
'publier'			=> 'Post a message',
'publier_nok'			=> 'Failed to send message',
'publier_ok'			=> 'Message published',
'raccourcir'			=> 'Shorten an url',
'raccourcir_ok'			=> 'Shortened url',
'raccourcir_nok'		=> 'Failed to get short url',
'recherche'			=> 'Back search',
'simulation'			=> 'Simulation mode',
'statut'			=> 'Plugin Status',
'timeout'			=> 'Connection timeout (sec)',
'titre'				=> 'SpipTweets',
'titre_config'			=> 'SpipTweets',
'tronque'			=> 'Truncated',
'token'				=> 'Token',
'token_secret'			=> 'Secret',
'twit_abonnez_vous'		=> 'Follow me on twitter',
'twitter_consumer_nok'		=> 'Missing Consumer Key/Secret',
'twitter_ok'			=> 'Twitter Tokens Ok',
'twitter_token_nok'		=> 'Access token missing',
'twitteroauthclass_nok'		=> 'Class TwitterOAuth is missing',
'twitteroauthclass_ok'		=> 'Class TwitterOAuth is available',
'unlink'			=> 'Unlink',
'url'				=> 'Address',
'verbose'			=> 'Verbose mode',
'verifier'			=> 'Verify',
'verifier_nok'			=> 'Authentication failed on twitter.com',
'verifier_ok'			=> 'Authentication sucess on twitter.com'
);

?>
