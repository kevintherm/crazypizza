<?php

namespace App\Models;

use App\Models\Ingredient;
use Illuminate\Database\Eloquent\Model;

class Pizza extends Model
{
    protected $guarded = ['id'];

    public const SIZE = [
        'small',
        'medium',
        'large'
    ];

    public function ingredients()
    {
        return $this->belongsToMany(Ingredient::class);
    }
}
