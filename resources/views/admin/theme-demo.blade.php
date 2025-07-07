@extends('admin.layouts.master')

@section('title', 'Démonstration Palette Harmonisée Lebayo')

@section('content')
<div class="row">
  <!-- En-tête de démonstration -->
  <div class="col-12 mb-4">
    <div class="card card-accent-primary">
      <div class="card-header card-header-primary">
        <h4 class="mb-0 text-white">🎨 Palette Harmonisée Moderne - Lebayo</h4>
      </div>
      <div class="card-body">
        <p class="mb-3">Voici la nouvelle palette de couleurs harmonisée mise en place pour le panel d'administration :</p>
                 <div class="alert alert-primary mb-3">
           <strong>📝 Principes de Design :</strong><br>
           • <strong>Dark Blue (#003049)</strong> : Couleur principale - professionnelle et rassurante<br>
           • <strong>Rouge (#D62828)</strong> : Actions critiques et alertes d'erreur<br>
           • <strong>Orange (#F77F00)</strong> : Actions dynamiques et notifications<br>
           • <strong>Jaune (#FCBF49)</strong> : Avertissements et actions spéciales<br>
           • <strong>Beige (#EAE2B7)</strong> : Arrière-plans doux et contenus secondaires
         </div>
        
                 <!-- Palette de couleurs -->
         <div class="row">
           <div class="col-md-2 mb-3">
             <div class="bg-theme-dark-blue p-3 rounded text-white text-center">
               <strong>Dark Blue</strong><br>
               <small>#003049</small>
             </div>
           </div>
           <div class="col-md-2 mb-3">
             <div class="bg-theme-red p-3 rounded text-white text-center">
               <strong>Red</strong><br>
               <small>#D62828</small>
             </div>
           </div>
           <div class="col-md-2 mb-3">
             <div class="bg-theme-orange p-3 rounded text-white text-center">
               <strong>Orange</strong><br>
               <small>#F77F00</small>
             </div>
           </div>
           <div class="col-md-2 mb-3">
             <div class="bg-theme-yellow p-3 rounded text-white text-center">
               <strong>Yellow</strong><br>
               <small>#FCBF49</small>
             </div>
           </div>
           <div class="col-md-2 mb-3">
             <div class="bg-theme-beige p-3 rounded text-dark text-center">
               <strong>Beige</strong><br>
               <small>#EAE2B7</small>
             </div>
           </div>
         </div>
      </div>
    </div>
  </div>

  <!-- Démonstration des boutons -->
     <div class="col-md-6 mb-4">
     <div class="card">
       <div class="card-header">
         <h5 class="mb-0">Boutons et Actions</h5>
         <small class="text-muted">Hiérarchie sémantique des couleurs</small>
       </div>
      <div class="card-body">
                 <div class="d-flex flex-wrap gap-2 mb-3">
           <button class="btn btn-primary">Bouton Principal</button>
           <button class="btn btn-outline-primary">Bouton Outline</button>
           <button class="btn btn-primary btn-sm">Petit Bouton</button>
         </div>
         
         <div class="d-flex flex-wrap gap-2 mb-3">
           <button class="btn btn-danger-theme">Action Critique</button>
           <button class="btn btn-warning-theme">Action Spéciale</button>
           <button class="btn btn-accent-theme">Action Dynamique</button>
         </div>
        
                 <div class="d-flex flex-wrap gap-2">
           <span class="badge bg-primary">Badge Principal</span>
           <span class="badge bg-label-primary">Badge Label</span>
           <span class="badge bg-theme-orange text-white">Badge Orange</span>
         </div>
      </div>
    </div>
  </div>

  <!-- Démonstration des formulaires -->
  <div class="col-md-6 mb-4">
    <div class="card">
      <div class="card-header">
        <h5 class="mb-0">Formulaires</h5>
      </div>
      <div class="card-body">
        <div class="mb-3">
          <label class="form-label">Champ de texte</label>
          <input type="text" class="form-control" placeholder="Focus pour voir la couleur">
        </div>
        
        <div class="mb-3">
          <div class="form-check">
            <input class="form-check-input" type="checkbox" checked>
            <label class="form-check-label">Checkbox activée</label>
          </div>
        </div>
        
        <div class="mb-3">
          <div class="form-check form-switch">
            <input class="form-check-input" type="checkbox" checked>
            <label class="form-check-label">Switch activé</label>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Démonstration des alertes -->
  <div class="col-md-6 mb-4">
    <div class="card">
      <div class="card-header">
        <h5 class="mb-0">Alertes et Messages</h5>
      </div>
      <div class="card-body">
        <div class="alert alert-primary" role="alert">
          <strong>Succès !</strong> Votre action a été réalisée avec succès.
        </div>
        
        <div class="alert alert-primary alert-dismissible" role="alert">
          <strong>Information !</strong> Voici une information importante.
          <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
      </div>
    </div>
  </div>

  <!-- Démonstration des éléments d'interface -->
  <div class="col-md-6 mb-4">
    <div class="card">
      <div class="card-header">
        <h5 class="mb-0">Éléments UI</h5>
      </div>
      <div class="card-body">
        <!-- Progress bar -->
        <div class="mb-3">
          <label class="form-label">Barre de progression</label>
          <div class="progress">
            <div class="progress-bar" style="width: 75%">75%</div>
          </div>
        </div>
        
        <!-- Navigation pills -->
        <ul class="nav nav-pills mb-3">
          <li class="nav-item">
            <a class="nav-link active" href="#">Actif</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#">Lien</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#">Autre</a>
          </li>
        </ul>
        
        <!-- Dropdown -->
        <div class="dropdown">
          <button class="btn btn-primary dropdown-toggle" type="button" data-bs-toggle="dropdown">
            Menu Déroulant
          </button>
          <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="#">Action</a></li>
            <li><a class="dropdown-item active" href="#">Action Active</a></li>
            <li><a class="dropdown-item" href="#">Autre action</a></li>
          </ul>
        </div>
      </div>
    </div>
  </div>

  <!-- Démonstration des statistiques -->
  <div class="col-12 mb-4">
    <div class="card">
      <div class="card-header">
        <h5 class="mb-0">Widgets de Statistiques</h5>
      </div>
      <div class="card-body">
                 <div class="row">
           <div class="col-md-3 mb-3">
             <div class="card bg-primary text-white">
               <div class="card-body text-center">
                 <h4 class="mb-1">2,340</h4>
                 <p class="mb-0">Ventes Totales</p>
               </div>
             </div>
           </div>
           <div class="col-md-3 mb-3">
             <div class="card bg-theme-orange text-white">
               <div class="card-body text-center">
                 <h4 class="mb-1">1,250 €</h4>
                 <p class="mb-0">Revenus</p>
               </div>
             </div>
           </div>
           <div class="col-md-3 mb-3">
             <div class="card bg-theme-dark-blue text-white">
               <div class="card-body text-center">
                 <h4 class="mb-1">98%</h4>
                 <p class="mb-0">Satisfaction</p>
               </div>
             </div>
           </div>
           <div class="col-md-3 mb-3">
             <div class="card bg-theme-yellow text-dark">
               <div class="card-body text-center">
                 <h4 class="mb-1">456</h4>
                 <p class="mb-0">Clients</p>
               </div>
             </div>
           </div>
         </div>
      </div>
    </div>
  </div>

  <!-- Graphique de démonstration -->
  <div class="col-12">
    <div class="card">
      <div class="card-header">
        <h5 class="mb-0">Graphique avec Nouvelle Palette</h5>
      </div>
      <div class="card-body">
        <div id="demoChart"></div>
      </div>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
  // Graphique de démonstration avec la palette rouge
  if (document.querySelector('#demoChart')) {
    const demoChart = new ApexCharts(document.querySelector('#demoChart'), {
      series: [{
        name: 'Ventes',
        data: [44, 55, 57, 56, 61, 58, 63, 60, 66]
      }, {
        name: 'Revenus',
        data: [76, 85, 101, 98, 87, 105, 91, 114, 94]
      }],
      chart: {
        type: 'bar',
        height: 350
      },
             colors: ['#003049', '#F77F00'],
      plotOptions: {
        bar: {
          horizontal: false,
          columnWidth: '55%',
        },
      },
      dataLabels: {
        enabled: false
      },
      stroke: {
        show: true,
        width: 2,
        colors: ['transparent']
      },
      xaxis: {
        categories: ['Fév', 'Mar', 'Avr', 'Mai', 'Jun', 'Jul', 'Aoû', 'Sep', 'Oct'],
      },
      fill: {
        opacity: 1
      },
      tooltip: {
        y: {
          formatter: function (val) {
            return val + " €"
          }
        }
      }
    });
    demoChart.render();
  }
});
</script>
@endpush 