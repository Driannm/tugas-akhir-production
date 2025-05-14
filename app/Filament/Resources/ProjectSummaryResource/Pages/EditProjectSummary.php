<?php

namespace App\Filament\Resources\ProjectSummaryResource\Pages;

use App\Filament\Resources\ProjectSummaryResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditProjectSummary extends EditRecord
{
    protected static string $resource = ProjectSummaryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
