<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ConstructionResource\RelationManagers\ItemsRelationManager;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Notifications\Notification;
use App\Filament\Resources\ConstructionResource\RelationManagers\WorkersRelationManager;
use App\Filament\Resources\ConstructionResource\RelationManagers\MaterialRelationManager;

use Filament\Forms\Get;
use App\Models\{Construction, User, Worker};
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Actions\{Action, ActionGroup, ViewAction, EditAction, DeleteAction, BulkActionGroup, DeleteBulkAction, ForceDeleteBulkAction, RestoreBulkAction};
use Filament\Tables\Columns\{TextColumn, Stack, ImageColumn};
use Filament\Forms\Components\{Select, Textarea, TextInput, DateTimePicker, FileUpload, Section, DatePicker};
use Illuminate\Database\Eloquent\{Builder, SoftDeletingScope};

use App\Filament\Resources\ConstructionResource\Pages;
use App\Filament\Resources\ConstructionResource\RelationManagers;

class ConstructionResource extends Resource
{
    protected static ?string $model = Construction::class;
    protected static ?string $recordTitleAttribute = 'construction_name';
    protected static ?string $label = 'Proyek';
    protected static ?string $navigationLabel = 'Proyek';
    protected static ?string $navigationGroup = 'Manajemen Proyek';
    protected static ?int $navigationSort = 1;
    protected static ?string $navigationIcon = 'heroicon-o-home-modern';
    protected static ?string $activeNavigationIcon = 'heroicon-s-home-modern';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Section::make('Informasi Umum')
                ->icon('heroicon-m-clipboard-document-list')
                ->iconColor('primary')
                ->schema([
                    TextInput::make('construction_name')
                        ->label('Nama Proyek')
                        ->required()
                        ->maxLength(255),

                    TextInput::make('location')
                        ->label('Lokasi Proyek')
                        ->nullable()
                        ->maxLength(255),

                    DateTimePicker::make('start_date')
                        ->label('Tanggal Mulai')
                        ->required()
                        ->native(false)
                        ->time(false)
                        ->timezone('Asia/Jakarta')
                        ->firstDayOfWeek(7),

                    DateTimePicker::make('end_date')
                        ->label('Tanggal Selesai')
                        ->required()
                        ->native(false)
                        ->time(false)
                        ->timezone('Asia/Jakarta')
                        ->firstDayOfWeek(7),

                    TextInput::make('client_name')
                        ->label('Nama Klien')
                        ->nullable()
                        ->maxLength(255),

                    TextInput::make('type_of_construction')
                        ->label('Jenis Konstruksi')
                        ->nullable()
                        ->maxLength(255),

                    Textarea::make('description')
                        ->label('Deskripsi Proyek')
                        ->required()
                        ->columnSpanFull(),
                ])
                ->columns(2)
                ->collapsible()
                ->persistCollapsed(),

            Section::make('Dokumentasi dan File')
                ->icon('heroicon-m-paper-clip')
                ->iconColor('primary')
                ->schema([
                    FileUpload::make('documentations')
                        ->label('Dokumentasi Proyek')
                        ->nullable()
                        ->multiple()
                        ->reorderable()
                        ->openable()
                        ->image()
                        ->maxFiles(10)
                        ->maxSize(10240)
                        ->panelLayout('grid')
                        ->directory('constructions/documentations')
                        ->helperText('Unggah maksimal 10 file, masing-masing maksimal 10MB.'),

                    FileUpload::make('contract_file')
                        ->label('File Kontrak')
                        ->nullable()
                        ->openable()
                        ->disk('public')
                        ->maxSize(10240)
                        ->directory('constructions/contracts')
                        ->helperText('Unggah file kontrak maksimal 10MB.'),
                ])
                ->columns(2)
                ->collapsible()
                ->persistCollapsed(),

