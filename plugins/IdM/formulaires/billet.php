<?php

include_spip('base/abstract_sql');

function notify_comite ($id_auteur, $id_article, $titre, $date) {
  $idm_team_billets = array();
  foreach (sql_allfetsel ("*", "spip_idm_teams", "team = 'billets'") as $e)
    $idm_team_billets[] = $e['id_auteur'];

  $today = floor(time()/(24*3600)) % count($idm_team_billets);
  $gars = $idm_team_billets [$today];

  $qui = sql_getfetsel ("nom", "spip_auteurs", "id_auteur = $id_auteur");

  $subject = "Un nouveau billet pour Images des Maths";

  $texte = _T('idm:mail_billet_valide', array('auteur'     => $qui,
                                              'titre'      => $titre,
                                              'date'       => $date,
                                              'id_article' => $id_article));

  idm_notify (array(0,$gars), $texte, $subject);
}

function formulaires_billet_charger () {
  $valeurs = array('titre'=>'', 'texte'=>'', 'id_article'=>false);

  return $valeurs;
}

function formulaires_billet_verifier () {
  $erreurs = array();

  foreach(array('titre','texte') as $obligatoire)
    if (!_request($obligatoire))
      $erreurs[$obligatoire] = "Le champ '$obligatoire' est obligatoire !";

  if (!count($erreurs)) {
    if (!$id_article = _request("id_article")) {
      $id_article = sql_insertq ("spip_articles", array (
        "id_rubrique" => 6,
        "statut" => "tmp",
        "accepter_forum" => "abo",
        "date" => "NOW()"));

      sql_insertq ("spip_auteurs_liens",
        array ("objet" => 'article',
               "id_objet" => $id_article,
               "id_auteur" => $GLOBALS['auteur_session']['id_auteur']));
    }

    $id_article = intval($id_article);
    sql_updateq ('spip_articles',
                 array ("titre" => _request("titre"), "texte" => _request("texte")),
                 "id_article = $id_article");

    if (!_request("ok")) $erreurs['id_article'] = $id_article;
  }

  return $erreurs;
}

function formulaires_billet_traiter () {
  $id_article = intval(_request("id_article"));
  $id_auteur = $GLOBALS['auteur_session']['id_auteur'];

  $today = time();
  $today -= $today % (24*3600); // Midnight this morning

  $previous = sql_getfetsel ("UNIX_TIMESTAMP(date)",
    "spip_auteurs_liens,spip_articles",
    "(spip_auteurs_liens.objet='article') AND (spip_articles.id_article=spip_auteurs_liens.id_objet) AND (id_auteur=$id_auteur) AND (statut='publie')",
    array(), "date DESC", "1");
  $previous -= $previous % (24*3600); // Their last contribution to the site

  $when = max ($today + 2*24*3600, $previous + 7*24*3600); // Publish the day after tomorrow, with one week minimum for the same contributor.
  $when += 7*3600; // Publish at 7:00 AM

  $threshold = 2;

  while (true) {
    $sqldate = date("Y-m-d H:i:s", $when);
    $howmany = sql_countsel('spip_articles', "(id_rubrique=6) AND (statut='publie') AND (date='$sqldate')");
    if ($howmany<$threshold) break;
    $when += 24*3600;
    $threshold += 1;
  }

  sql_updateq ("spip_articles",
    array ("statut" => "publie", "date" => date("Y-m-d H:i:s", $when)),
    "id_article = $id_article");

  notify_comite ($id_auteur, $id_article, _request("titre"), date("d/m/Y, H:i:s", $when));
  return array('message_ok' => "done");
}

?>