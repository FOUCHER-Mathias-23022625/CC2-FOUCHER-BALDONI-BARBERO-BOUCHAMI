# Projet UserProduit - API REST

Ce projet implémente une API REST pour la gestion d'utilisateurs et de produits. L'application est développée en Java avec Jakarta RESTful Web Services (JAX-RS).

## Structure du projet

Le projet est organisé selon une architecture en couches :
- **Modèles** : `User.java`, `Produit.java`
- **Repository** : Interfaces et implémentations pour l'accès aux données
- **Services** : Logique métier
- **Ressources** : Points d'entrée de l'API REST

## Base de données

La connexion à la base de données s'effectue via MariaDB avec les classes `UserRepositoryMariadb` et `ProduitRepositoryMariadb`.

## Points d'accès de l'API

L'API est accessible via le chemin de base : `/api`

### Gestion des utilisateurs

| Méthode | URL | Description |
|---------|-----|-------------|
| GET | `/api/user` | Récupère la liste de tous les utilisateurs |
| GET | `/api/user/{id}` | Récupère les informations d'un utilisateur spécifique |
| PUT | `/api/user/{id}` | Met à jour les informations d'un utilisateur |

### Authentification

| Méthode | URL | Description |
|---------|-----|-------------|
| GET | `/api/user/authenticate` | Authentifie un utilisateur (nécessite un header Basic Auth) |

### Gestion des produits

| Méthode | URL | Description |
|---------|-----|-------------|
| GET | `/api/produit` | Récupère la liste de tous les produits |
| GET | `/api/produit/{id}` | Récupère les informations d'un produit spécifique |
| POST | `/api/produit` | Ajoute un nouveau produit |
| PUT | `/api/produit/{id}` | Met à jour les informations d'un produit |
| DELETE | `/api/produit/{id}` | Supprime un produit |

## Formats de données

### Format d'un utilisateur (User)

```json
{
  "id": "string",
  "name": "string",
  "pwd": "string",
  "mail": "string",
  "gestionnaire": boolean
}
```

### Format d'un produit (Produit)

```json
{
  "id": "string",
  "nom": "string",
  "prix": double,
  "quantite": double,
  "unite": "string"
}
```

## Exemples d'utilisation

### Récupérer tous les produits

```bash
curl -X GET http://votre-serveur/api/produit
```

### Ajouter un produit

```bash
curl -X POST http://votre-serveur/api/produit \
  -H "Content-Type: application/json" \
  -d '{"id":"p001","nom":"Pomme","prix":2.5,"quantite":1.0,"unite":"kg"}'
```

### Authentifier un utilisateur

```bash
curl -X GET http://votre-serveur/api/user/authenticate \
  -H "Authorization: Basic $(echo -n 'username:password' | base64)"
```

## Configuration

La configuration de la base de données se trouve dans la classe `UserApplication.java`. La connexion est établie à l'adresse `jdbc:mariadb://mysql-td3-architecture.alwaysdata.net/td3-architecture_db`.

## Dépendances

- Jakarta RESTful Web Services (JAX-RS)
- Jakarta JSON Binding (JSON-B)
- MariaDB JDBC Driver

