<?php

namespace App\Filament\Resources\ConstructionResource\Pages;

use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\ConstructionResource;

class CreateConstruction extends CreateRecord
{
    protected static string $resource = ConstructionResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getCreatedNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->icon('heroicon-o-check-circle')
            ->title('Proyek Berhasil Ditambahkan')
            ->body('Data proyek baru telah berhasil disimpan ke sistem. Anda dapat melanjutkan ke manajemen proyek atau menambah proyek lainnya.');
    }
}
