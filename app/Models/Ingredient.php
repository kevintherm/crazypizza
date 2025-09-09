<?php

namespace App\Models;

use App\Casts\MoneyCast;
use App\Models\Pizza;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Helper;
use Storage;

class Ingredient extends Model
{
    use HasUuids, HasFactory, SoftDeletes;

    protected $guarded = ['id', 'created_at', 'updated_at', 'deleted_at'];

    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
        'price_per_unit' => MoneyCast::class,
    ];

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
            if ($ingredient->image) Helper::deleteFile($ingredient->image);
        });
    }
}
