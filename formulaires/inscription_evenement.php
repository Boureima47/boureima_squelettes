<?php
/**
 * Gestion du formulaire d'inscription à un événement
 *
 * @plugin Boureima Squelettes
 */

if (!defined('_ECRIRE_INC_VERSION')) {
	return;
}

include_spip('inc/actions');
include_spip('inc/editer');

/**
 * Chargement du formulaire d'inscription à un événement
 *
 * @param int $id_evenement
 *     Identifiant de l'événement
 * @return array|bool
 *     Environnement du formulaire ou false si l'événement n'existe pas ou n'accepte pas les inscriptions
 */
function formulaires_inscription_evenement_charger_dist($id_evenement) {
	// Valeurs par défaut
	$valeurs = array(
		'civilite' => '',
		'prenom' => '',
		'nom' => '',
		'email' => '',
		'institution' => '',
		'fonction' => '',
		'telephone' => '',
		'commentaire' => '',
	);

	// Si l'utilisateur est connecté, on récupère ses informations
	if (isset($GLOBALS['visiteur_session']['id_auteur'])) {
		$id_auteur = $GLOBALS['visiteur_session']['id_auteur'];

		// Récupérer les informations de base de l'auteur
		$auteur = sql_fetsel('*', 'spip_auteurs', 'id_auteur='.intval($id_auteur));
		if ($auteur) {
			// Nom complet - on essaie de séparer prénom et nom si possible
			$nom_complet = $auteur['nom'];
			$parties_nom = explode(' ', $nom_complet, 2);
			if (count($parties_nom) > 1) {
				$valeurs['prenom'] = $parties_nom[0];
				$valeurs['nom'] = $parties_nom[1];
			} else {
				$valeurs['nom'] = $nom_complet;
			}

			$valeurs['email'] = $auteur['email'];

			// Récupérer les informations supplémentaires si disponibles
			// Certains plugins comme "contacts_et_organisations" peuvent stocker ces informations

			// Vérifier si le plugin contacts_et_organisations est actif
			if (defined('_DIR_PLUGIN_CONTACTS')) {
				// Récupérer directement les informations du contact lié à l'auteur
				$contact = sql_fetsel(
					'id_contact, civilite, prenom, nom, fonction',
					'spip_contacts',
					'id_auteur='.intval($id_auteur)
				);

				if ($contact) {
					// Utiliser les informations du contact pour pré-remplir le formulaire
					$valeurs['civilite'] = $contact['civilite'];
					$valeurs['prenom'] = $contact['prenom'];
					$valeurs['nom'] = $contact['nom'];
					$valeurs['fonction'] = $contact['fonction'];

					// Récupérer l'organisation liée à ce contact via la table de liaison
					$organisation = sql_fetsel(
						'o.nom',
						'spip_organisations AS o, spip_organisations_liens AS ol',
						'ol.id_organisation = o.id_organisation AND ol.id_objet = '.intval($contact['id_contact']).' AND ol.objet = "contact"'
					);

					if ($organisation) {
						$valeurs['institution'] = $organisation['nom'];
					}
				}
			}

			// Vérifier si l'utilisateur s'est déjà inscrit à un événement
			$precedente_inscription = sql_fetsel(
				'civilite, prenom, nom, institution, fonction, telephone',
				'spip_evenements_participants',
				'id_auteur='.intval($id_auteur).' AND reponse="oui"',
				'',
				'date DESC',
				'0,1'
			);

			if ($precedente_inscription) {
				// Utiliser ces informations pour compléter les champs manquants
				if (empty($valeurs['civilite']) && !empty($precedente_inscription['civilite'])) {
					$valeurs['civilite'] = $precedente_inscription['civilite'];
				}
				if (empty($valeurs['prenom']) && !empty($precedente_inscription['prenom'])) {
					$valeurs['prenom'] = $precedente_inscription['prenom'];
				}
				if (empty($valeurs['institution']) && !empty($precedente_inscription['institution'])) {
					$valeurs['institution'] = $precedente_inscription['institution'];
				}
				if (empty($valeurs['fonction']) && !empty($precedente_inscription['fonction'])) {
					$valeurs['fonction'] = $precedente_inscription['fonction'];
				}
				if (empty($valeurs['telephone']) && !empty($precedente_inscription['telephone'])) {
					$valeurs['telephone'] = $precedente_inscription['telephone'];
				}
			}
		}
	}

	// Vérifier si l'événement existe et accepte les inscriptions
	$evenement = sql_fetsel('inscription,places,titre,date_debut', 'spip_evenements', 'id_evenement='.intval($id_evenement).' AND date_fin>'.sql_quote(date('Y-m-d H:i:s')));

	if (!$evenement || !$evenement['inscription']) {
		return false;
	}

	// Ajouter les informations sur l'événement
	$valeurs['id_evenement'] = $id_evenement;
	$valeurs['titre_evenement'] = $evenement['titre'];
	$valeurs['date_evenement'] = $evenement['date_debut'];

	// Calcul des places disponibles
	if ($places_total = intval($evenement['places'])) {
		$places_prises = sql_countsel('spip_evenements_participants', 'id_evenement='.intval($id_evenement)." AND reponse='oui'");
		$valeurs['places_total'] = $places_total;
		$valeurs['places_disponibles'] = max(0, $places_total - $places_prises);

		// Si l'événement est complet et que l'utilisateur n'est pas déjà inscrit, on désactive le formulaire
		if ($valeurs['places_disponibles'] <= 0) {
			$deja_inscrit = false;

			if (isset($GLOBALS['visiteur_session']['id_auteur'])) {
				$deja_inscrit = sql_getfetsel('id_evenement_participant', 'spip_evenements_participants',
					'id_evenement='.intval($id_evenement).' AND id_auteur='.intval($GLOBALS['visiteur_session']['id_auteur'])." AND reponse='oui'");
			}

			if (!$deja_inscrit) {
				$valeurs['message_erreur'] = 'Cet événement est complet.';
				$valeurs['editable'] = false;
			}
		}
	} else {
		// Si le nombre de places n'est pas limité
		$valeurs['places_total'] = 0;
		$valeurs['places_disponibles'] = 0;
	}

	return $valeurs;
}

