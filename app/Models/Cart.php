<?php

namespace App\Models;

use App\Casts\MoneyCast;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasUuids;

    protected $guarded = ['id'];

    protected $casts = [
        'total' => MoneyCast::class,
    ];

    public function items()
    {
        return $this->hasMany(CartItem::class);
    }
}
