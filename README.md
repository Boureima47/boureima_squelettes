# Plugin Boureima - Squelettes

Plugin contenant les squelettes personnalisés pour le site Boureima.

## Description

Ce plugin fournit les templates SPIP personnalisés pour le site, incluant :

- **sommaire.html** : Page d'accueil avec liste des articles récents
- **article.html** : Affichage des articles avec navigation entre articles
- **inclure/menu-principal.html** : Menu de navigation principal avec sous-menus

## Fonctionnalités

### Templates inclus

#### sommaire.html
- Affichage du nom et description du site
- Liste des 5 articles les plus récents
- Menu de navigation intégré
- Structure HTML5 sémantique

#### article.html
- Affichage complet d'un article (titre, date, auteur, contenu)
- Navigation entre articles (précédent/suivant)
- Affichage des mots-clés associés
- Métadonnées optimisées pour le SEO

#### inclure/menu-principal.html
- Menu hiérarchique avec rubriques et sous-rubriques
- Indicateur de page courante
- Support des sous-menus

## Structure des fichiers

```
plugins/boureima_squelettes/
├── paquet.xml
├── sommaire.html
├── article.html
├── inclure/
│   └── menu-principal.html
└── README.md
```

## Utilisation

1. Activer le plugin dans l'interface d'administration SPIP
2. Les templates remplacent automatiquement ceux par défaut
3. Personnaliser les templates selon vos besoins

## Personnalisation

Vous pouvez modifier les templates pour :
- Ajouter de nouveaux champs
- Modifier la structure HTML
- Intégrer de nouvelles boucles SPIP
- Adapter l'affichage responsive

## Compatibilité

- SPIP 4.4+
- Compatible avec le plugin thème Boureima

## Développement

Pour contribuer au développement :
1. Suivre les conventions SPIP pour les templates
2. Utiliser la syntaxe des boucles SPIP
3. Maintenir la compatibilité HTML5
4. Tester sur différents navigateurs