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
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Columns\{TextColumn, BadgeColumn};

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
                //
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
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
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
