# 🚀 Lebayo - Plateforme de Livraison Moderne

<div align="center">
  <img src="public/images/logo.png" alt="Lebayo Logo" width="120" height="120">
  
  **Votre solution de livraison rapide et fiable**
  
  [![Laravel](https://img.shields.io/badge/Laravel-12.x-red.svg?style=flat-square&logo=laravel)](https://laravel.com)
  [![PHP](https://img.shields.io/badge/PHP-8.3+-777bb4.svg?style=flat-square&logo=php)](https://php.net)
  [![License](https://img.shields.io/badge/License-MIT-green.svg?style=flat-square)](LICENSE)
  [![Vite](https://img.shields.io/badge/Vite-Latest-646CFF.svg?style=flat-square&logo=vite)](https://vitejs.dev)
</div>

---

## 📋 Table des Matières

- [À Propos](#-à-propos)
- [Fonctionnalités](#-fonctionnalités)
- [Technologies](#-technologies-utilisées)
- [Installation](#-installation)
- [Configuration](#-configuration)
- [Utilisation](#-utilisation)
- [Structure du Projet](#-structure-du-projet)
- [API & Routes](#-api--routes)
- [Contribution](#-contribution)
- [License](#-license)
- [Remerciements](#-remerciements)

---

## 🎯 À Propos

**Lebayo** est une plateforme moderne de livraison qui connecte les utilisateurs avec les meilleurs commerces de leur région. Que vous souhaitiez commander des plats de restaurant, faire vos courses en pharmacie, ou découvrir de nouveaux produits locaux, Lebayo vous offre une expérience de livraison rapide et intuitive.

### ✨ Pourquoi Lebayo ?

- 🏪 **Multi-commerces** : Restaurants, pharmacies, supermarchés, boutiques
- 📍 **Géolocalisation intelligente** : Trouvez les commerces près de chez vous
- 🛒 **Panier unifié** : Commandez dans plusieurs commerces en même temps
- ⚡ **Interface moderne** : Design responsive et expérience utilisateur optimale
- 🔐 **Sécurisé** : Authentification robuste et paiements sécurisés

---

## 🚀 Fonctionnalités

### 👤 Utilisateurs
- [x] **Inscription et connexion** sécurisées
- [x] **Profil utilisateur** personnalisable
- [x] **Géolocalisation** automatique (GPS + IP fallback)
- [x] **Gestion des adresses** multiples
- [x] **Historique des commandes**

### 🏪 Commerces
- [x] **Catalogue de commerces** par catégories
- [x] **Pages dédiées** pour chaque commerce
- [x] **Système de notation** et avis
- [x] **Informations détaillées** (horaires, livraison, contact)

### 🛍️ Produits & Commandes
- [x] **Catalogue produits** avec images et descriptions
- [x] **Panier intelligent** avec gestion des quantités
- [x] **Recherche avancée** produits et commerces
- [x] **Filtrage par catégories** et localisation

### 🎛️ Administration
- [x] **Panel d'administration** complet
- [x] **Gestion des commerces** et produits
- [x] **Système de catégories** hiérarchique
- [x] **Analytics et statistiques**

---

## 🛠️ Technologies Utilisées

### Backend
- **Laravel 12.x** - Framework PHP moderne
- **PHP 8.3+** - Langage de programmation
- **MySQL** - Base de données relationnelle
- **Eloquent ORM** - Mapping objet-relationnel

### Frontend
- **Blade Templates** - Moteur de templates Laravel
- **Vite** - Build tool moderne et rapide
- **Vanilla JavaScript** - Interactions dynamiques
- **CSS3** - Styles avancés avec variables CSS

### UI/UX
- **Bootstrap 5.3** - Framework CSS responsive
- **Font Awesome 6.4** - Icônes vectorielles
- **Design System personnalisé** - Cohérence visuelle
- **Responsive Design** - Compatible mobile/desktop

### Outils & Services
- **Composer** - Gestionnaire de dépendances PHP
- **NPM** - Gestionnaire de paquets JavaScript
- **Git** - Contrôle de version
- **OpenStreetMap** - Services de géolocalisation

---

## 🔧 Installation

### Prérequis

Assurez-vous d'avoir installé :
- **PHP 8.3+** avec extensions : `mbstring`, `pdo`, `pdo_mysql`, `tokenizer`, `xml`, `ctype`, `json`, `bcmath`
- **Composer** (gestionnaire de dépendances PHP)
- **Node.js 18+** et **NPM**
- **MySQL 8.0+** ou **MariaDB 10.4+**

### 1. Cloner le Projet

```bash
git clone https://github.com/votre-username/lebayo.git
cd lebayo
```

### 2. Installation des Dépendances

```bash
# Dépendances PHP
composer install

# Dépendances JavaScript
npm install
```

### 3. Configuration de l'Environnement

```bash
# Copier le fichier d'environnement
cp .env.example .env

# Générer la clé d'application
php artisan key:generate
```

### 4. Configuration de la Base de Données

Modifiez le fichier `.env` avec vos informations de base de données :

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=lebayo
DB_USERNAME=votre_username
DB_PASSWORD=votre_password
```

### 5. Migration et Données de Test

```bash
# Créer les tables
php artisan migrate

# Ajouter des données de démonstration (optionnel)
php artisan db:seed
```

### 6. Build des Assets

```bash
# Pour le développement
npm run dev

# Pour la production
npm run build
```

### 7. Lancer l'Application

```bash
# Serveur de développement
php artisan serve

# L'application sera accessible sur http://localhost:8000
```

---

## ⚙️ Configuration

### Services Tiers

#### Géolocalisation
L'application utilise plusieurs services pour la géolocalisation :
- **Navigator.geolocation** (GPS du navigateur)
- **OpenStreetMap Nominatim** (géocodage inverse)
- **Fallback IP** (géolocalisation approximative)

#### Stockage des Fichiers
```env
# Configuration pour les images
FILESYSTEM_DISK=public

# URL publique des assets
ASSET_URL=http://localhost:8000
```

### Variables d'Environnement Importantes

```env
# Application
APP_NAME=Lebayo
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000

# Base de données
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=lebayo

# Cache et Sessions
CACHE_DRIVER=file
SESSION_DRIVER=file
```

---

## 📖 Utilisation

### Interface Utilisateur

1. **Page d'Accueil** : Découverte des commerces et recherche
2. **Inscription/Connexion** : Création de compte sécurisée
3. **Navigation** : Parcours par catégories ou recherche
4. **Commerce** : Page détaillée avec produits et informations
5. **Panier** : Gestion des commandes avec quantités
6. **Profil** : Gestion des informations personnelles

### Panel d'Administration

Accédez au panel d'administration via `/admin` :
- Gestion des commerces et catégories
- Ajout/modification des produits
- Statistiques et analytics
- Gestion des utilisateurs

### API Endpoints Principaux

```bash
# Authentification
POST /login          # Connexion
POST /register       # Inscription
POST /logout         # Déconnexion

# Panier
GET  /cart           # Afficher le panier
POST /cart/add/{id}  # Ajouter un produit
PATCH /cart/update/{id} # Modifier la quantité
DELETE /cart/remove/{id} # Supprimer un produit

# Géolocalisation
GET  /api/location-by-ip     # Localisation par IP
POST /api/reverse-geocode    # Géocodage inverse
```

---

## 📁 Structure du Projet

```
lebayo/
├── 📂 app/
│   ├── Http/Controllers/     # Contrôleurs de l'application
│   │   ├── Admin/           # Contrôleurs d'administration
│   │   ├── CartController.php
│   │   └── LocationController.php
│   ├── Models/              # Modèles Eloquent
│   │   ├── User.php
│   │   ├── Commerce.php
│   │   ├── Product.php
│   │   └── Cart.php
│   └── Providers/          # Fournisseurs de services
│
├── 📂 database/
│   ├── migrations/         # Migrations de base de données
│   └── seeders/           # Données de démonstration
│
├── 📂 public/
│   ├── images/            # Images et assets publics
│   ├── products/          # Images des produits
│   └── commerces/         # Images des commerces
│
├── 📂 resources/
│   ├── css/               # Feuilles de style
│   │   ├── app.css
│   │   └── global.css
│   ├── js/                # Scripts JavaScript
│   └── views/             # Templates Blade
│       ├── layouts/       # Layouts principaux
│       ├── partials/      # Composants réutilisables
│       ├── admin/         # Vues d'administration
│       └── auth/          # Vues d'authentification
│
├── 📂 routes/
│   ├── web.php            # Routes web principales
│   └── console.php        # Commandes Artisan
│
└── 📄 Configuration Files
    ├── .env.example       # Variables d'environnement
    ├── composer.json      # Dépendances PHP
    ├── package.json       # Dépendances JavaScript
    └── vite.config.js     # Configuration Vite
```

---

## 🛣️ API & Routes

### Routes Web Principales

| Méthode | Route | Description |
|---------|-------|-------------|
| `GET` | `/` | Page d'accueil |
| `GET` | `/restaurant/{id}` | Page d'un commerce |
| `GET` | `/category/{slug}` | Commerces par catégorie |
| `GET` | `/search` | Recherche globale |
| `GET` | `/cart` | Panier utilisateur |

### Routes d'Administration

| Méthode | Route | Description |
|---------|-------|-------------|
| `GET` | `/admin` | Dashboard admin |
| `GET` | `/admin/commerces` | Gestion des commerces |
| `GET` | `/admin/products` | Gestion des produits |
| `GET` | `/admin/categories` | Gestion des catégories |

### Routes d'Authentification

| Méthode | Route | Description |
|---------|-------|-------------|
| `GET` | `/login` | Page de connexion |
| `POST` | `/login` | Traitement connexion |
| `GET` | `/register` | Page d'inscription |
| `POST` | `/register` | Traitement inscription |
| `POST` | `/logout` | Déconnexion |

---

## 🤝 Contribution

Nous accueillons les contributions ! Voici comment participer :

### 1. Fork du Projet
```bash
git clone https://github.com/votre-username/lebayo.git
```

### 2. Créer une Branche
```bash
git checkout -b feature/ma-nouvelle-fonctionnalite
```

### 3. Commit des Changements
```bash
git commit -m "✨ Ajout d'une nouvelle fonctionnalité"
```

### 4. Push et Pull Request
```bash
git push origin feature/ma-nouvelle-fonctionnalite
```

### Guidelines de Contribution

- 📝 **Documentation** : Documentez vos changements
- 🧪 **Tests** : Ajoutez des tests pour les nouvelles fonctionnalités
- 🎨 **Style** : Respectez les conventions de code existantes
- 🔄 **Pull Requests** : Descriptions claires et concises

### Types de Contributions

- 🐛 **Bug fixes** : Corrections de bugs
- ✨ **Features** : Nouvelles fonctionnalités
- 📚 **Documentation** : Améliorations de la documentation
- 🎨 **UI/UX** : Améliorations de l'interface
- ⚡ **Performance** : Optimisations

---

## 📋 Roadmap

### Version 2.0 (À venir)
- [ ] 💳 **Paiement en ligne** intégré
- [ ] 📱 **Application mobile** (React Native)
- [ ] 🔔 **Notifications push** en temps réel
- [ ] 🚚 **Suivi de livraison** GPS
- [ ] ⭐ **Système d'avis** et notes
- [ ] 🎯 **Programme de fidélité**

### Version 1.5 (En cours)
- [x] 🛒 **Panier intelligent** multi-commerces
- [x] 📍 **Géolocalisation** améliorée
- [ ] 🔍 **Recherche avancée** avec filtres
- [ ] 📊 **Analytics** détaillées pour les commerces

---

## 📄 License

Ce projet est sous licence MIT. Voir le fichier [LICENSE](LICENSE) pour plus de détails.

---

## 🙏 Remerciements

### Équipe de Développement
- **Design & Développement** : Créé avec ❤️ par l'équipe Lebayo
- **Design System** : Conçu par [Thuppers Inc](https://thuppers.com)

### Technologies & Communautés
- [Laravel](https://laravel.com) - Framework PHP exceptionnel
- [Vite](https://vitejs.dev) - Build tool moderne et rapide
- [Bootstrap](https://getbootstrap.com) - Framework CSS responsive
- [Font Awesome](https://fontawesome.com) - Icônes vectorielles
- [OpenStreetMap](https://www.openstreetmap.org) - Données cartographiques libres

### Inspiration
Merci à toutes les plateformes de livraison qui nous inspirent à créer une meilleure expérience utilisateur.

---

<div align="center">
  
  **⭐ N'oubliez pas de donner une étoile si ce projet vous plaît ! ⭐**
  
  [🌐 Site Web](https://lebayo.com) • [📧 Contact](mailto:contact@lebayo.com) • [🐦 Twitter](https://twitter.com/lebayo)
  
  ---
  
  *Développé avec 💖 en utilisant Laravel et des technologies modernes*
  
</div>
