<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class subCategorie extends Model
{
    use HasFactory;

    protected $fillable = [
        'Name',
        'category_id',
    ];

    public function category()
    {
        return $this->belongsTo(Categorie::class, 'category_id');
    }
    public function produits()
    {
        return $this->hasMany(Produit::class);
    }
}
