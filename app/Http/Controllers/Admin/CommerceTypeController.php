<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CommerceType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CommerceTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $commerceTypes = CommerceType::orderBy('name')->paginate(10);
        return view('admin.commerce-types.index', compact('commerceTypes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.commerce-types.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:commerce_types,name',
            'emoji' => 'required|string|max:10',
            'description' => 'nullable|string|max:1000',
            'is_active' => 'nullable|in:on,1,true,false,0',
        ], [
            'name.required' => 'Le nom est obligatoire',
            'name.unique' => 'Ce type de commerce existe déjà',
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

        $commerceType = CommerceType::create([
            'name' => $request->name,
            'emoji' => $request->emoji,
            'description' => $request->description,
            'is_active' => $request->has('is_active') && in_array($request->is_active, ['on', '1', 'true', 1, true]),
        ]);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Type de commerce créé avec succès',
                'commerce_type' => $commerceType
            ]);
        }

        return redirect()->route('admin.commerce-types.index')
                        ->with('success', 'Type de commerce créé avec succès');
    }

    /**
     * Display the specified resource.
     */
    public function show(CommerceType $commerceType)
    {
        return view('admin.commerce-types.show', compact('commerceType'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(CommerceType $commerceType)
    {
        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'commerce_type' => $commerceType
            ]);
        }
        return view('admin.commerce-types.edit', compact('commerceType'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, CommerceType $commerceType)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:commerce_types,name,' . $commerceType->id,
            'emoji' => 'required|string|max:10',
            'description' => 'nullable|string|max:1000',
            'is_active' => 'nullable|in:on,1,true,false,0',
        ], [
            'name.required' => 'Le nom est obligatoire',
            'name.unique' => 'Ce type de commerce existe déjà',
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

        $commerceType->update([
            'name' => $request->name,
            'emoji' => $request->emoji,
            'description' => $request->description,
            'is_active' => $request->has('is_active') && in_array($request->is_active, ['on', '1', 'true', 1, true]),
        ]);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Type de commerce modifié avec succès',
                'commerce_type' => $commerceType
            ]);
        }

        return redirect()->route('admin.commerce-types.index')
                        ->with('success', 'Type de commerce modifié avec succès');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CommerceType $commerceType)
    {
        $commerceType->delete();

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Type de commerce supprimé avec succès'
            ]);
        }

        return redirect()->route('admin.commerce-types.index')
                        ->with('success', 'Type de commerce supprimé avec succès');
    }

    /**
     * Toggle the active status of the specified resource.
     */
    public function toggleStatus(CommerceType $commerceType)
    {
        $commerceType->update([
            'is_active' => !$commerceType->is_active
        ]);

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Statut modifié avec succès',
                'is_active' => $commerceType->is_active
            ]);
        }

        return redirect()->back()->with('success', 'Statut modifié avec succès');
    }
}
