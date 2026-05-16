<?php

namespace App\Livewire;

use App\Models\Budget;
use App\Models\Category;
use App\Models\Expense;
use Livewire\Component;
use Livewire\WithPagination;

class BudgetManager extends Component
{
    use WithPagination;

    public string $category_id  = '';
    public string $limit_amount = '';
    public string $month        = '';
    public string $year         = '';

    public ?int   $editingId       = null;
    public string $editLimitAmount = '';
    public string $editMonth       = '';
    public string $editYear        = '';

    public ?int $deletingId = null;

    public bool $showAddModal    = false;
    public bool $showDeleteModal = false;

    public function mount(): void
    {
        $this->month = date('m');
        $this->year  = date('Y');
    }

    public function openAddModal(): void
    {
        $this->reset(['category_id', 'limit_amount']);
        $this->month        = date('m');
        $this->year         = date('Y');
        $this->showAddModal = true;
    }

    public function saveBudget(): void
    {
        $this->validate([
            'category_id'  => 'required|exists:categories,id',
            'limit_amount' => 'required|numeric|min:1|max:999999',
            'month'        => 'required|numeric|min:1|max:12',
            'year'         => 'required|numeric|min:2020|max:2099',
        ]);

        $exists = Budget::where('user_id', auth()->id())
            ->where('category_id', $this->category_id)
            ->where('period_month', (int) $this->month)
            ->where('period_year', (int) $this->year)
            ->exists();

        if ($exists) {
            $this->addError('category_id', 'A budget for this category and month already exists.');
            return;
        }

        Budget::create([
            'user_id'      => auth()->id(),
            'category_id'  => $this->category_id,
            'limit_amount' => $this->limit_amount,
            'period_month' => (int) $this->month,
            'period_year'  => (int) $this->year,
        ]);

        $this->reset(['category_id', 'limit_amount']);
        $this->showAddModal = false;
        $this->dispatch('notify', message: 'Budget added!', type: 'success');
    }

    public function startEdit(int $id): void
    {
        $budget = Budget::findOrFail($id);
        $this->editingId       = $id;
        $this->editLimitAmount = $budget->limit_amount;
        $this->editMonth       = str_pad($budget->period_month, 2, '0', STR_PAD_LEFT);
        $this->editYear        = (string) $budget->period_year;
    }

    public function updateBudget(): void
    {
        $this->validate([
            'editLimitAmount' => 'required|numeric|min:1|max:999999',
            'editMonth'       => 'required|numeric|min:1|max:12',
            'editYear'        => 'required|numeric|min:2020|max:2099',
        ]);

        Budget::findOrFail($this->editingId)->update([
            'limit_amount' => $this->editLimitAmount,
            'period_month' => (int) $this->editMonth,
            'period_year'  => (int) $this->editYear,
        ]);

        $this->editingId = null;
        $this->dispatch('notify', message: 'Budget updated!', type: 'success');
    }

    public function cancelEdit(): void
    {
        $this->editingId = null;
    }

    public function confirmDelete(int $id): void
    {
        $this->deletingId      = $id;
        $this->showDeleteModal = true;
    }

    public function deleteBudget(): void
    {
        Budget::findOrFail($this->deletingId)->delete();
        $this->deletingId      = null;
        $this->showDeleteModal = false;
        $this->dispatch('notify', message: 'Budget deleted!', type: 'success');
    }

    private function getSpent(Budget $budget): float
    {
        return (float) Expense::where('user_id', auth()->id())
            ->where('category_id', $budget->category_id)
            ->where('type', 'expense')
            ->whereMonth('expense_date', $budget->period_month)
            ->whereYear('expense_date', $budget->period_year)
            ->sum('amount');
    }

    public function render()
    {
        $budgets = Budget::where('user_id', auth()->id())
            ->with('category')
            ->orderBy('period_year', 'desc')
            ->orderBy('period_month', 'desc')
            ->paginate(8);

        $budgets->getCollection()->transform(function ($budget) {
            $budget->spent   = $this->getSpent($budget);
            $budget->percent = $budget->limit_amount > 0
                ? min(100, round(($budget->spent / $budget->limit_amount) * 100))
                : 0;
            return $budget;
        });

        $categories = Category::active()->orderBy('name')->get();
        $months     = [
            '01' => 'January',  '02' => 'February', '03' => 'March',
            '04' => 'April',    '05' => 'May',       '06' => 'June',
            '07' => 'July',     '08' => 'August',    '09' => 'September',
            '10' => 'October',  '11' => 'November',  '12' => 'December',
        ];

        return view('livewire.budget-manager', compact('budgets', 'categories', 'months'));
    }
}
