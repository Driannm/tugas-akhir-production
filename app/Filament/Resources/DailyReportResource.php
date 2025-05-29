<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Notifications\Notification;

use App\Models\{DailyReport, Construction};

use Illuminate\Support\Str;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Columns\{TextColumn, Stack, ImageColumn};
use Illuminate\Database\Eloquent\{SoftDeletingScope, Builder};
use App\Filament\Resources\DailyReportResource\{Pages, RelationManagers};
use Filament\Forms\Components\{Select, Textarea, FileUpload, TextInput, DatePicker, Section, Hidden};
use Filament\Tables\Actions\{Action, ActionGroup, ViewAction, EditAction, DeleteAction, DeleteBulkAction};
class DailyReportResource extends Resource
{
    protected static ?string $model = DailyReport::class;
    protected static ?string $label = 'Progress Harian';
    protected static ?string $navigationLabel = 'Progress Harian';
    protected static ?string $navigationGroup = 'Laporan & Keuangan';
    protected static ?int $navigationSort = 3;
    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Proyek dan Tanggal')
                    ->icon('heroicon-m-calendar-days')
                    ->iconColor('primary')
                    ->schema([
                        Select::make('construction_id')
                            ->label('Proyek')
                            ->native(false)
                            ->placeholder('Pilih proyek...')
                            ->options(function () {
                                return Construction::where('supervisor_id', auth()->id())
                                    ->where('status_construction', 'sedang_berlangsung')
                                    ->pluck('construction_name', 'id');
                            })
                            ->required()
                            ->reactive(),

                        DatePicker::make('report_date')
                            ->label('Tanggal Laporan')
                            ->required()
                            ->native(false)
                            ->default(now())
                            ->placeholder('Pilih tanggal laporan'),
                    ])
                    ->columns(2)
                    ->collapsible(),

                Section::make('Keterangan dan Kendala')
                    ->icon('heroicon-m-document-text')
                    ->iconColor('primary')
                    ->schema([
                        Textarea::make('description')
                            ->label('Uraian Pekerjaan')
                            ->placeholder('Contoh: Pemasangan rangka baja di area timur proyek.'),

                        Textarea::make('issues')
                            ->label('Kendala')
                            ->placeholder('Contoh: Keterlambatan material karena cuaca.'),
                    ])
                    ->columns(2)
                    ->collapsible(),

                Section::make('Cuaca dan Status')
                    ->icon('heroicon-m-cloud')
                    ->iconColor('primary')
                    ->schema([
                        Select::make('weather')
                            ->label('Cuaca')
                            ->native(false)
                            ->placeholder('Pilih kondisi cuaca...')
                            ->options([
                                'cerah' => 'Cerah',
                                'mendung' => 'Mendung',
                                'hujan' => 'Hujan',
                            ]),

                        Select::make('status')
                            ->label('Status Pekerjaan')
                            ->native(false)
                            ->placeholder('Pilih status pekerjaan...')
                            ->options([
                                'on_progress' => 'Sedang Berjalan',
                                'completed' => 'Selesai',
                                'delayed' => 'Tertunda',
                            ])
                            ->required(),
                    ])
                    ->columns(2)
                    ->collapsible(),

                FileUpload::make('photo')
                    ->label('Dokumentasi Proyek')
                    ->nullable()
                    ->openable()
                    ->image()
                    ->columnSpanFull()
                    ->openable()
                    ->maxSize(10240)
                    ->panelLayout('grid')
                    ->directory(function (callable $get) {
                        $constructionId = $get('construction_id');

                        if ($constructionId) {
                            $construction = Construction::find($constructionId);
                            if ($construction) {
                                return 'constructions/' . $construction->construction_name . '/daily-reports';
                            }
                        }

                        return 'constructions/unknown/daily-reports';
                    })
                    ->helperText('Unggah maksimal 10 file, masing-masing maksimal 10MB.'),

                Hidden::make('user_id')
                    ->default(auth()->id()),
            ]);

    }

    public static function table(Table $table): Table
    {
        return $table
            ->emptyStateHeading('Belum ada laporan')
            ->emptyStateDescription(function () {
                return auth()->user()->hasRole('super_admin')
                    ? 'Laporan harian yang dibuat oleh supervisor akan muncul disini.'
                    : 'Laporan harian proyek yang kamu buat akan muncul disini.';
            })
            ->emptyStateIcon('heroicon-o-clipboard-document')
            ->columns([
                ImageColumn::make('photo')
                    ->label('Dokumentasi')
                    ->disk('public')
                    ->toggleable()
                    ->height(100)
                    ->width(100),

                TextColumn::make('construction.construction_name')
                    ->label('Proyek')
                    ->wrap()
                    ->searchable()
                    ->sortable(),

                TextColumn::make('description')
                    ->label('Uraian Pekerjaan')
                    ->wrap(),

                TextColumn::make('issues')
                    ->label('Kendala')
                    ->wrap()
                    ->listWithLineBreaks(),

                TextColumn::make('weather')
                    ->label('Cuaca')
                    ->badge()
                    ->formatStateUsing(fn($state) => Str::ucfirst($state))
                    ->sortable(),

                TextColumn::make('report_date')
                    ->label('Tanggal Laporan')
                    ->date()
                    ->badge()
                    ->color('warning')
                    ->sortable()
                    ->searchable(),
            ])
            ->filters([
                SelectFilter::make('weather')
                    ->label('Cuaca')
                    ->options([
                        'cerah' => 'Cerah',
                        'mendung' => 'Mendung',
                        'hujan' => 'Hujan',
                    ]),
                SelectFilter::make('construction_id')
                    ->label('Proyek')
                    ->native(false)
                    ->placeholder('Pilih proyek...')
                    ->options(function () {
                        return Construction::where('supervisor_id', auth()->id())
                            ->where('status_construction', 'sedang_berlangsung')
                            ->pluck('construction_name', 'id');
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
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListDailyReports::route('/'),
            'create' => Pages\CreateDailyReport::route('/create'),
            'edit' => Pages\EditDailyReport::route('/{record}/edit'),
            'view' => Pages\ViewDailyReport::route('/{record}'),
        ];
    }
}
