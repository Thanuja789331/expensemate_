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

    // Reset pagination on search
    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    // Reset pagination on filter
    public function updatedFilterType(): void
    {
        $this->resetPage();
    }

    // Validation rules
    protected function rules(): array
    {
        return [
            'type'         => 'required|in:income,expense',
            'category_id'  => 'required|exists:categories,id',
            'amount'       => 'required|numeric|min:0.01|max:9999999',
            'note'         => 'nullable|string|max:500',
            'expense_date' => 'required|date|before_or_equal:today',
        ];
    }

    // Validation messages
    protected function messages(): array
    {
        return [
            'category_id.required' => 'Please select a category.',
            'amount.min'           => 'Amount must be greater than zero.',
            'amount.max'           => 'Amount is too large.',
            'expense_date.before_or_equal' =>
                'Cannot record future expenses.',
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

        // Sanitize note
        $note = $this->note
            ? strip_tags(htmlspecialchars(
                $this->note, ENT_QUOTES, 'UTF-8'))
            : null;

        $data = [
            'type'         => $this->type,
            'category_id'  => $this->category_id,
            'amount'       => $this->amount,
            'note'         => $note,
            'expense_date' => $this->expense_date,
        ];

        if ($this->editingId) {
            $expense = Expense::findOrFail($this->editingId);

            // Security: only owner can edit
            if ($expense->user_id !== auth()->id()) {
                abort(403);
            }

            $expense->update($data);
            session()->flash('success', '✅ Expense updated!');

        } else {
            Expense::create(array_merge($data, [
                'user_id' => auth()->id()
            ]));
            session()->flash('success', '✅ Expense saved!');
        }

        $this->resetForm();
    }

    // Load expense for editing
    public function editExpense(int $id): void
    {
        $expense = Expense::findOrFail($id);

        // Security: only owner can edit
        if ($expense->user_id !== auth()->id()) {
            abort(403);
        }

        $this->editingId    = $expense->id;
        $this->type         = $expense->type;
        $this->category_id  = $expense->category_id;
        $this->amount       = $expense->amount;
        $this->note         = $expense->note ?? '';
        $this->expense_date = $expense->expense_date
            ->format('Y-m-d');
        $this->showForm     = true;

        // Scroll to the edit form if the browser supports the event
        $this->dispatchBrowserEvent('scroll-to-form');
    }

    // Delete expense
    public function deleteExpense(int $id): void
    {
        $expense = Expense::findOrFail($id);

        // Security: only owner can delete
        if ($expense->user_id !== auth()->id()) {
            abort(403);
        }

        $expense->delete();
        session()->flash('success', '🗑️ Expense deleted!');
    }

    // Reset form
    public function resetForm(): void
    {
        $this->reset([
            'showForm', 'editingId', 'type',
            'category_id', 'amount', 'note',
            'expense_date'
        ]);
        $this->type = 'expense';
        $this->resetValidation();
    }

    public function render()
    {
        $expenses = Expense::forUser(auth()->id())
            ->with('category')
            ->when($this->search, fn($q) =>
                $q->where('note', 'like',
                    '%' . $this->search . '%')
                  ->orWhereHas('category', fn($q) =>
                      $q->where('name', 'like',
                          '%' . $this->search . '%')
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
