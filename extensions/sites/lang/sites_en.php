<?php
// This is a SPIP language file  --  Ceci est un fichier langue de SPIP
// extrait automatiquement de http://www.spip.net/trad-lang/
// ** ne pas modifier le fichier **

if (!defined('_ECRIRE_INC_VERSION')) return;

$GLOBALS[$GLOBALS['idx_lang']] = array(

	// A
	'articles_dispo' => 'En attente', # NEW
	'articles_meme_auteur' => 'Tous les articles de cet auteur', # NEW
	'articles_off' => 'Bloqués', # NEW
	'articles_publie' => 'Publiés', # NEW
	'articles_refuse' => 'Supprimés', # NEW
	'articles_tous' => 'Tous', # NEW
	'aucun_article_syndic' => 'Aucun article syndiqué', # NEW
	'avis_echec_syndication_01' => 'Syndication failed: either the selected backend is unreadable, or it does not contain any articles.',
	'avis_echec_syndication_02' => 'Syndication failed: could not find the backend file for this site.',
	'avis_site_introuvable' => 'Site not found',
	'avis_site_syndique_probleme' => 'Warning: syndication for this site has encountered a problem. As a result, the system has been temporarily interrupted. Please check the URL of this site\'s syndication file (<b>@url_syndic@</b>), and try again.',
	'avis_sites_probleme_syndication' => 'These sites encountered a syndication problem',
	'avis_sites_syndiques_probleme' => 'These syndicated sites generated a problem',

	// B
	'bouton_radio_modere_posteriori' => 'post-moderation',
	'bouton_radio_modere_priori' => 'pre-moderation',
	'bouton_radio_non_syndication' => 'No syndication',
	'bouton_radio_syndication' => 'Syndication:',

	// C
	'confirmer_purger_syndication' => 'Êtes-vous certain de vouloir supprimer tous les articles syndiqués de ce site ?', # NEW

	// E
	'entree_adresse_fichier_syndication' => 'Address of syndication file:',
	'entree_adresse_site' => '<b>Site URL</b> [Required]',
	'entree_description_site' => 'Site description',

	// F
	'form_prop_nom_site' => 'Site name',

	// I
	'icone_article_syndic' => 'Article syndiqué', # NEW
	'icone_articles_syndic' => 'Articles syndiqués', # NEW
	'icone_controler_syndication' => 'Publication des articles syndiqués', # NEW
	'icone_modifier_site' => 'Edit this site',
	'icone_referencer_nouveau_site' => 'Reference a new site',
	'icone_site_reference' => 'Sites référencés', # NEW
	'icone_supprimer_article' => 'Supprimer cet article', # NEW
	'icone_supprimer_articles' => 'Supprimer ces articles', # NEW
	'icone_valider_article' => 'Valider cet article', # NEW
	'icone_valider_articles' => 'Valider ces articles', # NEW
	'icone_voir_sites_references' => 'Show referenced websites',
	'info_a_valider' => '[awaiting validation]',
	'info_bloquer' => 'block',
	'info_bloquer_lien' => 'block this link',
	'info_derniere_syndication' => 'The last syndication of this site was on',
	'info_liens_syndiques_1' => 'syndicated links',
	'info_liens_syndiques_2' => 'awaiting validation.',
	'info_nom_site_2' => '<b>Site name</b> [Required]',
	'info_panne_site_syndique' => 'Syndicated site unavailable',
	'info_probleme_grave' => 'error with',
	'info_question_proposer_site' => 'Who can suggest links to other websites?',
	'info_retablir_lien' => 'restore this link',
	'info_site_attente' => 'Website awaiting validation',
	'info_site_propose' => 'Site submitted on:',
	'info_site_reference' => 'Referenced sites online',
	'info_site_refuse' => 'Website rejected',
	'info_site_syndique' => 'This site is syndicated...',
	'info_site_valider' => 'Sites awaiting validation',
	'info_sites_referencer' => 'Reference a site',
	'info_sites_refuses' => 'Rejected sites',
	'info_statut_site_1' => 'This site is:',
	'info_statut_site_2' => 'Published',
	'info_statut_site_3' => 'Submitted',
	'info_statut_site_4' => 'In the dustbin',
	'info_syndication' => 'syndication:',
	'info_syndication_articles' => 'article(s)',
	'item_bloquer_liens_syndiques' => 'Block syndicated links for moderation',
	'item_gerer_annuaire_site_web' => 'Manage website directory',
	'item_non_bloquer_liens_syndiques' => 'Do not block links emanating from syndication',
	'item_non_gerer_annuaire_site_web' => 'Disable website directory',
	'item_non_utiliser_syndication' => 'Do not use automatic syndication',
	'item_utiliser_syndication' => 'Use automatic syndication',

	// L
	'lien_mise_a_jour_syndication' => 'Update now',
	'lien_nouvelle_recuperation' => 'Try to retrieve data again',
	'lien_purger_syndication' => 'Effacer tous les articles syndiqués', # NEW

	// N
	'nombre_articles_syndic' => '@nb@ articles syndiqués', # NEW

	// S
	'statut_off' => 'Supprimé', # NEW
	'statut_prop' => 'En attente', # NEW
	'statut_publie' => 'Publié', # NEW
	'syndic_choix_moderation' => 'What should be done with future links from this site?',
	'syndic_choix_oublier' => 'What should be done with links which are no longer present in the syndication file?',
	'syndic_choix_resume' => 'Some sites include the full text of articles. When the full text is available, do you wish to syndicate:',
	'syndic_lien_obsolete' => 'obsolete link',
	'syndic_option_miroir' => 'block them automatically',
	'syndic_option_oubli' => 'delete them (after @mois@ months)',
	'syndic_option_resume_non' => 'the full content of the articles (HTML format)',
	'syndic_option_resume_oui' => 'just a summary (text format)',
	'syndic_options' => 'Syndication options:',

	// T
	'texte_liens_sites_syndiques' => 'Links from syndicated sites can
   be held for moderation.Below is
   the default setting of syndicated
   sites on their creation. In
   any case, it is possible to
   release each link individually, or to
   choose to block all future links
   from a particular site.',
	'texte_messages_publics' => 'Public Messages on this article:',
	'texte_non_fonction_referencement' => 'You can opt not to use this automated feature, and enter the elements for that site manually...',
	'texte_referencement_automatique' => '<b>Automated site referencing</b><br />You can reference a website quickly by indicating its URL, or the location of its syndication file, below. SPIP will automatically retrieve information from that site (title, description etc.).',
	'texte_referencement_automatique_verifier' => 'Please verify the information provided by <tt>@url@</tt> before saving.',
	'texte_syndication' => 'If a site permits, it is possible to retrieve the list of
   its latest articles automatically. To do this, you must activate syndication. 
  <blockquote><i>Some hosts disable this function; 
  in which case, you cannot use RSS content syndication
  from your site.</i></blockquote>',
	'titre_articles_syndiques' => 'Syndicated articles taken from this site',
	'titre_dernier_article_syndique' => 'Latest syndicated articles',
	'titre_page_sites_tous' => 'Referenced sites',
	'titre_referencement_sites' => 'Sites referencing and syndication',
	'titre_site_numero' => 'SITE NUMBER:',
	'titre_sites_proposes' => 'Submitted sites',
	'titre_sites_references_rubrique' => 'Referenced sites in this section',
	'titre_sites_syndiques' => 'Syndicated sites',
	'titre_sites_tous' => 'Referenced sites',
	'titre_syndication' => 'Sites syndication',
	'tout_voir' => 'Voir tous les articles syndiqués', # NEW

	// U
	'un_article_syndic' => '1 article syndiqué' # NEW
);

?>
