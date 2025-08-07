# PICARD
## Fonctionnalités

-   **Catalogue d'articles** : Consultation, recherche et évaluation des produits gourmets
-   **Système de panier** : Gestion complète du panier avec ajout, modification et suppression
-   **API RESTful** : Documentation interactive et endpoints standardisés
-   **Persistance des données** : Base de données MySQL avec architecture relationnelle optimisée
-   **Jeu de données réaliste** : Génération automatique avec la bibliothèque Faker

---

## Installation

### Configuration Requise

-   **PHP** 8.1 ou supérieur
-   **Composer** pour la gestion des dépendances
-   **MySQL/MariaDB** comme système de base de données
-   **Symfony CLI** (recommandé pour le développement)

### Procédure de Déploiement

1. **Récupération du Code Source**

    ```bash
    git clone https://github.com/26jeff/b3-rattrapages-knafo-jeff.git
    cd b3-rattrapages-knafo-jeff
    cd 11-architecture-des-donnees-creations-d-apis
    ```

2. **Installation des Dépendances**

    ```bash
    composer install
    ```

3. **Configuration de la Base de Données**

    ```env
    DATABASE_URL="mysql://root:root@127.0.0.1:3306/picard?serverVersion=8.0"
    ```

4. **Initialisation de la Base de Données**

    ```bash
    php bin/console doctrine:database:create
    ```

5. **Application des Migrations**

    ```bash
    php bin/console doctrine:migrations:migrate
    ```

6. **Injection des Données de Test**

    ```bash
    php bin/console doctrine:fixtures:load
    ```

7. **Démarrage du Serveur de Développement**
    ```bash
    symfony server:start
    ```

---

## Documentation

Interface d'accès principale : `http://localhost:8000/api`

### Endpoints

#### **Gestion des Articles**

-   `GET /api/articles` - Récupération de la liste complète des articles
-   `GET /api/articles/{id}` - Consultation du détail d'un article spécifique
-   `POST /api/articles/{id}/rate` - Attribution d'une note à un article

#### **Gestion des Paniers**

-   `GET /api/baskets` - Liste de tous les paniers
-   `GET /api/baskets/{id}` - Détails d'un panier particulier
-   `POST /api/baskets` - Création d'un nouveau panier
-   `POST /api/basket/{id}/add-article` - Ajout d'un article dans le panier
-   `DELETE /api/basket/{id}/remove-article/{articleId}` - Retrait d'un article du panier
-   `POST /api/basket/{id}/validate` - Validation et finalisation du panier

#### **Gestion des Entrées de Panier**

-   `GET /api/basket_entries` - Vue d'ensemble des entrées de tous les paniers
-   `GET /api/basket_entries/{id}` - Détail d'une entrée spécifique
-   `POST /api/basket_entries` - Création manuelle d'une entrée
-   `PUT /api/basket_entries/{id}` - Modification d'une entrée existante
-   `DELETE /api/basket_entries/{id}` - Suppression d'une entrée


## Vidéo

**[VIDÉO](https://youtu.be/1nmoR-PrMHQ?si=3Kz0gZZr0YfC42oB)**


