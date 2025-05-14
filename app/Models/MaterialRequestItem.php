<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MaterialRequestItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'material_request_id',
        'material_id',
        'quantity',
        'status',
        'notes',
    ];

    public function materialRequest()
    {
        return $this->belongsTo(MaterialRequest::class);
    }

    public function material()
    {
        return $this->belongsTo(Material::class);
    }

    protected static function booted()
    {
        static::updated(function ($item) {
            if ($item->isDirty('status') && $item->status === 'Disetujui') {
                $material = $item->material;
                if ($material && $material->stock_quantity >= $item->quantity) {
                    $material->stock_quantity -= $item->quantity;
                    $material->save();
                }
            }
        });
    }
}
