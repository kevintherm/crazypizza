<?php

namespace App\Models;

use App\Models\Cart;
use App\Models\Pizza;
use App\Models\Ingredient;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    use HasUuids;

    protected $guarded = ['id'];

    protected $hidden = ['created_at', 'updated_at', 'deleted_at'];

    protected $casts = [
        'ingredients' => 'array',
    ];

    public function cart()
    {
        return $this->belongsTo(Cart::class);
    }

    public function pizza()
    {
        return $this->belongsTo(Pizza::class)
            ->with('ingredients');
    }
}
