<?php

namespace App\Filament\Resources\PaymentResource\Pages;

use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\PaymentResource;

class CreatePayment extends CreateRecord
{
    protected static string $resource = PaymentResource::class;

    protected function getCreatedNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->icon('heroicon-o-check-circle')
            ->title('Pembayaran Berhasil Ditambahkan')
            ->body('Entri pembayaran baru telah berhasil disimpan ke sistem.');
    }
}
