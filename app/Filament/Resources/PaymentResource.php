<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;

use Filament\Forms\Get;
use Filament\Support\RawJs;
use App\Models\{Payment, Construction};
use Filament\Notifications\Notification;

use Illuminate\Database\Eloquent\{Builder, SoftDeletingScope};
use Filament\Tables\Filters\{Filter, SelectFilter, TrashedFilter};
use App\Filament\Resources\PaymentResource\{Pages, RelationManagers};
use Filament\Tables\Columns\{TextColumn, BadgeColumn, IconColumn, ImageColumn};
use Filament\Forms\Components\{Select, DatePicker, TextInput, Textarea, FileUpload, Section, Grid};
use Filament\Tables\Actions\{Action, ActionGroup, ViewAction, EditAction, DeleteAction, BulkActionGroup, DeleteBulkAction, RestoreAction, ForceDeleteAction};

class PaymentResource extends Resource
{
    protected static ?string $model = Payment::class;
    protected static ?string $label = 'Laporan Pembayaran';
    protected static ?string $navigationLabel = 'Pembayaran';
    protected static ?string $navigationGroup = 'Laporan & Keuangan';
    protected static ?int $navigationSort = 3;
    protected static ?string $navigationIcon = 'heroicon-o-banknotes';
    protected static ?string $activeNavigationIcon = 'heroicon-s-banknotes';
    protected static ?string $recordTitleAttribute = 'reference_number';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Informasi Pembayaran')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Select::make('construction_id')
                                    ->label('Proyek')
                                    ->native(false)
                                    ->options(Construction::query()->orderBy('construction_name')->pluck('construction_name', 'id'))
                                    ->searchable()
                                    ->required()
                                    ->live()
                                    ->columnSpan(1),

