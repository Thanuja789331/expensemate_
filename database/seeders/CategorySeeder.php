<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        // Disable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // Clear categories table
        Category::truncate();

        // Enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $categories = [
            ['name' => 'Food', 'status' => 'active'],
            ['name' => 'Transport', 'status' => 'active'],
            ['name' => 'Bills', 'status' => 'active'],
            ['name' => 'Shopping', 'status' => 'active'],
            ['name' => 'Health', 'status' => 'active'],
            ['name' => 'Education', 'status' => 'active'],
            ['name' => 'Entertainment', 'status' => 'active'],
            ['name' => 'Salary', 'status' => 'active'],
            ['name' => 'Freelance', 'status' => 'active'],
            ['name' => 'Other', 'status' => 'active'],
            ['name' => 'Rent', 'status' => 'active'],
            ['name' => 'Medical', 'status' => 'active'],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }

        $this->command->info('✅ CategorySeeder completed.');
    }
}
