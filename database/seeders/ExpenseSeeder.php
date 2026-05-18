<?php

namespace Database\Seeders;

use App\Models\Expense;
use App\Models\User;
use App\Models\Category;
use Database\Factories\ExpenseFactory;
use Illuminate\Database\Seeder;

class ExpenseSeeder extends Seeder
{
    public function run(): void
    {
        // Clear existing expenses first
        Expense::truncate();

        // Get demo user
        $demoUser = User::where('email', 'user@expensemate.com')->first();

        // Get all categories
        $categories = Category::all();

        if (!$demoUser) {
            $this->command->warn('⚠️  Demo user not found. Run UserSeeder first.');
            return;
        }

        if ($categories->isEmpty()) {
            $this->command->warn('⚠️  No categories found. Run CategorySeeder first.');
            return;
        }

        // ── 50 expenses for demo user ────────────────────
        for ($i = 0; $i < 50; $i++) {
            Expense::factory()
                ->forUser($demoUser->id)
                ->forCategory($categories->random()->id)
                ->create();
        }

        $this->command->info('✅ Created 50 expenses for demo user.');

        // ── 20 expenses for each random user ────────────
        $randomUsers = User::where('email', '!=', 'admin@expensemate.com')
            ->where('email', '!=', 'user@expensemate.com')
            ->get();

        foreach ($randomUsers as $user) {
            for ($i = 0; $i < 20; $i++) {
                Expense::factory()
                    ->forUser($user->id)
                    ->forCategory($categories->random()->id)
                    ->create();
            }
        }

        $total = 50 + ($randomUsers->count() * 20);
        $this->command->info("✅ ExpenseSeeder: {$total} total expenses created.");
    }
}
