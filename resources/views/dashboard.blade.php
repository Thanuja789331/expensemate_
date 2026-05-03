@extends('layouts.app')

@section('content')

{{-- Summary Cards --}}
<div class="grid grid-cols-3 gap-6 mb-8">

    {{-- Total Income --}}
    <div class="bg-white rounded-xl shadow p-6 border-l-4 border-green-500">
        <p class="text-gray-500 text-sm">Total Income</p>
        <p class="text-2xl font-bold text-green-600">
            ${{ number_format($totalIncome, 2) }}
        </p>
    </div>

    {{-- Total Expense --}}
    <div class="bg-white rounded-xl shadow p-6 border-l-4 border-red-500">
        <p class="text-gray-500 text-sm">Total Expense</p>
        <p class="text-2xl font-bold text-red-600">
            ${{ number_format($totalExpense, 2) }}
        </p>
    </div>

    {{-- Balance --}}
    <div class="bg-white rounded-xl shadow p-6 border-l-4 border-blue-500">
        <p class="text-gray-500 text-sm">Balance</p>
        <p class="text-2xl font-bold text-blue-600">
            ${{ number_format($balance, 2) }}
        </p>
    </div>

</div>

{{-- Recent Expenses Table --}}
<div class="bg-white rounded-xl shadow p-6">
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-lg font-bold text-gray-700">Recent Expenses</h2>
        <a href="{{ route('expenses.create') }}"
           class="bg-green-700 text-white px-4 py-2 rounded hover:bg-green-800">
            + Add New Entry
        </a>
    </div>

    <table class="w-full text-left">
        <thead class="bg-green-700 text-white">
            <tr>
                <th class="px-4 py-3">Date</th>
                <th class="px-4 py-3">Category</th>
                <th class="px-4 py-3">Type</th>
                <th class="px-4 py-3">Amount</th>
                <th class="px-4 py-3">Note</th>
                <th class="px-4 py-3">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($recentExpenses as $expense)
            <tr class="border-b hover:bg-gray-50">
                <td class="px-4 py-3">
                    {{ $expense->expense_date->format('d M Y') }}
                </td>
                <td class="px-4 py-3">{{ $expense->category->name }}</td>
                <td class="px-4 py-3">
                    <span class="px-2 py-1 rounded text-xs font-bold
                        {{ $expense->type === 'income'
                            ? 'bg-green-100 text-green-800'
                            : 'bg-red-100 text-red-800' }}">
                        {{ ucfirst($expense->type) }}
                    </span>
                </td>
                <td class="px-4 py-3 font-mono
                    {{ $expense->type === 'income'
                        ? 'text-green-600'
                        : 'text-red-600' }}">
                    {{ $expense->type === 'income' ? '+' : '-' }}
                    ${{ number_format($expense->amount, 2) }}
                </td>
                <td class="px-4 py-3 text-gray-500">
                    {{ $expense->note ?? '---' }}
                </td>
                <td class="px-4 py-3">
                    <a href="{{ route('expenses.edit', $expense->id) }}"
                       class="bg-blue-500 text-white px-3 py-1 rounded text-sm mr-1">
                        Edit
                    </a>
                    <form method="POST"
                          action="{{ route('expenses.destroy', $expense->id) }}"
                          class="inline"
                          onsubmit="return confirm('Delete this record?')">
                        @csrf
                        @method('DELETE')
                        <button class="bg-red-500 text-white px-3 py-1 rounded text-sm">
                            Delete
                        </button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="text-center py-8 text-gray-400">
                    No expenses yet. Click "Add New Entry" to start!
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="mt-4">
        <a href="{{ route('expenses.index') }}"
           class="text-green-700 hover:underline">
            View all expenses →
        </a>
    </div>
</div>

@endsection
