<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Budget;
use App\Models\Category;
use Illuminate\Database\Seeder;

class BudgetSeeder extends Seeder
{
    public function run(): void
    {
        $demoUser = User::where('email', 'user@expensemate.com')->first();

        $categories = Category::take(5)->get();

        foreach ($categories as $category) {

            Budget::create([
                'user_id' => $demoUser->id,
                'category_id' => $category->id,
                'limit_amount' => rand(1000, 5000),
                'period_month' => now()->month,
                'period_year' => now()->year,
                'alert_sent' => false,
            ]);
        }

        $this->command->info('✅ BudgetSeeder completed.');
    }
}
