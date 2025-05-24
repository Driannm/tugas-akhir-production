<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Notifications\Notification;

use App\Models\{Worker, Construction};

use Filament\Tables\Filters\{SelectFilter};
use Filament\Tables\Columns\{TextColumn, Stack, ImageColumn};
use Illuminate\Database\Eloquent\{SoftDeletingScope, Builder};
use App\Filament\Resources\WorkerResource\{RelationManagers, Pages};
use Filament\Forms\Components\{Tabs, Tab, Section, TextInput, Select, DatePicker, FileUpload, Grid, Placeholder};
use Filament\Tables\Actions\{Action, ActionGroup, ViewAction, EditAction, DeleteAction, BulkActionGroup, DeleteBulkAction};

class WorkerResource extends Resource
{
    protected static ?string $model = Worker::class;
    protected static ?string $recordTitleAttribute = 'worker_name';
    protected static ?string $label = 'Pekerja';
    protected static ?string $navigationLabel = 'Pekerja';
    protected static ?string $navigationGroup = 'Manajemen Sumber Daya';
    protected static ?int $navigationSort = 1;
    protected static ?string $navigationIcon = 'heroicon-o-user-group';
    protected static ?string $activeNavigationIcon = 'heroicon-s-user-group';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Informasi Pekerja')
                    ->icon('heroicon-m-identification')
                    ->iconColor('primary')
                    ->schema([
                        TextInput::make('worker_name')
                            ->label('Nama Lengkap Pekerja')
                            ->placeholder('Contoh: Budi Santoso')
                            ->autofocus()
                            ->required()
                            ->maxLength(255),

                        Select::make('gender')
                            ->label('Jenis Kelamin')
                            ->required()
                            ->options([
                                'Laki-laki' => 'Laki-laki',
                                'Perempuan' => 'Perempuan',
                            ])
                            ->native(false)
                            ->searchable(),

                        TextInput::make('position')
                            ->label('Posisi atau Jabatan')
                            ->placeholder('Contoh: Mandor, Tukang Las, dll.')
                            ->required()
                            ->maxLength(255),

                        DatePicker::make('birth_date')
                            ->label('Tanggal Lahir')
                            ->required()
                            ->native(false)
                            ->withoutSeconds(),
                    ])
                    ->collapsible()
                    ->columns(2),

                Section::make('Kontak dan Alamat')
                    ->icon('heroicon-m-phone')
                    ->iconColor('primary')
                    ->schema([
                        TextInput::make('contact')
                            ->label('Nomor HP Aktif')
                            ->tel()
                            ->required()
                            ->maxLength(15),

                        TextInput::make('address')
                            ->label('Alamat Domisili')
                            ->required()
                            ->maxLength(255),

                        TextInput::make('emergency_contact')
                            ->label('Kontak Darurat')
                            ->tel()
                            ->required()
                            ->maxLength(15),

                        TextInput::make('emergency_contact_name')
                            ->label('Nama Kontak Darurat')
                            ->maxLength(255),
                    ])
                    ->collapsible()
                    ->persistCollapsed()
                    ->columns(2),

                Section::make('Status Kerja dan Proyek')
                    ->icon('heroicon-m-briefcase')
                    ->iconColor('primary')
                    ->schema([
                        Select::make('employment_status')
                            ->label('Status Pekerjaan')
                            ->options([
                                'pekerja_tetap' => 'Pekerja Tetap',
                                'pekerja_lepas' => 'Pekerja Lepas',
                                'dipecat' => 'Dipecat',
                            ])
                            ->default('pekerja_tetap')
                            ->native(false)
                            ->required(),

                        Select::make('construction_id')
                            ->label('Proyek Konstruksi')
                            ->native(false)
                            ->searchable()
                            ->options(Construction::all()->pluck('construction_name', 'id'))
                            ->placeholder('Pilih proyek aktif...'),
                    ])
                    ->collapsible()
                    ->persistCollapsed()
                    ->columns(2),

