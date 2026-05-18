<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Clear existing users first
        User::truncate();

        // ── 1. Admin User ────────────────────────────────
        User::create([
            'name'       => 'Admin User',
            'email'      => 'admin@expensemate.com',
            'password'   => Hash::make('Admin@12345'),
            'role'       => 'admin',
            'is_active'  => true,
            'currency'   => 'USD',
            'country'    => 'US',
            'email_verified_at' => now(),
        ]);

        // ── 2. Demo User ─────────────────────────────────
        User::create([
            'name'       => 'Demo User',
            'email'      => 'user@expensemate.com',
            'password'   => Hash::make('User@12345'),
            'role'       => 'user',
            'is_active'  => true,
            'currency'   => 'USD',
            'country'    => 'US',
            'email_verified_at' => now(),
        ]);

        // ── 3. 10 Random Users ───────────────────────────
        User::factory(10)->create([
            'role'      => 'user',
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        $this->command->info('✅ UserSeeder: Admin + Demo + 10 random users created.');
    }
}
