<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProjectSummary extends Model
{
    use HasFactory;

    public function construction()
    {
        return $this->belongsTo(Construction::class);
    }

    public function workers()
    {
        return $this->belongsToMany(Worker::class, 'project_summary_workers')
            ->withPivot('start_date', 'end_date')
            ->withTimestamps();
    }

    public function dailyReports()
    {
        return $this->belongsToMany(DailyReport::class, 'project_summary_daily_reports')
            ->withTimestamps();
    }

    public function materialRequests()
    {
        return $this->belongsToMany(MaterialRequest::class, 'project_summary_material_requests')
            ->withTimestamps();
    }

    protected $fillable = [
        'construction_id',
        'date',
        'description',
        'notes',
        'documentation',
    ];

    protected $casts = [
        'documentation' => 'array',
        'date' => 'date',
    ];
}
