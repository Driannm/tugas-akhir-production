<?php

namespace App\Filament\Resources\ConstructionResource\Pages;

use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\ConstructionResource;

class ListConstructions extends ListRecords
{
    protected static string $resource = ConstructionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Tambah Proyek Baru'),
        ];
    }

    protected function getTableQuery(): Builder
    {
        $query = parent::getTableQuery();

        // Batasi query untuk hanya menampilkan proyek yang ditugaskan ke supervisor yang sedang login
        if (auth()->user()?->hasRole('supervisor')) {
            $query->where('supervisor_id', auth()->id());
        }

        return $query;
    }
}
