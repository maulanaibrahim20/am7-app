<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            'admin',
            'staff',
            'cashier',
            'mechanic'
        ];

        foreach ($roles as $role) {
            Role::create([
                'name' => $role,
            ]);
        }

        $admin = User::factory()->create([
            'name' => 'admin',
            'email' => 'admin@mailinator.com',
        ]);

        $admin->assignRole('admin');

        $staff = User::factory()->create([
            'name' => 'staff',
            'email' => 'staff@mailinator.com',
        ]);

        $staff->assignRole('staff');

        $cashier = User::factory()->create([
            'name' => 'cashier',
            'email' => 'cashier@mailinator.com',
        ]);

        $cashier->assignRole('cashier');

        $mechanic = User::factory()->create([
            'name' => 'mechanic',
            'email' => 'mechanic@mailinator.com',
        ]);

        $mechanic->assignRole('mechanic');
    }
}
