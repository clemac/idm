<?php
// This is a SPIP language file  --  Ceci est un fichier langue de SPIP
// extrait automatiquement de http://www.spip.net/trad-lang/
// ** ne pas modifier le fichier **

if (!defined('_ECRIRE_INC_VERSION')) return;

$GLOBALS[$GLOBALS['idx_lang']] = array(

	// C
	'choix_explication' => '<p>This configuration improves the public site\'s compatibility with Internet Explorer. </p>
<ul>
<li><a href=\'http://jquery.khurshid.com/ifixpng.php\'>iFixPng</a> (<b>default setting</b>) restores semi-transparency of PNG images on MSIE 5 and 6. </li>
<li><a href=\'http://code.google.com/p/ie7-js/\'>IE7.js</a> corrects PNG transparency and adds CSS2 selectors for MSIE 5 and 6 (<a href=\'http://ie7-js.googlecode.com/svn/test/index.html\'>here is a list of compatible selectors introduced by IE7.js and IE8.js</a>).</li>
<li>IE8.js enhances IE7.js with CSS selectors for MSIE 5 to 7.</li>
<li>IE7-squish fixes three bugs in MSIE 6 (including the double margin on floating elements), but side effects may appear (webmasters should make manual checks for compatibility). </li>
</ul>', # MODIF
	'choix_ie7' => '<a href=\'http://code.google.com/p/ie7-js/\'>IE7.js</a> corrige les images PNG et ajoute des sélecteurs CSS2 pour MSIE 5 et 6 (<a href=\'http://ie7-js.googlecode.com/svn/test/index.html\'>vous pouvez consulter la liste des sélecteurs compatibles introduits par IE7.js et IE8.js</a>).', # NEW
	'choix_ie7squish' => 'IE7-squish corrige trois bugs de MSIE 6 (notamment la double marge des éléments flottants), mais des effets indésirables peuvent apparaître (le webmestre doit vérifier la compatibilité).', # NEW
	'choix_ie8' => 'IE8.js complète IE7.js en enrichissant les comportements des CSS de MSIE 5 à 7.', # NEW
	'choix_ifixpng' => 'Choix par défaut, <a href=\'http://jquery.khurshid.com/ifixpng.php\'>iFixPng</a> rétablit la semi-transparence les images au format PNG sous MSIE 5 et 6.', # NEW
	'choix_non' => 'Disable: do not add anything to my pages',
	'choix_titre' => 'Microsoft Internet Explorer Compatibility'
);

?>
