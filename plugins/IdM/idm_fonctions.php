<?php

function idm_boite_infos (&$flux) {
  if ($flux['args']['type'] == 'article'
    AND $id_article = intval($flux['args']['id'])
    AND $statut = $flux['args']['row']['statut']
    AND $statut == 'prop') {

      $message = 'G&eacute;rer la relecture';
      $url     = generer_url_public ("propose", array("id_article" => $id_article));
      $previsu = icone_horizontale ($message, $url, find_in_path("img/relecteurs.gif"), "rien.gif", false);

      if ($p = strpos ($flux['data'], '</ul>')) {
        while ($q = strpos ($flux['data'],'</ul>',$p+5))
          $p=$q;
        $flux['data'] = substr($flux['data'],0,$p+5) . $previsu . substr($flux['data'],$p+5);
      }
      else $flux['data'] .= $previsu;
    }

  return $flux;
}

function idm_autoriser() {}

function autoriser_article_relire_dist ($faire, $type, $id, $qui, $opt) {
  if ($qui['id_auteur'] == 0) return false;
  if ($qui['statut'] == '0minirezo') return true;

  $id_auteur = $qui['id_auteur'];
  if (sql_countsel('spip_auteurs_articles', "id_article = $id AND id_auteur = $id_auteur")) return true;
  if (sql_countsel('spip_relecteurs_articles', "id_article = $id AND id_auteur = $id_auteur")) return true;

  return false;
}

function autoriser_idm_bouton_dist ($faire, $type, $id, $qui, $opt) {
  if ($qui['statut'] == '0minirezo') return true;
  return false;
}

function autoriser_idm_projets_bouton_dist ($faire, $type, $id, $qui, $opt) {
  return autoriser_idm_bouton_dist ($faire, $type, $id, $qui, $opt);
}

function autoriser_idm_relecteurs_bouton_dist ($faire, $type, $id, $qui, $opt) {
  return autoriser_idm_bouton_dist ($faire, $type, $id, $qui, $opt);
}

function autoriser_idm_relecture_bouton_dist ($faire, $type, $id, $qui, $opt) {
  return autoriser_idm_bouton_dist ($faire, $type, $id, $qui, $opt);
}

function autoriser_idm_moderation_bouton_dist ($faire, $type, $id, $qui, $opt) {
  return autoriser_idm_bouton_dist ($faire, $type, $id, $qui, $opt);
}

function autoriser_idm_billettistes_bouton_dist ($faire, $type, $id, $qui, $opt) {
  return autoriser_idm_bouton_dist ($faire, $type, $id, $qui, $opt);
}

function idm_notify ($ids, $message, $subject = "Un message du site \"Images des Maths\"") {
  $message = "Bonjour !\n\n" . $message . "\n\n-- \nLe robot du site http://images.math.cnrs.fr/\n";
  $envoyer_mail = charger_fonction ('envoyer_mail', 'inc');

  foreach ((array)$ids as $id) {
    $email = "comite@images.math.cnrs.fr";
    if ($id > 0) $email = sql_getfetsel ("email", "spip_auteurs", "id_auteur = $id");
    $envoyer_mail ($email, $subject, $message);
  }
}

function idm_jquery_plugins ($scripts) {
  $scripts[] = 'javascript/jquery-ui.min.js';
  //  $scripts[] = 'javascript/jquery.checkboxtree.js';
  $scripts[] = 'javascript/jquery.tablesorter.min.js';
  return $scripts;
}

function idm_import_sujets () {
  $raw = file_get_contents(find_in_path("Dewey.org"));

  preg_match_all ('/\| ([0-9\.]*) *\| ([0-9\.]*) *\| ([^\|]*[^ ]) *\|/',
                  $raw, $out, PREG_SET_ORDER);

  foreach ($out as $r)
    sql_insertq ("spip_idm_sujets",
                 array("id_sujet" => $r[1],
                       "id_parent" => $r[2],
                       "intitule" => $r[3]));
}

function idm_dewey ($cote) {
  return preg_replace ('/([0-9][0-9][0-9])([0-9]+)/', '$1.$2', $cote);
}

/*
 * Complete the automated treatment by NoSPAM to kill non-spam messages
 * by cranks and the like.
 *
 * @param array $flux
 * @return array
 */

function idm_pre_edition ($flux) {
  if ($flux['args']['table']=='spip_forum' AND $flux['args']['action']=='instituer' AND $flux['data']['statut']=='publie') {
    $id_auteur   = intval($GLOBALS['visiteur_session']['id_auteur']);
    $nb_forums   = sql_countsel ('spip_forum',            "id_auteur=$id_auteur AND statut='publie'");
    $nb_refuses  = sql_countsel ('spip_forum',            "id_auteur=$id_auteur AND statut='off'");
    $nb_articles = sql_countsel ('spip_auteurs_articles', "id_auteur=$id_auteur");

    if ($nb_refuses>0) {
      spip_log ("IdM: visitor $id_auteur has a comment in the trash, moderating.");
      $flux['data']['statut']='prop';
    }

    if (($nb_forums<1) AND ($nb_articles<1)) {
      spip_log ("IdM: visitor $id_auteur has no article and too few comments, moderating.");
      $flux['data']['statut']='prop';
    }

    if (strlen ($flux['data']['texte']) > 1500) {
      spip_log ("IdM: visitor $id_auteur posted a very long comment, moderating.");
      $flux['data']['statut']='prop';
    };
  }
  return $flux;
}

