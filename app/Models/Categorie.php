<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Categorie extends Model
{
    use HasFactory;
    protected $fillable = [
    'Name','Icon'
    ];
    public function subCategories()
    {
        return $this->hasMany(subCategorie::class, 'category_id');
    }
    // public function produits()
    // {
    //     return $this->hasMany(Produit::class);
    // }
}
