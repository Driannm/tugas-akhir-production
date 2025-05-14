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
        return [
            Actions\CreateAction::make(),
        ];
    }
}
