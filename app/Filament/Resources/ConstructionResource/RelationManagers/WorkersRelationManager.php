<?php

namespace App\Filament\Resources\ConstructionResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Resources\RelationManagers\RelationManager;

class WorkersRelationManager extends RelationManager
{
    protected static string $relationship = 'workers';
    protected static ?string $title = 'Pekerja';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('workers')
                ->label('Pekerja')
                ->multiple()
                ->searchable(),
                Forms\Components\TextInput::make('position')->label('Posisi')->nullable(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('worker_name')
            ->emptyStateHeading('Belum ada pekerja pada proyek ini')
            ->emptyStateDescription('Tambah pekerja baru untuk dapat ditugaskan ke proyek ini.')
            ->emptyStateIcon('heroicon-o-user-group')
            ->columns([
                Tables\Columns\TextColumn::make('worker_name')->label('Nama')->searchable(),
                Tables\Columns\TextColumn::make('contact')->label('Telepon')->toggleable(),
                Tables\Columns\TextColumn::make('position')->label('Posisi')
                    ->badge()
                    ->color(fn($state) => match ($state) {
                        'Mandor' => 'success',
                        'Tukang' => 'warning',
                        'Helper' => 'gray',
                        default => 'gray',
                    }),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    protected function beforeCreate(array $data): array
    {
        $data['construction_id'] = $this->getOwnerRecord()->id;

        return $data;
    }
}
