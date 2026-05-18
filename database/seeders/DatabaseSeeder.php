<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('🌱 Starting ExpenseMate Database Seeder...');
        $this->command->info('----------------------------------------');

        // ── Step 1: Categories ───────────────────────────
        $this->command->info('📂 Seeding categories...');
        $this->call(CategorySeeder::class);

        // ── Step 2: Users ────────────────────────────────
        $this->command->info('👥 Seeding users...');
        $this->call(UserSeeder::class);

        // ── Step 3: Expenses ─────────────────────────────
        $this->command->info('💸 Seeding expenses...');
        $this->call(ExpenseSeeder::class);

        // ── Step 4: Budgets ──────────────────────────────
        $this->command->info('💰 Seeding budgets...');
        $this->call(BudgetSeeder::class);

        $this->command->info('----------------------------------------');
        $this->command->info('✅ All seeders completed successfully!');
        $this->command->info('');
        $this->command->info('🔑 Login credentials:');
        $this->command->info('   Admin : admin@expensemate.com / Admin@12345');
        $this->command->info('   User  : user@expensemate.com  / User@12345');
    }
}
