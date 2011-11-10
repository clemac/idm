<?php

function idm_upgrade ($nom_meta_base_version, $version_cible) {
  $maj = array();

  $maj['create'] = array(array('maj_tables', array('spip_idm_projets',
                                                   'spip_idm_relecteurs',
                                                   'spip_idm_sujets',
                                                   'spip_idm_teams',
                                                   'spip_relecteurs_articles',
                                                   'spip_idm_sujets_articles',
                                                   'spip_auteurs',
                                                   'spip_articles')));

  $maj['201111102037'] = $maj['create'];

  include_spip ('base/upgrade');
  maj_plugin ($nom_meta_base_version, $version_cible, $maj);
}

function idm_vider_tables ($nom_meta_base_version) {
        /*
         * sql_drop_table("spip_chats");
         * effacer_meta($nom_meta_base_version);
         */
}

?>
