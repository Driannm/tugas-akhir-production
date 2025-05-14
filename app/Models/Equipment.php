<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Equipment extends Model
{
    use HasFactory;
    protected $table = 'equipments';

    protected $fillable = [
        'name',
        'code',
        'status',
        'quantity',
        'description',
        'last_maintenance',
    ];

    protected $casts = [
        'last_maintenance' => 'date',
    ];

    public function getAvailableStockAttribute(): int
    {
        $borrowed = $this->requestItems()
            ->where('status', 'approved')
            ->sum('quantity');

        return $this->quantity - $borrowed;
    }

    public static function boot()
    {
        parent::boot();

        static::saving(function ($equipment) {
            // Debugging untuk memastikan saving event dijalankan
            \Log::info('Saving Equipment: ' . $equipment->name . ' - Quantity: ' . $equipment->quantity . ' - Current Status: ' . $equipment->status);

            if ($equipment->quantity == 0) {
                $equipment->status = 'out_of_stock';
            } elseif ($equipment->quantity > 0 && $equipment->status == 'out_of_stock') {
                $equipment->status = 'available';
            }

            // Debugging untuk status yang akan disimpan
            \Log::info('Updated Status: ' . $equipment->status);
        });
    }

    public function constructions()
    {
        return $this->belongsToMany(Construction::class, 'construction_equipment')
            ->withPivot('quantity_used', 'start_date', 'end_date')
            ->withTimestamps();
    }

    public function projectSummaries()
    {
        return $this->belongsToMany(ProjectSummary::class, 'project_summary_equipments')
            ->withPivot('quantity')
            ->withTimestamps();
    }

    public function requestItems()
    {
        return $this->hasMany(EquipmentRequestItem::class);
    }
}
