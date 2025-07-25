<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DeliverySettings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DeliverySettingsController extends Controller
{
    /**
     * Afficher la liste des paramètres de livraison
     */
    public function index()
    {
        $settings = DeliverySettings::orderBy('created_at', 'desc')->get();
        $activeSettings = DeliverySettings::getActiveSettings();

        return view('admin.delivery-settings.index', compact('settings', 'activeSettings'));
    }

    /**
     * Afficher le formulaire de création
     */
    public function create()
    {
        return view('admin.delivery-settings.create');
    }

    /**
     * Enregistrer les nouveaux paramètres
     */
    public function store(Request $request)
    {
        $request->validate([
            'delivery_fee_per_commerce' => 'required|numeric|min:0',
            'first_order_discount' => 'required|numeric|min:0',
            'free_delivery_threshold' => 'nullable|numeric|min:0',
            'is_active' => 'boolean'
        ]);

        try {
            DB::beginTransaction();

            $settings = DeliverySettings::create([
                'delivery_fee_per_commerce' => $request->delivery_fee_per_commerce,
                'first_order_discount' => $request->first_order_discount,
                'free_delivery_threshold' => $request->free_delivery_threshold ?? 0,
                'is_active' => $request->has('is_active')
            ]);

            // Si ces paramètres sont actifs, désactiver les autres
            if ($settings->is_active) {
                $settings->activate();
            }

            DB::commit();

            return redirect()->route('admin.delivery-settings.index')
                ->with('success', 'Paramètres de livraison créés avec succès !');

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                ->with('error', 'Erreur lors de la création des paramètres : ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Afficher le formulaire d'édition
     */
    public function edit(DeliverySettings $deliverySetting)
    {
        return view('admin.delivery-settings.edit', compact('deliverySetting'));
    }

    /**
     * Mettre à jour les paramètres
     */
    public function update(Request $request, DeliverySettings $deliverySetting)
    {
        $request->validate([
            'delivery_fee_per_commerce' => 'required|numeric|min:0',
            'first_order_discount' => 'required|numeric|min:0',
            'free_delivery_threshold' => 'nullable|numeric|min:0',
            'is_active' => 'boolean'
        ]);

        try {
            DB::beginTransaction();

            $deliverySetting->update([
                'delivery_fee_per_commerce' => $request->delivery_fee_per_commerce,
                'first_order_discount' => $request->first_order_discount,
                'free_delivery_threshold' => $request->free_delivery_threshold ?? 0,
                'is_active' => $request->has('is_active')
            ]);

            // Si ces paramètres sont actifs, désactiver les autres
            if ($deliverySetting->is_active) {
                $deliverySetting->activate();
            }

            DB::commit();

            return redirect()->route('admin.delivery-settings.index')
                ->with('success', 'Paramètres de livraison mis à jour avec succès !');

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                ->with('error', 'Erreur lors de la mise à jour des paramètres : ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Activer des paramètres
     */
    public function activate(DeliverySettings $deliverySetting)
    {
        try {
            $deliverySetting->activate();
            return redirect()->route('admin.delivery-settings.index')
                ->with('success', 'Paramètres activés avec succès !');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Erreur lors de l\'activation : ' . $e->getMessage());
        }
    }

    /**
     * Supprimer des paramètres
     */
    public function destroy(DeliverySettings $deliverySetting)
    {
        try {
            // Ne pas supprimer s'il n'y a qu'un seul paramètre
            if (DeliverySettings::count() <= 1) {
                return redirect()->back()
                    ->with('error', 'Impossible de supprimer le dernier paramètre de livraison.');
            }

            $deliverySetting->delete();

            return redirect()->route('admin.delivery-settings.index')
                ->with('success', 'Paramètres supprimés avec succès !');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Erreur lors de la suppression : ' . $e->getMessage());
        }
    }
} 