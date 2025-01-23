<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ReviewsData extends Model
{
    use HasFactory;

    protected $fillable = [
        'reviewer',
        'product_id',
        'rating',
        'comment',
        'date',
    ];

    // Define relationship with Client model (assuming "clients" table exists)
    public function client()
    {
        return $this->belongsTo(Client::class, 'reviewer', 'Nom'); // Assuming 'Nom' is the client's name field
    }

    // Define relationship with Produit model
    public function product()
    {
        return $this->belongsTo(Produit::class, 'product_id');
    }
}
