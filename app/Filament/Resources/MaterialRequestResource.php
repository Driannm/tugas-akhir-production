<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use App\Filament\Resources\MaterialRequestResource\RelationManagers\MaterialRelationManager;


use App\Models\{Material, MaterialRequest, Construction};
use Filament\Forms\{Get, Form, Set};
use Filament\Tables\Columns\{TextColumn};
use Filament\Tables\Filters\{Filter, SelectFilter};
use Illuminate\Database\Eloquent\{Builder, SoftDeletingScope};
use App\Filament\Resources\MaterialRequestResource\{RelationManagers, Pages};
use Filament\Forms\Components\{Select, Textarea, Repeater, Hidden, TextInput, Radio};
use Filament\Tables\Actions\{Action, ActionGroup, ViewAction, EditAction, DeleteAction, BulkActionGroup, DeleteBulkAction, CreateAction};

class MaterialRequestResource extends Resource
{
    protected static ?string $model = MaterialRequest::class;

    protected static ?string $label = 'Permintaan Material';
    protected static ?string $navigationLabel = 'Permintaan Material';
    protected static ?string $navigationGroup = 'Manajemen Proyek';
    protected static ?int $navigationSort = 1;
    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-check';
    protected static ?string $activeNavigationIcon = 'heroicon-s-clipboard-document-check';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('construction_id')
                    ->label('Proyek')
                    ->native(false)
                    ->relationship('construction', 'construction_name')
                    ->required()
                    ->options(fn() => auth()->user()->is_admin == 1
                        ? Construction::where('supervisor_id', auth()->id())->pluck('construction_name', 'id')
                        : Construction::pluck('construction_name', 'id'))
                    ->placeholder('Pilih proyek yang sesuai')
                    ->searchable()
                    ->hint('Pilih proyek tempat material akan digunakan.'),

                Textarea::make('notes')
                    ->label('Catatan Tambahan')
                    ->rows(1)
                    ->placeholder('Tulis catatan jika ada informasi tambahan...')
                    ->maxLength(500),

                Repeater::make('materialRequestItems')
                    ->relationship()
                    ->label('Permintaan Material')
                    ->schema([
                        Select::make('material_id')
                            ->label('Material')
                            ->native(false)
                            ->relationship('material', 'material_name')
                            ->required()
                            ->placeholder('Pilih material')
                            ->reactive()
                            ->afterStateUpdated(function ($state, Set $set) {
                                $material = Material::find($state);
                                $set('material_stock', $material?->stock_quantity ?? 'Tidak ditemukan');
                            }),

                        TextInput::make('material_stock')
                            ->label('Stok Tersedia')
                            ->disabled()
                            ->dehydrated(false)
                            ->default(fn(Get $get) => optional(Material::find($get('material_id')))->stock_quantity)
                            ->suffix(fn(Get $get) => optional(Material::find($get('material_id')))->stock_quantity < 10
                                ? '🟥 Stok Rendah'
                                : '🟩 Stok Cukup')
                            ->hint('Informasi stok saat ini untuk material yang dipilih.'),

                        TextInput::make('quantity')
                            ->label('Jumlah Diminta')
                            ->numeric()
                            ->required()
                            ->placeholder('Masukkan jumlah yang dibutuhkan')
                            ->minValue(1)
                            ->rules([
                                function (Get $get) {
                                    return function ($attribute, $value, $fail) use ($get) {
                                        $material = Material::find($get('material_id'));
                                        if ($material && $value > $material->stock_quantity) {
                                            $fail('Jumlah melebihi stok yang tersedia.');
                                        }
                                    };
                                }
                            ]),

                        TextInput::make('notes')
                            ->label('Catatan Item')
                            ->placeholder('Contoh: Material untuk pekerjaan lantai...')
                            ->maxLength(200),
                    ])
                    ->createItemButtonLabel('Tambah Material')
                    ->columns(2)
                    ->columnSpanFull()
                    ->reorderable()
                    ->collapsible()
                    ->defaultItems(1)
                    ->visible(fn(?string $context) => $context === 'create'),

                Hidden::make('requested_by')
                    ->default(fn() => Auth::id())
                    ->dehydrated(true),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->emptyStateHeading(function () {
                // Periksa peran pengguna menggunakan Spatie Roles
                return auth()->user()->hasRole('super_admin')
                    ? 'Belum ada permintaan material dari supervisor'
                    : 'Belum ada permintaan material yang diajukan';
            })
            ->emptyStateDescription(function () {
                return auth()->user()->hasRole('super_admin')
                    ? 'Belum ada permintaan material yang diajukan oleh supervisor untuk proyek yang sedang dikelola.'
                    : 'Silakan buat permintaan material untuk mendukung kebutuhan proyek.';
            })
            ->emptyStateIcon('heroicon-o-truck')
            ->columns([
                TextColumn::make('construction.construction_name')
                    ->label('Proyek')
                    ->wrap()
                    ->sortable()
                    ->searchable(),

                TextColumn::make('requestedBy.name')
                    ->label('Supervisor')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('created_at')
                    ->label('Tanggal Permintaan')
                    ->dateTime('d M Y')
                    ->sortable(),

                TextColumn::make('overall_status')
                    ->label('Status')
                    ->badge()
                    ->color(fn(string $state): string => match (strtolower($state)) {
                        'pending' => 'warning',
                        'approved' => 'success',
                        'rejected' => 'danger',
                        'mixed' => 'gray',
                        default => 'gray',
                    })
                    ->icon(fn(string $state): ?string => match (strtolower($state)) {
                        'approved' => 'heroicon-m-check-circle',
                        'pending' => 'heroicon-m-exclamation-circle',
                        'rejected' => 'heroicon-m-x-circle',
                        default => null,
                    })
                    ->formatStateUsing(fn(string $state): string => match (strtolower($state)) {
                        'pending' => 'Menunggu Persetujuan',
                        'approved' => 'Disetujui',
                        'rejected' => 'Ditolak',
                        'mixed' => 'Campuran',
                        default => ucfirst($state),
                    }),
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
            ]);
    }


    public static function getRelations(): array
    {
        return [
            MaterialRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMaterialRequests::route('/'),
            'create' => Pages\CreateMaterialRequest::route('/create'),
            'view' => Pages\ViewMaterialRequest::route('/{record}'),
            'edit' => Pages\EditMaterialRequest::route('/{record}/edit'),
        ];
    }
}
