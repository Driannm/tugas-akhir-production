<?php

namespace App\Filament\Resources\DailyReportResource\Pages;

use Filament\Actions;
use Filament\Infolists\Infolist;
use Filament\Resources\Pages\ViewRecord;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\ImageEntry;
use App\Filament\Resources\DailyReportResource;


class ViewDailyReport extends ViewRecord
{
    protected static string $resource = DailyReportResource::class;

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
                Section::make('Proyek dan Tanggal')
                    ->icon('heroicon-m-calendar-days')
                    ->schema([
                        TextEntry::make('construction.construction_name')
                            ->label('Proyek'),

                        TextEntry::make('report_date')
                            ->label('Tanggal Laporan')
                            ->date('d F Y'),
                    ])
                    ->columns(2),

                Section::make('Keterangan dan Kendala')
                    ->icon('heroicon-m-document-text')
                    ->schema([
                        TextEntry::make('description')
                            ->label('Uraian Pekerjaan')
                            ->html()
                            ->bulleted()
                            ->columnSpan(1),

                        TextEntry::make('issues')
                            ->label('Kendala')
                            ->html()
                            ->bulleted()
                            ->columnSpan(1),
                    ])
                    ->columns(2),

                Section::make('Cuaca dan Status')
                    ->icon('heroicon-m-cloud')
                    ->schema([
                        TextEntry::make('weather')
                            ->label('Cuaca')
                            ->badge()
                            ->colors([
                                'primary' => 'cerah',
                                'gray' => 'mendung',
                                'danger' => 'hujan',
                            ])
                            ->formatStateUsing(fn($state) => ucfirst($state)),

                        TextEntry::make('status')
                            ->label('Status Pekerjaan')
                            ->badge()
                            ->colors([
                                'success' => 'completed',
                                'warning' => 'on_progress',
                                'danger' => 'delayed',
                            ])
                            ->formatStateUsing(fn($state) => match ($state) {
                                'on_progress' => 'Sedang Berjalan',
                                'completed' => 'Selesai',
                                'delayed' => 'Tertunda',
                                default => ucfirst($state),
                            }),

                            ImageEntry::make('photo')
                            ->label('Dokumentasi')
                            ->height(200)
                            ->stacked(),
                    ])
                    ->columns(3),
            ]);
    }
}
