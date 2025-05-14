<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EquipmentRequestItem extends Model
{
    protected $fillable = ['equipment_request_id', 'equipment_id', 'quantity', 'status'];

    public static function booted()
    {
        static::updating(function ($item) {
            if ($item->isDirty('status') && $item->status === 'approved') {
                $equipment = $item->equipment;

                if ($equipment->quantity >= $item->quantity) {
                    $equipment->decrement('quantity', $item->quantity);
                } else {
                    throw new \Exception("Stok tidak mencukupi untuk peralatan: {$equipment->name}");
                }
            }
        });
    }

    public function request()
    {
        return $this->belongsTo(EquipmentRequest::class, 'equipment_request_id');
    }

    public function equipment()
    {
        return $this->belongsTo(Equipment::class);
    }
}
