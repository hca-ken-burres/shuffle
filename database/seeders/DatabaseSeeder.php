<?php

namespace Database\Seeders;

use App\Models\PaymentMethod;
use App\Models\Requisition;
use App\Models\User;
use App\Models\Vendor;
use App\Models\Year;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        $admin = User::factory()->create([
            'name' => 'Administrator',
            'email' => 'techdept@hca.org',
            'password' => bcrypt('password'),
            'admin' => true
        ]);

        $manager = User::factory()->create([
            'name' => 'Manager',
            'email' => 'manager@hca.org',
            'password' => bcrypt('password'),
            'admin' => false
        ]);

        Vendor::factory()->create([
            'name' => 'Amazon',
        ]);

        Year::factory()->create(['starts_in' => 2023, 'ends_in' => 2024]);
        Year::factory()->create(['starts_in' => 2024, 'ends_in' => 2025]);
        // Year::factory()->create(['starts_in' => 2025, 'ends_in' => 2026]);

        $managerRole = Role::create(['name' => 'manager']);
        $permission = Permission::create(['name' => 'edit requisitions']);
        $managerRole->givePermissionTo($permission);

        $manager->assignRole('manager');

        $superAdminRole = Role::create(['name' => 'Super Admin']);
        $admin->assignRole('Super Admin');

        User::factory()->count(3)->create();

        Requisition::factory()->count(10)->create();
    }
}
