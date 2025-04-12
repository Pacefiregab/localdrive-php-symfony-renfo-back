# 🚗 LocaDrive — API Symfony pour la location de véhicules

LocaDrive est une API REST développée avec **Symfony** permettant de gérer une plateforme de **location de véhicules**.  
Elle inclut l'authentification (client/admin), la gestion du catalogue, la création de réservations avec assurance et paiement.

---

## 📦 Fonctionnalités principales

- 🔐 Authentification par JWT (admin & client)
- 👤 Création de compte client
- 🚘 Gestion des véhicules (admin)
- 🛒 Création et modification de réservations (client)
- ✅ Paiement d’une réservation avec ou sans assurance
- 📄 Architecture modulaire (screaming architecture)

---

## 🧱 Stack technique

- Symfony 6.x
- Doctrine ORM + PostgreSQL
- Docker & Docker Compose
- JWT via LexikJWTAuthenticationBundle
- Architecture orientée domaine (ports/use cases/entities)
- PlantUML pour modélisation

---

## 🚀 Installation

### 1. Cloner le projet

```bash
git clone https://github.com/ton-pseudo/locadrive-api.git
cd locadrive-api
```

### 2. Lancer l'environnement avec Docker

```bash
docker compose up -d --build
```

> Par défaut :
> - API accessible sur `http://localhost:8000`
> - PostgreSQL sur port `5432`

### 3. Installer les dépendances PHP

```bash
docker exec -it locadrive_php composer install
```

### 4. Générer les clés JWT

```bash
docker exec -it locadrive_php php bin/console lexik:jwt:generate-keypair
```

### 5. Exécuter les migrations

```bash
docker exec -it locadrive_php php bin/console doctrine:migrations:migrate
```

---

## 🔑 Authentification

- Créer un compte client : `POST /api/register`
- Se connecter (client/admin) : `POST /api/login`
  - Reçoit un token JWT
- Ajouter le token aux requêtes suivantes :
  ```http
  Authorization: Bearer <TOKEN>
  ```

---

## 📚 Routes principales

| Méthode | URL                                         | Description                           | Rôle requis     |
| ------- | ------------------------------------------- | ------------------------------------- | --------------- |
| POST    | `/api/register`                             | Créer un compte client                | Public          |
| POST    | `/api/login`                                | Connexion (JWT)                       | Public          |
| GET     | `/api/vehicules`                            | Lister les véhicules                  | Client/Admin    |
| POST    | `/api/vehicules`                            | Ajouter un véhicule                   | Admin           |
| PUT     | `/api/vehicules/{id}`                       | Modifier un véhicule                  | Admin           |
| DELETE  | `/api/vehicules/{id}`                       | Supprimer un véhicule                 | Admin           |
| POST    | `/api/reservations`                         | Créer une réservation                 | Client connecté |
| POST    | `/api/reservations/{id}/vehicules`          | Ajouter un véhicule à une réservation | Client          |
| DELETE  | `/api/reservations/{id}/vehicules/{itemId}` | Supprimer un véhicule                 | Client          |
| POST    | `/api/reservations/{id}/assurance`          | Ajouter une assurance                 | Client          |
| DELETE  | `/api/reservations/{id}/assurance`          | Retirer l’assurance                   | Client          |
| POST    | `/api/reservations/{id}/payer`              | Payer la réservation                  | Client          |
| GET     | `/api/reservations`                         | Lister mes réservations               | Client          |
| GET     | `/api/reservations/{id}`                    | Détails d’une réservation             | Client          |

---

## 🧠 Architecture

- `src/Entity` : entités métier (User, Vehicule, Reservation…)
- `src/Controller` : API REST
- `src/UseCase` : logique applicative (création, ajout, suppression…)
- `src/Entity/[Nom]/Port` : interfaces (ports) injectées dans les use cases
- Doctrine + migrations pour la persistance

---

## 🧑‍💻 Contributeurs

- Gabin Charasson (développeur principal)

---

## 📄 Licence

Projet libre pour usage éducatif ou personnel.