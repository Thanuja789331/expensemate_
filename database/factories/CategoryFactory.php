<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

class CategoryFactory extends Factory
{
    protected $model = Category::class;

    public function definition(): array
    {
        $names = [
            'Food', 'Transport', 'Bills', 'Shopping', 'Health',
            'Education', 'Entertainment', 'Salary', 'Freelance',
            'Other', 'Rent', 'Medical', 'Groceries', 'Travel',
            'Investment',
        ];

        return [
            'name'   => $this->faker->unique()->randomElement($names),
            'status' => $this->faker->randomElement([
                'active', 'active', 'active', 'active',
                'active', 'active', 'active', 'active',
                'inactive', 'inactive',
            ]),
        ];
    }

    // Convenience states
    public function active(): static
    {
        return $this->state(['status' => 'active']);
    }

    public function inactive(): static
    {
        return $this->state(['status' => 'inactive']);
    }
}
