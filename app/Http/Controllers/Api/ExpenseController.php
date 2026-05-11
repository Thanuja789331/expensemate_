<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Expense;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ExpenseController extends Controller
{
    // Get all expenses
    public function index(Request $request)
    {
        try {
            $expenses = Expense::forUser($request->user()->id)
                ->with('category')
                ->when($request->type, fn($q) =>
                    $q->ofType($request->type))
                ->when($request->from, fn($q) =>
                    $q->whereBetween('expense_date',
                        [$request->from, $request->to ?? now()]))
                ->when($request->category_id, fn($q) =>
                    $q->where('category_id', $request->category_id))
                ->latest('expense_date')
                ->paginate($request->per_page ?? 20);

            return response()->json([
                'success' => true,
                'data'    => $expenses,
                'message' => 'Expenses retrieved successfully.'
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve expenses.',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    // Create new expense
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'type'         => 'required|in:income,expense',
                'category_id'  => 'required|exists:categories,id',
                'amount'       => 'required|numeric|min:0.01|max:9999999',
                'note'         => 'nullable|string|max:500',
                'expense_date' => 'required|date|before_or_equal:today',
            ]);

            $expense = Expense::create([
                'user_id'      => $request->user()->id,
                'type'         => $validated['type'],
                'category_id'  => $validated['category_id'],
                'amount'       => $validated['amount'],
                'note'         => $validated['note'] ?? null,
                'expense_date' => $validated['expense_date'],
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Expense created successfully.',
                'expense' => $expense->load('category'),
            ], 201);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed.',
                'errors'  => $e->errors()
            ], 422);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create expense.',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    // Get single expense
    public function show(Request $request, int $id)
    {
        try {
            $expense = Expense::forUser($request->user()->id)
                ->with('category')
                ->findOrFail($id);

            return response()->json([
                'success' => true,
                'expense' => $expense,
                'message' => 'Expense retrieved successfully.'
            ], 200);

        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Expense not found.',
                'error'   => 'The requested expense does not exist.'
            ], 404);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve expense.',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    // Update expense
    public function update(Request $request, int $id)
    {
        try {
            $expense = Expense::forUser($request->user()->id)
                ->findOrFail($id);

            $validated = $request->validate([
                'type'         => 'sometimes|in:income,expense',
                'category_id'  => 'sometimes|exists:categories,id',
                'amount'       => 'sometimes|numeric|min:0.01|max:9999999',
                'note'         => 'nullable|string|max:500',
                'expense_date' => 'sometimes|date|before_or_equal:today',
            ]);

            $expense->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'Expense updated successfully.',
                'expense' => $expense->fresh()->load('category'),
            ], 200);

        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Expense not found.',
                'error'   => 'The requested expense does not exist.'
            ], 404);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed.',
                'errors'  => $e->errors()
            ], 422);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update expense.',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    // Delete expense
    public function destroy(Request $request, int $id)
    {
        try {
            $expense = Expense::forUser($request->user()->id)
                ->findOrFail($id);

            $expense->delete();

            return response()->json([
                'success' => true,
                'message' => 'Expense deleted successfully.'
            ], 200);

        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Expense not found.',
                'error'   => 'The requested expense does not exist.'
            ], 404);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete expense.',
                'error'   => $e->getMessage()
            ], 500);
        }
    }
}
