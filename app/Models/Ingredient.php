<?php

namespace App\Models;

use App\Models\Pizza;
use Helper;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Storage;

class Ingredient extends Model
{
    use SoftDeletes;

    protected $guarded = ['id', 'created_at', 'updated_at', 'deleted_at'];

    public const UNITS = [
        'gr' => 'grams',
        'ml' => 'milliliters',
        'slices' => 'slices'
    ];

    public function pizzas()
    {
        return $this->belongsToMany(Pizza::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($ingredient) {
            Helper::deleteFile($ingredient->image);
        });
    }
}
