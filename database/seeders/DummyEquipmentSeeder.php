<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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
                'description' => 'Bor listrik multifungsi cocok untuk kayu dan beton ringan.',
                'last_maintenance' => Carbon::now()->subDays(12),
            ],
            [
                'name' => 'Gerinda Tangan Makita 9553B',
                'code' => 'EQ-002-ALP',
                'status' => 'available',
                'quantity' => 4,
                'description' => 'Gerinda 4 inci untuk pemotongan besi ringan dan finishing.',
                'last_maintenance' => Carbon::now()->subDays(7),
            ],
            [
                'name' => 'Screwdriver Set Kenmaster',
                'code' => 'EQ-003-ALP',
                'status' => 'available',
                'quantity' => 20,
                'description' => 'Set obeng serbaguna untuk keperluan instalasi ringan.',
                'last_maintenance' => Carbon::now()->subDays(25),
            ],
            [
                'name' => 'Tang Potong Krisbow',
                'code' => 'EQ-004-ALP',
                'status' => 'available',
                'quantity' => 15,
                'description' => 'Tang potong kecil untuk kabel dan kawat ringan.',
                'last_maintenance' => Carbon::now()->subDays(45),
            ],
            [
                'name' => 'Genset Portable Yamaha EF1000',
                'code' => 'EQ-005-ALP',
                'status' => 'under_maintenance',
                'quantity' => 2,
                'description' => 'Genset 1000 watt cocok untuk proyek kecil tanpa listrik PLN.',
                'last_maintenance' => Carbon::now()->subDays(60),
            ],
            [
                'name' => 'Laser Meter Bosch GLM 40',
                'code' => 'EQ-006-ALP',
                'status' => 'available',
                'quantity' => 3,
                'description' => 'Alat ukur digital akurat untuk pengukuran jarak.',
                'last_maintenance' => Carbon::now()->subDays(18),
            ],
            [
                'name' => 'Palu Karet Kenmaster',
                'code' => 'EQ-007-ALP',
                'status' => 'available',
                'quantity' => 10,
                'description' => 'Digunakan untuk pemasangan keramik tanpa merusak permukaan.',
                'last_maintenance' => Carbon::now()->subDays(22),
            ],
            [
                'name' => 'Pemotong Keramik Mollar',
                'code' => 'EQ-008-ALP',
                'status' => 'available',
                'quantity' => 4,
                'description' => 'Tile cutter manual untuk pemotongan presisi.',
                'last_maintenance' => Carbon::now()->subDays(13),
            ],
            [
                'name' => 'Tangga Lipat 4 Meter Aluminium',
                'code' => 'EQ-009-ALP',
                'status' => 'available',
                'quantity' => 6,
                'description' => 'Tangga ringan dan fleksibel untuk instalasi plafon dan lampu.',
                'last_maintenance' => Carbon::now()->subDays(29),
            ],
            [
                'name' => 'Kompresor Mini Lakoni',
                'code' => 'EQ-010-ALP',
                'status' => 'under_maintenance',
                'quantity' => 1,
                'description' => 'Kompresor angin mini untuk alat semprot cat kecil.',
                'last_maintenance' => Carbon::now()->subDays(90),
            ],
            // Additional Equipment Records
            [
                'name' => 'Mesin Las Inverter Jasic',
                'code' => 'EQ-011-ALP',
                'status' => 'available',
                'quantity' => 8,
                'description' => 'Mesin las inverter, cocok untuk berbagai keperluan pengelasan.',
                'last_maintenance' => Carbon::now()->subDays(15),
            ],
            [
                'name' => 'Pompa Air Shimizu',
                'code' => 'EQ-012-ALP',
                'status' => 'under_maintenance',
                'quantity' => 3,
                'description' => 'Pompa air untuk keperluan distribusi air dalam proyek.',
                'last_maintenance' => Carbon::now()->subDays(40),
            ],
            [
                'name' => 'Kunci Inggris Stanley',
                'code' => 'EQ-013-ALP',
                'status' => 'available',
                'quantity' => 12,
                'description' => 'Kunci inggris dengan ukuran bervariasi untuk keperluan mekanik.',
                'last_maintenance' => Carbon::now()->subDays(5),
            ],
            [
                'name' => 'Alat Pemadam Api Portabel',
                'code' => 'EQ-014-ALP',
                'status' => 'available',
                'quantity' => 10,
                'description' => 'Pemadam api portabel yang dapat digunakan dalam kondisi darurat.',
                'last_maintenance' => Carbon::now()->subDays(100),
            ],
            [
                'name' => 'Pompa Beton Putzmeister',
                'code' => 'EQ-015-ALP',
                'status' => 'available',
                'quantity' => 2,
                'description' => 'Pompa beton besar untuk pemompaan beton dalam proyek konstruksi.',
                'last_maintenance' => Carbon::now()->subDays(30),
            ],
            [
                'name' => 'Mixer Beton Electric',
                'code' => 'EQ-016-ALP',
                'status' => 'under_maintenance',
                'quantity' => 1,
                'description' => 'Mixer beton electric untuk proyek pembangunan.',
                'last_maintenance' => Carbon::now()->subDays(65),
            ],
            [
                'name' => 'Alat Pendeteksi Logam Garrett',
                'code' => 'EQ-017-ALP',
                'status' => 'available',
                'quantity' => 5,
                'description' => 'Alat pendeteksi logam portabel untuk survei lokasi.',
                'last_maintenance' => Carbon::now()->subDays(50),
            ],
            [
                'name' => 'Trolley Barang 300 Kg',
                'code' => 'EQ-018-ALP',
                'status' => 'available',
                'quantity' => 6,
                'description' => 'Trolley untuk membawa barang berat dengan kapasitas hingga 300 Kg.',
                'last_maintenance' => Carbon::now()->subDays(80),
            ],
            [
                'name' => 'Kompresor Pneumatik Dewalt',
                'code' => 'EQ-019-ALP',
                'status' => 'available',
                'quantity' => 3,
                'description' => 'Kompresor pneumatik untuk alat-alat berbasis udara.',
                'last_maintenance' => Carbon::now()->subDays(14),
            ],
            [
                'name' => 'Generator Set Honda 2000W',
                'code' => 'EQ-020-ALP',
                'status' => 'available',
                'quantity' => 1,
                'description' => 'Genset portabel 2000 watt untuk keperluan darurat.',
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
