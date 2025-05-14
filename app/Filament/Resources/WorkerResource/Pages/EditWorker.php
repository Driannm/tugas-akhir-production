<?php

namespace App\Filament\Resources\WorkerResource\Pages;

use Filament\Actions\{ViewAction, DeleteAction};
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Resources\WorkerResource;

class EditWorker extends EditRecord
{
    protected static string $resource = WorkerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make()
                ->label('Lihat Detail Pekerja')
                ->color('gray'),
            DeleteAction::make()
                ->label('Hapus Data Pekerja')
                ->color('danger')
                ->successNotification(
                    Notification::make()
                        ->title('Berhasil Menghapus')
                        ->body('Data pekerja telah berhasil dihapus.')
                        ->success()
                        ->icon('heroicon-o-check-circle')
                ),
        ];
    }

    protected function getSavedNotification(): ?Notification
    {
        return Notification::make()
            ->title('Berhasil Memperbarui')
            ->body('Data pekerja telah berhasil diperbarui.')
            ->success()
            ->icon('heroicon-o-check-circle');
    }
}