function idm_clean_TeX ($texte) {
  $acc_tex = array(); $acc_html = array();

  $acc_tex[] = '\`a'; $acc_html[] = '&agrave;';
  $acc_tex[] = '\`e'; $acc_html[] = '&egrave;';
  $acc_tex[] = '\`i'; $acc_html[] = '&igrave;';
  $acc_tex[] = '\`o'; $acc_html[] = '&ograve;';
  $acc_tex[] = '\`u'; $acc_html[] = '&ugrave;';
  $acc_tex[] = '\`y'; $acc_html[] = '&ygrave;';

  $acc_tex[] = '\\\'a'; $acc_html[] = '&aacute;';
  $acc_tex[] = '\\\'e'; $acc_html[] = '&eacute;';
  $acc_tex[] = '\\\'i'; $acc_html[] = '&iacute;';
  $acc_tex[] = '\\\'o'; $acc_html[] = '&oacute;';
  $acc_tex[] = '\\\'u'; $acc_html[] = '&uacute;';
  $acc_tex[] = '\\\'y'; $acc_html[] = '&yacute;';

  $acc_tex[] = '\^a'; $acc_html[] = '&acirc;';
  $acc_tex[] = '\^e'; $acc_html[] = '&ecirc;';
  $acc_tex[] = '\^i'; $acc_html[] = '&icirc;';
  $acc_tex[] = '\^o'; $acc_html[] = '&ocirc;';
  $acc_tex[] = '\^u'; $acc_html[] = '&ucirc;';
  $acc_tex[] = '\^y'; $acc_html[] = '&ycirc;';

  $acc_tex[] = '\"a'; $acc_html[] = '&auml;';
  $acc_tex[] = '\"e'; $acc_html[] = '&euml;';
  $acc_tex[] = '\"i'; $acc_html[] = '&iuml;';
  $acc_tex[] = '\"o'; $acc_html[] = '&ouml;';
  $acc_tex[] = '\"u'; $acc_html[] = '&uuml;';
  $acc_tex[] = '\"y'; $acc_html[] = '&yuml;';

  $acc_tex[] = '\`A'; $acc_html[] = '&Agrave;';
  $acc_tex[] = '\`E'; $acc_html[] = '&Egrave;';
  $acc_tex[] = '\`I'; $acc_html[] = '&Igrave;';
  $acc_tex[] = '\`O'; $acc_html[] = '&Ograve;';
  $acc_tex[] = '\`U'; $acc_html[] = '&Ugrave;';
  $acc_tex[] = '\`Y'; $acc_html[] = '&Ygrave;';

  $acc_tex[] = '\\\'A'; $acc_html[] = '&Aacute;';
  $acc_tex[] = '\\\'E'; $acc_html[] = '&Eacute;';
  $acc_tex[] = '\\\'I'; $acc_html[] = '&Iacute;';
  $acc_tex[] = '\\\'O'; $acc_html[] = '&Oacute;';
  $acc_tex[] = '\\\'U'; $acc_html[] = '&Uacute;';
  $acc_tex[] = '\\\'Y'; $acc_html[] = '&Yacute;';

  $acc_tex[] = '\^A'; $acc_html[] = '&Acirc;';
  $acc_tex[] = '\^E'; $acc_html[] = '&Ecirc;';
  $acc_tex[] = '\^I'; $acc_html[] = '&Icirc;';
  $acc_tex[] = '\^O'; $acc_html[] = '&Ocirc;';
  $acc_tex[] = '\^U'; $acc_html[] = '&Ucirc;';
  $acc_tex[] = '\^Y'; $acc_html[] = '&Ycirc;';

  $acc_tex[] = '\"A'; $acc_html[] = '&Auml;';
  $acc_tex[] = '\"E'; $acc_html[] = '&Euml;';
  $acc_tex[] = '\"I'; $acc_html[] = '&Iuml;';
  $acc_tex[] = '\"O'; $acc_html[] = '&Ouml;';
  $acc_tex[] = '\"U'; $acc_html[] = '&Uuml;';
  $acc_tex[] = '\"Y'; $acc_html[] = '&Yuml;';

  $acc_tex[] = '\`{\i}';   $acc_html[] = '&igrave;';
  $acc_tex[] = '\\\'{\i}'; $acc_html[] = '&iacute;';
  $acc_tex[] = '\^{\i}';   $acc_html[] = '&icirc;';
  $acc_tex[] = '\"{\i}';   $acc_html[] = '&iuml;';

  $acc_tex[] = '\c{c}';    $acc_html[] = '&ccedil;';
  $acc_tex[] = '\c c';     $acc_html[] = '&ccedil;';
  $acc_tex[] = '\c{C}';    $acc_html[] = '&Ccedil;';
  $acc_tex[] = '\c C';     $acc_html[] = '&Ccedil;';

  $texte = str_replace ($acc_tex, $acc_html, $texte);

  $texte = preg_replace ('/\\\head\{([^{}]+)\}/s',    '{{{\1}}}', $texte);
  $texte = preg_replace ('/\\\textbf\{([^{}]+)\}/s',  '{{\1}}',   $texte);
  $texte = preg_replace ('/\\\section\{([^{}]+)\}/s', '{{{\1}}}', $texte);

  $texte = str_replace ('\ldots',    '...', $texte);
  $texte = str_replace ('\medskip',  '',    $texte);
  $texte = str_replace ('\newblock', '',    $texte);
  $texte = str_replace ('\noindent', '',    $texte);
  $texte = str_replace ('\emph',     '',    $texte);
  $texte = str_replace ('\em',       '',    $texte);
  $texte = str_replace ('\it',       '',    $texte);

  return $texte;
}