/**
 * Vérification du formulaire d'inscription à un événement
 *
 * @param int $id_evenement
 *     Identifiant de l'événement
 * @return array
 *     Tableau des erreurs
 */
function formulaires_inscription_evenement_verifier_dist($id_evenement) {
	$erreurs = array();

	// Vérification des champs obligatoires pour tous les utilisateurs
	if (!_request('prenom')) {
		$erreurs['prenom'] = 'Le prénom est obligatoire';
	}

	if (!_request('nom')) {
		$erreurs['nom'] = 'Le nom est obligatoire';
	}

	// Vérification de l'email uniquement pour les utilisateurs non connectés
	if (!isset($GLOBALS['visiteur_session']['id_auteur'])) {
		if (!_request('email')) {
			$erreurs['email'] = 'L\'email est obligatoire';
		} elseif (!email_valide(_request('email'))) {
			$erreurs['email'] = 'L\'email n\'est pas valide';
		}

		// Vérifier si l'email n'est pas déjà inscrit
		$email = _request('email');
		if ($email && sql_getfetsel('id_evenement_participant', 'spip_evenements_participants',
			'id_evenement='.intval($id_evenement).' AND email='.sql_quote($email))) {
			$erreurs['email'] = 'Cette adresse email est déjà inscrite à cet événement';
		}
	}

	// Vérifier s'il reste des places disponibles
	$evenement = sql_fetsel('places', 'spip_evenements', 'id_evenement='.intval($id_evenement));
	if ($places_total = intval($evenement['places'])) {
		$places_prises = sql_countsel('spip_evenements_participants', 'id_evenement='.intval($id_evenement)." AND reponse='oui'");
		$places_disponibles = max(0, $places_total - $places_prises);

		if ($places_disponibles <= 0) {
			// Si l'utilisateur est connecté, vérifier s'il est déjà inscrit
			$deja_inscrit = false;
			if (isset($GLOBALS['visiteur_session']['id_auteur'])) {
				$deja_inscrit = sql_getfetsel('id_evenement_participant', 'spip_evenements_participants',
					'id_evenement='.intval($id_evenement).' AND id_auteur='.intval($GLOBALS['visiteur_session']['id_auteur'])." AND reponse='oui'");
			}

			if (!$deja_inscrit) {
				$erreurs['message_erreur'] = 'Cet événement est complet.';
			}
		}
	}

	return $erreurs;
}

/**
 * Traitement du formulaire d'inscription à un événement
 *
 * @param int $id_evenement
 *     Identifiant de l'événement
 * @return array
 *     Retours du traitement
 */
