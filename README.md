# espererTest
Prérequis: 
  - Php 7.4
  - composer v2.0 ou plus
  - Symfony cli(https://symfony.com/download)
  - configurer le fichier .env pour l'accès à la base de donnée
Pour lancer l'application, dans un terminal à la racine du projet:
  - composer update
  - composer install
  - php bin/console doctrine:database:create
  - php bin/console make:migration
  - php bin/console doctrine:migrations:migrate
  - symfony serve:start
  
  sur un navigateur, aller à l'adresse "http://127.0.0.1:8000"
