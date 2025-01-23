<?php

namespace App\Http\Controllers;

use App\Models\subCategorie;
use Illuminate\Http\Request;

class SubCategorieController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        try {
            //code...
            $scategories = subCategorie::with("category")->get();
            return response()->json($scategories);
        } catch (\Exception $e) {
            //throw $th;
            return response()->json($e->getMessage(), $e->getCode());
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        try {
            //code...,

            $scategorie = new subCategorie([
                'Name'=> $request->input("Name"),
                'category_id'=> $request->input('category_id'),

            ]);
            $scategorie->save();
            return response()->json($scategorie);
        } catch (\Exception $e) {
            //throw $th;
            return response()->json($e->getMessage(), $e->getCode());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            //code...
            $scategorie=subCategorie::with("category")->findOrFail($id);
            return response()->json($scategorie);

        } catch (\Exception $e) {
            //throw $th;
            return response()->json($e->getMessage(), $e->getCode());
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        try {
            //code...
            $scategorie = subCategorie::findOrFail($id);
            $scategorie->update($request->all());
            return response()->json($scategorie);
        } catch (\Exception $e) {
            //throw $th;
            return response()->json($e->getMessage(), $e->getCode());
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
            $scategorie = subCategorie::findOrFail($id);
            $scategorie->delete();
            return response()->json("Sous catégorie supprimée avec succées");
        } catch (\Exception $e) {
            //throw $th;
            return response()->json($e->getMessage(), $e->getCode());

        }
    }
    public function countSubCategories()
    {
        try {
            // Count the number of subcategories
            $count = subCategorie::count();
            return response()->json(['count' => $count]);
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), $e->getCode());
        }
    }
}
