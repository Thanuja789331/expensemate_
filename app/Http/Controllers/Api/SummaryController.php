<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Expense;
use Illuminate\Http\Request;

class SummaryController extends Controller
{
    public function index(Request $request)
    {
        try {
            $userId = $request->user()->id;

            $totalIncome = Expense::forUser($userId)
                ->ofType('income')
                ->sum('amount');

            $totalExpense = Expense::forUser($userId)
                ->ofType('expense')
                ->sum('amount');

            $balance = $totalIncome - $totalExpense;

            // Category breakdown
            $categoryData = Expense::forUser($userId)
                ->ofType('expense')
                ->with('category')
                ->selectRaw('category_id, SUM(amount) as total')
                ->groupBy('category_id')
                ->get()
                ->map(fn($item) => [
                    'category' => $item->category->name ?? 'Unknown',
                    'total'    => (float) $item->total,
                ]);

            // Monthly trend
            $monthlyData = Expense::forUser($userId)
                ->selectRaw('MONTH(expense_date) as month,
                             YEAR(expense_date) as year,
                             type,
                             SUM(amount) as total')
                ->groupBy('year', 'month', 'type')
                ->orderBy('year')
                ->orderBy('month')
                ->get();

            return response()->json([
                'success' => true,
                'data'    => [
                    'total_income'       => (float) $totalIncome,
                    'total_expense'      => (float) $totalExpense,
                    'balance'            => (float) $balance,
                    'category_breakdown' => $categoryData,
                    'monthly_trend'      => $monthlyData,
                ],
                'generated_at' => now()->toDateTimeString(),
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve summary.',
                'error'   => $e->getMessage()
            ], 500);
        }
    }
}
