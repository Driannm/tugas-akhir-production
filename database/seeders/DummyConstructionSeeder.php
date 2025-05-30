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
        $constructionTypes = [
            'Pembangunan Rumah Tinggal',
            'Pembangunan Villa',
            'Pembangunan Cafe & Resto',
            'Pembangunan Kontrakan',
            'Pembangunan Kost',
            'Pembangunan Ruko',
            'Renovasi Rumah',
        ];

        $clients = [
            'Bapak Andi', 'Ibu Sari', 'CV Bangun Jaya', 'PT Rumah Idaman', 'Developer Properti Sejahtera', 'Keluarga Budi', 'Ibu Lestari',
        ];

        $projectData = [
            [
                'name' => 'Pembangunan Perumahan Griya Sumba Alam Type 45',
                'desc' => 'Pembangunan kompleks perumahan modern dengan desain minimalis, terdiri dari 20 unit rumah type 45 yang didesain untuk keluarga muda. Proyek ini berfokus pada penyediaan hunian nyaman dengan fasilitas taman bermain dan area parkir.',
            ],
            [
                'name' => 'Pembangunan Villa Artha Mulia',
                'desc' => 'Pembangunan villa eksklusif dengan konsep tropis modern, lengkap dengan kolam renang pribadi, taman terbuka hijau, dan ruang serbaguna untuk acara keluarga. Dirancang untuk pengalaman staycation yang nyaman.',
            ],
            [
                'name' => 'Renovasi Rumah Klasik Keluarga Hartono',
                'desc' => 'Proyek renovasi total rumah lama bergaya klasik menjadi hunian modern dengan sentuhan arsitektur vintage. Fokus utama pada perbaikan struktur atap, penambahan ruang keluarga, dan optimalisasi pencahayaan alami.',
            ],
            [
                'name' => 'Pembangunan Kontrakan Suka Damai 6 Pintu',
                'desc' => 'Pembangunan 6 unit kontrakan sederhana untuk memenuhi kebutuhan hunian terjangkau di kawasan padat penduduk. Konsep bangunan praktis dengan desain fungsional dan hemat biaya pemeliharaan.',
            ],
            [
                'name' => 'Cafe Tempat Peraduan',
                'desc' => 'Pembangunan kafe kekinian dengan konsep industrial minimalis. Memiliki area indoor-outdoor dengan pencahayaan natural, dinding bata ekspos, dan spot-spot instagramable untuk anak muda.',
            ],
            [
                'name' => 'Pembangunan Kost Serayu Agung 3 Lantai',
                'desc' => 'Pembangunan gedung kost 3 lantai dengan 18 kamar, lengkap dengan fasilitas kamar mandi dalam, area parkir motor, dan keamanan 24 jam. Target pasar mahasiswa dan karyawan di sekitar pusat kota.',
            ],
            [
                'name' => 'Pembangunan Ruko Mega Bisnis Center',
                'desc' => 'Pembangunan kompleks ruko modern dengan 10 unit yang difungsikan untuk usaha kuliner, retail, dan kantor. Didesain dengan akses jalan lebar, parkir luas, dan fasilitas penunjang usaha.',
            ],
            [
                'name' => 'Renovasi Total Rumah Minimalis Bpk Hendra',
                'desc' => 'Proyek renovasi rumah type 60 menjadi rumah 2 lantai dengan konsep open space. Pekerjaan meliputi pembongkaran atap lama, penambahan struktur lantai atas, serta finishing interior dan eksterior.',
            ],
            [
                'name' => 'Pembangunan Perumahan Bukit Asri Residence Type 36',
                'desc' => 'Pengembangan perumahan cluster dengan desain hijau dan ramah lingkungan. Setiap unit dilengkapi dengan taman kecil dan carport, cocok untuk pasangan muda atau keluarga kecil.',
            ],
            [
                'name' => 'Pembangunan Cafe & Resto Kopi Lembayung',
                'desc' => 'Pembangunan kafe dan resto semi-outdoor dengan konsep alam terbuka, menghadirkan suasana santai dengan pemandangan taman dan kolam ikan. Fasilitas lengkap dengan dapur besar, area makan, dan panggung kecil untuk live music.',
            ],
            [
                'name' => 'Pembangunan Kost Eksklusif Tegal Asri',
                'desc' => 'Pembangunan kost eksklusif dengan 12 kamar dilengkapi AC, wifi, dan lounge bersama. Target penghuni adalah profesional muda dan mahasiswa, dengan desain modern dan fasilitas premium.',
            ],
            [
                'name' => 'Pembangunan Warung Kopi Gubuk Tua',
                'desc' => 'Pembangunan warung kopi dengan konsep rustic tradisional, menggunakan material kayu bekas dan atap jerami untuk nuansa tempo dulu. Tempat nongkrong santai dengan suasana pedesaan.',
            ],
            [
                'name' => 'Renovasi Interior Rumah Type 90 Bpk Andika',
                'desc' => 'Proyek renovasi interior rumah, meliputi penggantian kitchen set, pengecatan dinding, pemasangan lantai vinyl, dan perbaikan plafon. Fokus pada peningkatan estetika dan kenyamanan ruang.',
            ],
            [
                'name' => 'Pembangunan Ruko Modern Square',
                'desc' => 'Pembangunan kompleks ruko 2 lantai dengan konsep facade modern dan area parkir luas. Cocok untuk usaha toko fashion, mini market, dan kantor layanan.',
            ],
            [
                'name' => 'Pembangunan Villa Harmoni Alam',
                'desc' => 'Pembangunan villa dengan konsep eco-living, memanfaatkan material ramah lingkungan, taman hijau, dan pencahayaan alami. Lokasi strategis dekat dengan area wisata alam.',
            ],
            [
                'name' => 'Pembangunan Perumahan Tegal Asri Type 45',
                'desc' => 'Pembangunan perumahan sederhana dengan desain minimalis modern, menyediakan rumah type 45 dengan tata ruang fungsional dan harga terjangkau untuk keluarga muda.',
            ],
        ];

        $supervisors = User::role('supervisor')->pluck('id')->toArray();

        foreach ($projectData as $item) {
            $start = Carbon::now()->subDays(rand(14, 90));
            $end = Carbon::now()->addDays(rand(30, 120));
            $status = Arr::random($statusList);
            $progress = $status === 'selesai' ? 100 : rand(40, 90);

            Construction::create([
                'construction_name' => $item['name'],
                'description' => $item['desc'],
                'start_date' => $start,
                'end_date' => $end,
                'status_construction' => $status,
                'location' => null,
                'budget' => rand(500, 3000) * 1000000, // 500 juta - 3 M
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
