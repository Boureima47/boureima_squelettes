# Plugin Boureima - Squelettes

Plugin contenant l'ensemble des squelettes personnalisés pour le site Boureima, offrant une solution complète pour la gestion de contenus, contacts, organisations et événements.

## Description

Ce plugin fournit un thème complet pour SPIP avec des templates personnalisés optimisés pour :

- **Gestion de contenu** : Articles, rubriques, auteurs avec templates responsive
- **Annuaire** : Contacts et organisations avec fiches détaillées
- **Agenda** : Événements avec système d'inscription
- **Interface utilisateur** : Navigation avancée, recherche, formulaires personnalisés
- **Modèles réutilisables** : Cards et blocs pour l'affichage modulaire

## Fonctionnalités principales

### 🏠 Templates de base
- **sommaire.html** : Page d'accueil avec présentation des contenus récents
- **article.html / articles.html** : Affichage des articles avec navigation
- **rubrique.html** : Pages de rubriques avec sous-contenus
- **auteur.html** : Fiches d'auteurs
- **recherche.html** : Moteur de recherche avancé

### 👥 Gestion des contacts et organisations
- **contact_detail.html** : Fiches détaillées des contacts
- **organisation_detail.html** : Fiches complètes des organisations
- **modifier_contact.html / modifier_organisation.html** : Édition des fiches
- **contacts.html / organisations.html** : Listes et annuaires

### 📅 Système d'événements
- **evenement.html** : Pages détaillées des événements
- **evenements.html** : Calendrier et liste des événements
- **inscription_evenement.html** : Formulaire d'inscription aux événements

### 📝 Formulaires personnalisés
- **editer_contact** : Création/modification de contacts
- **editer_organisation** : Gestion des organisations
- **inscription_evenement** : Inscription aux événements avec validation
- **contact** : Formulaire de contact général
- **recherche** : Recherche avancée multi-critères

### 🧩 Éléments d'interface
- **header.html / footer.html** : En-tête et pied de page
- **menu-principal.html** : Navigation hiérarchique avec sous-menus
- **breadcrumb.html** : Fil d'Ariane
- **pagination.html** : Navigation par pages
- **recherche.html** : Moteur de recherche intégré

### 🎨 Modèles réutilisables
- **article-card.html** : Carte d'aperçu d'article
- **evenement-card.html** : Carte d'événement avec détails
- **formation-card.html** : Carte de formation
- **rubrique-bloc.html** : Bloc de présentation de rubrique

## Structure complète des fichiers

```
plugins/boureima_squelettes/
├── paquet.xml
├── README.md
├── boureima_squelettes_pipelines.php
├── boureima_squelettes_administrations.php
├── js/
│   └── boureima_squelettes.js
│
├── Templates principaux
├── sommaire.html
├── article.html
├── articles.html
├── auteur.html
├── rubrique.html
├── recherche.html
│
├── Templates spécialisés
├── contact_detail.html
├── organisation_detail.html
├── modifier_contact.html
├── modifier_organisation.html
├── contacts.html
├── organisations.html
├── evenement.html
├── evenements.html
├── inscription_evenement.html
│
├── formulaires/
│   ├── editer_contact.html/.php
│   ├── editer_organisation.html/.php
│   ├── inscription_evenement.html/.php
│   ├── contact.html
│   └── recherche.html
│
├── inclure/
│   ├── header.html
│   ├── footer.html
│   ├── menu-principal.html
│   ├── breadcrumb.html
│   ├── pagination.html
│   ├── recherche.html
│   └── article_documents.html
│
├── content/
│   ├── article.html
│   ├── auteur.html
│   ├── rubrique.html
│   ├── contact.html
│   ├── organisation.html
│   ├── evenement.html
│   ├── recherche.html
│   ├── modifier_contact.html
│   └── modifier_organisation.html
│
├── pages/
│   ├── sommaire.html
│   ├── articles.html
│   ├── contacts.html
│   ├── organisations.html
│   ├── evenements.html
│   └── recherche.html
│
└── modeles/
    ├── article-card.html
    ├── evenement-card.html
    ├── formation-card.html
    └── rubrique-bloc.html
```

## Installation et configuration

### Prérequis
- **SPIP 4.4.0** ou supérieur
- **Plugin Agenda** (≥ 4.0.0) pour la gestion des événements
- Recommandé : Plugins Contacts & Organisations pour l'annuaire

