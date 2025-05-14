<?php

namespace App\Filament\Resources\WorkerResource\Pages;

use Filament\Actions\CreateAction;
use Filament\Resources\Components\Tab;
use App\Filament\Resources\WorkerResource;
use Filament\Resources\Pages\ListRecords;

class ListWorkers extends ListRecords
{
    protected static string $resource = WorkerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Tambah Pekerja Baru')
                ->color('primary'),
        ];
    }

    public function getTabs(): array
    {
        return [
            null => Tab::make('Semua'),
            'Pekerja Tetap' => Tab::make()->query(fn($query) => $query->where('employment_status', 'Pekerja Tetap')),
            'Pekerja Lepas' => Tab::make()->query(fn($query) => $query->where('employment_status', 'Pekerja Lepas')),
            'Dipecat' => Tab::make()->query(fn($query) => $query->where('employment_status', 'Dipecat')),
        ];
    }
}