<?php
/**
 * Pipelines pour le plugin Boureima Squelettes
 *
 * @plugin Boureima Squelettes
 */

if (!defined('_ECRIRE_INC_VERSION')) {
	return;
}

/**
 * Déclarer les champs supplémentaires pour les tables SQL
 *
 * @pipeline declarer_tables_objets_sql
 * @param array $tables
 *     Description des tables
 * @return array
 *     Description complétée des tables
 */
function boureima_squelettes_declarer_tables_objets_sql($tables) {
	// Ajouter les champs supplémentaires à la table spip_evenements_participants
	if (isset($tables['spip_evenements_participants'])) {
		$tables['spip_evenements_participants']['field']['civilite'] = "VARCHAR(10) DEFAULT '' NOT NULL";
		$tables['spip_evenements_participants']['field']['prenom'] = "TEXT DEFAULT '' NOT NULL";
		$tables['spip_evenements_participants']['field']['institution'] = "TEXT DEFAULT '' NOT NULL";
		$tables['spip_evenements_participants']['field']['fonction'] = "TEXT DEFAULT '' NOT NULL";
		$tables['spip_evenements_participants']['field']['telephone'] = "TEXT DEFAULT '' NOT NULL";
		$tables['spip_evenements_participants']['field']['commentaire'] = "TEXT DEFAULT '' NOT NULL";
	}

	return $tables;
}

/**
 * Déclarer les interfaces des tables
 *
 * @pipeline declarer_tables_interfaces
 * @param array $interface
 *     Description des interfaces des tables
 * @return array
 *     Description complétée des interfaces des tables
 */
function boureima_squelettes_declarer_tables_interfaces($interface) {
	// Déclarer les champs supplémentaires pour la recherche
	if (isset($interface['table_des_traitements']['CIVILITE'])) {
		$interface['table_des_traitements']['CIVILITE'][] = _TRAITEMENT_TYPO;
	}
	if (isset($interface['table_des_traitements']['PRENOM'])) {
		$interface['table_des_traitements']['PRENOM'][] = _TRAITEMENT_TYPO;
	}
	if (isset($interface['table_des_traitements']['INSTITUTION'])) {
		$interface['table_des_traitements']['INSTITUTION'][] = _TRAITEMENT_TYPO;
	}
	if (isset($interface['table_des_traitements']['FONCTION'])) {
		$interface['table_des_traitements']['FONCTION'][] = _TRAITEMENT_TYPO;
	}

	return $interface;
}