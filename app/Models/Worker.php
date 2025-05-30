<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Worker extends Model
{
    use HasFactory;
    public function constructions()
    {
        return $this->belongsToMany(Construction::class, 'construction_worker');
    }

    protected $fillable = [
        'worker_name',
        'gender',
        'position',
        'birth_date',
        'address',
        'contact',
        'emergency_contact',
        'emergency_contact_name',
        'employment_status',
        'construction_id',
        'photo',
    ];
}
