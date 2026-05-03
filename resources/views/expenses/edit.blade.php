@extends('layouts.app')

@section('content')

<div class="max-w-2xl mx-auto bg-white rounded-xl shadow p-6">
    <h2 class="text-xl font-bold text-gray-700 mb-6">Update Expense</h2>

    <form method="POST" action="{{ route('expenses.update', $expense->id) }}">
        @csrf
        @method('PUT')

        {{-- Transaction Type --}}
        <div class="mb-4">
            <label class="block text-gray-600 font-medium mb-1">
                Transaction Type
            </label>
            <select name="type"
                    class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
                <option value="expense" {{ $expense->type == 'expense' ? 'selected' : '' }}>
                    Expense
                </option>
                <option value="income" {{ $expense->type == 'income' ? 'selected' : '' }}>
                    Income
                </option>
            </select>
            @error('type')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Category --}}
        <div class="mb-4">
            <label class="block text-gray-600 font-medium mb-1">
                Category
            </label>
            <select name="category_id"
                    class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
                <option value="">Select category</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}"
                        {{ $expense->category_id == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
            @error('category_id')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Amount --}}
        <div class="mb-4">
            <label class="block text-gray-600 font-medium mb-1">
                Amount ($)
            </label>
            <input type="number"
                   name="amount"
                   step="0.01"
                   min="0.01"
                   value="{{ $expense->amount }}"
                   class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500" />
            @error('amount')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Date --}}
        <div class="mb-4">
            <label class="block text-gray-600 font-medium mb-1">
                Date of Transaction
            </label>
            <input type="date"
                   name="expense_date"
                   value="{{ $expense->expense_date->format('Y-m-d') }}"
                   class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500" />
            @error('expense_date')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Note --}}
        <div class="mb-6">
            <label class="block text-gray-600 font-medium mb-1">
                Note (Optional)
            </label>
            <input type="text"
                   name="note"
                   value="{{ $expense->note }}"
                   class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500"
                   placeholder="Describe this transaction" />
            @error('note')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Buttons --}}
        <div class="flex gap-3">
            <button type="submit"
                    class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700">
                Save Changes
            </button>
            <a href="{{ route('dashboard') }}"
               class="bg-gray-300 text-gray-700 px-6 py-2 rounded-lg hover:bg-gray-400">
                Cancel
            </a>
        </div>

    </form>
</div>

@endsection
