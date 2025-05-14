<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Notifications\Notification;

use App\Models\Equipment;

use Filament\Tables\Filters\{Filter, SelectFilter};
use Filament\Tables\Columns\{TextColumn, Stack, ImageColumn};
use Illuminate\Database\Eloquent\{SoftDeletingScope, Builder};
use App\Filament\Resources\EquipmentResource\{Pages, RelationManagers};
use Filament\Forms\Components\{Select, Textarea, TextInput, DatePicker, FileUpload, Tabs, Section};
use Filament\Tables\Actions\{Action, ActionGroup, ViewAction, EditAction, DeleteAction, BulkActionGroup, DeleteBulkAction};

class EquipmentResource extends Resource
{
    protected static ?string $model = Equipment::class;

    protected static ?string $navigationGroup = 'Manajemen Sumber Daya';
    protected static ?string $navigationLabel = 'Stok Peralatan';
    protected static ?string $label = 'Data Stok Peralatan';
    protected static ?int $navigationSort = 4;
    protected static ?string $navigationIcon = 'heroicon-o-cog';
    protected static ?string $activeNavigationIcon = 'heroicon-s-cog';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Informasi Alat')
                    ->description('Detail dasar mengenai alat yang akan didaftarkan')
                    ->icon('heroicon-m-clipboard')
                    ->iconColor('primary')
                    ->schema([
                        TextInput::make('name')
                            ->label('Nama Alat')
                            ->required()
                            ->maxLength(255)
                            ->validationMessages([
                                'required' => 'Nama Alat wajib diisi.',
                            ]),

                        TextInput::make('code')
                            ->label('Kode Alat')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(100)
                            ->validationMessages([
                                'required' => 'Kode Alat wajib diisi.',
                                'unique' => 'Kode Alat sudah digunakan, silakan pakai kode lain.',
                            ]),
                    ])
                    ->columns(2)
                    ->collapsible(),

                Section::make('Spesifikasi dan Kondisi')
                    ->description('Jumlah alat dan informasi perawatan terakhir')
                    ->icon('heroicon-m-wrench-screwdriver')
                    ->iconColor('primary')
                    ->schema([
                        TextInput::make('quantity')
                            ->numeric()
                            ->minValue(0)
                            ->label('Jumlah')
                            ->required()
                            ->validationMessages([
                                'required' => 'Jumlah Alat wajib diisi.',
                            ]),

                        DatePicker::make('last_maintenance')
                            ->label('Perawatan Terakhir')
                            ->native(false)
                            ->default(now())
                            ->required()
                            ->minDate(now()->subYear()),
                    ])
                    ->columns(2)
                    ->collapsible(),

                Section::make('Status dan Deskripsi')
                    ->description('Status pemakaian alat dan deskripsi tambahan')
                    ->icon('heroicon-m-clipboard-document-check')
                    ->iconColor('primary')
                    ->schema([
                        Select::make('status')
                            ->label('Status')
                            ->native(false)
                            ->options([
                                'available' => 'Tersedia',
                                'out_of_stock' => 'Tidak Tersedia',
                                'under_maintenance' => 'Perawatan',
                            ])
                            ->required()
                            ->default('available')
                            ->reactive(),

                        Textarea::make('description')
                            ->label('Deskripsi')
                            ->maxLength(500)
                            ->rows(1),
                    ])
                    ->columns(2)
                    ->collapsed(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultPaginationPageOption(option: 5)
            ->extremePaginationLinks()
            ->emptyStateHeading('Belum ada peralatan')
            ->emptyStateDescription('Data peralatan yang terdaftar akan muncul di sini setelah ditambahkan.')
            ->emptyStateIcon('heroicon-o-cog')
            ->columns([
                TextColumn::make('name')
                    ->label('Nama Alat')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('code')
                    ->label('Kode')
                    ->sortable(),

                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn($state) => match ($state) {
                        'available' => 'Tersedia',
                        'out_of_stock' => 'Tidak Tersedia',
                        'under_maintenance' => 'Perawatan',
                    })
                    ->icon(fn($state) => match ($state) {
                        'available' => 'heroicon-s-check-circle',
                        'out_of_stock' => 'heroicon-s-x-circle',
                        'under_maintenance' => 'heroicon-s-wrench-screwdriver',
                    })
                    ->color(fn($state) => match ($state) {
                        'available' => 'success',
                        'out_of_stock' => 'danger',
                        'under_maintenance' => 'warning',
                    }),

                TextColumn::make('quantity')
                    ->label('Jumlah')
                    ->sortable()
                    ->numeric(),

                TextColumn::make('last_maintenance')
                    ->label('Perawatan Terakhir')
                    ->date()
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'available' => 'Tersedia',
                        'out_of_stock' => 'Tidak Tersedia',
                        'under_maintenance' => 'Perawatan',
                    ])
                    ->native(false),

                Filter::make('last_maintenance')
                    ->form([
                        DatePicker::make('to')->label('Perawatan Terakhir')
                            ->native(false),
                    ])
                    ->query(function (Builder $query, array $data) {
                        return $query
                            ->when($data['to'], fn($query, $date) => $query->whereDate('last_maintenance', '<=', $date));
                    }),

                Filter::make('code')
                    ->form([
                        TextInput::make('code')->label('Kode')->prefix('EQ-')->suffix('-AJP'),
                    ])
                    ->query(function (Builder $query, array $data) {
                        return $query
                            ->when($data['code'], fn($query, $name) => $query->where('code', 'like', "%{$name}%"));
                    }),
            ])
            ->filtersFormColumns(1)
            ->filtersTriggerAction(fn(Action $action) => $action->label('Filter Peralatan')->button()->icon('heroicon-m-funnel'))
            ->actions([
                ActionGroup::make([
                    EditAction::make()
                        ->color('warning'),
                    ViewAction::make()
                        ->color('info'),
                    DeleteAction::make()
                        ->color('danger')
                        ->successNotification(
                            Notification::make()
                                ->danger()
                                ->icon('heroicon-o-check-badge')
                                ->title('Data Dihapus')
                                ->body('Data proyek konstruksi telah berhasil dihapus dari sistem. Pastikan untuk melakukan backup sebelum menghapus data penting.')
                        ),
                ])->color('primary'),
            ])
            ->bulkActions([
                DeleteBulkAction::make(),
            ]);
    }

    public static function getGlobalSearchResultTitle($record): string
    {
        return $record->name;
    }

    public static function getGlobalSearchResultDetails($record): array
    {
        $statusLabels = [
            'available' => 'Tersedia',
            'out_of_stock' => 'Tidak Tersedia',
            'under_maintenance' => 'Dalam Perawatan',
        ];

        return [
            'Kode' => $record->code,
            'Status' => $statusLabels[$record->status] ?? 'Tidak Diketahui',
        ];
    }


    public static function getGloballySearchableAttributes(): array
    {
        return ['name', 'code', 'status'];
    }

    public static function getGlobalSearchResultUrl($record): string
    {
        return static::getUrl('view', ['record' => $record]);
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
            'index' => Pages\ListEquipment::route('/'),
            'create' => Pages\CreateEquipment::route('/create'),
            'view' => Pages\ViewEquipment::route('/{record}'),
            'edit' => Pages\EditEquipment::route('/{record}/edit'),
        ];
    }
}
