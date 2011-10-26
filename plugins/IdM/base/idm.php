<?php

function idm_declarer_tables_interfaces ($interfaces) {
  $interfaces['table_des_tables']['idm_projets']         = 'idm_projets';
  $interfaces['table_des_tables']['idm_relecteurs']      = 'idm_relecteurs';
  $interfaces['table_des_tables']['idm_sujets']          = 'idm_sujets';
  $interfaces['table_des_tables']['relecteurs_articles'] = 'relecteurs_articles';
  $interfaces['table_des_tables']['idm_sujets_articles'] = 'idm_sujets_articles';

  /*
   * $interfaces['table_date']['syndication'] = 'date';
   *
   * $interfaces['tables_jointures']['spip_syndic_articles'][]= 'syndic';
   *
   * $interfaces['table_des_traitements']['NOM_SITE'][]=  _TRAITEMENT_TYPO;
   *
   * // Articles syndiques : passage des donnees telles quelles, sans traitement typo
   * // la securite et conformite XHTML de ces champs est assuree par safehtml()
   * foreach(array('DESCRIPTIF','SOURCE','URL','URL_SOURCE','LESAUTEURS','TAGS') as $balise)
   *   if (!isset($interfaces['table_des_traitements'][$balise]['syndic_articles']))
   *     $interfaces['table_des_traitements'][$balise]['syndic_articles'] = 'safehtml(%s)';
   *   else
   *     if (strpos($interfaces['table_des_traitements'][$balise]['syndic_articles'],'safehtml')==false)
   *       $interfaces['table_des_traitements'][$balise]['syndic_articles'] = 'safehtml('.$interfaces['table_des_traitements'][$balise]['syndic_articles'].')';
   */

  return $interfaces;
}

function idm_declarer_tables_objets_sql ($tables) {
  $tables['spip_idm_projets'] = array ('principale' => "oui",
                                       'field' => array ('id_projet'   => 'BIGINT(21) NOT NULL',
                                                         'id_editeur'  => 'BIGINT(21) NOT NULL',
                                                         'id_auteur'   => 'BIGINT(21) NOT NULL default 0',
                                                         'id_article'  => 'BIGINT(21) NOT NULL default 0',
                                                         'id_rubrique' => 'BIGINT(21) NOT NULL',
                                                         'auteur'      => 'TINYTEXT NOT NULL',
                                                         'sujet'       => 'TINYTEXT NOT NULL',
                                                         'modif'       => 'TIMESTAMP NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP',
                                                         'comment'     => 'TEXT NOT NULL',
                                                         'statut'      => "ENUM ('contact', 'redaction', 'relecture', 'publie', 'refus') NOT NULL DEFAULT 'contact'"),
                                       'key' => array ('PRIMARY KEY' => 'id_projet'));

  $tables['spip_idm_relecteurs'] = array ('field' => array ('id_auteur'   => "BIGINT(21) NOT NULL",
                                                            'role'        => "ENUM ('visiteur', 'candidat', 'relecteur', 'occasionnel') NOT NULL DEFAULT 'visiteur'",
                                                            'math'        => "TEXT NOT NULL",
                                                            'combien'     => "INT NOT NULL DEFAULT 0",
                                                            'lus'         => "INT NOT NULL DEFAULT 0",
                                                            'comments'    => "INT NOT NULL DEFAULT 0",
                                                            'quand'       => "TIMESTAMP NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP",
                                                            'comment'     => "TEXT NOT NULL",
                                                            'categorie'   => "ENUM ('nouveau', 'chercheur', 'enseignant', 'etudiant', 'autre', 'candidat', 'non_classe') NOT NULL DEFAULT 'nouveau'"),
                                          'key' => array('PRIMARY KEY' => "id_auteur"));

  $tables['spip_relecteurs_articles'] = array ('field' => array ('id_article'  => 'BIGINT(21) NOT NULL',
                                                                 'id_auteur'   => 'BIGINT(21) NOT NULL',
                                                                 'date_change' => 'TIMESTAMP NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP',
                                                                 'status'      => "ENUM('pas_vu','vu','non','moyen','oui') NOT NULL DEFAULT 'pas_vu'",
                                                                 'avis'        => "TINYTEXT"),
                                               'key' => array());

  $tables['spip_idm_sujets'] = array ('principale' => "oui",
                                      'field' => array ('id_sujet' => "BIGINT(21) NOT NULL",
                                                        'id_parent' => "BIGINT(21) NOT NULL DEFAULT 0",
                                                        'intitule' => "TINYTEXT NOT NULL",
                                                        'description' => "TEXT NOT NULL"),
                                      'key' => array ('PRIMARY KEY' => "id_sujet"));

  $tables['spip_idm_sujets_articles'] = array ('field' => array ('id_sujet' => "BIGINT(21) NOT NULL",
                                                                 'id_article' => "BIGINT(21) NOT NULL"),
                                               'key' => array ());

  return $tables;
}

?>
