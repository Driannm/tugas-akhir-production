<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DailyReport extends Model
{
    use HasFactory;
    public function supervisor()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function construction()
    {
        return $this->belongsTo(Construction::class);
    }

    protected $fillable = [
        'construction_id',
        'user_id',
        'report_date',
        'description',
        'issues',
        'weather',
        'photo',
        'status',
    ];
}
