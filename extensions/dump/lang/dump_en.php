<?php
// This is a SPIP language file  --  Ceci est un fichier langue de SPIP
// extrait automatiquement de http://www.spip.net/trad-lang/
// ** ne pas modifier le fichier **

if (!defined('_ECRIRE_INC_VERSION')) return;

$GLOBALS[$GLOBALS['idx_lang']] = array(

	// A
	'aucune_donnee' => 'vide', # NEW
	'avis_probleme_ecriture_fichier' => 'Problème d\'écriture du fichier @fichier@', # NEW

	// B
	'bouton_restaurer_base' => 'Restore the database',

	// C
	'confirmer_ecraser_base' => 'Oui, je veux écraser ma base avec cette sauvegarde', # NEW
	'confirmer_ecraser_tables_selection' => 'Oui, je veux écraser les tables sélectionnées avec cette sauvegarde', # NEW

	// D
	'details_sauvegarde' => 'Détails de la sauvegarde :', # NEW

	// E
	'erreur_aucune_donnee_restauree' => 'Aucune donnée restaurée', # NEW
	'erreur_connect_dump' => 'Un serveur nommé « @dump@ » existe déjà. Renommez-le.', # NEW
	'erreur_creation_base_sqlite' => 'Impossible de créer une base SQLite pour la sauvegarde', # NEW
	'erreur_nom_fichier' => 'Ce nom de fichier n\'est pas autorisé', # NEW
	'erreur_restaurer_verifiez' => 'Corrigez l\'erreur pour pouvoir restaurer.', # NEW
	'erreur_sauvegarde_deja_en_cours' => 'Vous avez déjà une sauvegarde en cours', # NEW
	'erreur_sqlite_indisponible' => 'Impossible de faire une sauvegarde SQLite sur votre hébergement', # NEW
	'erreur_table_absente' => 'Table @table@ absente', # NEW
	'erreur_table_donnees_manquantes' => 'Table @table@, données manquantes', # NEW
	'erreur_taille_sauvegarde' => 'La sauvegarde semble avoir échoué. Le fichier @fichier@ est vide ou absent.', # NEW

	// I
	'info_aucune_sauvegarde_trouvee' => 'Aucune sauvegarde trouvée', # NEW
	'info_restauration_finie' => 'C\'est fini !. La sauvegarde @archive@ a été restaurée dans votre site. Vous pouvez', # NEW
	'info_restauration_sauvegarde' => 'Restoring the backup @archive@',
	'info_sauvegarde' => 'Backup',
	'info_sauvegarde_reussi_02' => 'The database has been saved in @archive@. You can',
	'info_sauvegarde_reussi_03' => 'return to the management',
	'info_sauvegarde_reussi_04' => 'of your site.',
	'info_selection_sauvegarde' => 'Vous avez choisi de restaurer la sauvegarde @fichier@. Cette opération est irréversible.', # NEW

	// L
	'label_nom_fichier_restaurer' => 'Ou indiquez le nom du fichier à restaurer', # NEW
	'label_nom_fichier_sauvegarde' => 'Nom du fichier pour la sauvegarde', # NEW
	'label_selectionnez_fichier' => 'Sélectionnez un fichier dans la liste', # NEW

	// N
	'nb_donnees' => '@nb@ enregistrements', # NEW

	// R
	'restauration_en_cours' => 'Restauration en cours', # NEW

	// S
	'sauvegarde_en_cours' => 'Sauvegarde en cours', # NEW
	'sauvegardes_existantes' => 'Sauvegardes existantes', # NEW
	'selectionnez_table_a_restaurer' => 'Sélectionnez les tables à restaurer', # NEW

	// T
	'texte_admin_tech_01' => 'This option letd you save the contents of the database as a file in the directory @dossier@. Do not forget to retrieve the whole @img@ directory, which contains the images and documents used in the articles and sections.',
	'texte_admin_tech_02' => 'Warning: this backup can ONLY be restored by the same version of SPIP that created it. You cannot "empty the database" and expect to reinstall the backup after an upgrade... For more information refer to <a href="@spipnet@">SPIP documentation</a>.', # MODIF
	'texte_restaurer_base' => 'Restore a database content backup',
	'texte_restaurer_sauvegarde' => 'This option allows you to restore a previous
backup of the database. For this, the file containing the backup should have been
stored in the directory @dossier@.
Be very careful with this feature: <b>any potential modifications or losses are
irreversible.</b>',
	'texte_sauvegarde' => 'Backup database content',
	'texte_sauvegarde_base' => 'Backup the database',
	'tout_restaurer' => 'Restaurer toutes les tables', # NEW
	'tout_sauvegarder' => 'Sauvegarder toutes les tables', # NEW

	// U
	'une_donnee' => '1 enregistrement' # NEW
);

?>
