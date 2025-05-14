<?php

namespace App\Filament\Resources\WorkerResource\Pages;

use Filament\Actions\EditAction;
use App\Filament\Resources\WorkerResource;
use Filament\Resources\Pages\ViewRecord;

class ViewWorker extends ViewRecord
{
    protected static string $resource = WorkerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make()
                ->label('Edit Data Pekerja')
                ->color('info'),
        ];
    }
}