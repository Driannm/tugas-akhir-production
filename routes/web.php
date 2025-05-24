<?php

use App\Models\ProjectSummary;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/export-project-summary/{id}', function ($id) {
    $summary = ProjectSummary::with('construction')->findOrFail($id);

    $pdf = Pdf::loadView('exports.project-summary', [
        'summary' => $summary,
    ]);

    return $pdf->stream('project-summary-' . $summary->date->format('Ymd') . '.pdf');
})->name('export.project-summary');
