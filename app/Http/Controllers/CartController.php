<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Client;
use Illuminate\Http\Request;

class CartController extends Controller
{
    // Show the cart for the current client
    public function show()
    {
        try {
            $client = Client::find(1); // Replace with actual client identification logic
            if (!$client) {
                return response()->json(['message' => 'Client not found'], 404);
            }

            $cart = Cart::where('client_id', $client->id)->first(); // Get the client's cart
            if ($cart) {
                return response()->json([
                    'cart' => $cart,
                    'items' => $cart->items
                ]);
            }

            return response()->json(['message' => 'Cart not found'], 404);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error fetching cart', 'error' => $e->getMessage()], 500);
        }
    }

    // Add item to the cart
    public function addItem(Request $request)
    {
        try {
            $client = Client::find(1); // Replace with actual client identification logic
            if (!$client) {
                return response()->json(['message' => 'Client not found'], 404);
            }

            $cart = Cart::firstOrCreate(['client_id' => $client->id]); // Create a new cart if not found

            // Validate the incoming request
            $request->validate([
                'product_id' => 'required|exists:products,id',
                'quantity' => 'required|integer|min:1',
                'size' => 'nullable|string',
                'color' => 'nullable|string',
                'price' => 'required|numeric',
                'old_price' => 'nullable|numeric',
                'image' => 'nullable|string'
            ]);

            // Create or update the CartItem
            $cartItem = CartItem::updateOrCreate(
                [
                    'cart_id' => $cart->id,
                    'product_id' => $request->product_id,
                    'size' => $request->size,
                    'color' => $request->color
                ],
                [
                    'quantity' => $request->quantity,
                    'price' => $request->price,
                    'old_price' => $request->old_price,
                    'image' => $request->image
                ]
            );

            return response()->json(['message' => 'Item added to cart', 'cartItem' => $cartItem]);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error adding item to cart', 'error' => $e->getMessage()], 500);
        }
    }

    // Update cart item (quantity, size, color)
    public function updateItem(Request $request, $id)
    {
        try {
            $request->validate([
                'quantity' => 'nullable|integer|min:1',
                'size' => 'nullable|string',
                'color' => 'nullable|string'
            ]);

            $cartItem = CartItem::findOrFail($id);
            $cartItem->update($request->all());

            return response()->json(['message' => 'Cart item updated', 'cartItem' => $cartItem]);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error updating cart item', 'error' => $e->getMessage()], 500);
        }
    }

    // Remove item from the cart
    public function removeItem($id)
    {
        try {
            $cartItem = CartItem::findOrFail($id);
            $cartItem->delete();

            return response()->json(['message' => 'Item removed from cart']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error removing item from cart', 'error' => $e->getMessage()], 500);
        }
    }
}
