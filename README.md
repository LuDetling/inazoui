# Ina Zaoui

Pour se connecter avec le compte de Ina, il faut utiliser les identifiants suivants:
- identifiant : `ina`
- mot de passe : `password`

Vous trouverez dans le fichier `backup.zip` un dump SQL anonymisé de la base de données et toutes les images qui se trouvaient dans le dossier `public/uploads`.
Faudrait peut être trouver une meilleure solution car le fichier est très gros, il fait plus de 1Go.

# Prérequis

- PHP **>= 8.2.0**
- [Composer](https://getcomposer.org/) **>= 2.5**
- [Symfony CLI](https://symfony.com/download) (optionnel mais recommandé)
- Serveur de base de données (ex : **PostgreSQL**, **MySQL**, **SQLite**)

# Installation

- cloner le projet
- copier le fichier .env en .env.local et modifier la base de donnée

- symfony console doctrine:database:drop -f --if-exists --force
- symfony console doctrine:database:create
- symfony console doctrine:migration:migrate
- symfony console doctrine:fixtures:load
- psql -U postgres -d ina_zaoui -f .\public\media.sql

# Test

- symfony console doctrine:database:drop -f --if-exists --env=test
- symfony console doctrine:database:create --env=test
- symfony console doctrine:migrations:migrate -n --env=test
- symfony console doctrine:fixtures:load -n --purge-with-truncate --env=test