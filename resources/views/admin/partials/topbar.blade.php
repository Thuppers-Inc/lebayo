<!-- Navbar -->
<nav
  class="layout-navbar container-xxl navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme"
  id="layout-navbar"
>
  <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-xl-none">
    <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
      <i class="bx bx-menu bx-sm"></i>
    </a>
  </div>

  <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">
    <!-- Search -->
    <div class="navbar-nav align-items-center">
      <div class="nav-item d-flex align-items-center">
        <i class="bx bx-search fs-4 lh-0"></i>
        <input
          type="text"
          class="form-control border-0 shadow-none"
          placeholder="Rechercher..."
          aria-label="Search..."
        />
      </div>
    </div>
    <!-- /Search -->

    <ul class="navbar-nav flex-row align-items-center ms-auto">
      <!-- Notifications -->
      <li class="nav-item navbar-dropdown dropdown-user dropdown me-3">
        <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown">
          <div class="position-relative">
            <i class="bx bx-bell bx-sm"></i>
            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
              3
              <span class="visually-hidden">notifications non lues</span>
            </span>
          </div>
        </a>
        <ul class="dropdown-menu dropdown-menu-end">
          <li>
            <h6 class="dropdown-header">Notifications</h6>
          </li>
          <li>
            <div class="dropdown-divider"></div>
          </li>
          <li>
            <a class="dropdown-item" href="#">
              <div class="d-flex">
                <div class="flex-shrink-0 me-3">
                  <div class="avatar avatar-sm">
                    <span class="avatar-initial rounded-circle bg-label-primary">
                      <i class="bx bx-user"></i>
                    </span>
                  </div>
                </div>
                <div class="flex-grow-1">
                  <span class="fw-semibold d-block">Nouvel utilisateur inscrit</span>
                  <small class="text-muted">Il y a 5 minutes</small>
                </div>
              </div>
            </a>
          </li>
          <li>
            <a class="dropdown-item" href="#">
              <div class="d-flex">
                <div class="flex-shrink-0 me-3">
                  <div class="avatar avatar-sm">
                    <span class="avatar-initial rounded-circle bg-label-success">
                      <i class="bx bx-shopping-bag"></i>
                    </span>
                  </div>
                </div>
                <div class="flex-grow-1">
                  <span class="fw-semibold d-block">Nouvelle commande</span>
                  <small class="text-muted">Il y a 15 minutes</small>
                </div>
              </div>
            </a>
          </li>
          <li>
            <div class="dropdown-divider"></div>
          </li>
          <li>
            <a class="dropdown-item text-center" href="#">
              Voir toutes les notifications
            </a>
          </li>
        </ul>
      </li>

      <!-- User -->
      @auth
      @php
        $user = auth()->user();
        $userPhoto = $user ? ($user->photo_url ?? asset('images/default-avatar.png')) : asset('images/default-avatar.png');
        $userName = $user ? ($user->full_name ?? 'Utilisateur') : 'Utilisateur';
        $userRole = 'Client';
        if ($user) {
            try {
                $userRole = $user->isAdmin() ? 'Administrateur' : ucfirst($user->account_type->value ?? 'client');
            } catch (\Exception $e) {
                $userRole = 'Client';
            }
        }
      @endphp
      <li class="nav-item navbar-dropdown dropdown-user dropdown">
        <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown">
          <div class="avatar avatar-online">
            <img src="{{ $userPhoto }}" alt="{{ $userName }}" class="w-px-40 h-auto rounded-circle" onerror="this.src='{{ asset('images/default-avatar.png') }}'" />
          </div>
        </a>
        <ul class="dropdown-menu dropdown-menu-end">
          <li>
            <a class="dropdown-item" href="#">
              <div class="d-flex">
                <div class="flex-shrink-0 me-3">
                  <div class="avatar avatar-online">
                    <img src="{{ $userPhoto }}" alt="{{ $userName }}" class="w-px-40 h-auto rounded-circle" onerror="this.src='{{ asset('images/default-avatar.png') }}'" />
                  </div>
                </div>
                <div class="flex-grow-1">
                  <span class="fw-semibold d-block">{{ $userName }}</span>
                  <small class="text-muted">{{ $userRole }}</small>
                </div>
              </div>
            </a>
          </li>
          <li>
            <div class="dropdown-divider"></div>
          </li>
          <li>
            <a class="dropdown-item" href="#">
              <i class="bx bx-user me-2"></i>
              <span class="align-middle">Mon Profil</span>
            </a>
          </li>
          <li>
            <a class="dropdown-item" href="#">
              <i class="bx bx-cog me-2"></i>
              <span class="align-middle">Paramètres</span>
            </a>
          </li>
          <li>
            <a class="dropdown-item" href="#">
              <span class="d-flex align-items-center align-middle">
                <i class="flex-shrink-0 bx bx-credit-card me-2"></i>
                <span class="flex-grow-1 align-middle">Facturation</span>
                <span class="flex-shrink-0 badge badge-center rounded-pill bg-danger w-px-20 h-px-20">4</span>
              </span>
            </a>
          </li>
          <li>
            <div class="dropdown-divider"></div>
          </li>
          <li>
            <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
              <i class="bx bx-power-off me-2"></i>
              <span class="align-middle">Se déconnecter</span>
            </a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
              @csrf
            </form>
          </li>
        </ul>
      </li>
      @else
      <!-- Utilisateur non connecté -->
      <li class="nav-item">
        <a class="nav-link" href="{{ route('login') }}">
          <i class="bx bx-log-in me-2"></i>
          <span class="align-middle">Se connecter</span>
        </a>
      </li>
      @endauth
      <!--/ User -->
    </ul>
  </div>
</nav>
<!-- / Navbar --> 