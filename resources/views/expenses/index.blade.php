@extends('layouts.app')

@section('content')

<div class="bg-white rounded-xl shadow p-6">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-xl font-bold text-gray-700">All Expenses</h2>
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
            @forelse($expenses as $expense)
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
                    No expenses found.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    {{-- Pagination --}}
    <div class="mt-4">
        {{ $expenses->links() }}
    </div>
</div>

@endsection
