<?php

namespace App\Http\Controllers\Api;

use App\Models\Expense;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\ExpenseResource;

class ExpenseController extends Controller
{
    // Get all expenses
    public function index()
    {
        $expenses = Expense::with('category')
            ->where('user_id', auth()->id())
            ->latest('expense_date')
            ->get();

        return ExpenseResource::collection($expenses);
    }

    // Store new expense
    public function store(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|in:income,expense',
            'category_id' => 'required|exists:categories,id',
            'amount' => 'required|numeric|min:0.01|max:9999999',
            'note' => 'nullable|string|max:500',
            'expense_date' => 'required|date|before_or_equal:today',
        ]);

        // Sanitize note
        if (isset($validated['note'])) {
            $validated['note'] = strip_tags($validated['note']);
            $validated['note'] = htmlspecialchars(
                $validated['note'],
                ENT_QUOTES,
                'UTF-8'
            );
        }

        $expense = Expense::create([
            ...$validated,
            'user_id' => auth()->id(),
        ]);

        return response()->json([
            'message' => 'Expense created successfully',
            'data' => new ExpenseResource($expense)
        ], 201);
    }

    // Show single expense
    public function show($id)
    {
        $expense = Expense::with('category')
            ->where('user_id', auth()->id())
            ->findOrFail($id);

        return new ExpenseResource($expense);
    }

    // Update expense
    public function update(Request $request, $id)
    {
        $expense = Expense::where('user_id', auth()->id())
            ->findOrFail($id);

        $validated = $request->validate([
            'type' => 'required|in:income,expense',
            'category_id' => 'required|exists:categories,id',
            'amount' => 'required|numeric|min:0.01|max:9999999',
            'note' => 'nullable|string|max:500',
            'expense_date' => 'required|date|before_or_equal:today',
        ]);

        // Sanitize note
        if (isset($validated['note'])) {
            $validated['note'] = strip_tags($validated['note']);
            $validated['note'] = htmlspecialchars(
                $validated['note'],
                ENT_QUOTES,
                'UTF-8'
            );
        }

        $expense->update($validated);

        return response()->json([
            'message' => 'Expense updated successfully',
            'data' => new ExpenseResource($expense)
        ]);
    }

    // Delete expense
    public function destroy($id)
    {
        $expense = Expense::where('user_id', auth()->id())
            ->findOrFail($id);

        $expense->delete();

        return response()->json([
            'message' => 'Expense deleted successfully'
        ]);
    }
}
