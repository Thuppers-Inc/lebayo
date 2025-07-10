<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    /**
     * Afficher la liste des commandes
     */
    public function index(Request $request)
    {
        $query = Order::with(['user', 'commerce', 'deliveryAddress', 'items'])
            ->recentFirst();

        // Filtres
        if ($request->filled('status')) {
            $query->byStatus($request->status);
        }

        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                  ->orWhereHas('user', function($userQuery) use ($search) {
                      $userQuery->where('name', 'like', "%{$search}%")
                               ->orWhere('email', 'like', "%{$search}%");
                  })
                  ->orWhereHas('commerce', function($commerceQuery) use ($search) {
                      $commerceQuery->where('name', 'like', "%{$search}%");
                  });
            });
        }

        $orders = $query->paginate(15);

        // Statistiques
        $stats = [
            'total' => Order::count(),
            'pending' => Order::byStatus(Order::STATUS_PENDING)->count(),
            'confirmed' => Order::byStatus(Order::STATUS_CONFIRMED)->count(),
            'delivered' => Order::byStatus(Order::STATUS_DELIVERED)->count(),
            'cancelled' => Order::byStatus(Order::STATUS_CANCELLED)->count(),
        ];

        return view('admin.orders.index', compact('orders', 'stats'));
    }

    /**
     * Afficher les détails d'une commande
     */
    public function show(Order $order)
    {
        $order->load(['user', 'commerce', 'deliveryAddress', 'items.product']);
        
        return view('admin.orders.show', compact('order'));
    }

    /**
     * Mettre à jour le statut d'une commande
     */
    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:' . implode(',', [
                Order::STATUS_PENDING,
                Order::STATUS_CONFIRMED,
                Order::STATUS_PREPARING,
                Order::STATUS_READY,
                Order::STATUS_OUT_FOR_DELIVERY,
                Order::STATUS_DELIVERED,
                Order::STATUS_CANCELLED
            ])
        ]);

        $oldStatus = $order->status;
        $order->update(['status' => $request->status]);

        // Si la commande est livrée, enregistrer l'heure de livraison
        if ($request->status === Order::STATUS_DELIVERED && $oldStatus !== Order::STATUS_DELIVERED) {
            $order->update(['actual_delivery_time' => now()]);
        }

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Statut de la commande mis à jour avec succès',
                'status' => $order->status,
                'status_label' => $order->status_label
            ]);
        }

        return redirect()->back()->with('success', 'Statut de la commande mis à jour avec succès');
    }

    /**
     * Mettre à jour le statut de paiement d'une commande
     */
    public function updatePaymentStatus(Request $request, Order $order)
    {
        $request->validate([
            'payment_status' => 'required|in:' . implode(',', [
                Order::PAYMENT_STATUS_PENDING,
                Order::PAYMENT_STATUS_PAID,
                Order::PAYMENT_STATUS_FAILED,
                Order::PAYMENT_STATUS_REFUNDED
            ])
        ]);

        $order->update(['payment_status' => $request->payment_status]);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Statut de paiement mis à jour avec succès',
                'payment_status' => $order->payment_status,
                'payment_status_label' => $order->payment_status_label
            ]);
        }

        return redirect()->back()->with('success', 'Statut de paiement mis à jour avec succès');
    }
} 