<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\CommerceType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Category::with('commerceType')
                             ->orderBy('name')
                             ->paginate(10);
        
        $commerceTypes = CommerceType::active()->orderBy('name')->get();
        
        return view('admin.categories.index', compact('categories', 'commerceTypes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $commerceTypes = CommerceType::active()->orderBy('name')->get();
        return view('admin.categories.create', compact('commerceTypes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'commerce_type_id' => 'required|exists:commerce_types,id',
            'name' => 'required|string|max:255|unique:categories,name',
            'emoji' => 'required|string|max:10',
            'description' => 'nullable|string|max:1000',
            'is_active' => 'nullable|in:on,1,true,false,0',
        ], [
            'commerce_type_id.required' => 'Le type de commerce est obligatoire',
            'commerce_type_id.exists' => 'Le type de commerce sélectionné n\'existe pas',
            'name.required' => 'Le nom est obligatoire',
            'name.unique' => 'Cette catégorie existe déjà',
            'emoji.required' => 'L\'emoji est obligatoire',
        ]);

        if ($validator->fails()) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            }
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $category = Category::create([
            'commerce_type_id' => $request->commerce_type_id,
            'name' => $request->name,
            'emoji' => $request->emoji,
            'description' => $request->description,
            'is_active' => $request->has('is_active') && in_array($request->is_active, ['on', '1', 'true', 1, true]),
        ]);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Catégorie créée avec succès',
                'category' => $category->load('commerceType')
            ]);
        }

        return redirect()->route('admin.categories.index')
                        ->with('success', 'Catégorie créée avec succès');
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        $category->load('commerceType');
        return view('admin.categories.show', compact('category'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category)
    {
        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'category' => $category->load('commerceType')
            ]);
        }
        
        $commerceTypes = CommerceType::active()->orderBy('name')->get();
        return view('admin.categories.edit', compact('category', 'commerceTypes'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $category)
    {
        $validator = Validator::make($request->all(), [
            'commerce_type_id' => 'required|exists:commerce_types,id',
            'name' => 'required|string|max:255|unique:categories,name,' . $category->id,
            'emoji' => 'required|string|max:10',
            'description' => 'nullable|string|max:1000',
            'is_active' => 'nullable|in:on,1,true,false,0',
        ], [
            'commerce_type_id.required' => 'Le type de commerce est obligatoire',
            'commerce_type_id.exists' => 'Le type de commerce sélectionné n\'existe pas',
            'name.required' => 'Le nom est obligatoire',
            'name.unique' => 'Cette catégorie existe déjà',
            'emoji.required' => 'L\'emoji est obligatoire',
        ]);

        if ($validator->fails()) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            }
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $category->update([
            'commerce_type_id' => $request->commerce_type_id,
            'name' => $request->name,
            'emoji' => $request->emoji,
            'description' => $request->description,
            'is_active' => $request->has('is_active') && in_array($request->is_active, ['on', '1', 'true', 1, true]),
        ]);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Catégorie modifiée avec succès',
                'category' => $category->load('commerceType')
            ]);
        }

        return redirect()->route('admin.categories.index')
                        ->with('success', 'Catégorie modifiée avec succès');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        $category->delete();

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Catégorie supprimée avec succès'
            ]);
        }

        return redirect()->route('admin.categories.index')
                        ->with('success', 'Catégorie supprimée avec succès');
    }

    /**
     * Toggle the active status of the specified resource.
     */
    public function toggleStatus(Category $category)
    {
        $category->update([
            'is_active' => !$category->is_active
        ]);

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Statut modifié avec succès',
                'is_active' => $category->is_active
            ]);
        }

        return redirect()->back()->with('success', 'Statut modifié avec succès');
    }
}
