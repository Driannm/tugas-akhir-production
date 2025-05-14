<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MaterialResource\Pages;
use App\Filament\Resources\MaterialResource\RelationManagers;
use App\Models\Material;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Support\RawJs;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Filament\Notifications\Notification;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Columns\{TextColumn, Stack, ImageColumn};
use Filament\Forms\Components\{Select, FileUpload, TextInput, Fieldset};
use Filament\Tables\Actions\{EditAction, ViewAction, DeleteAction, CreateAction, BulkActionGroup, ActionGroup, DeleteBulkAction};

class MaterialResource extends Resource
{
    protected static ?string $model = Material::class;
    protected static ?string $recordTitleAttribute = 'material_name';
    protected static ?string $label = 'Material';
    protected static ?string $navigationLabel = 'Stok Material';
    protected static ?string $navigationGroup = 'Manajemen Sumber Daya';
    protected static ?int $navigationSort = 4;
    protected static ?string $navigationIcon = 'heroicon-o-archive-box';
    protected static ?string $activeNavigationIcon = 'heroicon-s-archive-box';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Fieldset::make('Dokumentasi Material')
                    ->schema([
                        FileUpload::make('image')
                            ->disk('public')
                            ->directory('material/dokumentasi')
                            ->preserveFilenames()
                            ->image()
                            ->visibility('public')
                            ->maxSize(10240)
                            ->helperText('Ukuran file maksimal 10MB')
                            ->uploadingMessage('Sedang mengunggah dokumentasi...')
                            ->columnSpanFull(),
                    ])
                    ->columnSpanFull(),
                Fieldset::make('Detail Material')
                    ->schema([
                        TextInput::make('material_name')
                            ->label('Nama Material')
                            ->placeholder('Contoh: Semen Portland, Besi Beton, Batu Bata')
                            ->required()
                            ->maxLength(255),

                        TextInput::make('stock_quantity')
                            ->label('Jumlah Stok')
                            ->placeholder('Contoh: 100, 250, 50')
                            ->required()
                            ->maxLength(255),

                        TextInput::make('unit_price')
                            ->label('Harga per Satuan')
                            ->placeholder('Contoh: 15.000')
                            ->required()
                            ->mask(RawJs::make(<<<'JS'
                                $money($input, '.', ',', 0)
                            JS))
                            ->stripCharacters(',')
                            ->numeric()
                            ->prefix('Rp')
                            ->maxLength(255),

                        Select::make('unit')
                            ->label('Jenis Satuan')
                            ->native(false)
                            ->options([
                                'pcs' => 'Pcs',
                                'kg' => 'Kilogram',
                                'liter' => 'Liter',
                                'meter' => 'Meter',
                                'roll' => 'Roll',
                                'sak' => 'Sak',
                                'ton' => 'Ton',
                                'kubik' => 'Kubik',
                                'batang' => 'Batang',
                                'buah' => 'Buah',
                                'lembar' => 'Lembar',
                            ])
                            ->required(),
                    ])
                    ->columns(2)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->emptyStateHeading('Belum ada data material')
            ->emptyStateDescription('Tambahkan material baru untuk kebutuhan proyek konstruksi.')
            ->emptyStateIcon('heroicon-o-cube')
            ->columns([
                ImageColumn::make('image')
                    ->square()
                    ->toggleable(),
                TextColumn::make('material_name')
                    ->label('Nama Material')
                    ->searchable(),
                TextColumn::make('stock_quantity')
                    ->label('Jumlah Material')
                    ->sortable(),
                TextColumn::make('unit')
                    ->label('Satuan')
                    ->sortable()
                    ->badge()
                    ->color('primary')
                    ->formatStateUsing(fn($state) => Str::ucfirst($state)),
                TextColumn::make('unit_price')
                    ->label('Harga Satuan')
                    ->sortable()
                    ->formatStateUsing(fn($state) => 'Rp ' . number_format($state, 0, ',', '.')),

            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getGlobalSearchResultTitle($record): string
    {
        return $record->material_name;
    }

    public static function getGlobalSearchResultDetails($record): array
    {
        return [
            'Nama Material' => $record->material_name,
            'Jumlah Stok' => $record->stock_quantity,
            'Satuan' => ucfirst($record->unit),
            'Harga Satuan' => 'Rp ' . number_format($record->unit_price, 0, ',', '.'),
        ];
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['material_name', 'stock_quantity', 'unit', 'unit_price'];
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
            'index' => Pages\ListMaterials::route('/'),
            'create' => Pages\CreateMaterial::route('/create'),
            'view' => Pages\ViewMaterial::route('/{record}'),
            'edit' => Pages\EditMaterial::route('/{record}/edit'),
        ];
    }
}
