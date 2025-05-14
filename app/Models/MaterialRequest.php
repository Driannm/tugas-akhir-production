<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MaterialRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'construction_id',
        'requested_by',
        'notes',
    ];

    public function getOverallStatusAttribute()
    {
        if ($this->materialRequestItems()->where('status', 'pending')->exists()) {
            return 'pending';
        }

        if ($this->materialRequestItems()->where('status', 'rejected')->count() === $this->materialRequestItems()->count()) {
            return 'rejected';
        }

        return 'approved';
    }

    public function construction()
    {
        return $this->belongsTo(Construction::class);
    }
    public function requestedBy()
    {
        return $this->belongsTo(User::class, 'requested_by');
    }

    public function materialRequestItems()
    {
        return $this->hasMany(MaterialRequestItem::class);
    }
}
