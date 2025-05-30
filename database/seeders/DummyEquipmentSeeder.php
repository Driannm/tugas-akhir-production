<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DummyEquipmentSeeder extends Seeder
{
    public function run()
    {
        $equipments = [
            [
                'name' => 'Bor Listrik Bosch GSB 550',
                'code' => 'EQ-001-ALP',
                'status' => 'available',
                'quantity' => 6,
                'description' => 'Bor listrik multifungsi untuk pengeboran dinding rumah, pemasangan gantungan lampu, dan keperluan renovasi ringan.',
                'last_maintenance' => Carbon::now()->subDays(12),
            ],
            [
                'name' => 'Gerinda Tangan Makita 9553B',
                'code' => 'EQ-002-ALP',
                'status' => 'available',
                'quantity' => 4,
                'description' => 'Gerinda tangan 4 inci, digunakan untuk pemotongan besi rangka atap, kusen besi, dan finishing renovasi rumah atau cafe.',
                'last_maintenance' => Carbon::now()->subDays(7),
            ],
            [
                'name' => 'Screwdriver Set Kenmaster',
                'code' => 'EQ-003-ALP',
                'status' => 'available',
                'quantity' => 20,
                'description' => 'Set obeng lengkap untuk pekerjaan instalasi listrik, perbaikan furniture, dan pemasangan peralatan rumah tangga.',
                'last_maintenance' => Carbon::now()->subDays(25),
            ],
            [
                'name' => 'Tang Potong Krisbow',
                'code' => 'EQ-004-ALP',
                'status' => 'available',
                'quantity' => 15,
                'description' => 'Tang potong praktis untuk pemotongan kabel listrik, kawat pagar, dan instalasi ringan lainnya di proyek rumah atau kost.',
                'last_maintenance' => Carbon::now()->subDays(45),
            ],
            [
                'name' => 'Genset Portable Yamaha EF1000',
                'code' => 'EQ-005-ALP',
                'status' => 'under_maintenance',
                'quantity' => 2,
                'description' => 'Genset portable 1000 watt untuk kebutuhan listrik darurat di lokasi proyek cafe, villa, atau rumah tanpa akses PLN.',
                'last_maintenance' => Carbon::now()->subDays(60),
            ],
            [
                'name' => 'Laser Meter Bosch GLM 40',
                'code' => 'EQ-006-ALP',
                'status' => 'available',
                'quantity' => 3,
                'description' => 'Alat ukur jarak digital dengan akurasi tinggi, digunakan untuk pengukuran ruang, denah, dan perhitungan desain interior.',
                'last_maintenance' => Carbon::now()->subDays(18),
            ],
            [
                'name' => 'Palu Karet Kenmaster',
                'code' => 'EQ-007-ALP',
                'status' => 'available',
                'quantity' => 10,
                'description' => 'Palu karet digunakan untuk pemasangan keramik, lantai vinyl, dan material rapuh agar tidak pecah saat proses instalasi.',
                'last_maintenance' => Carbon::now()->subDays(22),
            ],
            [
                'name' => 'Pemotong Keramik Mollar',
                'code' => 'EQ-008-ALP',
                'status' => 'available',
                'quantity' => 4,
                'description' => 'Tile cutter manual untuk pemotongan keramik secara presisi, cocok digunakan pada proyek cafe atau perumahan.',
                'last_maintenance' => Carbon::now()->subDays(13),
            ],
            [
                'name' => 'Tangga Lipat 4 Meter Aluminium',
                'code' => 'EQ-009-ALP',
                'status' => 'available',
                'quantity' => 6,
                'description' => 'Tangga lipat aluminium untuk pemasangan plafon, pengecatan dinding, atau instalasi lampu di rumah dan ruko.',
                'last_maintenance' => Carbon::now()->subDays(29),
            ],
            [
                'name' => 'Kompresor Mini Lakoni',
                'code' => 'EQ-010-ALP',
                'status' => 'under_maintenance',
                'quantity' => 1,
                'description' => 'Kompresor mini untuk mendukung pekerjaan pengecatan tembok, pintu, dan pagar di proyek kecil seperti cafe atau rumah.',
                'last_maintenance' => Carbon::now()->subDays(90),
            ],
            [
                'name' => 'Mesin Las Inverter Jasic',
                'code' => 'EQ-011-ALP',
                'status' => 'available',
                'quantity' => 8,
                'description' => 'Mesin las portable untuk pembuatan rangka kanopi, pagar rumah, dan teralis jendela.',
                'last_maintenance' => Carbon::now()->subDays(15),
            ],
            [
                'name' => 'Pompa Air Shimizu',
                'code' => 'EQ-012-ALP',
                'status' => 'under_maintenance',
                'quantity' => 3,
                'description' => 'Pompa air untuk mendukung distribusi air pada proyek pembangunan kost dan kontrakan.',
                'last_maintenance' => Carbon::now()->subDays(40),
            ],
            [
                'name' => 'Kunci Inggris Stanley',
                'code' => 'EQ-013-ALP',
                'status' => 'available',
                'quantity' => 12,
                'description' => 'Kunci inggris multifungsi untuk pekerjaan instalasi pipa air, perbaikan AC, dan plumbing rumah.',
                'last_maintenance' => Carbon::now()->subDays(5),
            ],
            [
                'name' => 'Alat Pemadam Api Portabel',
                'code' => 'EQ-014-ALP',
                'status' => 'available',
                'quantity' => 10,
                'description' => 'Alat pemadam api ringan (APAR) yang wajib tersedia di proyek cafe, villa, dan rumah untuk antisipasi kebakaran.',
                'last_maintenance' => Carbon::now()->subDays(100),
            ],
            [
                'name' => 'Pompa Beton Putzmeister',
                'code' => 'EQ-015-ALP',
                'status' => 'available',
                'quantity' => 2,
                'description' => 'Pompa beton kapasitas sedang untuk pengecoran lantai 2 rumah tinggal atau proyek ruko.',
                'last_maintenance' => Carbon::now()->subDays(30),
            ],
            [
                'name' => 'Mixer Beton Electric',
                'code' => 'EQ-016-ALP',
                'status' => 'under_maintenance',
                'quantity' => 1,
                'description' => 'Mesin pengaduk beton mini untuk pengecoran lantai rumah, kolom, dan pondasi ringan.',
                'last_maintenance' => Carbon::now()->subDays(65),
            ],
            [
                'name' => 'Alat Pendeteksi Logam Garrett',
                'code' => 'EQ-017-ALP',
                'status' => 'available',
                'quantity' => 5,
                'description' => 'Alat pendeteksi logam portabel, membantu dalam pemeriksaan pipa bawah tanah dan kabel instalasi sebelum pengeboran.',
                'last_maintenance' => Carbon::now()->subDays(50),
            ],
            [
                'name' => 'Trolley Barang 300 Kg',
                'code' => 'EQ-018-ALP',
                'status' => 'available',
                'quantity' => 6,
                'description' => 'Trolley dorong untuk mengangkut bahan bangunan seperti semen, cat, dan keramik di lokasi proyek.',
                'last_maintenance' => Carbon::now()->subDays(80),
            ],
            [
                'name' => 'Kompresor Pneumatik Dewalt',
                'code' => 'EQ-019-ALP',
                'status' => 'available',
                'quantity' => 3,
                'description' => 'Kompresor untuk alat tembak paku atau cat semprot di pekerjaan interior rumah dan cafe.',
                'last_maintenance' => Carbon::now()->subDays(14),
            ],
            [
                'name' => 'Generator Set Honda 2000W',
                'code' => 'EQ-020-ALP',
                'status' => 'available',
                'quantity' => 1,
                'description' => 'Genset portabel 2000 watt sebagai sumber listrik cadangan di lokasi proyek rumah atau cafe.',
                'last_maintenance' => Carbon::now()->subDays(20),
            ],
        ];

        foreach ($equipments as $equipment) {
            DB::table('equipments')->insert([
                'name' => $equipment['name'],
                'code' => $equipment['code'],
                'status' => $equipment['status'],
                'quantity' => $equipment['quantity'],
                'description' => $equipment['description'],
                'last_maintenance' => $equipment['last_maintenance'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
