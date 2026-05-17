<?php

namespace Database\Factories;

use App\Models\Expense;
use App\Models\User;
use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

class ExpenseFactory extends Factory
{
    protected $model = Expense::class;

    public function definition(): array
    {
        // 70% expense, 30% income
        $type = $this->faker->randomElement([
            'expense', 'expense', 'expense', 'expense', 'expense',
            'expense', 'expense', 'income', 'income', 'income',
        ]);

        $amount = $this->faker->randomFloat(2, 10, 5000);

        $notes = [
            'expense' => [
                'Paid for grocery shopping',
                'Monthly electricity bill payment',
                'Dinner at restaurant with family',
                'Bought new clothes for work',
                'Car fuel refill',
                'Doctor consultation fee',
                'Internet bill payment',
                'Coffee and snacks at cafe',
                'Gym membership fee',
                'Bought medicine from pharmacy',
                'Paid water bill',
                'Bus ticket for commute',
                'Lunch at office canteen',
                'Haircut and grooming',
                'Movie tickets for weekend',
                'Books and stationery',
                'Repaired laptop charger',
                'Parking fee at mall',
                'Subscription renewal',
                'Home cleaning supplies',
            ],
            'income' => [
                'Monthly salary received',
                'Freelance project payment',
                'Client invoice cleared',
                'Bonus from employer',
                'Part time work payment',
                'Sold old laptop',
                'Investment returns credited',
                'Rental income received',
                'Online tutoring payment',
                'Dividend from shares',
                'Cashback reward credited',
                'Gift money received',
                'Side hustle income',
                'Consulting fee received',
                'Refund from online order',
            ],
        ];

        return [
            'user_id'      => User::inRandomOrder()->first()?->id ?? 1,
            'category_id'  => Category::inRandomOrder()->first()?->id ?? 1,
            'type'         => $type,
            'amount'       => $amount,
            'note'         => $this->faker->randomElement($notes[$type]),
            'expense_date' => $this->faker->dateTimeBetween('-6 months', 'now')->format('Y-m-d'),
        ];
    }

    // State: force income type
    public function income(): static
    {
        return $this->state([
            'type'   => 'income',
            'note'   => $this->faker->randomElement([
                'Monthly salary received',
                'Freelance project payment',
                'Client invoice cleared',
                'Bonus from employer',
                'Investment returns credited',
            ]),
        ]);
    }

    // State: force expense type
    public function expense(): static
    {
        return $this->state([
            'type'   => 'expense',
            'note'   => $this->faker->randomElement([
                'Paid for grocery shopping',
                'Monthly electricity bill payment',
                'Dinner at restaurant with family',
                'Car fuel refill',
                'Doctor consultation fee',
            ]),
        ]);
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
}
