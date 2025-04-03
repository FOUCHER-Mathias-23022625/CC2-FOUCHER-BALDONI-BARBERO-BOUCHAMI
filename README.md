# API Commande

## Présentation

L'API Commande est conçue pour gérer les commandes et leur contenu. Elle interagit avec l'API Panier pour récupérer et gérer les détails des paniers.

## Points d'entrée de l'API

### Commandes

- **Récupérer toutes les commandes**
  ```bash
  GET /apiCommandes-1.0-SNAPSHOT/api/commandes
  ```

- **Récupérer une commande spécifique**
  ```bash
  GET /apiCommandes-1.0-SNAPSHOT/api/commandes/{id}
  ```

- **Créer une nouvelle commande**
  ```bash
  POST /apiCommandes-1.0-SNAPSHOT/api/commandes
  Content-Type: application/json
  Body: {
    "id": 101,
    "localisationRetrait": "Marseille",
    "dateRetrait": "2024-06-01"
  }
  ```

- **Mettre à jour une commande**
  ```bash
  PUT /apiCommandes-1.0-SNAPSHOT/api/commandes/{id}
  Content-Type: application/json
  Body: {
    "localisationRetrait": "Aix-en-Provence",
    "dateRetrait": "2024-06-15"
  }
  ```

- **Supprimer une commande**
  ```bash
  DELETE /apiCommandes-1.0-SNAPSHOT/api/commandes/{id}
  ```

- **Récupérer le contenu d'une commande**
  ```bash
  GET /apiCommandes-1.0-SNAPSHOT/api/commandes/{id}/contenu
  ```

- **Ajouter un panier à une commande**
  ```bash
  POST /apiCommandes-1.0-SNAPSHOT/api/commandes/{id}/contenu
  Content-Type: application/json
  Body: {
    "idPanier": 25,
    "quantite": 2
  }
  ```

- **Mettre à jour la quantité d'un panier dans une commande**
  ```bash
  PUT /apiCommandes-1.0-SNAPSHOT/api/commandes/{id}/contenu/{idPanier}
  Content-Type: application/json
  Body: {
    "quantite": 3
  }
  ```

- **Supprimer un panier d'une commande**
  ```bash
  DELETE /apiCommandes-1.0-SNAPSHOT/api/commandes/{id}/contenu/{idPanier}
  ```

- **Obtenir le prix total d'une commande**
  ```bash
  GET /apiCommandes-1.0-SNAPSHOT/api/commandes/{id}/total
  ```

## Liens vers l'API Panier

L'API Panier est utilisée pour gérer les paniers et leur contenu. L'URL de base de l'API Panier est :

```
http://localhost:7080/paniers-1.0-SNAPSHOT/api/paniers
```

Si vous devez modifier l'URL de l'API Panier, mettez à jour la constante `PANIER_API_URL` dans la classe `CommandeApplication` :

```java
private static final String PANIER_API_URL = "http://localhost:7080/paniers-1.0-SNAPSHOT/api/paniers";
```

## Informations importantes

le prix est calculé automatiquement en fonction des produits contenus dans la commande

- Assurez-vous que l'API Panier est en cours d'exécution et accessible à l'URL spécifiée.
- La classe `CommandeRepositoryMariadb` nécessite une connexion valide à MariaDB. Mettez à jour les paramètres de connexion dans la méthode `createCommandeRepository` de la classe `CommandeApplication`.
- La classe `PanierClient` est responsable de l'interaction avec l'API Panier en utilisant l'URL définie dans `PANIER_API_URL`.


PS: J'ai écrit ce fichier avec l'IA pour gagner du temps.
