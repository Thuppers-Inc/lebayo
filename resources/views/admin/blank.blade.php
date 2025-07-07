@extends('admin.layouts.master')

@section('title', 'Page Vide')

@section('description', 'Template vide pour créer de nouvelles pages d\'administration')

@section('content')
<div class="row">
  <div class="col-12">
    <div class="card">
      <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Titre de votre page</h5>
        <small class="text-muted">Sous-titre optionnel</small>
      </div>
      <div class="card-body">
        <p class="card-text">
          Bienvenue dans votre nouvelle page d'administration ! 
          Vous pouvez commencer à ajouter votre contenu ici.
        </p>
        
        <!-- Exemple de contenu -->
        <div class="row">
          <div class="col-md-6">
            <h6>Section Gauche</h6>
            <p>Contenu de la section gauche...</p>
          </div>
          <div class="col-md-6">
            <h6>Section Droite</h6>
            <p>Contenu de la section droite...</p>
          </div>
        </div>

        <!-- Exemple de boutons d'action -->
        <div class="mt-3">
          <button type="button" class="btn btn-primary">Action Principale</button>
          <button type="button" class="btn btn-secondary">Action Secondaire</button>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Exemple d'une deuxième carte -->
<div class="row mt-4">
  <div class="col-12">
    <div class="card">
      <div class="card-header">
        <h6 class="mb-0">Deuxième Section</h6>
      </div>
      <div class="card-body">
        <p>Vous pouvez ajouter autant de sections que nécessaire...</p>
        
        <!-- Exemple de tableau simple -->
        <div class="table-responsive">
          <table class="table table-striped">
            <thead>
              <tr>
                <th>Colonne 1</th>
                <th>Colonne 2</th>
                <th>Colonne 3</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>Données exemple 1</td>
                <td>Données exemple 2</td>
                <td>Données exemple 3</td>
                <td>
                  <button class="btn btn-sm btn-outline-primary">Modifier</button>
                  <button class="btn btn-sm btn-outline-danger">Supprimer</button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@push('styles')
<style>
/* CSS spécifique à cette page */
.custom-style {
    /* Vos styles personnalisés ici */
}
</style>
@endpush

@push('scripts')
<script>
// JavaScript spécifique à cette page
document.addEventListener('DOMContentLoaded', function() {
    console.log('Page vide chargée avec succès !');
    
    // Exemple d'interaction
    const buttons = document.querySelectorAll('.btn-primary');
    buttons.forEach(button => {
        button.addEventListener('click', function() {
            alert('Bouton cliqué ! Ajoutez votre logique ici.');
        });
    });
});
</script>
@endpush 