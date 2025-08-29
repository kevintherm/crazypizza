<?php

namespace App\Models;

use App\Models\Pizza;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ingredient extends Model
{
    use SoftDeletes;

    protected $guarded = ['id'];

    public const UNITS = [
        'gr' => 'grams',
        'ml' => 'milliliters',
        'slices' => 'slices'
    ];

    public function pizzas()
    {
        return $this->belongsToMany(Pizza::class);
    }
}
