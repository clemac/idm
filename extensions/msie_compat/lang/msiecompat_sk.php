<?php
// This is a SPIP language file  --  Ceci est un fichier langue de SPIP
// extrait automatiquement de http://www.spip.net/trad-lang/
// ** ne pas modifier le fichier **

if (!defined('_ECRIRE_INC_VERSION')) return;

$GLOBALS[$GLOBALS['idx_lang']] = array(

	// C
	'choix_explication' => '<p>Toto nastavenie zlepšuje kompatibilitu verejnej stránky s prehliadačom Internet Explorer. </p>
<ul>
<li><a href=\'http://jquery.khurshid.com/ifixpng.php\'>iFixPng</a> (<b>predvolené nastavenia</b>) obnoví polopriehľadnosť obrázkov PNG v MSIE 5 a 6. </li>
<li><a href=\'http://code.google.com/p/ie7-js/\'>IE7.js</a> upraví priehľadnosť obrázkov PNG a pridá selektory CSS2 pre MSIE 5 a 6 (<a href=\'http://ie7-js.googlecode.com/svn/test/index.html\'>tu je zoznam kompatibilných selektorov zavedených v IE7.js a IE8.js</a>).</li>
<li>IE8.js vylepšuje IE7.js vďaka selektorom CSS pre MSIE 5 až 7.</li>
<li>IE7-mix opravuje 3 chyby v MSIE 6 (vrátane dvojitého okraja plávajúcich ojektov), ale môžu sa objaviť vedľajšie produkty (webmasteri by mali urobiť manuálne kontroly kompatibility). </li>
</ul>', # MODIF
	'choix_ie7' => '<a href=\'http://code.google.com/p/ie7-js/\'>IE7.js</a> corrige les images PNG et ajoute des sélecteurs CSS2 pour MSIE 5 et 6 (<a href=\'http://ie7-js.googlecode.com/svn/test/index.html\'>vous pouvez consulter la liste des sélecteurs compatibles introduits par IE7.js et IE8.js</a>).', # NEW
	'choix_ie7squish' => 'IE7-squish corrige trois bugs de MSIE 6 (notamment la double marge des éléments flottants), mais des effets indésirables peuvent apparaître (le webmestre doit vérifier la compatibilité).', # NEW
	'choix_ie8' => 'IE8.js complète IE7.js en enrichissant les comportements des CSS de MSIE 5 à 7.', # NEW
	'choix_ifixpng' => 'Choix par défaut, <a href=\'http://jquery.khurshid.com/ifixpng.php\'>iFixPng</a> rétablit la semi-transparence les images au format PNG sous MSIE 5 et 6.', # NEW
	'choix_non' => 'Nepovoliť: nepridávať nič na moje stránky',
	'choix_titre' => 'Kompatibilita s prehliadačom Microsoft Internet Explorer'
);

?>
