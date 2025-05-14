<?php

namespace App\Filament\Widgets;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Filament\Support\Enums\IconPosition;
use App\Models\{Construction, User, Worker};
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;

class DataOverview extends BaseWidget
{
    protected static bool $isLazy = false;
    protected ?string $heading = 'Analisis';
    protected ?string $description = 'Sekilas tentang beberapa analisis.';
    protected function getStats(): array
    {
        return [
            Stat::make('Statistik Pengguna', User::count())
                ->description('Total pengguna yang telah terdaftar di sistem.')
                ->color('primary')
                ->chart(
                    User::select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as count'))
                        ->where('created_at', '>=', Carbon::now()->subDays(7))
                        ->groupBy('date')
                        ->orderBy('date')
                        ->pluck('count')
                        ->toArray()
                ),

            Stat::make('Total Proyek Selesai', Construction::where('status_construction', 'selesai')->count())
                ->description('Jumlah proyek yang berhasil diselesaikan tepat waktu.')
                ->color('success')
                ->chart(
                    Construction::select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as count'))
                        ->where('status_construction', 'selesai')
                        ->where('created_at', '>=', Carbon::now()->subDays(7))
                        ->groupBy('date')
                        ->orderBy('date')
                        ->pluck('count')
                        ->toArray()
                ),

            Stat::make('Proyek Sedang Berjalan', Construction::where('status_construction', 'sedang_berlangsung')->count())
                ->description('Jumlah proyek yang saat ini masih dalam tahap pengerjaan.')
                ->color('info')
                ->chart(
                    Construction::select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as count'))
                        ->where('status_construction', 'sedang_berlangsung')
                        ->where('created_at', '>=', Carbon::now()->subDays(7))
                        ->groupBy('date')
                        ->orderBy('date')
                        ->pluck('count')
                        ->toArray()
                ),

            Stat::make('Jumlah Pekerja Aktif', Worker::where('employment_status', 'pekerja_tetap')->count())
                ->description('Jumlah pekerja Tetap pada Ajuna Property.')
                ->color('danger')
                ->chart(
                    Worker::select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as count'))
                        ->where('employment_status', 'pekerja_tetap')
                        ->where('created_at', '>=', Carbon::now()->subDays(7))
                        ->groupBy('date')
                        ->orderBy('date')
                        ->pluck('count')
                        ->toArray()
                ),
        ];
    }
}

