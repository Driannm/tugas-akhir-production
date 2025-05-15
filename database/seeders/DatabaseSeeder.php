<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            ShieldSetupSeeder::class,
            DummyMaterialSeeder::class,
            DummyConstructionSeeder::class,
            DummyWorkerSeeder::class,
            DummyEquipmentSeeder::class,
        ]);

        // User::create([
        //     'name' => 'Superadmin - Drian',
        //     'email' => 'superadmin@ajunaproperty.com',
        //     'password' => bcrypt('superadmin1'),
        // ]);

        // User::create([
        //     'name' => 'Supervisor - Adnan',
        //     'email' => 'supervisor1@ajunaproperty.com',
        //     'password' => bcrypt('supervisor1'),
        // ]);

        // User::create([
        //     'name' => 'Supervisor - Khafid',
        //     'email' => 'supervisor2@ajunaproperty.com',
        //     'password' => bcrypt('supervisor2'),
        // ]);
    }
}
