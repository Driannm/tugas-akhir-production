<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Get;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Notifications\Notification;
use Filament\Support\RawJs;

use App\Models\{Payment, Construction};
use Filament\Tables\Filters\{Filter, SelectFilter, TrashedFilter};
use Illuminate\Database\Eloquent\{Builder, SoftDeletingScope};
use Filament\Tables\Columns\{TextColumn, BadgeColumn, IconColumn};
use App\Filament\Resources\PaymentResource\{Pages, RelationManagers};
use Filament\Forms\Components\{Select, DatePicker, TextInput, Textarea, FileUpload};
use Filament\Tables\Actions\{Action, ActionGroup, ViewAction, EditAction, DeleteAction, BulkActionGroup, DeleteBulkAction};

class PaymentResource extends Resource
{
    protected static ?string $model = Payment::class;
    protected static ?string $label = 'Laporan Pembayaran';
    protected static ?string $navigationLabel = 'Pembayaran';
    protected static ?string $navigationGroup = 'Laporan & Keuangan';
    protected static ?int $navigationSort = 3;
    protected static ?string $navigationIcon = 'heroicon-o-banknotes';
    protected static ?string $activeNavigationIcon = 'heroicon-s-banknotes';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('construction_id')
                    ->label('Proyek')
                    ->native(false)
                    ->options(Construction::all()->pluck('construction_name', 'id'))
                    ->searchable()
                    ->required(),

                DatePicker::make('payment_date')
                    ->label('Tanggal Pembayaran')
                    ->native(false)
                    ->required(),

                TextInput::make('amount')
                    ->label('Jumlah Pembayaran')
                    ->prefix('Rp')
                    ->mask(RawJs::make(<<<'JS'
                                $money($input, '.', ',', 0)
                            JS))
                    ->stripCharacters(',')
                    ->numeric()
                    ->required(),

                TextInput::make('remaining_payment')
                    ->label('Sisa Pembayaran')
                    ->prefix('Rp')
                    ->disabled()
                    ->default(function (Get $get) {
                        $constructionId = $get('construction_id');
                        if (!$constructionId)
                            return null;

                        $construction = Construction::find($constructionId);
                        if (!$construction)
                            return null;

                        return number_format($construction->remaining_payment, 0, ',', '.');
                    })
                    ->visible(fn(Get $get) => $get('construction_id') !== null),


                Select::make('payment_type')
                    ->label('Tipe Pembayaran')
                    ->native(false)
                    ->options([
                        'cash' => 'Cash',
                        'credit_tempo' => 'Credit / Tempo',
                        'transfer' => 'Transfer Bank',
                        'qris' => 'QRIS / eWallet',
                        'lainnya' => 'Lainnya',
                    ])
                    ->required()
                    ->reactive(),

                Select::make('status')
                    ->label('Status Pembayaran')
                    ->native(false)
                    ->options([
                        'lunas' => 'Lunas',
                        'belum_lunas' => 'Belum Lunas',
                        'cicilan' => 'Cicilan',
                    ])
                    ->required(),

                TextInput::make('installment_number')
                    ->label('Cicilan Ke-')
                    ->numeric()
                    ->visible(fn(Get $get) => $get('payment_type') === 'credit_tempo'),

                DatePicker::make('due_date')
                    ->label('Jatuh Tempo')
                    ->native(false)
                    ->visible(fn(Get $get) => $get('payment_type') === 'credit_tempo'),

                TextInput::make('reference_number')
                    ->label('No. Referensi / Bukti Transfer')
                    ->default(fn() => 'PAY-' . now()->format('Ymd') . '-' . str_pad(rand(1, 99999), 5, '0', STR_PAD_LEFT))
                    ->disabled()
                    ->dehydrated()
                    ->required(),


                FileUpload::make('proof_file')
                    ->label('Bukti Pembayaran')
                    ->disk('public')
                    ->directory('proofs')
                    ->nullable(),

                Textarea::make('note')
                    ->label('Catatan Tambahan')
                    ->rows(3)
                    ->nullable(),
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
                        'transfer' => 'Tranfers',
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
                TrashedFilter::make()
                    ->native(false),
            ])
            ->filtersFormColumns(1)
            ->filtersTriggerAction(fn(Action $action) => $action->label('Filter')->button()->icon('heroicon-m-funnel'))
            ->actions([
                ActionGroup::make([
                    EditAction::make()
                        ->label('Edit')
                        ->icon('heroicon-o-pencil-square')
                        ->color('warning'),

                    ViewAction::make()
                        ->label('Detail')
                        ->icon('heroicon-o-eye')
                        ->color('info'),

                    DeleteAction::make()
                        ->label('Arsipkan')
                        ->icon('heroicon-o-arrow-down-on-square')
                        ->modalHeading('Arsipkan Pembayaran Ini?')
                        ->modalDescription('Data tidak akan dihapus, hanya disembunyikan.')
                        ->successNotification(Notification::make()
                            ->success()
                            ->icon('heroicon-o-check-circle')
                            ->title('Pembayaran Telah Diarsipkan')
                            ->body('Data pembayaran telah berhasil dipindahkan ke arsip.'))
                ])
            ])
            ->bulkActions([
                BulkActionGroup::make([

                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPayments::route('/'),
            'create' => Pages\CreatePayment::route('/create'),
            'view' => Pages\ViewPayment::route('/{record}'),
            'edit' => Pages\EditPayment::route('/{record}/edit'),
        ];
    }
}

