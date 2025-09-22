<!-- Menu -->
<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
  <div class="app-brand demo">
    <a href="{{ route('admin.dashboard') }}" class="app-brand-link">
      <span class="app-brand-logo demo">
        <img src="{{ asset('admin-assets/assets/img/logo.png') }}" alt="Lebayo Logo" style="height: 40px; width: auto;">
      </span>
      {{-- <span class="app-brand-text demo menu-text fw-bolder ms-2">Lebayo</span> --}}
    </a>

    <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-block d-xl-none">
      <i class="bx bx-chevron-left bx-sm align-middle"></i>
    </a>
  </div>

  <div class="menu-inner-shadow"></div>

  <ul class="menu-inner py-1">
    <!-- Tableau de bord -->
    <li class="menu-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
      <a href="{{ route('admin.dashboard') }}" class="menu-link">
        <i class="menu-icon tf-icons bx bx-tachometer"></i>
        <div data-i18n="Dashboard">Tableau de bord</div>
      </a>
    </li>

    <!-- GESTION PRINCIPALE -->
    <li class="menu-header small text-uppercase">
      <span class="menu-header-text">Gestion Principale</span>
    </li>

    @auth
      @if(auth()->user()->isAdmin())
        <!-- Commerces (Admin complet seulement) -->
        <li class="menu-item {{ request()->routeIs('admin.commerces.*') ? 'active' : '' }}">
          <a href="{{ route('admin.commerces.index') }}" class="menu-link">
            <i class="menu-icon tf-icons bx bx-buildings"></i>
            <div data-i18n="Commerces">Commerces</div>
          </a>
        </li>
      @endif

      @if(auth()->user()->canModerate())
        <!-- Produits (Modérateurs et Admins) -->
        <li class="menu-item {{ request()->routeIs('admin.products.*') ? 'active' : '' }}">
          <a href="{{ route('admin.products.index') }}" class="menu-link">
            <i class="menu-icon tf-icons bx bx-package"></i>
            <div data-i18n="Products">Produits</div>
          </a>
        </li>

        <!-- Commandes (Modérateurs et Admins) -->
        <li class="menu-item {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">
          <a href="{{ route('admin.orders.index') }}" class="menu-link">
            <i class="menu-icon tf-icons bx bx-shopping-bag"></i>
            <div data-i18n="Orders">Commandes</div>
          </a>
        </li>

        <!-- Demandes de course (Modérateurs et Admins) -->
        <li class="menu-item {{ request()->routeIs('admin.errand-requests.*') ? 'active' : '' }}">
          <a href="{{ route('admin.errand-requests.index') }}" class="menu-link">
            <i class="menu-icon tf-icons bx bx-task"></i>
            <div data-i18n="Errand Requests">Demandes de course</div>
          </a>
        </li>

        <!-- Clients (Modérateurs et Admins) -->
        <li class="menu-item {{ request()->routeIs('admin.clients.*') ? 'active' : '' }}">
          <a href="{{ route('admin.clients.index') }}" class="menu-link">
            <i class="menu-icon tf-icons bx bx-user"></i>
            <div data-i18n="Clients">Clients</div>
          </a>
        </li>
      @endif

      @if(auth()->user()->isAdmin())
        <!-- Livreurs (Admin complet seulement) -->
        <li class="menu-item {{ request()->routeIs('admin.livreurs.*') ? 'active' : '' }}">
          <a href="{{ route('admin.livreurs.index') }}" class="menu-link">
            <i class="menu-icon tf-icons bx bx-cycling"></i>
            <div data-i18n="Delivery">Livreurs</div>
          </a>
        </li>

        <!-- Utilisateurs (Admin complet seulement) -->
        <li class="menu-item {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
          <a href="{{ route('admin.users.index') }}" class="menu-link">
            <i class="menu-icon tf-icons bx bx-group"></i>
            <div data-i18n="Users">Utilisateurs</div>
          </a>
        </li>
      @endif
    @endauth

    @auth
      @if(auth()->user()->isAdmin())
        <!-- PARAMÉTRAGE (Admin complet seulement) -->
        <li class="menu-header small text-uppercase">
          <span class="menu-header-text">Paramétrage</span>
        </li>

        <!-- Types de Commerce -->
        <li class="menu-item {{ request()->routeIs('admin.commerce-types.*') ? 'active' : '' }}">
          <a href="{{ route('admin.commerce-types.index') }}" class="menu-link">
            <i class="menu-icon tf-icons bx bx-store"></i>
            <div data-i18n="Commerce Types">Types de Commerce</div>
          </a>
        </li>

        <!-- Catégories -->
        <li class="menu-item {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
          <a href="{{ route('admin.categories.index') }}" class="menu-link">
            <i class="menu-icon tf-icons bx bx-category"></i>
            <div data-i18n="Categories">Catégories</div>
          </a>
        </li>

        <!-- Paramètres de livraison -->
        <li class="menu-item {{ request()->routeIs('admin.delivery-settings.*') ? 'active' : '' }}">
          <a href="{{ route('admin.delivery-settings.index') }}" class="menu-link">
            <i class="menu-icon tf-icons bx bx-cog"></i>
            <div data-i18n="Delivery Settings">Paramètres de livraison</div>
          </a>
        </li>
      @endif
    @endauth



    <!-- Utilisateurs -->
    {{-- <li class="menu-item">
      <a href="javascript:void(0);" class="menu-link menu-toggle">
        <i class="menu-icon tf-icons bx bx-group"></i>
        <div data-i18n="Users">Utilisateurs</div>
      </a>
      <ul class="menu-sub">
        <li class="menu-item">
          <a href="#" class="menu-link">
            <div data-i18n="All Users">Tous les utilisateurs</div>
          </a>
        </li>
        <li class="menu-item">
          <a href="#" class="menu-link">
            <div data-i18n="Add User">Ajouter un utilisateur</div>
          </a>
        </li>
        <li class="menu-item">
          <a href="#" class="menu-link">
            <div data-i18n="User Roles">Rôles utilisateurs</div>
          </a>
        </li>
      </ul>
    </li> --}}

    <!-- OUTILS DE DÉVELOPPEMENT -->
    {{-- <li class="menu-header small text-uppercase">
      <span class="menu-header-text">Outils</span>
    </li>

    <!-- Page Vide -->
    <li class="menu-item {{ request()->routeIs('admin.blank') ? 'active' : '' }}">
      <a href="{{ route('admin.blank') }}" class="menu-link">
        <i class="menu-icon tf-icons bx bx-file"></i>
        <div data-i18n="Blank">Page Vide</div>
      </a>
    </li>

    <!-- Démonstration Thème -->
    <li class="menu-item {{ request()->routeIs('admin.theme.demo') ? 'active' : '' }}">
      <a href="{{ route('admin.theme.demo') }}" class="menu-link">
        <i class="menu-icon tf-icons bx bx-palette"></i>
        <div data-i18n="ThemeDemo">Palette Moderne</div>
      </a>
    </li> --}}
  </ul>
</aside>
<!-- / Menu -->
