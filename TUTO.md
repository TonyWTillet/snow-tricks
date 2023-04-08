# Formation Symfony

## Installation de Symfony
- Checker les requis pour installer Symfony avec la commande `symfony check:requirements`
- Installer Symfony avec la commande `symfony new mon-projet --webapp`
- Lien vers la documentation : https://symfony.com/doc/current/index.html
- Commande générale pour symfony : `symfony`
- Installer Encore avec la commande  `composer require symfony/webpack-encore-bundle`
- Lancer `yarn install` ou `npm install` pour installer les dépendances
- Démarrer le serveur de développement avec la commande `yarn encore dev --watch` ou `npm run dev --watch`

## Lancer le serveur de développement
- Lancer le serveur de développement avec la commande `symfony serve`

## Créer notre première page web
- Voir toutes les commandes disponibles avec la commande `php bin/console`
- Créer un controller avec la commande `php bin/console make:controller`
- Ce qui va créer un fichier `src/Controller/DefaultController.php`
- Ajouter une route dans le fichier `config/routes.yaml`
- Ajouter une méthode dans le controller `src/Controller/DefaultController.php`
- Ajouter une vue dans le dossier `templates/default/index.html.twig`

## Twig
- Twig est un moteur de template
- Nous allonfs fragmenter notre code HTML en plusieurs fichiers par exemple `partials/_header.html.twig`, `partials/_footer.html.twig`, `base.html.twig`. Puis nous crééons un ficher `home/index.html.twig` pour afficher le contenu de notre controller `HomeController.php` qui extends de `base.html.twig`
- Pour faciliter l'import des assets (css, js, images) nous allons utiliser le package `symfony/webpack-encore-bundle` avec la commande `composer require symfony/webpack-encore-bundle` mais aussi le package assets avec la commande `composer require symfony/asset` : https://symfony.com/doc/current/components/asset.html

## Utiliser Doctrine / ORM
- Créer la base de données avec la commande `php bin/console doctrine:database:create` ou `php bin/console d:d:c`
- Créer une entité avec la commande `php bin/console make:entity`
- Créer une migration avec la commande avec les entités `php bin/console doctrine:migration:diff` ou `php bin/console d:m:diff`
- Créer les tables en éxécutant les migrations avec la commande `php bin/console doctrine:migrations:migrate` ou `php bin/console d:m:m`
 
## Utiliser MAKE  avec Makefile
- Créer un fichier Makefile à la racine du projet
  - `# Variables
    PHP = php
    COMPOSER = composer
    NPM = npm
    SYMFONY_CONSOLE = PHP bin/console
    SYMFONY_CLI = symfony`
    `# Colors
    GREEN = /bin/echo -e "\x1b[32m\#\# $1\x1b[0m"
    RED = /bin/echo -e "\x1b[31m\#\# $1\x1b[0m"`
    `## —— 🔥 App ——
    init: ## Init the project
    $(COMPOSER) install
    $(NPM) install
    $(NPM) run
    $(SYMFONY_CLI) server:start
    @$(call GREEN,"The application is available at: http://127.0.0.1:8000/.")`

## Installation de l'evironnement de test
- https://symfony.com/doc/6.2/the-fast-track/fr/17-tests.html#ecrire-des-tests-unitaires
- Créer la base de donnée de test : `php bin/console d:d:c --env=test`
- Puis générer les migrations : `php bin/console d:m:m --env=test`

## Installer Slugify pour générer des slugs
- Installer le package avec la commande `composer require cocur/slugify`
- Utiliser le package dans l'entité `src/Entity/Trick.php`
- Ajouter une function `public function prePersist(): void { $this->slug = (new Slugify())->slugify($this->name); }`

## UniqueEntity
- https://symfony.com/doc/current/reference/constraints/UniqueEntity.html
- Ajouter la contrainte `#[UniqueEntity(fields: ['champUnique'], message: 'Votre message.')]` dans une entité comme par exemple `src/Entity/Trick.php`

## Installer VichUploader
- Installer le package avec la commande `composer require vich/uploader-bundle`
- Ajouter la déclaration dans le fichier `config/bundles.php`
- Ajouter la configuration dans le fichier `config/packages/vich_uploader.yaml`

## Utiliser les Fixtures
- Documentation : https://symfony.com/doc/current/bundles/DoctrineFixturesBundle/index.html
- Installer le package avec la commande `composer require --dev orm-fixtures`
- Installer Faker pour générer des données aléatoires avec la commande `composer require --dev fakerphp/faker`
- Créer un fichier `src/DataFixtures/AppFixtures.php`
- Ajouter la déclaration dans le fichier `config/bundles.php`
- Exécutez la commande `php bin/console doctrine:fixtures:load` pour charger les fixtures