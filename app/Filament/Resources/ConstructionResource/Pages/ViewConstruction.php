<?php

namespace App\Filament\Resources\ConstructionResource\Pages;

use Filament\Actions\EditAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;
use Filament\Actions\Action;
use App\Models\ProjectSummary;
use App\Filament\Resources\ConstructionResource;
use Filament\Forms\Components\{Section, TextInput};   
class ViewConstruction extends ViewRecord
{
    protected static string $resource = ConstructionResource::class;

    public function getHeaderActions(): array
    {
        return [
            Action::make('Generate Summary')
                ->label('Generate Summary')
                ->icon('heroicon-o-document-text')
                ->color('primary') 
                ->requiresConfirmation()
                ->action(function () {

                    $construction = $this->record;

                    $summary = ProjectSummary::create([
                        'construction_id' => $construction->id,
                        'date' => now(),
                        'description' => "Ringkasan otomatis proyek: {$construction->construction_name}",
                    ]);

                    foreach ($construction->workers as $worker) {
                        $summary->workers()->attach($worker->id, [
                            'start_date' => $worker->created_at,  
                            'end_date' => $construction->end_date ?? now(),
                        ]);
                    }

                    foreach ($construction->dailyReports as $report) {
                        $summary->dailyReports()->attach($report->id);
                    }

                    foreach ($construction->materialRequests as $request) {
                        $summary->materialRequests()->attach($request->id);
                    }

                    Notification::make()
                        ->title('Ringkasan Proyek Berhasil Dibuat')
                        ->body("Ringkasan proyek untuk *{$construction->construction_name}* telah berhasil digenerate. Klik tombol di bawah untuk melihat detail ringkasan.")
                        ->success()
                        ->actions([
                            \Filament\Notifications\Actions\Action::make('view')
                                ->label('Lihat Ringkasan')
                                ->button()
                                ->url(route('filament.main.resources.project-summaries.view', ['record' => $summary->id]), shouldOpenInNewTab: true),
                        ])
                        ->send();
                }),
        ];
    }

    public function getFormSchema(): array
    {
        return [
            Section::make('Informasi Umum')
                ->schema([
                    TextInput::make('construction_name')
                        ->label('Nama Proyek')
                        ->disabled()
                        ->value($this->record->construction_name),

                    TextInput::make('location')
                        ->label('Lokasi Proyek')
                        ->disabled()
                        ->value($this->record->location ?? 'Tidak ada lokasi'),

                    TextInput::make('start_date')
                        ->label('Tanggal Mulai')
                        ->disabled()
                        ->value($this->record->start_date)
                        ->date(),

                    TextInput::make('end_date')
                        ->label('Tanggal Selesai')
                        ->disabled()
                        ->value($this->record->end_date)
                        ->date(),
                ])
                ->collapsed(),

            Section::make('Status & Penanggung Jawab')
                ->schema([
                    TextInput::make('status_construction')
                        ->label('Status Proyek')
                        ->disabled()
                        ->value($this->record->status_construction)
                        ->badge()
                        ->color(fn($state) => match ($state) {
                            'selesai' => 'success',
                            'sedang_berlangsung' => 'info',
                            'dibatalkan' => 'danger',
                        })
                        ->formatStateUsing(fn($state) => match ($state) {
                            'sedang_berlangsung' => 'Sedang Berlangsung',
                            'selesai' => 'Selesai',
                            'dibatalkan' => 'Dibatalkan',
                        }),

                    TextInput::make('supervisor.name')
                        ->label('Supervisor')
                        ->disabled()
                        ->value($this->record->supervisor->name ?? 'Tidak ada supervisor'),
                ])
                ->collapsed(),
        ];
    }

    public function hasCombinedRelationManagerTabsWithContent(): bool
    {
        return true;
    }

    public function getContentTabLabel(): string|null
    {
        return 'Detail Proyek';
    }

    public function getContentTabIcon(): string|null
    {
        return 'heroicon-o-document-text';
    }
}
