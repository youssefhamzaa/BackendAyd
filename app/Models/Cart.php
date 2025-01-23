<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    protected $fillable = ['client_id'];

    // Define a one-to-many relationship with CartItem
    public function items()
    {
        return $this->hasMany(CartItem::class);
    }

    // Relationship with Client model
    public function client()
    {
        return $this->belongsTo(Client::class);
    }
}
