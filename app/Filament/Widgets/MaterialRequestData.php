<?php

namespace App\Filament\Widgets;

use Carbon\Carbon;
use Filament\Tables;
use App\Models\MaterialRequestItem;
use Filament\Widgets\TableWidget as BaseWidget;
use Filament\Tables\Table;

class MaterialRequestData extends BaseWidget
{
    protected int|string|array $columnSpan = 'full';
    protected static ?string $heading = 'Permintaan Material Hari Ini';
    protected static ?int $sort = 2;
    protected static bool $isLazy = false;

    public function table(Table $table): Table
    {
        return $table
            ->emptyStateHeading('Belum ada permintaan material hari ini.')
            ->emptyStateDescription('Belum ada permintaan material yang diajukan oleh supervisor.')
            ->emptyStateIcon('heroicon-o-truck')
            ->query(
                MaterialRequestItem::query()
                    ->whereDate('created_at', Carbon::today())
                    ->with(['material', 'materialRequest.construction', 'materialRequest.requestedBy'])
            )
            ->columns([
                Tables\Columns\TextColumn::make('materialRequest.construction.construction_name')
                    ->label('Nama Proyek')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('material.material_name')
                    ->label('Nama Material')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('quantity')
                    ->label('Jumlah Diminta')
                    ->sortable(),

                Tables\Columns\TextColumn::make('materialRequest.requestedBy.name')
                    ->label('Pengaju')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tanggal')
                    ->dateTime('d-m-Y H:i'),
            ]);
    }
}
