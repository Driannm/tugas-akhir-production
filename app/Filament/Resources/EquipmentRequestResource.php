<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;
use Filament\Notifications\Notification;

use Filament\Tables\Columns\{TextColumn, BadgeColumn};
use Illuminate\Database\Eloquent\{SoftDeletingScope, Builder};
use Filament\Tables\Actions\{EditAction, DeleteAction, ActionGroup, ViewAction};
use App\Models\{Equipment, Construction, EquipmentRequest, EquipmentRequestItem};
use App\Filament\Resources\EquipmentRequestResource\{Pages, RelationManagers};
use Filament\Forms\Components\{Select, Textarea, TextInput, Hidden, Repeater, Section, DatePicker};
use App\Filament\Resources\EquipmentRequestResource\RelationManagers\EquipmentRelationManager;

class EquipmentRequestResource extends Resource
{
    protected static ?string $model = EquipmentRequest::class;
    protected static ?string $label = 'Peminjaman Peralatan';
    protected static ?string $navigationLabel = 'Peminjaman Peralatan';
    protected static ?string $navigationGroup = 'Manajemen Proyek';
    protected static ?int $navigationSort = 3;
    protected static ?string $navigationIcon = 'heroicon-o-wrench-screwdriver';
    protected static ?string $navigationBadgeTooltip = 'Jumlah permintaan peralatan yang menunggu persetujuan';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Cart')
                    ->description('The items you have selected for purchase')
                    ->icon('heroicon-m-shopping-bag')
                    ->iconColor('primary')
                    ->schema([
                        Select::make('construction_id')
                            ->label('Proyek')
                            ->placeholder('Pilih proyek...')
                            ->options(
                                fn() =>
                                Construction::where('supervisor_id', auth()->id())
                                    ->where('status_construction', '!=', 'selesai')
                                    ->pluck('construction_name', 'id')
                            )
                            ->required()
                            ->searchable(),
                        Textarea::make('notes')
                            ->label('Catatan Tambahan')
                            ->placeholder('Masukkan catatan jika diperlukan...')
                            ->rows(1),
                    ])
                    ->collapsed()
                    ->columns(2),

                Repeater::make('items')
                    ->relationship('items')
                    ->label('Peralatan Dipinjam')
                    ->schema([
                        Select::make('equipment_id')
                            ->label('Pilih Peralatan')
                            ->options(
                                Equipment::where('status', 'available')
                                    ->pluck('name', 'id')
                            )
                            ->searchable()
                            ->required()
                            ->reactive()
                            ->afterStateUpdated(function (callable $set, callable $get, $state) {
                                if ($state) {
                                    $equipment = Equipment::find($state);
                                    if ($equipment) {
                                        $set('available_stock', $equipment->quantity);
                                        $set('last_maintenance', $equipment->last_maintenance ? $equipment->last_maintenance->format('Y-m-d') : null);
                                    }
                                }
                            }),

                        TextInput::make('quantity')
                            ->label('Jumlah Pinjam')
                            ->placeholder('Contoh: 5')
                            ->numeric()
                            ->minValue(1)
                            ->required()
                            ->afterStateUpdated(function (callable $set, callable $get, $state, $context) {
                                $equipmentId = $get('equipment_id');

                                if ($equipmentId) {
                                    $equipment = Equipment::find($equipmentId);
                                    if ($equipment && $state > $equipment->quantity) {

                                        $set('quantity', $equipment->quantity);

                                        Notification::make()
                                            ->title('Jumlah Melebihi Stok')
                                            ->body("Jumlah pinjaman melebihi stok tersedia ({$equipment->quantity}). Jumlah otomatis disesuaikan.")
                                            ->danger()
                                            ->persistent()
                                            ->send();
                                    }
                                }
                            }),

                        TextInput::make('available_stock')
                            ->label('Stok Tersedia')
                            ->disabled()
                            ->dehydrated(false),

                        DatePicker::make('last_maintenance')
                            ->label('Terakhir Maintenance')
                            ->disabled()
                            ->format('d/F/Y')
                            ->default(fn($get) => $get('last_maintenance'))
                            ->dehydrated(false),
                    ])
                    ->createItemButtonLabel('Tambah Peralatan')
                    ->columns(2)
                    ->columnSpanFull(),

                Hidden::make('requested_by')
                    ->default(fn() => Auth::id())
                    ->dehydrated(true),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultPaginationPageOption(10)
            ->paginationPageOptions([10, 25, 50])
            ->emptyStateHeading('Belum ada peminjaman peralatan')
            ->emptyStateDescription('Buat peminjaman peralatan baru untuk mendukung kebutuhan proyek.')
            ->emptyStateIcon('heroicon-o-wrench-screwdriver')
            ->columns([
                TextColumn::make('construction.construction_name')
                    ->label('Proyek')
                    ->wrap()
                    ->searchable()
                    ->sortable(),

                TextColumn::make('requestedBy.name')
                    ->label('Supervisor')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('created_at')
                    ->label('Tanggal Permintaan')
                    ->dateTime('d M Y')
                    ->sortable(),

                BadgeColumn::make('overall_status')
                    ->label('Status')
                    ->getStateUsing(fn($record) => $record->overall_status)
                    ->formatStateUsing(fn($state) => match ($state) {
                        'pending' => 'Menunggu Persetujuan',
                        'approved' => 'Disetujui',
                        'rejected' => 'Ditolak',
                    })
                    ->icon(fn(string $state): ?string => match (strtolower($state)) {
                        'approved' => 'heroicon-m-check-circle',
                        'pending' => 'heroicon-m-exclamation-circle',
                        'rejected' => 'heroicon-m-x-circle',
                        default => null,
                    })
                    ->colors([
                        'warning' => 'pending',
                        'success' => 'approved',
                        'danger' => 'rejected',
                    ])
                    ->sortable(),
            ])
            ->filters([
                //
            ])
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
                        ->label('Hapus')
                        ->icon('heroicon-o-trash')
                        ->color('danger'),
                ])->color('primary'),
            ])
            ->bulkActions([
                //
            ]);
    }

    public static function getRelations(): array
    {
        return [
            EquipmentRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListEquipmentRequests::route('/'),
            'create' => Pages\CreateEquipmentRequest::route('/create'),
            'view' => Pages\ViewEquipmentRequest::route('/{record}'),
            'edit' => Pages\EditEquipmentRequest::route('/{record}/edit'),
        ];
    }
}
