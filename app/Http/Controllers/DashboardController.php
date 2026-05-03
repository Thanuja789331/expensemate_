<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Expense;
use App\Models\Category;

class DashboardController extends Controller
{
    public function index()
    {
        $userId = auth()->id();

        $totalIncome = Expense::forUser($userId)
            ->ofType('income')
            ->sum('amount');

        $totalExpense = Expense::forUser($userId)
            ->ofType('expense')
            ->sum('amount');

        $balance = $totalIncome - $totalExpense;

        $recentExpenses = Expense::forUser($userId)
            ->with('category')
            ->latest('expense_date')
            ->take(5)
            ->get();

        return view('dashboard', compact(
            'totalIncome',
            'totalExpense',
            'balance',
            'recentExpenses'
        ));
    }
    public function summary()
{
    $userId = auth()->id();

    $totalIncome = Expense::forUser($userId)
        ->ofType('income')
        ->sum('amount');

    $totalExpense = Expense::forUser($userId)
        ->ofType('expense')
        ->sum('amount');

    $balance = $totalIncome - $totalExpense;

    // Category breakdown for pie chart
    $categoryData = Expense::forUser($userId)
        ->ofType('expense')
        ->with('category')
        ->selectRaw('category_id, SUM(amount) as total')
        ->groupBy('category_id')
        ->get();

    // Monthly trend for bar chart
    $monthlyData = Expense::forUser($userId)
        ->selectRaw('MONTH(expense_date) as month,
                     YEAR(expense_date) as year,
                     type,
                     SUM(amount) as total')
        ->groupBy('year', 'month', 'type')
        ->orderBy('year')
        ->orderBy('month')
        ->get();

    return view('summary', compact(
        'totalIncome',
        'totalExpense',
        'balance',
        'categoryData',
        'monthlyData'
    ));
}
}
