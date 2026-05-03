<?php
namespace Database\Factories;

use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ExpenseFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id'      => User::factory(),
            'category_id'  => Category::factory(),
            'type'         => $this->faker->randomElement(['income', 'expense']),
            'amount'       => $this->faker->randomFloat(2, 10, 5000),
            'note'         => $this->faker->sentence(),
            'expense_date' => $this->faker->dateTimeBetween('-6 months', 'now')
                                         ->format('Y-m-d'),
        ];
    }
}
