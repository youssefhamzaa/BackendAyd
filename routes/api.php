<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\CategorieController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\ProduitController;
use App\Http\Controllers\SubCategorieController;
use App\Http\Controllers\ReviewsDataController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::prefix('cart')->group(function () {
    Route::get('/{clientId}', [CartController::class, 'show']); // Get cart by clientId
    Route::post('/{clientId}/item', [CartController::class, 'addItem']); // Add item to cart
    Route::put('/{clientId}/item/{cartItemId}', [CartController::class, 'updateItem']); // Update item
    Route::delete('/{clientId}/item/{cartItemId}', [CartController::class, 'removeItem']); // Remove item from cart
});
// Define the route for filtering products
Route::get('produits/filter', [ProduitController::class, 'filterProducts']);

Route::get('/produitsPaginate', [ProduitController::class, 'produitsPaginate']);

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
Route::get('categories/count', [CategorieController::class, 'countCategories']);
Route::middleware('api')->group(function () {
    Route::resource('categories', CategorieController::class);
    });
Route::get('subcategories/count', [SubCategorieController::class, 'countSubCategories']);
Route::middleware('api')->group(function () {
    Route::resource('subcategories', SubCategorieController::class);
    });
// Route to get Hot Products
Route::get('produits/hot', [ProduitController::class, 'getHotProducts']);
// Route to get Best Seller Products
Route::get('produits/bestseller', [ProduitController::class, 'getBestSellerProducts']);
// Route to get Top Rated Products
Route::get('produits/toprated', [ProduitController::class, 'getTopRatedProducts']);
// Route to get All Products
Route::get('produits/orders-sales', [ProduitController::class, 'calculateOrdersAndSales']);
// Route to get the count of all products
Route::get('produits/count', [ProduitController::class, 'countProduits']);
Route::middleware('api')->group(function () {
    Route::resource('produits', ProduitController::class);
});

// Define the route for filtering products

Route::get('clients/count', [ClientController::class, 'countClients']);
Route::resource('clients', ClientController::class);
Route::get('reviews/count', [ReviewsDataController::class, 'countReviews']);
Route::resource('reviews', ReviewsDataController::class);
Route::get('produits/{id}/reviews-stats', [ReviewsDataController::class, 'getProductReviewStats']);





