<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EquipmentRequest extends Model
{
    protected $fillable = ['construction_id', 'requested_by', 'notes'];

    public function construction()
    {
        return $this->belongsTo(Construction::class);
    }

    public function requestedBy()
    {
        return $this->belongsTo(User::class, 'requested_by');
    }

    public function items()
    {
        return $this->hasMany(EquipmentRequestItem::class);
    }

    public function getOverallStatusAttribute()
    {
        if ($this->items()->where('status', 'pending')->exists()) {
            return 'pending';
        }

        if ($this->items()->where('status', 'rejected')->count() === $this->items()->count()) {
            return 'rejected';
        }

        return 'approved';
    }

    public function getTitleAttribute()
    {
        return $this->construction?->construction_name ?? 'Peminjaman #' . $this->id;
    }
}
