<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Expense;
use App\Models\Category;
use Illuminate\Http\Request;

class ExpenseController extends Controller
{
    // Get all expenses
    public function index(Request $request)
    {
        $expenses = Expense::forUser($request->user()->id)
            ->with('category')
            ->when($request->type, fn($q) =>
                $q->ofType($request->type))
            ->when($request->from, fn($q) =>
                $q->whereBetween('expense_date',
                    [$request->from, $request->to ?? now()]))
            ->latest('expense_date')
            ->paginate(20);

        return response()->json($expenses, 200);
    }

    // Create new expense
    public function store(Request $request)
    {
        $request->validate([
            'type'         => 'required|in:income,expense',
            'category_id'  => 'required|exists:categories,id',
            'amount'       => 'required|numeric|min:0.01',
            'note'         => 'nullable|string|max:500',
            'expense_date' => 'required|date|before_or_equal:today',
        ]);

        $expense = Expense::create([
            'user_id'      => $request->user()->id,
            'type'         => $request->type,
            'category_id'  => $request->category_id,
            'amount'       => $request->amount,
            'note'         => $request->note,
            'expense_date' => $request->expense_date,
        ]);

        return response()->json([
            'message' => 'Expense created successfully.',
            'expense' => $expense->load('category'),
        ], 201);
    }

    // Get single expense
    public function show(Request $request, int $id)
    {
        $expense = Expense::forUser($request->user()->id)
            ->with('category')
            ->findOrFail($id);

        return response()->json($expense, 200);
    }

    // Update expense
    public function update(Request $request, int $id)
    {
        $expense = Expense::forUser($request->user()->id)
            ->findOrFail($id);

        $request->validate([
            'type'         => 'sometimes|in:income,expense',
            'category_id'  => 'sometimes|exists:categories,id',
            'amount'       => 'sometimes|numeric|min:0.01',
            'note'         => 'nullable|string|max:500',
            'expense_date' => 'sometimes|date|before_or_equal:today',
        ]);

        $expense->update($request->all());

        return response()->json([
            'message' => 'Expense updated successfully.',
            'expense' => $expense->load('category'),
        ], 200);
    }

    // Delete expense
    public function destroy(Request $request, int $id)
    {
        $expense = Expense::forUser($request->user()->id)
            ->findOrFail($id);

        $expense->delete();

        return response()->json([
            'message' => 'Expense deleted successfully.'
        ], 200);
    }
}
