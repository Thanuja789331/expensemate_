<?php
namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Expense;
use App\Models\Category;

class ExpenseManager extends Component
{
    use WithPagination;

    // Form fields
    public string $type         = 'expense';
    public ?int $category_id    = null;
    public float $amount        = 0;
    public string $note         = '';
    public string $expense_date = '';

    // UI state
    public bool $showForm     = false;
    public ?int $editingId    = null;
    public string $search     = '';
    public string $filterType = 'all';

    // Validation rules
    protected function rules(): array
    {
        return [
            'type'         => 'required|in:income,expense',
            'category_id'  => 'required|exists:categories,id',
            'amount'       => 'required|numeric|min:0.01',
            'note'         => 'nullable|string|max:500',
            'expense_date' => 'required|date|before_or_equal:today',
        ];
    }

    // Real-time validation
    public function updated($field): void
    {
        $this->validateOnly($field);
    }

    // Save or update expense
    public function saveExpense(): void
    {
        $this->validate();

        $data = [
            'type'         => $this->type,
            'category_id'  => $this->category_id,
            'amount'       => $this->amount,
            'note'         => $this->note,
            'expense_date' => $this->expense_date,
        ];

        if ($this->editingId) {
            $expense = Expense::findOrFail($this->editingId);

            if ($expense->user_id !== auth()->id()) {
                abort(403);
            }

            $expense->update($data);
            session()->flash('success', 'Expense updated!');
        } else {
            Expense::create(array_merge($data, [
                'user_id' => auth()->id()
            ]));
            session()->flash('success', 'Expense saved!');
        }

        $this->resetForm();
    }

    // Load expense for editing
    public function editExpense(int $id): void
    {
        $expense = Expense::findOrFail($id);

        if ($expense->user_id !== auth()->id()) {
            abort(403);
        }

        $this->editingId    = $expense->id;
        $this->type         = $expense->type;
        $this->category_id  = $expense->category_id;
        $this->amount       = $expense->amount;
        $this->note         = $expense->note ?? '';
        $this->expense_date = $expense->expense_date->format('Y-m-d');
        $this->showForm     = true;
    }

    // Delete expense
    public function deleteExpense(int $id): void
    {
        $expense = Expense::findOrFail($id);

        if ($expense->user_id !== auth()->id()) {
            abort(403);
        }

        $expense->delete();
        session()->flash('success', 'Expense deleted!');
    }

    // Reset form
    public function resetForm(): void
    {
        $this->reset([
            'showForm', 'editingId', 'type',
            'category_id', 'amount', 'note', 'expense_date'
        ]);
        $this->type = 'expense';
    }

    public function render()
    {
        $expenses = Expense::forUser(auth()->id())
            ->with('category')
            ->when($this->search, fn($q) =>
                $q->where('note', 'like', '%' . $this->search . '%')
                  ->orWhereHas('category', fn($q) =>
                      $q->where('name', 'like', '%' . $this->search . '%')
                  )
            )
            ->when($this->filterType !== 'all', fn($q) =>
                $q->ofType($this->filterType)
            )
            ->latest('expense_date')
            ->paginate(10);

        return view('livewire.expense-manager', [
            'expenses'   => $expenses,
            'categories' => Category::active()->get(),
        ]);
    }
}
