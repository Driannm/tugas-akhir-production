<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;
use App\Models\User;
use Spatie\Permission\Models\Role;

class ShieldSetupSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Generate permissions & policies

        // 2. Create roles if not exist
        $superAdminRole = Role::firstOrCreate(['name' => 'super_admin']);
        $supervisorRole = Role::firstOrCreate(['name' => 'supervisor']);

        // 3. Create Superadmin
        $superAdmin = User::firstOrCreate(
            ['email' => 'superadmin@ajunaproperty.com'],
            [
                'name' => 'Superadmin - Drian',
                'password' => bcrypt('superadmin1'),
            ]
        );
        $superAdmin->assignRole($superAdminRole);

        // 4. Create Supervisors
        $supervisor1 = User::firstOrCreate(
            ['email' => 'supervisor1@ajunaproperty.com'],
            [
                'name' => 'Supervisor - Adnan',
                'password' => bcrypt('supervisor1'),
            ]
        );
        $supervisor1->assignRole($supervisorRole);

        $supervisor2 = User::firstOrCreate(
            ['email' => 'supervisor2@ajunaproperty.com'],
            [
                'name' => 'Supervisor - Khafid',
                'password' => bcrypt('supervisor2'),
            ]
        );
        $supervisor2->assignRole($supervisorRole);

        // 5. Info log
        $this->command->info('✅ Super Admin & Supervisors created and assigned roles.');
    }
}
