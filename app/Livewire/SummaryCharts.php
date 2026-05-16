<?php

namespace App\Livewire;

use App\Models\Expense;
use App\Models\Category;
use Livewire\Component;

class SummaryCharts extends Component
{
    // Filters
    public string $filterType  = 'all';
    public string $filterMonth = '';
    public string $filterYear  = '';

    public function mount(): void
    {
        $this->filterMonth = date('m');
        $this->filterYear  = date('Y');
    }

    // Reset to current month
    public function resetFilters(): void
    {
        $this->filterType  = 'all';
        $this->filterMonth = date('m');
        $this->filterYear  = date('Y');
    }

    public function render()
    {
        $userId = auth()->id();

        // ── Base query ──────────────────────────────────
        $query = Expense::where('user_id', $userId)
            ->when($this->filterType !== 'all', fn($q) =>
                $q->where('type', $this->filterType)
            )
            ->when($this->filterMonth, fn($q) =>
                $q->whereMonth('expense_date', $this->filterMonth)
            )
            ->when($this->filterYear, fn($q) =>
                $q->whereYear('expense_date', $this->filterYear)
            );

        // ── Summary totals ───────────────────────────────
        $totalIncome = (float) (clone $query)
            ->where('type', 'income')->sum('amount');

        $totalExpense = (float) (clone $query)
            ->where('type', 'expense')->sum('amount');

        $balance = $totalIncome - $totalExpense;

        // ── Category breakdown (expenses only) ──────────
        $categoryData = Expense::where('user_id', $userId)
            ->where('type', 'expense')
            ->when($this->filterMonth, fn($q) =>
                $q->whereMonth('expense_date', $this->filterMonth)
            )
            ->when($this->filterYear, fn($q) =>
                $q->whereYear('expense_date', $this->filterYear)
            )
            ->with('category')
            ->get()
            ->groupBy('category_id')
            ->map(fn($group) => [
                'name'   => $group->first()->category->name ?? 'Uncategorized',
                'amount' => round($group->sum('amount'), 2),
                'count'  => $group->count(),
            ])
            ->sortByDesc('amount')
            ->values();

        // ── Monthly trend (last 6 months) ───────────────
        $monthlyTrend = collect();
        for ($i = 5; $i >= 0; $i--) {
            $date  = now()->subMonths($i);
            $month = $date->month;
            $year  = $date->year;

            $inc = (float) Expense::where('user_id', $userId)
                ->where('type', 'income')
                ->whereMonth('expense_date', $month)
                ->whereYear('expense_date', $year)
                ->sum('amount');

            $exp = (float) Expense::where('user_id', $userId)
                ->where('type', 'expense')
                ->whereMonth('expense_date', $month)
                ->whereYear('expense_date', $year)
                ->sum('amount');

            $monthlyTrend->push([
                'label'   => $date->format('M Y'),
                'income'  => $inc,
                'expense' => $exp,
            ]);
        }

        // ── Months / Years for filters ───────────────────
        $months = [
            '01' => 'January',  '02' => 'February', '03' => 'March',
            '04' => 'April',    '05' => 'May',       '06' => 'June',
            '07' => 'July',     '08' => 'August',    '09' => 'September',
            '10' => 'October',  '11' => 'November',  '12' => 'December',
        ];

        $years = collect(range(date('Y') - 3, date('Y') + 1))
            ->mapWithKeys(fn($y) => [$y => $y]);

        return view('livewire.summary-charts', compact(
            'totalIncome',
            'totalExpense',
            'balance',
            'categoryData',
            'monthlyTrend',
            'months',
            'years'
        ));
    }
}
