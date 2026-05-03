<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        // Create Admin account
        User::create([
            'name'      => 'Admin',
            'email'     => 'admin@expensemate.com',
            'password'  => Hash::make('Admin@12345'),
            'role'      => 'admin',
            'is_active' => true,
        ]);

        // Create Demo User account
        User::create([
            'name'      => 'Demo User',
            'email'     => 'user@expensemate.com',
            'password'  => Hash::make('User@12345'),
            'role'      => 'user',
            'is_active' => true,
        ]);
    }
}
