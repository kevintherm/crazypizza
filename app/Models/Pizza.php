<?php

namespace App\Models;

use App\Casts\MoneyCast;
use App\Models\Ingredient;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pizza extends Model
{
    use HasUuids, HasFactory, SoftDeletes;

    protected $guarded = ['id'];

    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
        'price' => MoneyCast::class,
    ];

    public const SIZE = [
        'small' => 'small',
        'medium' => 'medium',
        'large' => 'large'
    ];

    public function ingredients()
    {
        return $this->belongsToMany(Ingredient::class, 'pizza_ingredients')
            ->withPivot('quantity')
            ->where('available_as_topping', false);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    protected function price(): Attribute
    {
        return Attribute::make(
            set: fn ($value) => str_replace(',', '', $value),
        );
    }
}
