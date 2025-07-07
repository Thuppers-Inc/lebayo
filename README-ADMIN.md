# 🏗️ Panel d'Administration Lebayo

## 📖 Documentation Complète

La documentation complète de la structure modulaire du panel d'administration est disponible dans : 
**[docs/admin-panel-structure.md](docs/admin-panel-structure.md)**

## 🚀 Accès Rapide

- **URL Admin** : `/admin`
- **Layout Master** : `resources/views/admin/layouts/master.blade.php`
- **Assets** : `public/admin-assets/`

## 📁 Structure Modulaire

```
resources/views/admin/
├── layouts/master.blade.php      # Layout principal
├── partials/
│   ├── sidebar.blade.php         # Menu latéral
│   ├── topbar.blade.php          # Barre supérieure
│   └── footer.blade.php          # Pied de page
└── dashboard/index.blade.php     # Dashboard
```

## ⚡ Démarrage Rapide

### Créer une nouvelle page admin

```blade
@extends('admin.layouts.master')

@section('title', 'Ma Page')

@section('content')
    <!-- Votre contenu -->
@endsection
```

### Ajouter la route

```php
Route::get('/admin/ma-page', function () {
    return view('admin.ma-page.index');
})->name('admin.ma-page');
```

## 🛠️ Composants Disponibles

| Composant | Fichier | Description |
|-----------|---------|-------------|
| **Layout Master** | `layouts/master.blade.php` | Structure HTML principale |
| **Sidebar** | `partials/sidebar.blade.php` | Menu de navigation |
| **Topbar** | `partials/topbar.blade.php` | Barre supérieure + user menu |
| **Footer** | `partials/footer.blade.php` | Pied de page |

## 📋 Sections Blade Disponibles

- `@section('title')` - Titre de la page
- `@section('content')` - Contenu principal  
- `@push('styles')` - CSS personnalisés
- `@push('scripts')` - JavaScript personnalisé

## 🎯 Fonctionnalités

✅ **Structure modulaire flexible**  
✅ **Menu de navigation adaptatif**  
✅ **Système de notifications**  
✅ **Graphiques ApexCharts intégrés**  
✅ **Interface responsive**  
✅ **Authentification intégrée**  
✅ **Assets optimisés**  

## 📊 Dashboard Inclus

Le dashboard comprend :
- Widgets de statistiques
- Graphiques interactifs (revenus, croissance, etc.)
- Transactions récentes
- Statistiques des commandes
- Interface entièrement en français

## 🔗 Liens Utiles

- [Documentation complète](docs/admin-panel-structure.md)
- [Laravel Blade Docs](https://laravel.com/docs/blade)
- [Bootstrap 5](https://getbootstrap.com/docs/5.0/)
- [ApexCharts](https://apexcharts.com/docs/)

---

**Développé pour Lebayo** 🚀 