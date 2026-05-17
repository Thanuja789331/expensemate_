<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Admin user
        User::updateOrCreate(
            ['email' => 'admin@expensemate.com'],
            [
                'name' => 'Admin User',
                'password' => Hash::make('Admin@12345'),
                'role' => 'admin',
                'is_active' => true,
            ]
        );

        // Demo user
        User::updateOrCreate(
            ['email' => 'user@expensemate.com'],
            [
                'name' => 'Demo User',
                'password' => Hash::make('User@12345'),
                'role' => 'user',
                'is_active' => true,
            ]
        );

        // Random users
        User::factory(10)->create();

        $this->command->info('✅ UserSeeder completed.');
    }
}
