<?php

namespace Database\Factories;

use App\Models\Budget;
use App\Models\User;
use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

class BudgetFactory extends Factory
{
    protected $model = Budget::class;

    public function definition(): array
    {
        // Current month or last month
        $monthOffset = $this->faker->randomElement([0, 0, 0, 1]);
        $date        = now()->subMonths($monthOffset);

        return [
            'user_id'      => User::inRandomOrder()->first()?->id ?? 1,
            'category_id'  => Category::inRandomOrder()->first()?->id ?? 1,
            'limit_amount' => $this->faker->randomFloat(2, 100, 5000),
            'period_month' => (int) $date->format('m'),
            'period_year'  => (int) $date->format('Y'),
            'alert_sent'   => false,
        ];
    }

    // State: for a specific user
    public function forUser(int $userId): static
    {
        return $this->state(['user_id' => $userId]);
    }

    // State: for a specific category
    public function forCategory(int $categoryId): static
    {
        return $this->state(['category_id' => $categoryId]);
    }

    // State: current month only
    public function currentMonth(): static
    {
        return $this->state([
            'period_month' => (int) now()->format('m'),
            'period_year'  => (int) now()->format('Y'),
        ]);
    }

    // State: last month only
    public function lastMonth(): static
    {
        return $this->state([
            'period_month' => (int) now()->subMonth()->format('m'),
            'period_year'  => (int) now()->subMonth()->format('Y'),
        ]);
    }
}
