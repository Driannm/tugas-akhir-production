<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Construction;
use App\Models\User;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;

class DummyConstructionSeeder extends Seeder
{
    public function run(): void
    {
        $statusList = ['sedang_berlangsung', 'selesai'];
        $constructionTypes = ['Gedung Perkantoran', 'Jalan Raya', 'Jembatan', 'Apartemen', 'Gudang', 'Pabrik', 'Taman Kota'];
        $locations = ['Jakarta Selatan', 'Bandung Barat', 'Surabaya Timur', 'Medan Kota', 'Yogyakarta Utara'];
        $clients = ['PT Wijaya Karya', 'PT Adhi Karya', 'PT PP (Persero)', 'PT Nindya Karya', 'PT Brantas Abipraya'];
        $projectNames = [
            'Pembangunan Gedung Kantor Walikota',
            'Rehabilitasi Jalan Nasional Lintas Timur',
            'Pembangunan Jembatan Sungai Ciliwung',
            'Proyek Apartemen GreenView',
            'Pembangunan Gudang Logistik Sentral',
            'Revitalisasi Taman Kota Mandiri',
            'Pembangunan Pabrik Otomotif Baru',
            'Proyek Infrastruktur Kawasan Industri',
            'Pembangunan Jalan Tol Cikampek',
            'Peningkatan Drainase dan Trotoar Kota',
            'Pembangunan Stadion Olahraga Nasional',
            'Pembangunan Pusat Perbelanjaan Modern',
            'Rehabilitasi Jaringan Jalan Kota Jakarta',
        ];

        // Ambil semua user yang punya role 'supervisor'
        $supervisors = User::role('supervisor')->pluck('id')->toArray();

        foreach ($projectNames as $name) {
            $start = Carbon::now()->subDays(rand(14, 90));
            $end = Carbon::now()->addDays(rand(30, 120));
            $status = Arr::random($statusList);
            $progress = $status === 'dibatalkan' ? rand(0, 50) : rand(30, 90);

            Construction::create([
                'construction_name' => $name,
                'description' => 'Proyek ' . strtolower($name) . ' yang dilaksanakan oleh kontraktor terpercaya dalam jangka waktu sesuai perencanaan.',
                'start_date' => $start,
                'end_date' => $end,
                'status_construction' => $status,
                'location' => Arr::random($locations),
                'budget' => rand(2, 20) * 100000000,
                'client_name' => Arr::random($clients),
                'supervisor_id' => count($supervisors) > 0 ? Arr::random($supervisors) : null,
                'progress_percentage' => $progress,
                'documentations' => null,
                'contract_file' => null,
                'type_of_construction' => Arr::random($constructionTypes),
            ]);
        }
    }
}
