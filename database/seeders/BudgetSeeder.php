<?php

namespace Database\Seeders;

use App\Models\Budget;
use App\Models\User;
use App\Models\Category;
use Illuminate\Database\Seeder;

class BudgetSeeder extends Seeder
{
    public function run(): void
    {
        // Clear existing budgets first
        Budget::truncate();

        // Get demo user
        $demoUser = User::where('email', 'user@expensemate.com')->first();

        if (!$demoUser) {
            $this->command->warn('⚠️  Demo user not found. Run UserSeeder first.');
            return;
        }

        // Get 5 different categories
        $categories = Category::inRandomOrder()->take(5)->get();

        if ($categories->count() < 5) {
            $this->command->warn('⚠️  Not enough categories. Run CategorySeeder first.');
            return;
        }

        $currentMonth = (int) now()->format('m');
        $currentYear  = (int) now()->format('Y');

        // ── 5 budgets for demo user ──────────────────────
        $budgetAmounts = [500, 300, 200, 1000, 150];

        foreach ($categories as $index => $category) {
            Budget::create([
                'user_id'      => $demoUser->id,
                'category_id'  => $category->id,
                'limit_amount' => $budgetAmounts[$index],
                'period_month' => $currentMonth,
                'period_year'  => $currentYear,
                'alert_sent'   => false,
            ]);
        }

        $this->command->info('✅ BudgetSeeder: 5 budgets created for demo user.');
    }
}
