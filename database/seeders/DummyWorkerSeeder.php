<?php

namespace Database\Seeders;

use App\Models\Worker;
use Illuminate\Support\Arr;
use Illuminate\Database\Seeder;

class DummyWorkerSeeder extends Seeder
{
    public function run()
    {
        $data = [
            ['worker_name' => 'Andi Saputra', 'position' => 'Tukang Batu', 'contact' => '0812-3456-7890'],
            ['worker_name' => 'Budi Santoso', 'position' => 'Tukang Kayu', 'contact' => '0813-9876-5432'],
            ['worker_name' => 'Citra Maharani', 'position' => 'Mandor', 'contact' => '0814-6789-1234'],
            ['worker_name' => 'Dedi Firmansyah', 'position' => 'Tukang Besi', 'contact' => '0815-2345-6789'],
            ['worker_name' => 'Eko Wijaya', 'position' => 'Operator Excavator', 'contact' => '0816-8765-4321'],
            ['worker_name' => 'Fajar Pratama', 'position' => 'Tukang Las', 'contact' => '0817-4567-8901'],
            ['worker_name' => 'Gita Permata', 'position' => 'Administrasi Proyek', 'contact' => '0818-5678-9012'],
            ['worker_name' => 'Hendra Setiawan', 'position' => 'Tukang Cat', 'contact' => '0819-6789-0123'],
            ['worker_name' => 'Indra Maulana', 'position' => 'Helper', 'contact' => '0821-7890-1234'],
            ['worker_name' => 'Joko Widodo', 'position' => 'Surveyor', 'contact' => '0822-8901-2345'],
            ['worker_name' => 'Kurniawan Saputra', 'position' => 'Mandor', 'contact' => '0823-9012-3456'],
            ['worker_name' => 'Lestari Purnama', 'position' => 'Staff Logistik', 'contact' => '0824-0123-4567'],
            ['worker_name' => 'Mulyadi Rahman', 'position' => 'Tukang Pipa', 'contact' => '0825-1234-5678'],
            ['worker_name' => 'Novan Hidayat', 'position' => 'Tukang Plester', 'contact' => '0826-2345-6789'],
            ['worker_name' => 'Oka Ramadhan', 'position' => 'Operator Crane', 'contact' => '0827-3456-7890'],
            ['worker_name' => 'Putra Wijaya', 'position' => 'Tukang Baja', 'contact' => '0828-4567-8901'],
            ['worker_name' => 'Qomaruddin Hasan', 'position' => 'Supervisor Lapangan', 'contact' => '0829-5678-9012'],
            ['worker_name' => 'Rudi Hartanto', 'position' => 'Teknisi Listrik', 'contact' => '0831-6789-0123'],
            ['worker_name' => 'Sandi Prasetyo', 'position' => 'Pengawas Konstruksi', 'contact' => '0832-7890-1234'],
            ['worker_name' => 'Taufik Hidayat', 'position' => 'Helper', 'contact' => '0833-8901-2345'],
            ['worker_name' => 'Ujang Suryana', 'position' => 'Tukang Cor Beton', 'contact' => '0834-9012-3456'],
            ['worker_name' => 'Vino Prasetya', 'position' => 'Operator Forklift', 'contact' => '0835-0123-4567'],
            ['worker_name' => 'Wahyu Setiawan', 'position' => 'Tukang Galian', 'contact' => '0836-1234-5678'],
            ['worker_name' => 'Xander Mahendra', 'position' => 'Inspektor Konstruksi', 'contact' => '0837-2345-6789'],
            ['worker_name' => 'Yoga Pratama', 'position' => 'Tukang Plafon', 'contact' => '0838-3456-7890'],
            ['worker_name' => 'Zainal Abidin', 'position' => 'Montir Alat Berat', 'contact' => '0839-4567-8901'],
            ['worker_name' => 'Bayu Nugroho', 'position' => 'Staff Keselamatan', 'contact' => '0841-5678-9012'],
            ['worker_name' => 'Cahya Pranata', 'position' => 'Tukang Keramik', 'contact' => '0842-6789-0123'],
            ['worker_name' => 'Dani Firmansyah', 'position' => 'Tukang Rangka Atap', 'contact' => '0843-7890-1234'],
            ['worker_name' => 'Edwin Kusuma', 'position' => 'Staff Perencanaan', 'contact' => '0844-8901-2345'],
        ];

        $statuses = ['pekerja_tetap', 'pekerja_lepas'];
        $genders = ['Laki-laki', 'Perempuan'];
        $emergencyNames = ['Ibu', 'Ayah', 'Saudara', 'Pasangan', 'Teman Dekat'];

        foreach ($data as $item) {
            Worker::create([
                'worker_name' => $item['worker_name'],
                'gender' => Arr::random($genders),
                'birth_date' => rand(1970, 2000) . '-' . rand(1, 12) . '-' . rand(1, 28), 
                'position' => $item['position'],
                'address' => 'Jl. Contoh No. ' . rand(1, 100),
                'contact' => $item['contact'],
                'emergency_contact' => '08' . rand(1000, 9999) . '-' . rand(1000, 9999),
                'emergency_contact_name' => Arr::random($emergencyNames) . ' ' . rand(1, 99),
                'employment_status' => Arr::random($statuses),
                'construction_id' => null,
                'photo' => null,
            ]);
        }
    }
}
