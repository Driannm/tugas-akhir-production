<?php

namespace App\Filament\Resources\ProjectSummaryResource\Pages;

use App\Filament\Resources\ProjectSummaryResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListProjectSummaries extends ListRecords
{
    protected static string $resource = ProjectSummaryResource::class;

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
