# UniFolio

Un outil de création de portfolio à destination de l'IUT de Troyes.

## Installation

### Clonage du projet

    git clone https://github.com/CyndelHerolt/UniFolio.git
    cd UniFolio
    composer install ou composer update

### Mise à jours des infos

    cp .env .env.local

Mettre à jour le fichier .env.local avec vos informations

### Créer la database et importer les données

    bin/console doctrine:database:create

    // version courte
    bin/console d:d:c

Mettre à jour la database avec les données

    bin/console doctrine:schema:update --force

    // version courte
    bin/console d:s:u -f

Installer les fixtures et avoir un jeu de données fictives

    bin/console doctrine:fixtures:load

    bin/console d:f:l

### Installation des dépendances front

    npm install --force

ou

    yarn install --force

### Compilation des ressources front

    npm run encore dev --watch

ou

    yarn encore dev --watch

### Démonstration

Normalement, le projet est maintenant installé et fonctionnel. Vous pouvez accéder à la page d'accueil en tapant l'url suivante dans votre navigateur : http://localhost:8000/.

#### Comptes utilisateurs
Pour vous connecter (/login), utilisez l'un des login ci-dessous, et le mot de passe « test »

- admin
- enseignantTest
- etudiantTest

## Licence

[MPL-2.0](https://choosealicense.com/licenses/mpl-2.0/)