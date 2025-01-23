<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;

    protected $fillable = [
        'Nom',
        'Email',
        'Adresse',
        'Phone_1',
        'Phone_2',
        'Gouvernorat',
        'Delegation',
    ];

    public function carts()
    {
        return $this->hasOne(Cart::class, 'client_id'); // This links the client_id in the carts table
    }
}
