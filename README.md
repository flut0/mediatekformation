# Mediatekformation — Fonctionnalités ajoutées

> Ce dépôt contient les fonctionnalités ajoutées au projet d'origine.  
> Le dépôt d'origine est disponible ici : https://github.com/CNED-SLAM/mediatekformation  
> Il contient dans son readme la présentation complète de l'application d'origine.

## Site en ligne
https://ilyesahouzi.alwaysdata.net

## Fonctionnalités ajoutées

### 1. Qualité du code (Mission 1)
- Corrections des problèmes détectés par SonarLint
- Ajout d'une colonne "nb formations" dans la page playlists, avec tri possible

### 2. Back office d'administration (Mission 2)
Le back office permet de gérer les formations, playlists et catégories :<br>
- **Formations** : affichage, tri, recherche, ajout, modification, suppression<br>
- **Playlists** : affichage, tri, recherche, ajout, modification, suppression (si vide)<br>
- **Catégories** : affichage, ajout, suppression (si non utilisée)<br>

L'accès au back office est sécurisé par une authentification (login/mot de passe).<br>
Les informations de connexion sont disponibles dans la fiche de réalisation professionnelle.

### 3. Tests PHPUnit (Mission 3)
16 tests automatisés ont été mis en place :<br>
- Tests unitaires (Formation)
- Tests de validation (contrainte de date)
- Tests d'intégration (Repository)
- Tests fonctionnels (pages du site)

Résultat : OK (16 tests, 21 assertions)

### 4. Déploiement (Mission 4)
- Site déployé sur Alwaysdata
- Sauvegarde automatique quotidienne de la base de données
- Déploiement continu via GitHub Actions (chaque push met à jour le site)

## Installation en local

### Prérequis
- PHP 8.2, Composer, Git, XAMPP (ou équivalent)

### Étapes
1. Cloner le dépôt : `git clone https://github.com/flut0/mediatekformation.git`
2. Se positionner dans le dossier : `cd mediatekformation`
3. Installer les dépendances : `composer install`
4. Créer la base de données `mediatekformation` dans phpMyAdmin
5. Importer le fichier `mediatekformation.sql` dans la BDD
6. Lancer le site : http://localhost/mediatekformation/public/index.php

## Lancer les tests en local

1. Créer la base de données de test `mediatekformation_test`
2. Importer le fichier `mediatekformation.sql` dans cette BDD
3. Lancer les tests : `php bin/phpunit tests/`

## Tester l'application en ligne

Le site est accessible à : https://ilyesahouzi.alwaysdata.net<br>
Les informations pour accéder à la partie administration sont disponibles dans la fiche de réalisation professionnelle.