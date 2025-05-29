<?php

namespace App\Filament\Resources\ConstructionResource\Pages;

use Auth;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Filament\Actions\{ViewAction, DeleteAction};
use App\Filament\Resources\ConstructionResource;
use Illuminate\Database\Eloquent\Model;

class EditConstruction extends EditRecord
{
    protected static string $resource = ConstructionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make()
                ->label('Detail Proyek')
                ->color('info'),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getSavedNotification(): ?Notification
    {
        return Notification::make()
            ->info()
            ->icon('heroicon-o-check-circle')
            ->title('Perubahan Disimpan')
            ->body('Semua perubahan pada proyek telah berhasil diperbarui. Pastikan untuk meninjau kembali data terbaru yang telah disimpan.');
    }

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        $record->update($data);

        Notification::make()
            ->title('Saved successfully')
            ->sendToDatabase(Auth::user());

        return $record;
    }
}