                                DatePicker::make('payment_date')
                                    ->label('Tanggal Pembayaran')
                                    ->native(false)
                                    ->default(now())
                                    ->required()
                                    ->columnSpan(1),
                            ]),

                        Grid::make(2)
                            ->schema([
                                TextInput::make('amount')
                                    ->label('Jumlah Pembayaran')
                                    ->prefix('Rp')
                                    ->mask(RawJs::make(<<<'JS'
                                        $money($input, '.', ',', 0)
                                    JS))
                                    ->stripCharacters(',')
                                    ->numeric()
                                    ->required()
                                    ->columnSpan(1),

                                TextInput::make('remaining_payment')
                                    ->label('Sisa Pembayaran')
                                    ->prefix('Rp')
                                    ->disabled()
                                    ->default(function (Get $get) {
                                        $constructionId = $get('construction_id');
                                        if (!$constructionId)
                                            return null;

                                        $construction = Construction::find($constructionId);
                                        return $construction ? number_format($construction->remaining_payment, 0, ',', '.') : null;
                                    })
                                    ->columnSpan(1),
                            ]),

                        Grid::make(2)
                            ->schema([
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
                                    ->live()
                                    ->columnSpan(1),

                                Select::make('status')
                                    ->label('Status Pembayaran')
                                    ->native(false)
                                    ->options([
                                        'lunas' => 'Lunas',
                                        'belum_lunas' => 'Belum Lunas',
                                        'cicilan' => 'Cicilan',
                                    ])
                                    ->required()
                                    ->columnSpan(1),
                            ]),

                        Grid::make(2)
                            ->schema([
                                TextInput::make('installment_number')
                                    ->label('Cicilan Ke-')
                                    ->numeric()
                                    ->visible(fn(Get $get) => $get('payment_type') === 'credit_tempo')
                                    ->columnSpan(1),

                                DatePicker::make('due_date')
                                    ->label('Jatuh Tempo')
                                    ->native(false)
                                    ->visible(fn(Get $get) => $get('payment_type') === 'credit_tempo')
                                    ->columnSpan(1),
                            ]),

                        TextInput::make('reference_number')
                            ->label('No. Referensi / Bukti Transfer')
                            ->default(fn() => 'PAY-' . now()->format('YmdHis') . '-' . strtoupper(substr(uniqid(), -5)))
                            ->disabled()
                            ->dehydrated()
                            ->required(),
                    ]),

                Section::make('Dokumentasi')
                    ->schema([
                        FileUpload::make('proof_file')
                            ->label('Bukti Pembayaran')
                            ->disk('public')
                            ->directory('proofs')
                            ->image()
                            ->imageEditor()
                            ->downloadable()
                            ->openable()
                            ->nullable()
                            ->columnSpanFull(),

                        Textarea::make('note')
                            ->label('Catatan Tambahan')
                            ->rows(3)
                            ->nullable()
                            ->columnSpanFull(),
                    ])
                    ->collapsible(),
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
                    ->searchable()
                    ->sortable(),

                TextColumn::make('construction.construction_name')
                    ->label('Proyek')
                    ->searchable()
                    ->sortable()
                    ->url(fn(Payment $record) => ConstructionResource::getUrl('edit', ['record' => $record->construction_id]))
                    ->openUrlInNewTab(),

                TextColumn::make('payment_date')
                    ->label('Tanggal Bayar')
                    ->date('d M Y')
                    ->sortable(),

                TextColumn::make('amount')
                    ->label('Jumlah')
                    ->money('IDR')
                    ->sortable()
                    ->summarize([
                        Tables\Columns\Summarizers\Sum::make()
                            ->money('IDR')
                            ->label('Total')
                    ]),

                BadgeColumn::make('payment_type')
                    ->label('Tipe')
                    ->colors([
                        'primary' => 'cash',
                        'success' => 'transfer',
                        'warning' => 'credit_tempo',
                        'gray' => 'lainnya',
                        'info' => 'qris',
                    ])
                    ->formatStateUsing(fn(string $state): string => match (strtolower($state)) {
                        'cash' => 'Cash',
                        'transfer' => 'Transfer',
                        'credit_tempo' => 'Credit',
                        'qris' => 'QRIS',
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
                    ->date('d M Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                ImageColumn::make('proof_file')
                    ->label('Bukti')
                    ->toggleable()
                    ->circular()
                    ->defaultImageUrl(url('/images/no-image.png')),
            ])
            ->filters([
                SelectFilter::make('payment_type')
                    ->label('Tipe Pembayaran')
                    ->options([
                        'cash' => 'Cash',
                        'transfer' => 'Transfer Bank',
                        'credit_tempo' => 'Credit / Tempo',
                        'qris' => 'QRIS / eWallet',
                        'lainnya' => 'Lainnya',
                    ])
                    ->native(false),

                SelectFilter::make('status')
                    ->label('Status Pembayaran')
                    ->options([
                        'lunas' => 'Lunas',
                        'belum_lunas' => 'Belum Lunas',
                        'cicilan' => 'Cicilan',
                    ])
                    ->native(false),

                SelectFilter::make('construction_id')
                    ->label('Proyek')
                    ->relationship('construction', 'construction_name')
                    ->searchable()
                    ->preload()
                    ->native(false),

                TrashedFilter::make()
                    ->native(false)
                    ->label('Arsip'),
            ])
            ->filtersFormColumns(2)
            ->filtersTriggerAction(fn(Action $action) => $action
                ->label('Filter')
                ->button()
                ->icon('heroicon-m-funnel')
                ->size('sm'))
            ->actions([
                Action::make('previewInvoice')
                    ->label('Lihat Invoice')
                    ->icon('heroicon-m-newspaper')
                    ->color('success')
                    ->url(fn(Payment $record) => route('invoice.generate', $record))
                    ->openUrlInNewTab(),
                ActionGroup::make([
                    ViewAction::make()
                        ->label('Detail')
                        ->icon('heroicon-o-eye')
                        ->color('info'),

                    EditAction::make()
                        ->label('Edit')
                        ->icon('heroicon-o-pencil-square')
                        ->color('warning'),

                    DeleteAction::make()
                        ->label('Arsipkan')
                        ->icon('heroicon-o-archive-box')
                        ->modalHeading('Arsipkan Pembayaran Ini?')
                        ->modalDescription('Data tidak akan dihapus, hanya disembunyikan.')
                        ->successNotification(
                            Notification::make()
                                ->success()
                                ->icon('heroicon-o-check-circle')
                                ->title('Pembayaran Telah Diarsipkan')
                                ->body('Data pembayaran telah berhasil dipindahkan ke arsip.')
                        ),

                    RestoreAction::make()
                        ->label('Pulihkan')
                        ->icon('heroicon-o-arrow-up-on-square'),

                    ForceDeleteAction::make()
                        ->label('Hapus Permanen')
                        ->icon('heroicon-o-trash')
                        ->color('danger'),
                ])
                    ->color('primary'),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->label('Arsipkan yang dipilih')
                        ->icon('heroicon-o-archive-box'),

                    Tables\Actions\RestoreBulkAction::make()
                        ->label('Pulihkan yang dipilih'),

                    Tables\Actions\ForceDeleteBulkAction::make()
                        ->label('Hapus permanen yang dipilih'),
                ]),
            ])
            ->defaultSort('payment_date', 'desc')
            ->groups([
                Tables\Grouping\Group::make('payment_date')
                    ->label('Tanggal Pembayaran')
                    ->date()
                    ->collapsible(),

                Tables\Grouping\Group::make('construction.construction_name')
                    ->label('Proyek')
                    ->collapsible(),
            ]);
            // ->groupSettingsTriggerAction(
            //     fn(Action $action) => $action
            //         ->label('Pengelompokan')
            //         ->icon('heroicon-m-squares-2x2')
            //         ->size('sm')
            // );
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

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['reference_number', 'construction.construction_name'];
    }
}