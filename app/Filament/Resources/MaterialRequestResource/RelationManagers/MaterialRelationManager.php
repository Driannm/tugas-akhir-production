<?php

namespace App\Filament\Resources\MaterialRequestResource\RelationManagers;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;

use App\Models\Material;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\{TextColumn, BadgeColumn};
use Filament\Resources\RelationManagers\RelationManager;
use Illuminate\Database\Eloquent\{SoftDeletingScope, Builder};
use Filament\Forms\Components\{DatePicker, TextArea, TextInput, Select};
use Filament\Tables\Actions\{ActionGroup, Action, BulkAction, BulkActionGroup, DeleteBulkAction};

class MaterialRelationManager extends RelationManager
{
    protected static string $relationship = 'materialRequestItems';
    protected static ?string $title = 'Material yang Diminta';


    public function form(Form $form): Form
    {
        return $form->schema([
            Select::make('material_id')
                ->label('Pilih Material')
                ->options(Material::pluck('material_name', 'id'))
                ->required()
                ->reactive()    
                ->afterStateUpdated(function (callable $set, callable $get) {
                    $materialId = $get('material_id');
                    if ($materialId) {
                        $material = Material::find($materialId);
                        $set('available_stock', $material ? $material->stock_quantity : 0);
                    }
                }),

            TextInput::make('available_stock')
                ->label('Stok Tersedia')
                ->disabled()
                ->default(0),

            TextInput::make('quantity')
                ->label('Jumlah Diminta')
                ->numeric()
                ->minValue(1)
                ->required()
                ->afterStateUpdated(function (callable $set, callable $get, $state) {
                    $materialId = $get('material_id');
                    if ($materialId) {
                        $material = Material::find($materialId);
                        if ($material && $state > $material->stock_quantity) {
                            $set('quantity', $material->stock_quantity);
                            Notification::make()
                                ->title('Jumlah Melebihi Stok')
                                ->body("Jumlah permintaan melebihi stok yang tersedia ({$material->stock_quantity}). Jumlah otomatis disesuaikan.")
                                ->danger()
                                ->persistent()
                                ->send();
                        }
                    }
                }),

            TextArea::make('notes')
                ->label('Catatan Tambahan')
                ->rows(2),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('material.material_name')
            ->columns([
                TextColumn::make('material.material_name')
                    ->label('Nama Material'),

                TextColumn::make('material.stock_quantity')
                    ->label('Stok Material'),

                TextColumn::make('quantity')
                    ->label('Jumlah Diminta')
                    ->sortable(),

                BadgeColumn::make('status')
                    ->label('Status')
                    ->searchable()
                    ->formatStateUsing(fn($state) => match ($state) {
                        'pending' => 'Menunggu Persetujuan',
                        'approved' => 'Disetujui',
                        'rejected' => 'Ditolak',
                        default => ucfirst($state),
                    })
                    ->colors([
                        'warning' => 'pending',
                        'success' => 'approved',
                        'danger' => 'rejected',
                    ])
                    ->sortable(),

                TextColumn::make('created_at')
                    ->label('Tanggal Permintaan')
                    ->dateTime('d M Y')
                    ->sortable(),
            ])
            ->filters([

            ])
            ->headerActions([
                Action::make('accept_all')
                    ->label('Setujui Semua')
                    ->icon('heroicon-s-check-circle')
                    ->color(fn() => $this->ownerRecord->materialRequestItems()->where('status', 'pending')->count() == 0 ? 'gray' : 'success')
                    ->requiresConfirmation()
                    ->disabled(fn() => $this->ownerRecord->materialRequestItems()->where('status', 'pending')->count() == 0)
                    ->action(function () {
                        $this->ownerRecord->materialRequestItems()
                            ->where('status', 'pending')
                            ->get()
                            ->each(function ($item) {
                                $item->update(['status' => 'approved']);
                            });

                        Notification::make()
                            ->title('Permintaan Disetujui')
                            ->body('Semua permintaan material yang sedang menunggu telah disetujui. Harap pastikan ketersediaan material sebelum digunakan.')
                            ->success()
                            ->send();
                    })
                    ->visible(fn() => auth()->user()?->hasRole('super_admin')),

                Action::make('reject_all')
                    ->label('Tolak Semua')
                    ->icon('heroicon-s-x-circle')
                    ->color(fn() => $this->ownerRecord->materialRequestItems()->where('status', 'pending')->count() == 0 ? 'gray' : 'danger')
                    ->requiresConfirmation()
                    ->disabled(fn() => $this->ownerRecord->materialRequestItems()->where('status', 'pending')->count() == 0)
                    ->action(function () {
                        $this->ownerRecord->materialRequestItems()
                            ->where('status', 'pending')
                            ->get()
                            ->each(function ($item) {
                                $item->update(['status' => 'rejected']);
                            });

                        Notification::make()
                            ->title('Permintaan Ditolak')
                            ->body('Semua permintaan material yang masih menunggu telah ditolak. Untuk pertanyaan atau klarifikasi, silakan hubungi pihak yang bertanggung jawab sesuai prosedur.')
                            ->danger()
                            ->send();
                    })
                    ->visible(fn() => auth()->user()?->hasRole('super_admin')),
            ])
            ->actions([
                ActionGroup::make([
                    Action::make('approve')
                        ->label('Setujui')
                        ->icon('heroicon-s-check-circle')
                        ->action(function ($record) {
                            $record->update(['status' => 'approved']);
                            Notification::make()
                                ->title('Permintaan Disetujui')
                                ->body("Permintaan material '{$record->material->material_name}' telah disetujui. Harap pastikan ketersediaan material sebelum digunakan.")
                                ->success()
                                ->send();
                        })
                        ->color('success')
                        ->hidden(fn($record) => $record->status !== 'pending'),

                    Action::make('reject')
                        ->label('Tolak')
                        ->icon('heroicon-s-x-circle')
                        ->action(function ($record) {
                            $record->update(['status' => 'rejected']);
                            Notification::make()
                                ->title('Permintaan Ditolak')
                                ->body("Permintaan material '{$record->material->material_name}' tidak disetujui. Untuk pertanyaan atau klarifikasi, silakan hubungi pihak yang bertanggung jawab sesuai prosedur.")
                                ->danger()
                                ->send();
                        })
                        ->color('danger')
                        ->hidden(fn($record) => $record->status !== 'pending'),
                ])->color('primary'),
            ])
            ->bulkActions([
                //
            ]);
    }
}