### Installation
1. Placer le plugin dans `plugins/boureima_squelettes/`
2. Activer le plugin dans l'interface d'administration SPIP
3. Les templates remplacent automatiquement ceux par défaut
4. Configurer les pipelines si nécessaire

### Configuration
Le plugin s'intègre automatiquement avec :
- Le système de contenus SPIP (articles, rubriques, auteurs)
- Le plugin Agenda pour les événements
- Les plugins Contacts & Organisations pour l'annuaire
- Le système de recherche SPIP

## Utilisation des modèles

### Dans les squelettes
```html
<!-- Utiliser les cartes d'articles -->
<BOUCLE_articles_recents(ARTICLES){par date}{inverse}{0,3}>
    [(#MODELE{article-card}{id_article})]
</BOUCLE_articles_recents>

<!-- Afficher les événements à venir -->
<BOUCLE_evenements(EVENEMENTS){par date_debut}{age_fin<0}{0,5}>
    [(#MODELE{evenement-card}{id_evenement})]
</BOUCLE_evenements>

<!-- Inclure la navigation -->
[(#INCLURE{fond=inclure/menu-principal})]

<!-- Fil d'Ariane -->
[(#INCLURE{fond=inclure/breadcrumb})]
```

### Formulaires personnalisés
```html
<!-- Formulaire de contact -->
[(#FORMULAIRE_CONTACT)]

<!-- Édition de contact -->
[(#FORMULAIRE_EDITER_CONTACT{#ID_CONTACT})]

<!-- Inscription à un événement -->
[(#FORMULAIRE_INSCRIPTION_EVENEMENT{#ID_EVENEMENT})]
```

## Fonctionnalités avancées

### Pipelines intégrés
- **declarer_tables_objets_sql** : Extension des tables SPIP
- **declarer_tables_interfaces** : Interfaces personnalisées

### JavaScript intégré
- Script `boureima_squelettes.js` pour les interactions dynamiques
- Support des formulaires AJAX
- Animations et effets visuels

### Responsive design
- Templates optimisés pour mobile et desktop
- Grilles flexibles avec CSS Grid/Flexbox
- Images adaptatives

## Personnalisation

### Modifier l'apparence
1. Éditer les fichiers CSS du thème compagnon
2. Personnaliser les templates selon vos besoins
3. Ajouter de nouveaux modèles dans `modeles/`

### Ajouter des fonctionnalités
1. Créer de nouveaux formulaires dans `formulaires/`
2. Ajouter des inclusions dans `inclure/`
3. Étendre les pipelines dans `boureima_squelettes_pipelines.php`

### Structure modulaire
- **content/** : Logique métier des contenus
- **pages/** : Mise en page complète
- **inclure/** : Éléments réutilisables
- **modeles/** : Composants modulaires

## Compatibilité et dépendances

### Compatible avec
- **SPIP 4.4+** (version de développement)
- **Plugin Agenda** pour les événements
- **Plugins Contacts & Organisations** pour l'annuaire
- **Navigateurs modernes** (Chrome, Firefox, Safari, Edge)

### Optimisé pour
- **SEO** : Métadonnées et structure sémantique
- **Accessibilité** : Respect des standards WCAG
- **Performance** : Code optimisé et mise en cache
- **Sécurité** : Validation des formulaires et protection CSRF

## Développement et contribution

### Architecture
- **MVC Pattern** : Séparation logique/présentation
- **Templates modulaires** : Réutilisabilité maximale
- **Progressive enhancement** : Amélioration progressive

### Standards respectés
- **HTML5 sémantique** : Structure claire et accessible
- **CSS moderne** : Grid, Flexbox, Custom Properties
- **JavaScript vanilla** : Performance et compatibilité
- **Conventions SPIP** : Respect des bonnes pratiques

### Maintenance
- **Version** : 1.0.1 (développement)
- **État** : Version de développement
- **Licence** : GPL-3.0-only
- **Auteur** : Équipe Boureima

## Support et documentation

### Ressources
- **Code source** : Disponible dans le plugin
- **Templates de base** : Documentation SPIP officielle
- **Plugins liés** : Agenda, Contacts & Organisations

### Signaler un problème
1. Identifier le template ou la fonctionnalité concernée
2. Fournir les détails de configuration SPIP
3. Inclure les messages d'erreur éventuels
4. Contacter l'équipe de développement

---

*Ce plugin fait partie de l'écosystème Boureima pour SPIP 4.4+*