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

## Utiliser Doctrine 
- Créer une base de données avec la commande `php bin/console doctrine:database:create` ou `php bin/console d:d:c`
- Créer une entité avec la commande `php bin/console make:entity`
 