<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Commerce;
use App\Models\CommerceType;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CommerceController extends Controller
{
    /**
     * Afficher la liste des commerces
     */
    public function index()
    {
        $commerces = Commerce::with(['commerceType'])
            ->latest()
            ->get();

        $commerceTypes = CommerceType::active()->get();

        return view('admin.commerces.index', compact('commerces', 'commerceTypes'));
    }

    /**
     * Afficher le formulaire de création
     */
    public function create()
    {
        $commerceTypes = CommerceType::active()->get();
        $categories = Category::active()->get();
        
        return view('admin.commerces.create', compact('commerceTypes', 'categories'));
    }

    /**
     * Enregistrer un nouveau commerce
     */
    public function store(Request $request)
    {
        // Gérer la checkbox is_active (convertir "0"/"1" en boolean)
        $request->merge([
            'is_active' => filter_var($request->input('is_active'), FILTER_VALIDATE_BOOLEAN)
        ]);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'commerce_type_id' => 'required|exists:commerce_types,id',
            'city' => 'required|string|max:255',
            'address' => 'required|string',
            'contact' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'description' => 'nullable|string',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_active' => 'boolean',
            'opening_hours' => 'nullable|array',
            'categories' => 'nullable|array',
            'categories.*' => 'exists:categories,id'
        ]);

        // Traiter les horaires d'ouverture
        if ($request->has('opening_hours')) {
            $openingHours = [];
            $days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
            
            foreach ($days as $day) {
                $dayData = $request->input("opening_hours.{$day}", []);
                
                if (isset($dayData['closed']) && $dayData['closed'] == '1') {
                    $openingHours[$day] = ['closed' => true];
                } elseif (isset($dayData['open_24h']) && $dayData['open_24h'] == '1') {
                    $openingHours[$day] = ['open_24h' => true];
                } elseif (!empty($dayData['open']) && !empty($dayData['close'])) {
                    $openingHours[$day] = [
                        'open' => $dayData['open'],
                        'close' => $dayData['close']
                    ];
                }
            }
            
            $validated['opening_hours'] = !empty($openingHours) ? $openingHours : null;
        }

        // Gestion du logo
        if ($request->hasFile('logo')) {
            $logo = $request->file('logo');
            $filename = Str::uuid() . '.' . $logo->getClientOriginalExtension();
            
            // Créer le répertoire s'il n'existe pas
            $directory = public_path('commerces');
            if (!file_exists($directory)) {
                mkdir($directory, 0755, true);
            }
            
            // Déplacer le fichier vers public/commerces
            $logo->move($directory, $filename);
            $validated['logo'] = $filename;
        }

        $commerce = Commerce::create($validated);

        // Associer les catégories
        if ($request->has('categories')) {
            $commerce->categories()->sync($request->categories);
        }

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Commerce créé avec succès !',
                'commerce' => $commerce->load('commerceType')
            ]);
        }

        return redirect()->route('admin.commerces.index')
            ->with('success', 'Commerce créé avec succès !');
    }

    /**
     * Afficher un commerce
     */
    public function show(Commerce $commerce)
    {
        $commerce->load(['commerceType', 'categories']);
        return view('admin.commerces.show', compact('commerce'));
    }

    /**
     * Afficher le formulaire d'édition
     */
    public function edit(Commerce $commerce)
    {
        $commerceTypes = CommerceType::active()->get();
        $categories = Category::active()->get();
        $commerce->load('categories');
        
        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'commerce' => $commerce,
                'selected_categories' => $commerce->categories->pluck('id')->toArray()
            ]);
        }

        return view('admin.commerces.edit', compact('commerce', 'commerceTypes', 'categories'));
    }

    /**
     * Mettre à jour un commerce
     */
    public function update(Request $request, Commerce $commerce)
    {
        // Gérer la checkbox is_active (convertir "0"/"1" en boolean)
        $request->merge([
            'is_active' => filter_var($request->input('is_active'), FILTER_VALIDATE_BOOLEAN)
        ]);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'commerce_type_id' => 'required|exists:commerce_types,id',
            'city' => 'required|string|max:255',
            'address' => 'required|string',
            'contact' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'description' => 'nullable|string',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_active' => 'boolean',
            'opening_hours' => 'nullable|array',
            'categories' => 'nullable|array',
            'categories.*' => 'exists:categories,id'
        ]);

        // Traiter les horaires d'ouverture
        if ($request->has('opening_hours')) {
            $openingHours = [];
            $days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
            
            foreach ($days as $day) {
                $dayData = $request->input("opening_hours.{$day}", []);
                
                if (isset($dayData['closed']) && $dayData['closed'] == '1') {
                    $openingHours[$day] = ['closed' => true];
                } elseif (isset($dayData['open_24h']) && $dayData['open_24h'] == '1') {
                    $openingHours[$day] = ['open_24h' => true];
                } elseif (!empty($dayData['open']) && !empty($dayData['close'])) {
                    $openingHours[$day] = [
                        'open' => $dayData['open'],
                        'close' => $dayData['close']
                    ];
                }
            }
            
            $validated['opening_hours'] = !empty($openingHours) ? $openingHours : null;
        }

        // Gestion du logo
        if ($request->hasFile('logo')) {
            // Supprimer l'ancien logo
            if ($commerce->logo) {
                $oldLogoPath = public_path('commerces/' . $commerce->logo);
                if (file_exists($oldLogoPath)) {
                    unlink($oldLogoPath);
                }
            }
            
            $logo = $request->file('logo');
            $filename = Str::uuid() . '.' . $logo->getClientOriginalExtension();
            
            // Créer le répertoire s'il n'existe pas
            $directory = public_path('commerces');
            if (!file_exists($directory)) {
                mkdir($directory, 0755, true);
            }
            
            // Déplacer le fichier vers public/commerces
            $logo->move($directory, $filename);
            $validated['logo'] = $filename;
        }

        $commerce->update($validated);

        // Mettre à jour les catégories
        if ($request->has('categories')) {
            $commerce->categories()->sync($request->categories);
        } else {
            $commerce->categories()->detach();
        }

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Commerce modifié avec succès !',
                'commerce' => $commerce->fresh()->load('commerceType')
            ]);
        }

        return redirect()->route('admin.commerces.index')
            ->with('success', 'Commerce modifié avec succès !');
    }

    /**
     * Supprimer un commerce
     */
    public function destroy(Commerce $commerce)
    {
        try {
            // Supprimer le logo
            if ($commerce->logo) {
                $logoPath = public_path('commerces/' . $commerce->logo);
                if (file_exists($logoPath)) {
                    unlink($logoPath);
                }
            }
            
            $commerce->delete();

            if (request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Commerce supprimé avec succès !'
                ]);
            }

            return redirect()->route('admin.commerces.index')
                ->with('success', 'Commerce supprimé avec succès !');
                
        } catch (\Exception $e) {
            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors de la suppression du commerce.'
                ], 500);
            }

            return redirect()->route('admin.commerces.index')
                ->with('error', 'Erreur lors de la suppression du commerce.');
        }
    }

    /**
     * Changer le statut d'un commerce
     */
    public function toggleStatus(Commerce $commerce)
    {
        try {
            $commerce->update([
                'is_active' => !$commerce->is_active
            ]);

            $status = $commerce->is_active ? 'activé' : 'désactivé';

            if (request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => "Commerce {$status} avec succès !",
                    'is_active' => $commerce->is_active
                ]);
            }

            return redirect()->route('admin.commerces.index')
                ->with('success', "Commerce {$status} avec succès !");
                
        } catch (\Exception $e) {
            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors du changement de statut.'
                ], 500);
            }

            return redirect()->route('admin.commerces.index')
                ->with('error', 'Erreur lors du changement de statut.');
        }
    }
} 