# Backend Symfony

Ce backend Symfony fournit l'API REST pour l'application e-commerce. Il gère les opérations CRUD (Create, Read, Update, Delete) sur les entités suivantes :

* **Produits**
* **Catégories**
* **Commandes**
* **User**
* **Order**
* **OrderDetail**
* **Panier**
* **ProductMaterials**
* **Message**
* **ProductPhoto**
* **Designer**




## Prérequis

* PHP 8.1 ou supérieur
* Symfony 6.x
* MySQL

## Installation

1. **Cloner le dépôt** : `git clone `
2. **Installer les dépendances** : `composer install`
3. **Configurer la base de données** :
    * Créez une base de données MySQL.
    * Copiez le fichier `.env` en `.env.local` et renseignez les informations de connexion à votre base de données.
4. **Créer la structure de la base de données** : dans phpMyAdmin creez la base de données sous le nom de "e-airneis"
5. **Charger les données ** : importez la base de données fournie dans le repository
6. **Démarrer le serveur** : `symfony server:start`

L'API devrait maintenant être accessible à l'adresse [http://localhost:8000](http://localhost:8000).

## Documentation de l'API

La documentation détaillée de l'API, incluant les endpoints, les méthodes HTTP, les paramètres et les exemples de requêtes/réponses, est disponible aux formats suivants :

* **OpenAPI/Swagger** : [http://localhost:8000/api/doc](http://localhost:8000/api/doc) (ou une autre URL si vous l'avez configurée différemment)


## Tests

Pour exécuter les tests unitaires et fonctionnels, utilisez la commande : `php bin/phpunit`

## Structure du projet

* **src/Controller/** : Contient les contrôleurs de l'API.
* **src/Entity/** : Définit les entités Doctrine (modèles de données).
* **src/Repository/** : Contient les dépôts Doctrine pour interagir avec la base de données.

## Contributeurs

* Tarik OUKACI


