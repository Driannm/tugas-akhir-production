<?php

namespace App\Filament\Resources\PaymentResource\Pages;

use Filament\Actions\{ViewAction, DeleteAction};
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Resources\PaymentResource;

class EditPayment extends EditRecord
{
    protected static string $resource = PaymentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make()
            ->label('Detail Pembayaran')
            ->color('info'),
        ];
    }
    protected function getSavedNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->icon('heroicon-o-pencil')
            ->title('Data Pembayaran Diperbarui')
            ->body('Informasi pembayaran telah berhasil diperbarui.');
    }
}
