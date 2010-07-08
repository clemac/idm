<?php
include_spip ('base/serial.php');
include_spip ('inc/envoyer_mail');

global $tables_principales;
$tables_principales['spip_auteurs']['field']['role'] = "enum('visiteur','candidat','relecteur') NOT NULL DEFAULT 'visiteur'";
$tables_principales['spip_relecteurs_articles'] = array (
  'field' => array (
    'id_article' => 'BIGINT(21) NOT NULL',
    'id_auteur' => 'BIGINT(21) NOT NULL',
    'date_change' => 'TIMESTAMP',
    'status' => "ENUM('pas_vu','vu','non','moyen','oui')" ),
  'key' => array());

global $table_des_tables;
$table_des_tables['relecteurs_articles'] = 'relecteurs_articles';

function relecteurs_install_orig ($action) {
  $desc = spip_abstract_showtable('spip_auteurs', '', true);

  switch ($action) {

  case 'test':
    return (isset($desc['field']['role']));
    break;

  case 'install':
    spip_query("ALTER TABLE spip_auteurs ADD `role` enum('visiteur','candidat','relecteur') NOT NULL DEFAULT 'visiteur'");
    break;

  case 'uninstall':
    spip_query("ALTER TABLE spip_auteurs DROP COLUMN role");
    break;
  }
}	

function relecteurs_update_referee ($id, $status) {
  if (($status == 'visiteur') || ($status == 'candidat') || ($status == 'relecteur')) {
    sql_updateq ("spip_auteurs", array("role"=>$status), "id_auteur = $id");
  }
  if ($status == "visiteur") {
    sql_delete ("spip_relecteurs_articles", "id_auteur = $id");
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
          $id_auteur = $matches[1];
          relecteurs_update_referee ($id_auteur,'visiteur');
          $reload = true;
        }
      }

      if (preg_match('/^form_relecteurs_vote_([0-9]*)_([0-9]*)$/', $key, $matches)) {
        $id_auteur = $matches[1];
        $id_article = $matches[2];
        if ( ($caller==$id_auteur) && (($value=='vu')||($value=='oui')||($value=='moyen')||($value=='non'))) {
          sql_updateq ("spip_relecteurs_articles", array("status"=>$value), "id_auteur = $id_auteur AND id_article = $id_article");
          $reload = true;
        }
      }

      if (preg_match('/^form_relecteurs_unassign_([0-9]*)_([0-9]*)$/', $key, $matches)) {
        if ($caller == 'admin') {
          $id_auteur = $matches[1];
          $id_article = $matches[2];
          sql_delete ("spip_relecteurs_articles", "id_auteur = $id_auteur AND id_article = $id_article");
          $reload = true;
        }
      }

      if (preg_match('/^form_relecteurs_assign_([0-9]*)_([0-9]*)$/', $key, $matches)) {
        if ($caller == 'admin') {
          $id_auteur = $matches[1];
          $id_article = $matches[2];
          sql_insertq ("spip_relecteurs_articles", array("id_auteur"=>$id_auteur, "id_article"=>$id_article));
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
  $texte .= '  $(document).ready(function() { $(".sortable").tablesorter( {sortList : [[0,0]]} ); } ); ' . "\n";
  $texte .= '</script>' . "\n";
  return $texte;
}
?>
