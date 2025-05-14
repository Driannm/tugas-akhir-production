<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Notifications\Notification;

use App\Models\Payment;

use Filament\Tables\Filters\{Filter, SelectFilter, TrashedFilter};
use Illuminate\Database\Eloquent\{Builder, SoftDeletingScope};
use Filament\Tables\Columns\{TextColumn, BadgeColumn, IconColumn};
use App\Filament\Resources\PaymentArchivedResource\{Pages, RelationManagers};
use Filament\Forms\Components\{Select, DatePicker, TextInput, Textarea, FileUpload};
use Filament\Tables\Actions\{Action, ActionGroup, ViewAction, EditAction, RestoreAction, BulkActionGroup, RestoreBulkAction};

class PaymentArchivedResource extends Resource
{
    protected static ?string $model = Payment::class;
    protected static ?string $label = 'Arsip Pembayaran';
    protected static ?string $navigationLabel = 'Arsip Pembayaran';
    protected static ?string $navigationGroup = 'Laporan & Keuangan';
    protected static ?int $navigationSort = 4;
    protected static ?string $navigationIcon = 'heroicon-o-inbox-arrow-down';
    protected static ?string $activeNavigationIcon = 'heroicon-s-inbox-arrow-down';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('reference_number')
                    ->label('No. Referensi')
                    ->badge()
                    ->color('primary')
                    ->searchable(),

                TextColumn::make('construction.construction_name')
                    ->label('Proyek')
                    ->searchable(),

                TextColumn::make('payment_date')
                    ->label('Tanggal Bayar')
                    ->date(),

                TextColumn::make('amount')
                    ->label('Jumlah')
                    ->money('IDR'),

                BadgeColumn::make('payment_type')
                    ->label('Tipe')
                    ->colors([
                        'primary' => 'cash',
                        'success' => 'transfer',
                        'warning' => 'credit_tempo',
                        'gray' => 'lainnya',
                    ])
                    ->formatStateUsing(fn(string $state): string => match (strtolower($state)) {
                        'cash' => 'Cash',
                        'transfer' => 'Transfer',
                        'credit_tempo' => 'Credit / Tempo',
                        'lainnya' => 'Lainnya',
                        default => ucfirst($state),
                    }),

                BadgeColumn::make('status')
                    ->label('Status')
                    ->colors([
                        'success' => 'lunas',
                        'danger' => 'belum_lunas',
                        'warning' => 'cicilan',
                    ])
                    ->formatStateUsing(fn(string $state): string => match (strtolower($state)) {
                        'lunas' => 'Lunas',
                        'belum_lunas' => 'Belum Lunas',
                        'cicilan' => 'Cicilan',
                        default => ucfirst($state),
                    }),

                TextColumn::make('due_date')
                    ->label('Jatuh Tempo')
                    ->date()
                    ->sortable()
                    ->toggleable(),

                IconColumn::make('proof_file')
                    ->label('Bukti')
                    ->toggleable()
                    ->boolean(fn($record) => !empty($record->proof_file)),
            ])
            ->filters([
                SelectFilter::make('payment_type')
                    ->options([
                        'cash' => 'Cash',
                        'credit_tempo' => 'Credit / Tempo',
                        'transfer' => 'Transfer Bank',
                        'qris' => 'QRIS / eWallet',
                        'lainnya' => 'Lainnya',
                    ])
                    ->label('Tipe Pembayaran')
                    ->native(false),

                SelectFilter::make('status')
                    ->options([
                        'lunas' => 'Lunas',
                        'belum_lunas' => 'Belum Lunas',
                        'cicilan' => 'Cicilan',
                    ])
                    ->label('Status')
                    ->native(false),
            ])
            ->filtersFormColumns(1)
            ->filtersTriggerAction(fn(Action $action) => $action->label('Filter')->button()->icon('heroicon-m-funnel'))
            ->actions([
                ActionGroup::make([
                    ViewAction::make()
                        ->label('Detail')
                        ->icon('heroicon-o-eye')
                        ->color('warning'),

                    RestoreAction::make()
                        ->label('Pulihkan')
                        ->icon('heroicon-o-arrow-path')
                        ->color('primary')
                        ->visible(fn($record) => $record->trashed())
                        ->successNotification(
                            Notification::make()
                                ->info()
                                ->icon('heroicon-o-check-circle')
                                ->title('Pembayaran Dipulihkan')
                                ->body('Data pembayaran telah berhasil dikembalikan dari arsip.')
                        ),
                ])
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    RestoreBulkAction::make()
                        ->successNotification(
                            Notification::make()
                                ->info()
                                ->icon('heroicon-o-check-circle')
                                ->title('Pembayaran Dipulihkan')
                                ->body('Data pembayaran telah berhasil dikembalikan dari arsip.')
                        ),
                ]),
            ]);
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->onlyTrashed();
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPaymentArchiveds::route('/'),
        ];
    }
}
