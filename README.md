# Backend Symfony

Ce backend Symfony fournit l'API pour l'application e-commerce.

## Démarrage

1.  Assurez-vous d'être dans le dossier `server`.
2.  Démarrez le serveur Symfony : `symfony server:start`

L'API devrait maintenant être accessible à l'adresse [http://localhost:8000](http://localhost:8000).

## Base de données

*   **Migrations** : Pour créer ou mettre à jour la structure de la base de données, utilisez les commandes `php bin/console doctrine:migrations:migrate`.
*   **Seed** : Pour charger des données initiales dans la base de données, utilisez la commande `php bin/console doctrine:fixtures:load`.

## Tests

Pour exécuter les tests unitaires et fonctionnels, utilisez la commande : `php bin/phpunit`
