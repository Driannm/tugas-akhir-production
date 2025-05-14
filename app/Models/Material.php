<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Material extends Model
{
    protected $fillable = [
        'material_name',
        'stock_quantity',
        'unit_price',
        'unit',
        'image',
    ];
}
