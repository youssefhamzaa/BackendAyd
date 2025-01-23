<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    protected $fillable = [
        'cart_id',
        'product_id',
        'size',
        'color',
        'quantity',
        'price',
        'old_price',
        'image'
    ];

    // Relationship with Cart model
    public function cart()
    {
        return $this->belongsTo(Cart::class);
    }

    // Relationship with Produit model
    public function produit()
    {
        return $this->belongsTo(Produit::class, 'produit_id');
    }
}
