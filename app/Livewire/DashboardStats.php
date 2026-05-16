<?php

namespace App\Livewire;

use App\Models\Expense;
use Livewire\Component;

class DashboardStats extends Component
{
    // Auto refresh every 30 seconds
    protected $listeners = ['refreshStats' => '$refresh'];

    public function render()
    {
        $userId = auth()->id();

        // ── ALL TIME ──────────────────────────────────────
        $totalIncome = (float) Expense::where('user_id', $userId)
            ->where('type', 'income')
            ->sum('amount');

        $totalExpense = (float) Expense::where('user_id', $userId)
            ->where('type', 'expense')
            ->sum('amount');

        $balance = $totalIncome - $totalExpense;

        $totalTransactions = Expense::where('user_id', $userId)->count();

        // ── THIS MONTH ────────────────────────────────────
        $monthIncome = (float) Expense::where('user_id', $userId)
            ->where('type', 'income')
            ->whereMonth('expense_date', now()->month)
            ->whereYear('expense_date', now()->year)
            ->sum('amount');

        $monthExpense = (float) Expense::where('user_id', $userId)
            ->where('type', 'expense')
            ->whereMonth('expense_date', now()->month)
            ->whereYear('expense_date', now()->year)
            ->sum('amount');

        // ── SAVINGS RATE ──────────────────────────────────
        $savingsRate = $totalIncome > 0
            ? round((($totalIncome - $totalExpense) / $totalIncome) * 100, 1)
            : 0;

        return view('livewire.dashboard-stats', compact(
            'totalIncome',
            'totalExpense',
            'balance',
            'totalTransactions',
            'monthIncome',
            'monthExpense',
            'savingsRate'
        ));
    }
}
