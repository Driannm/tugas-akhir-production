<?php

namespace App\Filament\Resources\EquipmentRequestResource\Pages;

use App\Filament\Resources\EquipmentRequestResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditEquipmentRequest extends EditRecord
{
    protected static string $resource = EquipmentRequestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
