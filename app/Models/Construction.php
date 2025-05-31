<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Observers\ProjectAssignObserver;

//#[ObservedBy(ProjectAssignObserver::class)]
class Construction extends Model
{
    use HasFactory;
    public function workers()
    {
        return $this->belongsToMany(Worker::class, 'construction_worker');
    }

    public function supervisor()
    {
        return $this->belongsTo(User::class, 'supervisor_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function construction()
    {
        return $this->hasMany(Construction::class);
    }

    public function dailyReports()
    {
        return $this->hasMany(DailyReport::class);
    }

    public function materialRequests()
    {
        return $this->hasMany(MaterialRequest::class);
    }

    public function materialRequestItems()
    {
        return $this->hasManyThrough(
            MaterialRequestItem::class,
            MaterialRequest::class,
            'construction_id',
            'material_request_id',
            'id',
            'id'
        );
    }

    public function equipments()
    {
        return $this->belongsToMany(Equipment::class, 'construction_equipment')
            ->withPivot('quantity_used', 'start_date', 'end_date')
            ->withTimestamps();
    }
    public function items()
    {
        return $this->materialRequestItems()->get()->merge(
            $this->equipmentRequestItems()->get()
        );
    }

    public function equipmentRequestItems()
    {
        return $this->hasManyThrough(
            \App\Models\EquipmentRequestItem::class,
            \App\Models\EquipmentRequest::class,
            'construction_id',
            'equipment_request_id',
            'id',
            'id'
        );
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function getTotalPaidAttribute(): float
    {
        return $this->payments()->sum('amount');
    }

    public function getRemainingPaymentAttribute(): float
    {
        return max(0, $this->budget - $this->total_paid);
    }

    public function getIsPaidOffAttribute(): bool
    {
        return $this->remaining_payment <= 0;
    }

    protected $fillable = [
        'construction_name',
        'description',
        'start_date',
        'end_date',
        'status_construction',
        'location',
        'budget',
        'client_name',
        'project_manager',
        'supervisor_id',
        'progress_percentage',
        'documentations',
        'contract_file',
        'type_of_construction',
        'created_by',
    ];

    protected $casts = [
        'documentations' => 'array',
        'start_date' => 'datetime',
        'end_date' => 'datetime',
    ];
}
