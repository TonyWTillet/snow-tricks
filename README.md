[![Codacy Badge](https://app.codacy.com/project/badge/Grade/b2e151dc2c704172921d41d5faab1f3d)](https://app.codacy.com/gh/TonyWTillet/snow-tricks/dashboard?utm_source=gh&utm_medium=referral&utm_content=&utm_campaign=Badge_grade)

# CONTEXTE
Projet 6 de mon parcours Développeur d'application PHP/Symfony chez OpenClassrooms.
Création d'un Porfolio via une architecture MVC Orienté objet.

## Project summary
Jimmy Sweat est un entrepreneur ambitieux passionné de snowboard. Son objectif est la création d'un site collaboratif pour faire connaître ce sport auprès du grand public et aider à l'apprentissage des figures (tricks).
Il souhaite capitaliser sur du contenu apporté par les internautes afin de développer un contenu riche et suscitant l’intérêt des utilisateurs du site. Par la suite, Jimmy souhaite développer un business de mise en relation avec les marques de snowboard grâce au trafic que le contenu aura généré.
Pour ce projet, nous allons nous concentrer sur la création technique du site pour Jimmy.

## Project needs
Vous êtes chargé de développer le site répondant aux besoins de Jimmy. Vous devez ainsi implémenter les fonctionnalités suivantes : 

- un annuaire des figures de snowboard. Vous pouvez vous inspirer de la liste des figures sur Wikipédia. Contentez-vous d'intégrer 10 figures, le reste sera saisi par les internautes ;
- la gestion des figures (création, modification, consultation) ;
- un espace de discussion commun à toutes les figures.

Pour implémenter ces fonctionnalités, vous devez créer les pages suivantes :
- la page d’accueil où figurera la liste des figures ; 
- la page de création d'une nouvelle figure ;
- la page de modification d'une figure ;
- la page de présentation d’une figure (contenant l’espace de discussion commun autour d’une figure).

## Deliverables
Un lien vers l’ensemble du projet (fichiers PHP/HTML/JS/CSS…) sur un repository GitHub.
L’ensemble des diagrammes demandés (modèles de données, classes, use cases, séquentiels).
Les issues sur le repository GitHub.
Les instructions pour installer le projet (dans un fichier README à la racine du projet).
Jeu de données initiales avec l’ensemble des figures de snowboard.
Lien vers les analyses SensioLabsInsight, Codacy ou Codeclimate (via une médaille dans le README, par exemple).

# HOW INSTALL THIS PROJECT 

## Template
- Demo : https://colorlib.com/wp/template/trips/

## Required and technical environment
> Language => PHP 8.3.*

> Database => MySQL 5.7.25

> Web Server 

> Symfony 

> Composer 

> NodeJS 

> Make


## Step 1: clone the projet
    git clone https://github.com/TonyWTillet/snow-tricks.git

## Step 2: install composer
https://getcomposer.org/download/

## Step 3: install Makefile
    https://gnuwin32.sourceforge.net/packages/make.html

## Step 4: config .env

## Step 5: install dependencies
    make init

## Step 6: create DB
    php bin/console d:d:c

## Step 7: install database
    make database-init

## Step 8: start server
    symfony server:start

## Step 9: default user
<table>
    <thead>
        <tr>
            <th>pseudo</th>
            <th align="center">password</th>
            <th align="right">role</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>tony.tillet@gmail.com</td>
            <td align="center">admin</td>
            <td align="right">ROLE_ADMIN</td>
        </tr>
        <tr>
            <td>user@gmail.com</td>
            <td align="center">user</td>
            <td align="right">ROLE_USER</td>
        </tr>
    </tbody>
</table>