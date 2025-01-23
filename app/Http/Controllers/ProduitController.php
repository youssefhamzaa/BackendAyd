<?php

namespace App\Http\Controllers;

use App\Models\Produit;
use Illuminate\Http\Request;

class ProduitController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Récupérer les produits avec leur sous-catégorie et la catégorie de la sous-catégorie
        $produits = Produit::with('subCategory.category')->get();

        // Formater la réponse comme vous le souhaitez
        $produitsFormates = $produits->map(function ($produit) {
            return [
                'UID' => $produit->UID,
                'Product' => $produit->Product,
                'Description' => $produit->Description,
                'Category' => $produit->subCategory->category->Name, // Extraire le nom de la catégorie
                'SubCategory' => $produit->subCategory->Name, // Extraire le nom de la sous-catégorie
                'Brand' => $produit->Brand,
                'OldPrice' => $produit->OldPrice,
                'Price' => $produit->Price,
                'Stock' => $produit->Stock,
                'Rating' => $produit->Rating,
                'HotProduct' => $produit->HotProduct,
                'BestSeller' => $produit->BestSeller,
                'TopRated' => $produit->TopRated,
                'Order' => $produit->Order,
                'Sales' => $produit->Sales,
                'IsFeatured' => $produit->IsFeatured,
                'Image' => json_decode($produit->Image), // Décoder le JSON pour l'image
                'Tags' => json_decode($produit->Tags), // Décoder le JSON pour les tags
                'Variants' => json_decode($produit->Variants), // Décoder le JSON pour les variantes
            ];
        });

        return response()->json($produitsFormates);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Cette méthode peut être utilisée pour retourner un formulaire si nécessaire
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            // Validation des données
            $request->validate([
                'UID' => 'required|unique:produits',
                'Product' => 'required|string|max:255',
                'Description' => 'required|string',
                'SubCategory_id' => 'required|exists:sub_categories,id',
                'Brand' => 'required|string|max:255',
                'Price' => 'required|numeric',
                'Stock' => 'required|integer',
                'Rating' => 'nullable|numeric|min:0|max:5',
                'HotProduct' => 'nullable|boolean',
                'BestSeller' => 'nullable|boolean',
                'TopRated' => 'nullable|boolean',
                'Order' => 'nullable|integer',
                'Sales' => 'nullable|integer',
                'IsFeatured' => 'nullable|boolean',
                'Image' => 'nullable|array',
                'Tags' => 'nullable|array',
                'Variants' => 'nullable|array',
            ]);


            // Convertir les champs JSON si nécessaire
            $data = $request->all();
            $data['Image'] = json_encode($data['Image']);
            $data['Tags'] = json_encode($data['Tags']);
            $data['Variants'] = json_encode($data['Variants']);

            // Création du produit
            $produit = Produit::create($data);

            return response()->json([
                'message' => 'Produit créé avec succès',
                'data' => $produit
            ], 201);

        } catch (\Illuminate\Validation\ValidationException $e) {
            // Gérer les erreurs de validation
            return response()->json([
                'message' => 'Erreur de validation',
                'errors' => $e->errors()
            ], 422);

        } catch (\Exception $e) {
            // Gérer toute autre exception
            return response()->json([
                'message' => 'Une erreur est survenue lors de la création du produit',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        // Récupérer un produit spécifique
        $produit = Produit::findOrFail($id);

        // Retourner les informations du produit avec sous-catégorie et catégorie
        return response()->json([
            'UID' => $produit->UID,
            'Product' => $produit->Product,
            'Description' => $produit->Description,
            'Category' => $produit->subCategory->category->Name,
            'SubCategory' => $produit->subCategory->Name,
            'Brand' => $produit->Brand,
            'OldPrice' => $produit->OldPrice,
            'Price' => $produit->Price,
            'Stock' => $produit->Stock,
            'Rating' => $produit->Rating,
            'HotProduct' => $produit->HotProduct,
            'BestSeller' => $produit->BestSeller,
            'TopRated' => $produit->TopRated,
            'Order' => $produit->Order,
            'Sales' => $produit->Sales,
            'IsFeatured' => $produit->IsFeatured,
            'Image' => json_decode($produit->Image),
            'Tags' => json_decode($produit->Tags),
            'Variants' => json_decode($produit->Variants),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $produit = Produit::findOrFail($id);

        $validatedData = $request->validate([
            'UID' => 'string',
            'Product' => 'string',
            'Description' => 'string',
            'Category' => 'string',
            'SubCategory' => 'string',
            'Brand' => 'string',
            'OldPrice' => 'numeric',
            'Price' => 'numeric',
            'Stock' => 'integer',
            'Rating' => 'numeric',
            'HotProduct' => 'boolean',
            'BestSeller' => 'boolean',
            'TopRated' => 'boolean',
            'Order' => 'integer',
            'Sales' => 'integer',
            'IsFeatured' => 'boolean',
            'Image' => 'nullable|array',
            'Tags' => 'nullable|array',
            'Variants' => 'nullable|array',
            'CreatedAt' => 'nullable|date',
        ]);

        // Mise à jour des données
        $produit->update($validatedData);
        return response()->json($produit);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $produit = Produit::findOrFail($id);
        $produit->delete();
        return response()->json(['message' => 'Produit deleted successfully']);
    }
    public function countProduits()
    {
        try {
            // Count the number of Produits
            $count = Produit::count();
            return response()->json(['count' => $count]);
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), $e->getCode());
        }
    }
    public function calculateOrdersAndSales()
    {
        try {
            $products = Produit::all();
            $totalOrders = $products->sum('Order');
            $totalSales = $products->sum('Sales');

            return response()->json([
                'totalOrders' => $totalOrders,
                'totalSales' => $totalSales,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An error occurred while calculating orders and sales.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
    public function getHotProducts()
{
    try {
        // Retrieve products where 'HotProduct' is true
        $hotProducts = Produit::where('HotProduct', true)->get();

        // Format the response
        $produitsFormates = $hotProducts->map(function ($produit) {
            return [
                'UID' => $produit->UID,
                'Product' => $produit->Product,
                'Description' => $produit->Description,
                'Category' => $produit->subCategory->category->Name, // Extract category name
                'SubCategory' => $produit->subCategory->Name, // Extract subcategory name
                'Brand' => $produit->Brand,
                'OldPrice' => $produit->OldPrice,
                'Price' => $produit->Price,
                'Stock' => $produit->Stock,
                'Rating' => $produit->Rating,
                'HotProduct' => $produit->HotProduct,
                'BestSeller' => $produit->BestSeller,
                'TopRated' => $produit->TopRated,
                'Order' => $produit->Order,
                'Sales' => $produit->Sales,
                'IsFeatured' => $produit->IsFeatured,
                'Image' => json_decode($produit->Image), // Decode the JSON for Image
                'Tags' => json_decode($produit->Tags), // Decode the JSON for Tags
                'Variants' => json_decode($produit->Variants), // Decode the JSON for Variants
            ];
        });

        // Return the formatted response
        return response()->json($produitsFormates);
    } catch (\Exception $e) {
        return response()->json([
            'message' => 'Error retrieving hot products',
            'error' => $e->getMessage()
        ], 500);
    }
}

public function getBestSellerProducts()
{
    try {
        // Retrieve products where 'BestSeller' is true
        $bestSellerProducts = Produit::where('BestSeller', true)->get();

        // Format the response
        $produitsFormates = $bestSellerProducts->map(function ($produit) {
            return [
                'UID' => $produit->UID,
                'Product' => $produit->Product,
                'Description' => $produit->Description,
                'Category' => $produit->subCategory->category->Name, // Extract category name
                'SubCategory' => $produit->subCategory->Name, // Extract subcategory name
                'Brand' => $produit->Brand,
                'OldPrice' => $produit->OldPrice,
                'Price' => $produit->Price,
                'Stock' => $produit->Stock,
                'Rating' => $produit->Rating,
                'HotProduct' => $produit->HotProduct,
                'BestSeller' => $produit->BestSeller,
                'TopRated' => $produit->TopRated,
                'Order' => $produit->Order,
                'Sales' => $produit->Sales,
                'IsFeatured' => $produit->IsFeatured,
                'Image' => json_decode($produit->Image), // Decode the JSON for Image
                'Tags' => json_decode($produit->Tags), // Decode the JSON for Tags
                'Variants' => json_decode($produit->Variants), // Decode the JSON for Variants
            ];
        });

        // Return the formatted response
        return response()->json($produitsFormates);
    } catch (\Exception $e) {
        return response()->json([
            'message' => 'Error retrieving best seller products',
            'error' => $e->getMessage()
        ], 500);
    }
}

public function getTopRatedProducts()
{
    try {
        // Retrieve products where 'TopRated' is true
        $topRatedProducts = Produit::where('TopRated', true)->get();

        // Format the response
        $produitsFormates = $topRatedProducts->map(function ($produit) {
            return [
                'UID' => $produit->UID,
                'Product' => $produit->Product,
                'Description' => $produit->Description,
                'Category' => $produit->subCategory->category->Name, // Extract category name
                'SubCategory' => $produit->subCategory->Name, // Extract subcategory name
                'Brand' => $produit->Brand,
                'OldPrice' => $produit->OldPrice,
                'Price' => $produit->Price,
                'Stock' => $produit->Stock,
                'Rating' => $produit->Rating,
                'HotProduct' => $produit->HotProduct,
                'BestSeller' => $produit->BestSeller,
                'TopRated' => $produit->TopRated,
                'Order' => $produit->Order,
                'Sales' => $produit->Sales,
                'IsFeatured' => $produit->IsFeatured,
                'Image' => json_decode($produit->Image), // Decode the JSON for Image
                'Tags' => json_decode($produit->Tags), // Decode the JSON for Tags
                'Variants' => json_decode($produit->Variants), // Decode the JSON for Variants
            ];
        });

        // Return the formatted response
        return response()->json($produitsFormates);
    } catch (\Exception $e) {
        return response()->json([
            'message' => 'Error retrieving top-rated products',
            'error' => $e->getMessage()
        ], 500);
    }
}
// public function filterProducts(Request $request)
// {
//     try {
//         $query = Produit::query();

//         // Filter by "Newest" products (assumes 'created_at' exists)
//         if ($request->has('newest') && $request->input('newest') == true) {
//             $query->orderBy('created_at', 'desc'); // Order by the newest created
//         }

//         // Filter by "TopRated" products based on 'Rating' in descending order
//         if ($request->has('topRated') && $request->input('topRated') == true) {
//             $query->orderBy('Rating', 'desc'); // Order by the highest rating
//         }

//         // Filter by "BestSeller" products, ordered by sales in descending order
//         if ($request->has('bestSellers') && $request->input('bestSellers') == true) {
//             $query->where('BestSeller', true)->orderBy('Sales', 'desc'); // Order by sales
//         }

//         // Execute the query
//         $filteredProducts = $query->get();

//         // Format the response as needed
//         $produitsFormates = $filteredProducts->map(function ($produit) {
//             return [
//                 'UID' => $produit->UID,
//                 'Product' => $produit->Product,
//                 'Description' => $produit->Description,
//                 'Category' => $produit->subCategory->category->Name, // Extract category name
//                 'SubCategory' => $produit->subCategory->Name, // Extract subcategory name
//                 'Brand' => $produit->Brand,
//                 'OldPrice' => $produit->OldPrice,
//                 'Price' => $produit->Price,
//                 'Stock' => $produit->Stock,
//                 'Rating' => $produit->Rating,
//                 'HotProduct' => $produit->HotProduct,
//                 'BestSeller' => $produit->BestSeller,
//                 'TopRated' => $produit->TopRated,
//                 'Order' => $produit->Order,
//                 'Sales' => $produit->Sales,
//                 'IsFeatured' => $produit->IsFeatured,
//                 'Image' => json_decode($produit->Image), // Decode the JSON for Image
//                 'Tags' => json_decode($produit->Tags), // Decode the JSON for Tags
//                 'Variants' => json_decode($produit->Variants), // Decode the JSON for Variants
//             ];
//         });

//         // Return the filtered and formatted response
//         return response()->json($produitsFormates);

//     } catch (\Exception $e) {
//         return response()->json([
//             'message' => 'Error filtering products',
//             'error' => $e->getMessage()
//         ], 500);
//     }
// }


}
