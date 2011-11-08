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
	'bouton_restaurer_base' => 'Datenbank wieder herstellen',

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
	'info_restauration_sauvegarde' => 'Wiederherstellung der Sicherung @archive@',
	'info_sauvegarde' => 'Sicherung',
	'info_sauvegarde_reussi_02' => 'Die Datenbank wurde in @archive@ gesichert. Sie können ',
	'info_sauvegarde_reussi_03' => 'zur Administration',
	'info_sauvegarde_reussi_04' => 'Ihrer Site zurückkehren.',
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
	'texte_admin_tech_01' => 'Diese Option ermöglicht es, den Inhalt der Datenbank in das Verzeichnis @dossier@ zu sichern. Vergessen Sie bitte nicht, ebenfalls den Inhalt des Verzeichnisses <i>img/</i> zu sichern, denn es enthält die Bilder und Grafiken, welche für Rubriken und Artikel verwendet werden.',
	'texte_admin_tech_02' => 'Achtung: Diese Sicherungskopie kann AUSSCHLIESSLICH in eine Website wieder eingespielt werden, die unter der gleichen Version von SPIP läuft.  So darf insbesondere die Datenbank vor einem Update nicht "geleert" werden. Bitte verwenden Sie keine Sicherungskopie, um den Inhalt einer Website nach einem Update wieder einzuspielen. Mehr dazu steht in der <a href="@spipnet@">die SPIP Dokumentation</a>.', # MODIF
	'texte_restaurer_base' => 'Wiederherstellung des Inhalts der Datenbank',
	'texte_restaurer_sauvegarde' => 'Mit dieser Funktion können Sie eine Sicherungskopie Ihrer Datenbank wieder einspielen. Dazu muss die Sicherungsdatei in das Verzeichnis @dossier@ kopiert werden. Verwenden Sie diese Funktion mit der nötigen Vorsicht. <b>Die Änderungen können nicht wieder rückgängig gemacht werden.</b>',
	'texte_sauvegarde' => 'Inhalt der Datenbank sichern',
	'texte_sauvegarde_base' => 'Datenbank sichern',
	'tout_restaurer' => 'Restaurer toutes les tables', # NEW
	'tout_sauvegarder' => 'Sauvegarder toutes les tables', # NEW

	// U
	'une_donnee' => '1 enregistrement' # NEW
);

?>
