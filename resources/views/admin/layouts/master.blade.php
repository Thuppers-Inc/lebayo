<!DOCTYPE html>

<!-- =========================================================
* Sneat - Bootstrap 5 HTML Admin Template - Pro | v1.0.0
==============================================================

* Product Page: https://themeselection.com/products/sneat-bootstrap-html-admin-template/
* Created by: ThemeSelection
* License: You must have a valid license purchased in order to legally use the theme for your project.
* Copyright ThemeSelection (https://themeselection.com)

=========================================================
 -->
<!-- beautify ignore:start -->
<html
  lang="fr"
  class="light-style layout-menu-fixed"
  dir="ltr"
  data-theme="theme-default"
  data-assets-path="{{ asset('admin-assets/assets/') }}"
  data-template="vertical-menu-template-free"
>
  <head>
    <meta charset="utf-8" />
    <meta
      name="viewport"
      content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0"
    />
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Tableau de bord') - Admin Lebayo</title>

    <meta name="description" content="@yield('description', 'Tableau de bord administrateur pour Lebayo')" />

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap"
      rel="stylesheet"
    />

    <!-- Icons -->
    <link rel="stylesheet" href="{{ asset('admin-assets/assets/vendor/fonts/boxicons.css') }}" />

      <!-- Core CSS -->
  <link rel="stylesheet" href="{{ asset('admin-assets/assets/vendor/css/core.css') }}" class="template-customizer-core-css" />
  <link rel="stylesheet" href="{{ asset('admin-assets/assets/vendor/css/theme-default.css') }}" class="template-customizer-theme-css" />
  <link rel="stylesheet" href="{{ asset('admin-assets/assets/css/demo.css') }}" />
  
  <!-- Thème Moderne Lebayo -->
  <link rel="stylesheet" href="{{ asset('admin-assets/assets/css/custom-modern-theme.css') }}" />
  
  <!-- Composants Réutilisables Admin -->
  <link rel="stylesheet" href="{{ asset('admin-assets/assets/css/admin-components.css') }}" />

    <!-- Vendors CSS -->
    <link rel="stylesheet" href="{{ asset('admin-assets/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css') }}" />
    <link rel="stylesheet" href="{{ asset('admin-assets/assets/vendor/libs/apex-charts/apex-charts.css') }}" />

    <!-- Page CSS -->
    @stack('styles')

    <!-- Helpers -->
    <script src="{{ asset('admin-assets/assets/vendor/js/helpers.js') }}"></script>

    <!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section -->
    <!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->
    <script src="{{ asset('admin-assets/assets/js/config.js') }}"></script>
  </head>

  <body>
    <!-- Layout wrapper -->
    <div class="layout-wrapper layout-content-navbar">
      <div class="layout-container">
        
        <!-- Sidebar -->
        @include('admin.partials.sidebar')
        
        <!-- Layout page -->
        <div class="layout-page">
          
          <!-- Topbar -->
          @include('admin.partials.topbar')
          
          <!-- Content wrapper -->
          <div class="content-wrapper">
            <!-- Content -->
            <div class="container-xxl flex-grow-1 container-p-y">
              @yield('content')
            </div>
            <!-- / Content -->

            <!-- Footer -->
            @include('admin.partials.footer')

            <div class="content-backdrop fade"></div>
          </div>
          <!-- Content wrapper -->
        </div>
        <!-- / Layout page -->
      </div>

      <!-- Overlay -->
      <div class="layout-overlay layout-menu-toggle"></div>
    </div>
    <!-- / Layout wrapper -->

    <!-- Core JS -->
    <script src="{{ asset('admin-assets/assets/vendor/libs/jquery/jquery.js') }}"></script>
    <script src="{{ asset('admin-assets/assets/vendor/libs/popper/popper.js') }}"></script>
    <script src="{{ asset('admin-assets/assets/vendor/js/bootstrap.js') }}"></script>
    <script src="{{ asset('admin-assets/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js') }}"></script>
    <script src="{{ asset('admin-assets/assets/vendor/js/menu.js') }}"></script>

    <!-- Vendors JS -->
    <script src="{{ asset('admin-assets/assets/vendor/libs/apex-charts/apexcharts.js') }}"></script>

    <!-- Main JS -->
    <script src="{{ asset('admin-assets/assets/js/main.js') }}"></script>
    
    <!-- Composants Réutilisables Admin -->
    <script src="{{ asset('admin-assets/assets/js/admin-components.js') }}"></script>

    <!-- Page JS -->
    <script src="{{ asset('admin-assets/assets/js/dashboards-analytics.js') }}"></script>

    <!-- Page Scripts -->
    @stack('scripts')

    <!-- Place this tag in your head or just before your close body tag. -->
    <script async defer src="https://buttons.github.io/buttons.js"></script>
  </body>
</html> 