<?php

namespace Database\Seeders;

use App\Models\Worker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use Faker\Factory as Faker;

class DummyWorkerSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create('id_ID');

        // Posisi Pekerja (realistis untuk proyek rumah, cafe, villa, kontrakan)
        $positions = [
            'Tukang Batu', 'Tukang Kayu', 'Tukang Besi', 'Tukang Plester', 'Tukang Cat',
            'Tukang Keramik', 'Tukang Plafon', 'Tukang Listrik', 'Tukang Air', 'Tukang Finishing',
            'Mandor', 'Helper'
        ];

        $statuses = ['pekerja_tetap', 'pekerja_lepas'];
        $emergencyRelations = ['Ibu', 'Ayah', 'Saudara', 'Pasangan', 'Teman Dekat'];

        // Avatar placeholder (jika ada)
        $avatars = [
            'Laki-laki' => 'avatar-male.jpg',
            'Perempuan' => 'avatar-female.jpg',
        ];

        for ($i = 0; $i < 100; $i++) {
            $gender = $faker->randomElement(['Laki-laki', 'Perempuan']);
            $position = $faker->randomElement($positions);

            // Salin file avatar jika belum ada
            $fileName = $avatars[$gender];
            $sourcePath = public_path('images/dummy-images/' . $fileName);
            $destinationPath = 'worker/images/' . $fileName;

            if (!Storage::disk('public')->exists($destinationPath)) {
                Storage::disk('public')->put($destinationPath, file_get_contents($sourcePath));
            }

            Worker::create([
                'worker_name' => $faker->name($gender == 'Laki-laki' ? 'male' : 'female'),
                'gender' => $gender,
                'birth_date' => $faker->dateTimeBetween('-50 years', '-20 years')->format('Y-m-d'),
                'position' => $position,
                'address' => $faker->address,
                'contact' => '08' . $faker->numberBetween(1000, 9999) . '-' . $faker->numberBetween(1000, 9999),
                'emergency_contact' => '08' . $faker->numberBetween(1000, 9999) . '-' . $faker->numberBetween(1000, 9999),
                'emergency_contact_name' => $faker->randomElement($emergencyRelations) . ' ' . $faker->name,
                'employment_status' => $faker->randomElement($statuses),
                'construction_id' => null,
                'photo' => $destinationPath,
            ]);
        }
    }
}
