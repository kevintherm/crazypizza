<?php

namespace App\Models;

use App\Models\Ingredient;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pizza extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = ['id'];

    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
    ];

    public const SIZE = [
        'small',
        'medium',
        'large'
    ];

    public function ingredients()
    {
        return $this->belongsToMany(Ingredient::class);
    }

    protected function price(): Attribute
    {
        return Attribute::make(
            set: fn ($value) => str_replace(',', '', $value),
        );
    }
}
