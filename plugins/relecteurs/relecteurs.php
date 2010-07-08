<?php
include_spip ('base/serial.php');
include_spip ('inc/envoyer_mail');

global $tables_principales;
$tables_principales['spip_auteurs']['field']['role'] =
  "enum('visiteur','candidat','relecteur') NOT NULL DEFAULT 'visiteur'";

function relecteurs_update_referee ($id, $status) {
  $id = intval($id);
  if (($status == 'visiteur') || ($status == 'candidat') || ($status == 'relecteur')) {
    spip_query ( "UPDATE spip_auteurs SET role = '$status' WHERE id_auteur = $id LIMIT 1");
  }
}

function relecteurs_notify_user ($id_auteur, $id_article) {
  $email = sql_getfetsel ("email", "spip_auteurs", "id_auteur = $id_auteur");
  $titre = sql_getfetsel ("titre", "spip_articles", "id_article = $id_article");
  $titre = utf8_decode ($titre);

  $subject = "Relecture d'un article pour Images des Maths";

  $texte = "Bonjour !\n" .
    "\n" .
    "Un article vient d'�tre propos� pour publication dans \"Images des\n" .
    "Math�matiques\". Il s'intitule :\n" .
    "\n" .
    "  � $titre �\n" .
    "\n" .
    "Comme vous avez manifest� votre int�r�t � participer � notre\n" .
    "processus �ditorial, nous vous invitons � en �tre un des relecteurs,\n" .
    "et � nous faire part de vos commentaires. Pour ce faire, apr�s vous\n" .
    "�tre identifi�(e) sur le site, il vous suffit de vous rendre �\n" .
    "l'adresse suivante :\n" .
    "\n" .
    "  http://images.math.cnrs.fr/spip/spip.php?page=propose&id_article=$id_article\n" .
    "\n" .
    "Vous y trouverez l'article dans son �tat actuel, un forum de discussion\n" .
    "vous permettant de d�poser vos commentaires et de dialoguer avec les\n" .
    "autres relecteurs ainsi qu'avec l'auteur de l'article, et enfin un\n" .
    "formulaire de vote pour donner votre avis sur sa publication.\n" .
    "\n" .
    "Merci pour votre aide !\n\n" .
    "-- \n" .
    "Le comit� de r�daction de \"Images des Math�matiques\".";

  inc_envoyer_mail ($email, $subject, utf8_encode($texte));
}

function relecteurs_nettoie () {
  $reload = false;

  $liberes = sql_fetsel ( "spip_relecteurs_articles.id_auteur",
    array ("spip_relecteurs_articles", "spip_articles"),
    array ("spip_relecteurs_articles.id_article = spip_articles.id_article", "spip_articles.statut = 'publie'"));

  if ($liberes) {
    foreach ($liberes as $id) { sql_delete ("spip_relecteurs_articles", "id_auteur = $id"); }
    $reload = true;
  }

  return $reload;
}

function relecteurs_effect_change ($target='', $caller='admin') {
  if (!$target) $target = str_replace('&amp;','&',self());

  $reload = relecteurs_nettoie();

  if (!empty($_POST)) {
    foreach($_POST as $key=>$value) {
      if (preg_match('/^form_relecteur_statut_([0-9]*)$/', $key, $matches)) {
        $id = $matches[1];
        if ( ($caller == 'admin') || (($matches[1] == $caller) && ($value != 'relecteur')) ) {
          relecteurs_update_referee ($matches[1],$value);
          $reload = true;
        }
      }

      if (preg_match('/^form_relecteur_exterminate_([0-9]*)$/', $key, $matches)) {
        if ( ($value == 'on') && (($caller == 'admin') || ($caller == $matches[1])) ) {
          relecteurs_update_referee ($matches[1],'visiteur');
          $reload = true;
        }
      }

      if (preg_match('/^form_relecteurs_vote_([0-9]*)_([0-9]*)$/', $key, $matches)) {
        $id_auteur = $matches[1];
        $id_article = $matches[2];
        if ( ($caller==$id_auteur) && (($value=='vu')||($value=='oui')||($value=='moyen')||($value=='non'))) {
          spip_query ("UPDATE spip_relecteurs_articles SET status = '$value' WHERE id_auteur = $id_auteur AND id_article = $id_article");
          $reload = true;
        }
      }

      if (preg_match('/^form_relecteurs_unassign_([0-9]*)_([0-9]*)$/', $key, $matches)) {
        if ($caller == 'admin') {
          $id_auteur = $matches[1];
          $id_article = $matches[2];
          spip_query ("DELETE FROM spip_relecteurs_articles WHERE id_auteur = $id_auteur AND id_article = $id_article");
          $reload = true;
        }
      }

      if (preg_match('/^form_relecteurs_assign_([0-9]*)_([0-9]*)$/', $key, $matches)) {
        if ($caller == 'admin') {
          $id_auteur = $matches[1];
          $id_article = $matches[2];
          spip_query ("INSERT INTO spip_relecteurs_articles (id_auteur,id_article) VALUES ($id_auteur,$id_article)");
          relecteurs_notify_user ($id_auteur, $id_article);
          $reload = true;
        }
      }
    }
  }

  // Si on a fait quelque chose, on recharge la page apr�s avoir vid� 
  // $_POST (pour pouvoir lancer cette fonction plusieurs fois sur une 
  // m�me page sans risque - c'est sans doute inutile, en fait).

  if ($reload) {
    $_POST = array();
    @header ("Location: $target");
  }
}

function relecteurs_insert_head ($texte) {
  $texte .= "\n";
  $texte .= '<script type="text/javascript" src="' . find_in_path('javascript/jquery.tablesorter.min.js') . "\"></script>\n";
  $texte .= '<script type="text/javascript">' . "\n";
  $texte .= '  $(document).ready(function() { $(".relecteurs_sortable").tablesorter( {sortList : [[0,0]]} ); } ); ' . "\n";
  $texte .= '</script>' . "\n";
  return $texte;
}
?>
