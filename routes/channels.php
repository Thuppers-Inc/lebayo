<?php

use Illuminate\Support\Facades\Broadcast;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

// Channel public pour les notifications de commandes
// Tous les utilisateurs authentifiés peuvent écouter
Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

// Pour une sécurité accrue, décommentez ceci pour rendre le channel 'commandes' privé
// et restreignez l'accès aux admins uniquement
/*
Broadcast::channel('commandes', function ($user) {
    // Vérifier si l'utilisateur est admin
    return $user->account_type === \App\Models\AccountType::ADMIN || 
           $user->account_type === \App\Models\AccountType::SUPER_ADMIN;
});
*/
