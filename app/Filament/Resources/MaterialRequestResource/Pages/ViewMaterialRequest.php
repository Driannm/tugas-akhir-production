<?php

namespace App\Filament\Resources\MaterialRequestResource\Pages;

use Filament\Actions;
use Filament\Infolists\Infolist;
use Filament\Resources\Pages\ViewRecord;
use App\Filament\Resources\MaterialRequestResource;
use Filament\Infolists\Components\{Section, TextEntry, BadgeEntry};

class ViewMaterialRequest extends ViewRecord
{
    protected static string $resource = MaterialRequestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Section::make('Informasi Permintaan')
                    ->icon('heroicon-o-information-circle')
                    ->iconColor('primary')
                    ->schema([
                        TextEntry::make('construction.construction_name')
                            ->label('Proyek'),

                        TextEntry::make('requestedBy.name')
                            ->label('Diajukan Oleh'),

                        TextEntry::make('notes')
                            ->label('Catatan')
                            ->default('Tidak ada catatan.'),

                        TextEntry::make('created_at')
                            ->label('Tanggal Permintaan')
                            ->badge()
                            ->color('info')
                            ->date('d M Y'),
                    ])
                    ->columns(2),
            ]);
    }
}
