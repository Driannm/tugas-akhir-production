<?php

namespace App\Filament\Resources\EquipmentRequestResource\Pages;

use App\Filament\Resources\EquipmentRequestResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListEquipmentRequests extends ListRecords
{
    protected static string $resource = EquipmentRequestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
