<?php

namespace App\Events;

use App\Models\Order;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

/**
 * Event diffusé lorsqu'une nouvelle commande est créée
 *
 * Cet event est capté côté dashboard admin pour afficher
 * une notification temps réel avec son
 *
 * Note: Utilise ShouldBroadcastNow pour diffusion immédiate (synchrone)
 * Pour mettre en queue, utiliser ShouldBroadcast à la place
 */
class NouvelleCommande implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Instance de la commande créée
     *
     * @var Order
     */
    public $order;

    /**
     * Données supplémentaires à envoyer au client
     *
     * @var array
     */
    public $orderData;

    /**
     * Create a new event instance.
     *
     * @param Order $order La commande nouvellement créée
     */
    public function __construct(Order $order)
    {
        // Charger les relations nécessaires si elles ne sont pas déjà chargées
        if (!$order->relationLoaded('user')) {
            $order->load('user');
        }
        if (!$order->relationLoaded('items')) {
            $order->load('items');
        }

        $this->order = $order;

        // Préparer les données essentielles à transmettre
        $this->orderData = [
            'id' => $order->id,
            'order_number' => $order->order_number,
            'total' => $order->total,
            'formatted_total' => $order->formatted_total,
            'status' => $order->status,
            'status_label' => $order->status_label,
            'user_name' => $order->user ? $order->user->name : 'Client inconnu',
            'user_email' => $order->user ? $order->user->email : '',
            'created_at' => $order->created_at->format('d/m/Y H:i'),
            'items_count' => $order->items->count(),
        ];
    }

    /**
     * Définir le channel de diffusion
     *
     * Utilise un channel public 'commandes' pour permettre
     * à tous les admins d'écouter les nouvelles commandes
     *
     * Note: Pour une sécurité accrue, ce channel pourrait être
     * transformé en PrivateChannel avec autorisation
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new Channel('commandes'),
        ];
    }

    /**
     * Nom de l'event côté client
     *
     * @return string
     */
    public function broadcastAs(): string
    {
        return 'commande.nouvelle';
    }

    /**
     * Données envoyées au client
     *
     * @return array
     */
    public function broadcastWith(): array
    {
        return [
            'order' => $this->orderData,
        ];
    }
}
