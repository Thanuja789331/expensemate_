<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Expense;
use Illuminate\Database\Seeder;

class ExpenseSeeder extends Seeder
{
    public function run(): void
    {
        $demoUser = User::where('email', 'user@expensemate.com')->first();

        // 50 expenses for demo user
        Expense::factory(50)->create([
            'user_id' => $demoUser->id,
        ]);

        // 20 expenses for other users
        User::where('email', '!=', 'user@expensemate.com')
            ->get()
            ->each(function ($user) {

                Expense::factory(20)->create([
                    'user_id' => $user->id,
                ]);
            });

        $this->command->info('✅ ExpenseSeeder completed.');
    }
}
