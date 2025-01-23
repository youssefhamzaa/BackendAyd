<?php

namespace App\Http\Controllers;

use App\Models\Categorie;
use Illuminate\Http\Request;

class CategorieController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
             // Récupérer les catégories avec leurs sous-catégories
    $categories = Categorie::with('subCategories')->get();

    // Transformer les données pour ajuster le format
    $categories = $categories->map(function ($categorie) {
        return [
            'id' => $categorie->id,
            'Name' => $categorie->Name,
            'subCategorie' => $categorie->subCategories->isEmpty() ? [] : $categorie->subCategories->pluck('Name')->toArray(),
            'Icon' => $categorie->Icon,
            'created_at' => $categorie->created_at,
            'updated_at' => $categorie->updated_at,
        ];
    });

    return response()->json($categories);
        } catch (\Exception $e) {
            return response()->json("Problème de récupération de la liste des catégories", 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            // Valider les données entrantes
            $validatedData = $request->validate([
                'Name' => 'required|string|max:255',
                'Icon' => 'nullable|string|max:255',
            ]);

            // Créer la catégorie
            $categorie = Categorie::create([
                'Name' => $validatedData['Name'],
                'Icon' => $validatedData['Icon'] ?? null,
            ]);

            return response()->json([
                'message' => 'Catégorie créée avec succès',
                'categorie' => $categorie,
            ], 201);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
           // Récupérer la catégorie par ID avec ses sous-catégories
        $categorie = Categorie::with('subCategories')->findOrFail($id);

        // Transformer les données pour ajuster le format
        $formattedCategorie = [
            'id' => $categorie->id,
            'Name' => $categorie->Name,
            'subCategorie' => $categorie->subCategories->isEmpty() ? [] : $categorie->subCategories->pluck('Name')->toArray(),
            'Icon' => $categorie->Icon,
            'created_at' => $categorie->created_at,
            'updated_at' => $categorie->updated_at,
        ];

        return response()->json($formattedCategorie, 200);
        } catch (\Exception $e) {
            //throw $th;
            return response()->json("Probleme d'affichage");
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Categorie $categorie,$id)
    {
        //
        try {
            //code...
            $categorie=Categorie::findOrFail($id);
            $categorie->update($request->all());
            return response()->json($categorie);
        } catch (\Exception $e) {
            //throw $th;
            return response()->json("Modification impossible");

        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        //
        try {
            //code...
            $categorie=Categorie::findOrFail($id);
            $categorie->delete();
            return response()->json("Categorie supprimée avec succès");
        } catch (\Exception $e) {
            return response()->json("Suppresion impossible");
            //throw $th;
        }
    }
    public function countCategories()
    {
        try {
            // Count the number of categories
            $count = Categorie::count();
            return response()->json(['count' => $count]);
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), $e->getCode());
        }
    }
}
