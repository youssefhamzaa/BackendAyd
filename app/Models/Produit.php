<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produit extends Model
{
    use HasFactory;

    protected $fillable = [
        'UID',
        'Product',
        'Description',
        'SubCategory_id',
        'Brand',
        'OldPrice',
        'Price',
        'Stock',
        'Rating',
        'HotProduct',
        'BestSeller',
        'TopRated',
        'Order',
        'Sales',
        'IsFeatured',
        'Image',
        'Tags',
        'Variants',
    ];

    protected $casts = [
        'Image' => 'array', // Cast JSON to array
        'Tags' => 'array', // Cast JSON to array
        'Variants' => 'array', // Cast JSON to array
    ];
    // Définir la relation avec SubCategory
    public function subCategory()
    {
        return $this->belongsTo(subCategorie::class, 'SubCategory_id');
    }
}
