<?php

namespace App\Filament\Widgets;

use Carbon\Carbon;
use Filament\Tables;
use App\Models\EquipmentRequestItem;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class EquipmentRequestTable extends BaseWidget
{
    protected int|string|array $columnSpan = 'full';
    protected static ?string $heading = 'Peminjaman Alat Hari Ini';
    protected static ?int $sort = 2;
    protected static bool $isLazy = false;

    public function table(Table $table): Table
    {
        return $table
            ->emptyStateHeading('Belum ada peminjaman alat hari ini.')
            ->emptyStateDescription('Belum ada permintaan peminjaman alat yang diajukan.')
            ->emptyStateIcon('heroicon-o-wrench-screwdriver')
            ->query(
                EquipmentRequestItem::query()
                    ->whereDate('created_at', Carbon::today())
                    ->with(['equipment', 'request.construction', 'request.requestedBy']) // eager loading
            )
            ->columns([
                Tables\Columns\TextColumn::make('request.construction.construction_name')
                    ->label('Nama Proyek')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('equipment.name')
                    ->label('Nama Alat')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('quantity')
                    ->label('Jumlah Dipinjam')
                    ->sortable(),

                Tables\Columns\TextColumn::make('request.requestedBy.name')
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
