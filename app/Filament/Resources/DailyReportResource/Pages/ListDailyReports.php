<?php

namespace App\Filament\Resources\DailyReportResource\Pages;

use App\Filament\Resources\DailyReportResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListDailyReports extends ListRecords
{
    protected static string $resource = DailyReportResource::class;

    protected function getHeaderActions(): array
    {
        if (auth()->user()?->hasRole('super_admin')) {
            return []; // Jangan tampilkan tombol apa-apa untuk superadmin
        }

        return [
            Actions\CreateAction::make(),
        ];
    }
}
