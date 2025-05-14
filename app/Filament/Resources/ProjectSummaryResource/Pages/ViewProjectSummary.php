<?php

namespace App\Filament\Resources\ProjectSummaryResource\Pages;

use App\Filament\Resources\ProjectSummaryResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewProjectSummary extends ViewRecord
{
    protected static string $resource = ProjectSummaryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
