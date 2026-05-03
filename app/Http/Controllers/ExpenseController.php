<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Expense;
use App\Models\Category;

class ExpenseController extends Controller
{
    // Show all expenses
    public function index()
    {
        $expenses = Expense::forUser(auth()->id())
            ->with('category')
            ->latest('expense_date')
            ->paginate(15);

        return view('expenses.index', compact('expenses'));
    }

    // Show create form
    public function create()
    {
        $categories = Category::active()->get();
        return view('expenses.create', compact('categories'));
    }

    // Save new expense
    public function store(Request $request)
    {
        $request->validate([
            'type'         => 'required|in:income,expense',
            'category_id'  => 'required|exists:categories,id',
            'amount'       => 'required|numeric|min:0.01',
            'note'         => 'nullable|string|max:500',
            'expense_date' => 'required|date|before_or_equal:today',
        ]);

        Expense::create([
            'user_id'      => auth()->id(),
            'type'         => $request->type,
            'category_id'  => $request->category_id,
            'amount'       => $request->amount,
            'note'         => $request->note,
            'expense_date' => $request->expense_date,
        ]);

        return redirect()->route('dashboard')
            ->with('success', 'Expense saved successfully!');
    }

    // Show edit form
    public function edit(Expense $expense)
    {
        // Security: only owner can edit
        if ($expense->user_id !== auth()->id()) {
            abort(403);
        }

        $categories = Category::active()->get();
        return view('expenses.edit', compact('expense', 'categories'));
    }

    // Update expense
    public function update(Request $request, Expense $expense)
    {
        // Security: only owner can update
        if ($expense->user_id !== auth()->id()) {
            abort(403);
        }

        $request->validate([
            'type'         => 'required|in:income,expense',
            'category_id'  => 'required|exists:categories,id',
            'amount'       => 'required|numeric|min:0.01',
            'note'         => 'nullable|string|max:500',
            'expense_date' => 'required|date|before_or_equal:today',
        ]);

        $expense->update($request->all());

        return redirect()->route('dashboard')
            ->with('success', 'Expense updated successfully!');
    }

    // Delete expense
    public function destroy(Expense $expense)
    {
        // Security: only owner can delete
        if ($expense->user_id !== auth()->id()) {
            abort(403);
        }

        $expense->delete();

        return redirect()->route('dashboard')
            ->with('success', 'Expense deleted successfully!');
    }
}
