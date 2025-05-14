<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Payment extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'construction_id',
        'amount',
        'payment_type',
        'status',
        'payment_date',
        'reference_number',
        'proof_file',
        'note',
        'due_date',
        'installment_number',
    ];

    public function construction()
    {
        return $this->belongsTo(Construction::class);
    }
}
