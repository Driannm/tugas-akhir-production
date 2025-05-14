<?php

namespace App\Filament\Resources\WorkerResource\Pages;

use Filament\Notifications\Notification;
use App\Filament\Resources\WorkerResource;
use Filament\Resources\Pages\CreateRecord;

class CreateWorker extends CreateRecord
{
    protected static string $resource = WorkerResource::class;

    protected function getCreatedNotification(): ?Notification
    {
        return Notification::make()
            ->title('Berhasil Menambahkan')
            ->body('Data pekerja baru berhasil ditambahkan ke sistem.')
            ->success()
            ->icon('heroicon-o-check-circle');
    }
}