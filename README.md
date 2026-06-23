# ZARTICLE

ZARTICLE est une application de publication d'articles construite avec Laravel. Elle permet à des contributeurs de créer des contenus, à des lecteurs d'interagir avec ces contenus et à des administrateurs de modérer la plateforme.

## Description du projet

ZARTICLE offre un système de publication d'articles structuré par catégories, avec un espace social et des outils de modération.

Le projet inclut :

- Publication d'articles avec titre, slug SEO, contenu et image.
- Catégories d'articles pour organiser les contenus.
- Recherche par mot-clé et filtres de catégorie.
- Exploration des articles populaires basée sur les réactions et les interactions.
- Favoris pour sauvegarder des articles.
- Commentaires avec support des réponses et des likes sur les commentaires.
- Profil utilisateur avec avatar, biographie, suivi et listes d'abonnements.
- Notifications pour alerter les abonnés lorsqu'un auteur publie un nouvel article.
- Tableau d'administration pour gérer les catégories, les articles et les utilisateurs.
- Gestion des comptes bannis et contrôle d'accès administrateur.

## Fonctionnalités

- Authentification des utilisateurs (inscription, connexion, déconnexion).
- Création, édition et suppression d'articles par les auteurs.
- Tous les articles publiés sont visibles sur la page d'accueil.
- L'espace `/explore` affiche des articles triés par popularité.
- Les utilisateurs peuvent aimer, mettre en favori et commenter des articles.
- Les auteurs peuvent suivre d'autres utilisateurs et recevoir des notifications.
- Page de profil public pour voir les articles d'un membre.
- Espace administratif sécurisé sous `/admin`.

## Architecture

### Modèles principaux

- `User` : utilisateur avec rôle administrateur, statut vérifié, et champ de bannissement.
- `Article` : articles liés à un auteur et à une catégorie, pouvant être bloqués ou publiés.
- `ArticleCategory` : catégories pour organiser les articles.
- `Comment` : commentaires d'articles avec relation parent/enfant pour les réponses.

### Relations sociales

- `article_user_like` : likes d'articles.
- `article_user_favorite` : articles favoris.
- `comment_user_like` : likes de commentaires.
- `follows` : relations de suivi entre utilisateurs.

## Administration

L'administration est protégée par le middleware `auth` et `admin`.

- Gestion des articles : consultation, blocage et suppression.
- Gestion des catégories : création, modification et suppression.
- Gestion des utilisateurs : vérification et bannissement.

## Authentification et sécurité

- Inscription avec mot de passe confirmé.
- Connexion sécurisée et gestion de session.
- Un utilisateur banni est empêché de se connecter.
- Les administrateurs sont créés automatiquement pour les adresses email se terminant par `@zarticle.com`.

## Installation

1. Copier l'exemple d'environnement :

```bash
copy .env.example .env
```

2. Installer les dépendances PHP :

```bash
composer install
```

3. Générer la clé d'application :

```bash
php artisan key:generate
```

4. Configurer la base de données dans `.env`.

5. Lancer les migrations :

```bash
php artisan migrate
```

6. Créer le lien de stockage public si nécessaire :

```bash
php artisan storage:link
```

## Exécution

- Lancer le serveur de développement :

```bash
php artisan serve
```

- Exécuter les tests :

```bash
composer test
```

## Scripts utiles

- `composer setup` : installe les dépendances, copie le fichier `.env`, génère la clé et lance les migrations.
- `composer dev` : lance le serveur local, la queue, les logs et Vite en développement.
- `composer test` : exécute les tests PHPUnit.

## Routes principales

- `/` : accueil des articles publiés.
- `/explore` : exploration des articles populaires.
- `/articles/create` : création d'article.
- `/articles/{slug}` : lecture d'un article.
- `/search` : recherche globale.
- `/profile` : profil utilisateur connecté.
- `/notifications` : listes des notifications.
- `/admin` : tableau de bord administrateur.

## Technologies

- Laravel 13
- PHP 8.3
- Eloquent ORM
- Blade Templates
- Système de notifications Laravel
- Migrations et relations Eloquent

## Licence

Projet distribué sous licence MIT.