                Section::make('Foto Pekerja')
                    ->icon('heroicon-m-camera')
                    ->iconColor('primary')
                    ->schema([
                        FileUpload::make('photo')
                            ->label('Unggah Foto Pekerja')
                            ->nullable()
                            ->directory('workers/photos')
                            ->disk('public')
                            ->image()
                            ->imageEditor()
                            ->imageCropAspectRatio('1:1')
                            ->maxSize(1024)
                            ->helperText('Maksimum 1MB. Format jpg/png disarankan.'),
                    ])
                    ->collapsed()
                    ->persistCollapsed(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultPaginationPageOption(5)
            ->emptyStateHeading('Belum ada data pekerja')
            ->emptyStateDescription('Tambah pekerja baru untuk dapat ditugaskan ke proyek.')
            ->emptyStateIcon('heroicon-o-users')
            ->extremePaginationLinks()
            ->columns([
                ImageColumn::make('photo')
                    ->label('Foto')
                    ->toggleable()
                    ->circular(),

                TextColumn::make('worker_name')
                    ->label('Nama Pekerja'),

                TextColumn::make('position')
                    ->label('Posisi Pekerja')
                    ->limit(20),

                TextColumn::make('contact')
                    ->label('Kontak')
                    ->limit(10),

                TextColumn::make('emergency_contact')
                    ->label('Kontak Darurat')
                    ->limit(10),

                TextColumn::make('address')
                    ->label('Alamat')
                    ->toggleable()
                    ->limit(10),

                TextColumn::make('employment_status')
                    ->label('Status Pekerja')
                    ->badge()
                    ->toggleable()
                    ->color(fn($state) => match ($state) {
                        'pekerja_tetap' => 'success',
                        'dipecat' => 'danger',
                        'pekerja_lepas' => 'info',
                    })
                    ->icon(fn($state) => match ($state) {
                        'pekerja_tetap' => 'heroicon-m-check-circle',
                        'dipecat' => 'heroicon-m-x-circle',
                        'pekerja_lepas' => 'heroicon-m-question-mark-circle',
                    })
                    ->formatStateUsing(fn($state) => match ($state) {
                        'pekerja_tetap' => 'Pekerja Tetap',
                        'dipecat' => 'Dipecat',
                        'pekerja_lepas' => 'Pekerja Lepas',
                    })
                    ->limit(30),

                TextColumn::make('construction.construction_name')
                    ->label('Proyek')
                    ->sortable()
                    ->toggleable()
                    ->searchable()
                    ->limit(20),
            ])
            ->filters([
                SelectFilter::make('employment_status')
                    ->label('Status Pekerja')
                    ->native(false)
                    ->options([
                        'pekerja_tetap' => 'Pekerja Tetap',
                        'dipecat' => 'Dipecat',
                        'pekerja_lepas' => 'Pekerja Lepas',
                    ]),

                SelectFilter::make('gender')
                    ->label('Jenis Kelamin')
                    ->native(false)
                    ->options([
                        'Laki-laki' => 'Laki-laki',
                        'Perempuan' => 'Perempuan',
                    ]),

                SelectFilter::make('construction_id')
                    ->label('Proyek Konstruksi')
                    ->options(fn() => Construction::pluck('construction_name', 'id')->toArray())
                    ->searchable()
                    ->native(false),

                SelectFilter::make('position')
                    ->label('Posisi')
                    ->searchable()
                    ->options(fn() => Worker::select('position')->distinct()->pluck('position', 'position')->toArray())
                    ->native(false),
            ])
            ->filtersFormColumns(2)
            ->filtersTriggerAction(
                fn(Action $action) => $action
                    ->button()
                    ->label('Filter'),
            )
            ->actions([
                ActionGroup::make([
                    EditAction::make()
                        ->color('warning')
                        ->icon('heroicon-m-pencil-square'),
                    ViewAction::make()
                        ->color('info')
                        ->icon('heroicon-m-magnifying-glass'),
                    DeleteAction::make()
                        ->color('danger')
                        ->icon('heroicon-m-trash')
                        ->successNotification(
                            Notification::make()
                                ->title('Berhasil Menghapus')
                                ->body('Data pekerja telah berhasil dihapus.')
                                ->success()
                                ->icon('heroicon-o-check-circle')
                        ),
                ])
                    ->color('primary')
                    ->tooltip('Tindakan'),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->successNotification(
                            Notification::make()
                                ->title('Berhasil Menghapus')
                                ->body('Data pekerja telah berhasil dihapus.')
                                ->success()
                                ->icon('heroicon-o-check-circle')
                        ),
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
            'index' => Pages\ListWorkers::route('/'),
            'create' => Pages\CreateWorker::route('/create'),
            'view' => Pages\ViewWorker::route('/{record}'),
            'edit' => Pages\EditWorker::route('/{record}/edit'),
        ];
    }
}
