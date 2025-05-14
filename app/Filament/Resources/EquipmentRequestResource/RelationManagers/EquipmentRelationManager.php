<?php

namespace App\Filament\Resources\EquipmentRequestResource\RelationManagers;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;

use Filament\Notifications\Notification;
use Filament\Tables\Columns\{TextColumn, BadgeColumn};
use Filament\Resources\RelationManagers\RelationManager;
use Illuminate\Database\Eloquent\{SoftDeletingScope, Builder};
use Filament\Forms\Components\{DatePicker, TextArea, TextInput, Select};
use Filament\Tables\Actions\{ActionGroup, Action, BulkAction, BulkActionGroup, DeleteBulkAction};

class EquipmentRelationManager extends RelationManager
{
    protected static string $relationship = 'items';
    protected static ?string $title = 'Peralatan yang dipinjam';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('equipment_id')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('equipment.name')
            ->columns([
                TextColumn::make('equipment.name')
                    ->label('Nama Peralatan'),

                TextColumn::make('equipment.quantity')
                    ->label('Stok Peralatan'),

                TextColumn::make('quantity')
                    ->label('Jumlah Dipinjam')
                    ->sortable(),

                BadgeColumn::make('status')
                    ->label('Status')
                    ->searchable()
                    ->formatStateUsing(fn($state) => match ($state) {
                        'pending' => 'Menunggu',
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
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),

                // ✅ Tambahan: Setujui Semua
                Action::make('accept_all')
                    ->label('Setujui Semua')
                    ->icon('heroicon-s-check-circle')
                    ->color(
                        fn($livewire) =>
                        $livewire->getOwnerRecord()->items()->where('status', 'pending')->count() === 0 ? 'gray' : 'success'
                    )
                    ->requiresConfirmation()
                    ->disabled(
                        fn($livewire) =>
                        $livewire->getOwnerRecord()->items()->where('status', 'pending')->count() === 0
                    )
                    ->action(function ($livewire) {
                        $record = $livewire->getOwnerRecord();

                        $record->items()
                            ->where('status', 'pending')
                            ->get()
                            ->each(fn($item) => $item->update(['status' => 'approved']));

                        Notification::make()
                            ->title('Permintaan Disetujui')
                            ->body('Semua permintaan peralatan yang menunggu telah disetujui.')
                            ->success()
                            ->send();
                    })
                    ->visible(fn() => auth()->user()?->hasRole('super_admin')),

                // ✅ Tambahan: Tolak Semua
                Action::make('reject_all')
                    ->label('Tolak Semua')
                    ->icon('heroicon-s-x-circle')
                    ->color(
                        fn($livewire) =>
                        $livewire->getOwnerRecord()->items()->where('status', 'pending')->count() === 0 ? 'gray' : 'danger'
                    )
                    ->requiresConfirmation()
                    ->disabled(
                        fn($livewire) =>
                        $livewire->getOwnerRecord()->items()->where('status', 'pending')->count() === 0
                    )
                    ->action(function ($livewire) {
                        $record = $livewire->getOwnerRecord();

                        $record->items()
                            ->where('status', 'pending')
                            ->get()
                            ->each(fn($item) => $item->update(['status' => 'rejected']));

                        Notification::make()
                            ->title('Permintaan Ditolak')
                            ->body('Semua permintaan peralatan yang menunggu telah ditolak.')
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
                                ->body("Permintaan peralatan '{$record->equipment->name}' telah disetujui.")
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
                                ->body("Permintaan peralatan '{$record->equipment->name}' telah ditolak.")
                                ->danger()
                                ->send();
                        })
                        ->color('danger')
                        ->hidden(fn($record) => $record->status !== 'pending'),
                ]),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

}
