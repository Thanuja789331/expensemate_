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
}
