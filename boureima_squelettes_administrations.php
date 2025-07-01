<?php
/**
 * Fichier d'administration du plugin Boureima Squelettes
 *
 * @plugin Boureima Squelettes
 */

if (!defined('_ECRIRE_INC_VERSION')) {
	return;
}

/**
 * Fonction d'installation et de mise à jour du plugin
 *
 * @param string $nom_meta_base_version
 *     Nom de la meta informant de la version du schéma de données du plugin installé dans SPIP
 * @param string $version_cible
 *     Version du schéma de données dans ce plugin (déclaré dans paquet.xml)
 * @return void
 */
function boureima_squelettes_upgrade($nom_meta_base_version, $version_cible) {
	$maj = array();

	$maj['create'] = array(
		array('boureima_squelettes_maj_tables')
	);

	$maj['1.0.1'] = array(
		array('maj_tables', array('spip_evenements_participants')),
	);

	include_spip('base/upgrade');
	maj_plugin($nom_meta_base_version, $version_cible, $maj);
}

/**
 * Fonction de désinstallation du plugin
 *
 * @param string $nom_meta_base_version
 *     Nom de la meta informant de la version du schéma de données du plugin installé dans SPIP
 * @return void
 */
function boureima_squelettes_vider_tables($nom_meta_base_version) {
	effacer_meta($nom_meta_base_version);
}

/**
 * Ajoute les champs nécessaires à la table spip_evenements_participants
 */
function boureima_squelettes_maj_tables() {
	include_spip('base/create');
	include_spip('base/abstract_sql');

	// Ajouter les champs manquants à la table spip_evenements_participants
	sql_alter("TABLE spip_evenements_participants ADD civilite VARCHAR(10) DEFAULT '' NOT NULL");
	sql_alter("TABLE spip_evenements_participants ADD prenom TEXT DEFAULT '' NOT NULL");
	sql_alter("TABLE spip_evenements_participants ADD institution TEXT DEFAULT '' NOT NULL");
	sql_alter("TABLE spip_evenements_participants ADD fonction TEXT DEFAULT '' NOT NULL");
	sql_alter("TABLE spip_evenements_participants ADD telephone TEXT DEFAULT '' NOT NULL");
	sql_alter("TABLE spip_evenements_participants ADD commentaire TEXT DEFAULT '' NOT NULL");
}