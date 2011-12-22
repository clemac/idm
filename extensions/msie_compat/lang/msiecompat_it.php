<?php
// This is a SPIP language file  --  Ceci est un fichier langue de SPIP
// extrait automatiquement de http://www.spip.net/trad-lang/
// ** ne pas modifier le fichier **

if (!defined('_ECRIRE_INC_VERSION')) return;

$GLOBALS[$GLOBALS['idx_lang']] = array(

	// C
	'choix_explication' => '<p>Questa configurazione ti consente di migliorare la compatibilità del sito pubblico con il browser Internet Explorer.</p>
   <ul>
   <li><a href=\'http://jquery.khurshid.com/ifixpng.php\'>iFixPng</a> (<b>predefinito</b>) ristabilisce la trasparenza delle immagini nel formato PNG con MSIE 5 e 6.</li>
   <li><a href=\'http://code.google.com/p/ie7-js/\'>IE7.js</a> corregge le immagini PNG e aggiunge dei selettori CSS2 per MSIE 5 e 6 (<a href=\'http://ie7-js.googlecode.com/svn/test/index.html\'>puoi consultare la lista dei selettori compatibili introdotti da IE7.js e IE8.js</a>).</li>
   <li>IE8.js completa IE7.js arricchendo i comportamenti del CSS da MSIE 5 a 7. </li>
   <li>IE7-squish corregge tre bug di MSIE 6 (in particolare il doppio margine degli elementi float), ma possono verificarsi degli effetti collaterali (il webmaster deve verificarne la compatibilità).</li>
   </ul>', # MODIF
	'choix_ie7' => '<a href=\'http://code.google.com/p/ie7-js/\'>IE7.js</a> corrige les images PNG et ajoute des sélecteurs CSS2 pour MSIE 5 et 6 (<a href=\'http://ie7-js.googlecode.com/svn/test/index.html\'>vous pouvez consulter la liste des sélecteurs compatibles introduits par IE7.js et IE8.js</a>).', # NEW
	'choix_ie7squish' => 'IE7-squish corrige trois bugs de MSIE 6 (notamment la double marge des éléments flottants), mais des effets indésirables peuvent apparaître (le webmestre doit vérifier la compatibilité).', # NEW
	'choix_ie8' => 'IE8.js complète IE7.js en enrichissant les comportements des CSS de MSIE 5 à 7.', # NEW
	'choix_ifixpng' => 'Choix par défaut, <a href=\'http://jquery.khurshid.com/ifixpng.php\'>iFixPng</a> rétablit la semi-transparence les images au format PNG sous MSIE 5 et 6.', # NEW
	'choix_non' => 'Non attivare: non aggiungere niente ai miei modelli',
	'choix_titre' => 'Compatibilità Microsoft Internet Explorer'
);

?>
