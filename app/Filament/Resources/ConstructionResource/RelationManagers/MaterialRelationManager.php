<?php

namespace App\Filament\Resources\ConstructionResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\{TextColumn, BadgeColumn};
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class MaterialRelationManager extends RelationManager
{
    protected static string $relationship = 'materialRequestItems';
    protected static ?string $title = 'Material';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('material_id')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('material_id')
            ->emptyStateHeading('Belum ada data material')
            ->emptyStateDescription('Tambahkan material baru untuk kebutuhan proyek konstruksi.')
            ->emptyStateIcon('heroicon-o-cube')
            ->columns([
                TextColumn::make('material.material_name')
                    ->label('Nama Material'),

                TextColumn::make('material.stock_quantity')
                    ->label('Stok Material'),

                TextColumn::make('quantity')
                    ->label('Jumlah Diminta')
                    ->sortable(),

                TextColumn::make('materialRequest.requestedBy.name')
                    ->label('Diajukan Oleh')
                    ->sortable(),

                BadgeColumn::make('status')
                    ->label('Status')
                    ->searchable()
                    ->formatStateUsing(fn($state) => match ($state) {
                        'pending' => 'Menunggu Persetujuan',
                        'approved' => 'Disetujui',
                        'rejected' => 'Ditolak',
                        default => ucfirst($state),
                    })
                    ->colors([
                        'warning' => 'pending',
                        'success' => 'approved',
                        'danger' => 'rejected',
                    ])
                    ->sortable(),

                TextColumn::make('created_at')
                    ->label('Tanggal Permintaan')
                    ->dateTime('d M Y')
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                //
            ])
            ->actions([
                //
            ])
            ->bulkActions([
                //
            ]);
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->with(['material_request']);
    }
}