function idm_labelref ($texte) {
  $texte = preg_replace ('/\$\$\\s*\\\\label\{([^\}]*)\}/',
                         '<div class="mjlabel_top" id="eq_\1">(\1)</div>$$',
                         $texte);
  $texte = preg_replace ('/\\\\label\{([^\}]*)\}\\s*\$\$/',
                         '$$<div class="mjlabel_bot" id="eq_\1">(\1)</div>',
                         $texte);
  $texte = preg_replace ('/\$*\\\\ref\{([^\}]*)\}\$*/',
                         '<a href="#eq_\1">(\1)</a>',
                         $texte);
  return $texte;
}

function idm_protect_TeX ($texte) {
  $texte = str_replace ('\[', '$$', $texte);
  $texte = str_replace ('\]', '$$', $texte);
  $texte = str_replace ('\(', '$', $texte);
  $texte = str_replace ('\)', '$', $texte);

  $texte = preg_replace ('/\$\$([^$]+)\$\$/s', '<html>\[\1\]</html>', $texte);
  $texte = preg_replace ('/\$([^$]+)\$/s', '<html>$\1$</html>', $texte);
  $texte = str_replace ('\[', '$$', $texte);
  $texte = str_replace ('\]', '$$', $texte);

  while (preg_match ('/<html>[$]+[^$]+</s', $texte)) {
    $texte = preg_replace ('/(<html>[$]+[^$]+)</s', '\1&lt;', $texte);
  }

  return echappe_html ($texte);
}

function idm_pre_typo ($texte) {
  $texte = idm_labelref ($texte);
  $texte = idm_protect_TeX ($texte);
  $texte = idm_clean_TeX ($texte);

  return $texte;
}

function idm_insert_head ($texte) {
  $mj_insert = <<<END
    <script type="text/javascript" src="http://cdn.mathjax.org/mathjax/latest/MathJax.js">
      MathJax.Hub.Config({
        extensions: ["tex2jax.js", "jsMath2jax.js", "TeX/noErrors.js", "TeX/AMSmath.js", "TeX/AMSsymbols.js"],
        jax:        ["input/TeX",  "output/HTML-CSS"],
        tex2jax: {
          inlineMath:          [ ['$','$'],   ["\\\\(","\\\\)"] ],
          processEnvironments: false,
        }
      });
    </script>
END;
  return $texte . $mj_insert;
}

function idm_header_prive ($texte) {
  return idm_insert_head ($texte);
}

function idm_initiale ($mot) {
  return strtoupper($mot[0]);
}

function idm_texify ($texte) {
  include_spip ('inc/documents');

  $texte = preg_replace ('/{{{(.*?)}}}/', '\section*{\1}', $texte);
  $texte = preg_replace ('/\[\[(.*?)\]\]/', '\footnote{\1}', $texte);
  $texte = preg_replace ('/\[([^]]*)->([^]]*)\]/', '\href{\2}{\1}', $texte);

  preg_match_all ('/<(doc|img)([0-9]*).*?>/', $texte, $matches, PREG_SET_ORDER);
  foreach ($matches as $m) {
    $f = generer_url_document_dist(intval($m[2]));
    $f = preg_replace ('/.*\//', '', $f);
    $texte = str_replace ($m[0], '\centerline{\includegraphics[width=.5\hsize]{'.$f.'}}', $texte);
  }

  $texte = preg_replace ('/\\\\href{([0-9]*)}/', '\href{http://images.math.cnrs.fr/?article\1}', $texte);
  return $texte;
}

function idm_prenom_nom ($texte) {
  $texte = preg_replace ('/([^,]+), ([^,]+)/s', '\2 \1', $texte);
  return $texte;
}

?>
