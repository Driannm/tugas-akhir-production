<?php

namespace App\Filament\Resources\MaterialRequestResource\Pages;

use App\Filament\Resources\MaterialRequestResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMaterialRequests extends ListRecords
{
    protected static string $resource = MaterialRequestResource::class;

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
