# Prérequis

- php >= 8.2
- composer
- symfony

# Installation 

- git clone https://github.com/LuDetling/inazoui.git
- cd inazoui
- cp .env .env.local
- mettre les bonnes données de ta bdd
- composer install
- php bin/console doctrine:database:create
- php bin/console doctrine:migrations:migrate
- php bin/console doctrine:fixtures:load

# Branches

- Afin de travailler sur un nouveau package, ou un correctif d'un package existant, il est nécessaire de créer une nouvelle branche à partir de la branche dev

```shell
git checkout -b prefixe/ma-branche dev
```

- Pour les commits mettez la description en français ou en anglais avec en prefix:
    - fix pour réparer
    - feat pour une nouvelle fonctionnalité
    - docs pour de la docs

- Exemple : feat - ajout de moderateurs

# Bonnes pratiques

- Utiliser au moins profiler de symfony pour voir la vitesse du site de votre implémentation
- Faire des tests avant de commit