function formulaires_inscription_evenement_traiter_dist($id_evenement) {
	$retours = array();

	// Récupérer les données du formulaire
	$civilite = _request('civilite');
	$prenom = _request('prenom');
	$nom = _request('nom');
	$email = _request('email');
	$institution = _request('institution');
	$fonction = _request('fonction');
	$telephone = _request('telephone');
	$commentaire = _request('commentaire');

	// Récupérer les informations de l'événement
	$evenement = sql_fetsel('titre,date_debut', 'spip_evenements', 'id_evenement='.intval($id_evenement));

	// Si l'utilisateur est connecté
	if (isset($GLOBALS['visiteur_session']['id_auteur'])) {
		$id_auteur = $GLOBALS['visiteur_session']['id_auteur'];
		$email = $GLOBALS['visiteur_session']['email']; // Utiliser l'email de l'utilisateur connecté

		// Vérifier si l'utilisateur est déjà inscrit
		$id_participant = sql_getfetsel('id_evenement_participant', 'spip_evenements_participants',
			'id_evenement='.intval($id_evenement).' AND id_auteur='.intval($id_auteur));

		if ($id_participant) {
			// Mise à jour de l'inscription existante
			sql_updateq(
				'spip_evenements_participants',
				array(
					'reponse' => 'oui',
					'date' => date('Y-m-d H:i:s'),
					'civilite' => $civilite,
					'prenom' => $prenom,
					'nom' => $nom,
					'institution' => $institution,
					'fonction' => $fonction,
					'telephone' => $telephone,
					'commentaire' => $commentaire
				),
				'id_evenement_participant='.intval($id_participant)
			);
		} else {
			// Nouvelle inscription
			$id_participant = sql_insertq(
				'spip_evenements_participants',
				array(
					'id_evenement' => $id_evenement,
					'id_auteur' => $id_auteur,
					'reponse' => 'oui',
					'date' => date('Y-m-d H:i:s'),
					'civilite' => $civilite,
					'prenom' => $prenom,
					'nom' => $nom,
					'email' => $email,
					'institution' => $institution,
					'fonction' => $fonction,
					'telephone' => $telephone,
					'commentaire' => $commentaire
				)
			);
		}
	} else {
		// Utilisateur anonyme
		$id_participant = sql_insertq(
			'spip_evenements_participants',
			array(
				'id_evenement' => $id_evenement,
				'civilite' => $civilite,
				'prenom' => $prenom,
				'nom' => $nom,
				'email' => $email,
				'institution' => $institution,
				'fonction' => $fonction,
				'reponse' => 'oui',
				'date' => date('Y-m-d H:i:s'),
				'telephone' => $telephone,
				'commentaire' => $commentaire
			)
		);
	}

	// Envoyer un email de confirmation
	if ($id_participant) {
		// Préparer le message
		$sujet = 'Confirmation d\'inscription : ' . $evenement['titre'];
		$message = "Bonjour " . ($civilite ? $civilite . ' ' : '') . ($prenom ? $prenom . ' ' : '') . ($nom ? $nom : 'Participant') . ",\n\n";
		$message .= "Votre inscription à l'événement suivant a bien été prise en compte :\n\n";
		$message .= "Événement : " . $evenement['titre'] . "\n";
		$message .= "Date : " . affdate($evenement['date_debut']) . "\n\n";
		$message .= "Merci de votre participation.\n\n";
		$message .= "Cordialement,\n";
		$message .= "L'équipe " . $GLOBALS['meta']['nom_site'] . "\n";

		// Envoyer l'email
		$destinataire = $email;
		if ($destinataire) {
			$envoyer_mail = charger_fonction('envoyer_mail', 'inc');
			$envoyer_mail($destinataire, $sujet, $message);
		}

		// Notifier l'administrateur
		$sujet_admin = 'Nouvelle inscription : ' . $evenement['titre'];
		$message_admin = "Une nouvelle inscription a été enregistrée :\n\n";
		$message_admin .= "Événement : " . $evenement['titre'] . "\n";
		$message_admin .= "Date : " . affdate($evenement['date_debut']) . "\n";
		$message_admin .= "Participant : " . ($civilite ? $civilite . ' ' : '') . ($prenom ? $prenom . ' ' : '') . ($nom ? $nom : '') . "\n";
		$message_admin .= "Email : " . $email . "\n";
		if ($institution) $message_admin .= "Institution : " . $institution . "\n";
		if ($fonction) $message_admin .= "Fonction : " . $fonction . "\n";
		if ($telephone) $message_admin .= "Téléphone : " . $telephone . "\n";
		if ($commentaire) $message_admin .= "Commentaire : " . $commentaire . "\n";

		$envoyer_mail($GLOBALS['meta']['email_webmaster'], $sujet_admin, $message_admin);

		// Message de confirmation
		$retours['message_ok'] = 'Votre inscription a bien été prise en compte.';
		$retours['editable'] = false;
	} else {
		$retours['message_erreur'] = 'Une erreur est survenue lors de l\'inscription.';
	}

	// Invalider le cache
	include_spip('inc/invalideur');
	suivre_invalideur("id='evenement/$id_evenement'");

	return $retours;
}