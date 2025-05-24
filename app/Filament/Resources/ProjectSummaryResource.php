<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProjectSummaryResource\Pages;
use App\Filament\Resources\ProjectSummaryResource\RelationManagers;
use App\Models\ProjectSummary;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Actions\{Action, ActionGroup};
use Filament\Tables\Columns\{TextColumn, BadgeColumn};
use Illuminate\Database\Eloquent\{Builder, SoftDeletingScope};
use Filament\Forms\Components\{Select, Textarea, TextInput, DateTimePicker, DatePicker, FileUpload, Tabs, Section};

class ProjectSummaryResource extends Resource
{
    protected static ?string $model = ProjectSummary::class;
    protected static ?string $label = 'Laporan Ringkasan Proyek';
    protected static ?string $navigationLabel = 'Ringkasan Proyek';
    protected static ?string $navigationGroup = 'Laporan & Keuangan';
    protected static ?int $navigationSort = 2;
    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document';
    protected static ?string $activeNavigationIcon = 'heroicon-s-clipboard-document';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()
                    ->schema([
                        Select::make('construction_id')
                            ->label('Nama Proyek')
                            ->relationship('construction', 'construction_name')
                            ->required()
                            ->native(false)
                            ->columnSpan(1),

                        DatePicker::make('date')
                            ->label('Tanggal')
                            ->required()
                            ->native(false),
                    ])
                    ->columns(2),

                Section::make('Deskripsi dan Catatan')
                    ->schema([
                        Textarea::make('description')
                            ->label('Deskripsi')
                            ->required()
                            ->rows(4),

                        Textarea::make('notes')
                            ->label('Catatan')
                            ->rows(4),
                    ])
                    ->columns(2),

                Section::make('Dokumentasi')
                    ->schema([
                        FileUpload::make('documentation')
                            ->label('Dokumentasi')
                            ->multiple()
                            ->columnSpanFull()
                            ->image()
                            ->directory('documentation-progress'),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->emptyStateHeading('Belum ada laporan')
            ->emptyStateDescription('Laporan harian proyek yang kamu buat akan muncul di sini.')
            ->emptyStateIcon('heroicon-o-clipboard-document')
            ->columns([
                TextColumn::make('construction.construction_name')
                    ->label('Nama Proyek')
                    ->searchable(),
                TextColumn::make('date')->date()
                    ->label('Tanggal Pelaksanaan')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('description')
                    ->limit(50)
                    ->label('Deskripsi'),
                    
                TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->badge()
                    ->color('muted')
                    ->sortable()
                    ->since(),
            ])
            ->filters([
                //
            ])
            ->actions([
                ActionGroup::make([
                    Action::make('Download')
                        ->icon('heroicon-s-arrow-down-tray')
                        ->url(fn(ProjectSummary $record) => route('export.project-summary', $record->id))
                        ->openUrlInNewTab()
                        ->color('success'),
                    Tables\Actions\ViewAction::make()
                        ->color('info'),
                        Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListProjectSummaries::route('/'),
            'create' => Pages\CreateProjectSummary::route('/create'),
            'view' => Pages\ViewProjectSummary::route('/{record}'),
            'edit' => Pages\EditProjectSummary::route('/{record}/edit'),
        ];
    }
}
