<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // Indexes for expenses table
        Schema::table('expenses', function (Blueprint $table) {
            // Speed up user dashboard queries
            $table->index(['user_id', 'expense_date'],
                'expenses_user_date_index');

            // Speed up income/expense filtering
            $table->index(['user_id', 'type'],
                'expenses_user_type_index');

            // Speed up category queries
            $table->index(['user_id', 'category_id'],
                'expenses_user_category_index');
        });

        // Indexes for categories table
        Schema::table('categories', function (Blueprint $table) {
            // Speed up active category queries
            $table->index(['status'],
                'categories_status_index');
        });

        // Indexes for budgets table
        Schema::table('budgets', function (Blueprint $table) {
            // Speed up budget lookups
            $table->index(['user_id', 'category_id'],
                'budgets_user_category_index');

            // Speed up period queries
            $table->index(['user_id', 'period_year', 'period_month'],
                'budgets_user_period_index');
        });
    }

    public function down(): void
    {
        Schema::table('expenses', function (Blueprint $table) {
            $table->dropIndex('expenses_user_date_index');
            $table->dropIndex('expenses_user_type_index');
            $table->dropIndex('expenses_user_category_index');
        });

        Schema::table('categories', function (Blueprint $table) {
            $table->dropIndex('categories_status_index');
        });

        Schema::table('budgets', function (Blueprint $table) {
            $table->dropIndex('budgets_user_category_index');
            $table->dropIndex('budgets_user_period_index');
        });
    }
};