            Section::make('Status dan Penanggung Jawab')
                ->icon('heroicon-m-user')
                ->iconColor('primary')
                ->schema([
                    Select::make('status_construction')
                        ->label('Status Proyek')
                        ->native(false)
                        ->required()
                        ->options([
                            'sedang_berlangsung' => 'Sedang Berlangsung',
                            'selesai' => 'Selesai',
                            'dibatalkan' => 'Dibatalkan',
                        ])
                        ->default('sedang_berlangsung'),

                    Select::make('supervisor_id')
                        ->label('Supervisor')
                        ->options(
                            User::role('Supervisor')->pluck('name', 'id')
                        )
                        ->nullable()
                        ->searchable(),

                    Select::make('workers')
                        ->label('Pekerja')
                        ->multiple()
                        ->options(function ($get) {
                            $recordId = request()->route('record');
                            return Worker::whereDoesntHave('constructions', function ($query) use ($recordId) {
                                $query->where('constructions.id', '!=', $recordId);
                            })->pluck('worker_name', 'id');
                        })
                        ->default(fn($record) => $record?->workers?->pluck('id'))
                        ->searchable()
                        ->columnSpanFull()
                ])
                ->collapsible()
                ->columns(2)
                ->persistCollapsed(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('status_construction', 'asc')
            ->emptyStateHeading(function () {
                return auth()->user()->hasRole('super_admin')
                    ? 'Belum Ada Proyek'
                    : 'Belum Ada Proyek';
            })
            ->emptyStateDescription(function () {
                return auth()->user()->hasRole('super_admin')
                    ? 'Proyek yang Anda tugaskan akan muncul di sini jika sudah tersedia.'
                    : 'Proyek yang ditugaskan kepadamu akan muncul di sini jika sudah tersedia.';
            })
            ->emptyStateIcon('heroicon-o-briefcase')
            ->columns([
                TextColumn::make('construction_name')
                    ->label('Nama Proyek')
                    ->searchable()
                    ->wrap()
                    ->sortable(),

                TextColumn::make('status_construction')
                    ->label('Status')
                    ->badge()
                    ->color(function ($record) {
                        if ($record->status_construction === 'sedang_berlangsung' && $record->end_date < now()) {
                            return 'danger';
                        }

                        return match ($record->status_construction) {
                            'selesai' => 'success',
                            'sedang_berlangsung' => 'info',
                            'dibatalkan' => 'gray',
                        };
                    })
                    ->icon(function ($record) {
                        if ($record->status_construction === 'sedang_berlangsung' && $record->end_date < now()) {
                            return 'heroicon-m-exclamation-triangle';
                        }

                        return match ($record->status_construction) {
                            'selesai' => 'heroicon-m-check-circle',
                            'sedang_berlangsung' => 'heroicon-m-sparkles',
                            'dibatalkan' => 'heroicon-m-x-circle',
                        };
                    })
                    ->formatStateUsing(function ($record) {
                        if ($record->status_construction === 'sedang_berlangsung' && $record->end_date < now()) {
                            return 'Terlambat';
                        }

                        return match ($record->status_construction) {
                            'selesai' => 'Selesai',
                            'sedang_berlangsung' => 'Sedang Berlangsung',
                            'dibatalkan' => 'Dibatalkan',
                        };
                    }),

                TextColumn::make('start_date')
                    ->label('Tanggal Mulai')
                    ->date()
                    ->sortable(),

                TextColumn::make('end_date')
                    ->label('Tanggal Selesai')
                    ->date()
                    ->sortable(),

                TextColumn::make('location')
                    ->label('Lokasi')
                    ->searchable()
                    ->limit(30),

                TextColumn::make('description')
                    ->label('Deskripsi')
                    ->wrap()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->limit(20)
                    ->tooltip(fn($state) => strip_tags($state)),

                TextColumn::make('supervisor.name')
                    ->label('Supervisor')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->searchable()
                    ->limit(30),

                    TextColumn::make('type_of_construction')
                    ->label('Jenis Konstruksi')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->searchable()
                    ->limit(30),
            ])
            ->filters([
                SelectFilter::make('status_construction')
                    ->label('Status Proyek')
                    ->native(false)
                    ->options([
                        'sedang_berlangsung' => 'Sedang Berlangsung',
                        'selesai' => 'Selesai',
                        'dibatalkan' => 'Dibatalkan',
                    ]),

                Filter::make('client_name')
                    ->form([
                        TextInput::make('name')->label('Nama Klien'),
                    ])
                    ->query(function (Builder $query, array $data) {
                        return $query
                            ->when($data['name'], fn($query, $name) => $query->where('client_name', 'like', "%{$name}%"));
                    }),

                Filter::make('start_date')
                    ->form([
                        DatePicker::make('from')->label('Tanggal Mulai Dari')
                            ->native(false),
                    ])
                    ->query(function (Builder $query, array $data) {
                        return $query
                            ->when($data['from'], fn($query, $date) => $query->whereDate('start_date', '>=', $date));
                    }),

                Filter::make('end_date')
                    ->form([
                        DatePicker::make('to')->label('Tanggal Selesai Sampai')
                            ->native(false),
                    ])
                    ->query(function (Builder $query, array $data) {
                        return $query
                            ->when($data['to'], fn($query, $date) => $query->whereDate('end_date', '<=', $date));
                    }),

                Filter::make('supervisor_id')
                    ->label('Supervisor')
                    ->form([
                        Select::make('supervisor')
                            ->label('Pilih Supervisor')
                            ->options(
                                User::role('Supervisor')->pluck('name', 'id')
                            )
                            ->searchable(),
                    ])
                    ->query(function (Builder $query, array $data) {
                        return $query
                            ->when($data['supervisor'], fn($query, $id) => $query->where('supervisor_id', $id));
                    })
                    ->visible(fn() => auth()->user()?->hasRole('super_admin')),
            ])
            ->filtersFormColumns(2)
            ->filtersTriggerAction(fn(Action $action) => $action->label('Filter Proyek')->button()->icon('heroicon-m-funnel'))
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
                        ->color('danger')
                        ->successNotification(
                            Notification::make()
                                ->danger()
                                ->title('Proyek Berhasil Dihapus')
                                ->body('Data proyek telah dihapus dari sistem. Tindakan ini tidak dapat dibatalkan.')
                        ),
                ])
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->successNotification(
                            Notification::make()
                                ->danger()
                                ->title('Proyek Berhasil Dihapus')
                                ->body('Data proyek telah dihapus dari sistem. Tindakan ini tidak dapat dibatalkan.')
                        ),
                ]),
            ]);
    }
    public static function getGloballySearchableAttributes(): array
    {
        return [
            'construction_name',
            'location',
            'client_name',
        ];
    }

    public static function getGlobalSearchResultTitle($record): string
    {
        return $record->construction_name;
    }

    public static function getGlobalSearchResultDetails($record): array
    {
        return [
            'Lokasi' => $record->location ?? '-',
            'Klien' => $record->client_name ?? '-',
            'Status' => match ($record->status_construction) {
                'sedang_berlangsung' => 'Sedang Berlangsung',
                'selesai' => 'Selesai',
                'dibatalkan' => 'Dibatalkan',
                default => 'Tidak Diketahui',
            },
        ];
    }

    public static function getGlobalSearchResultUrl($record): string
    {
        return static::getUrl('view', ['record' => $record]);
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }

    public static function getRelations(): array
    {
        return [
            WorkersRelationManager::class,
            MaterialRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListConstructions::route('/'),
            'create' => Pages\CreateConstruction::route('/create'),
            'view' => Pages\ViewConstruction::route('/{record}'),
            'edit' => Pages\EditConstruction::route('/{record}/edit'),
        ];
    }
}
