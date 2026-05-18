@extends('layouts.app')

@section('content')

@php
$currencySymbols = [
    'USD' => '$',   'GBP' => '£',   'EUR' => '€',   'AUD' => '$',
    'CAD' => '$',   'INR' => '₹',   'LKR' => 'Rs',  'SGD' => '$',
    'MYR' => 'RM',  'JPY' => '¥',   'CNY' => '¥',   'AED' => 'د.إ',
    'SAR' => '﷼',  'NGN' => '₦',   'ZAR' => 'R',
];
$userCurrency = auth()->user()->currency ?? 'USD';
$userSymbol   = $currencySymbols[$userCurrency] ?? '$';
@endphp

<div class="max-w-2xl mx-auto bg-white rounded-xl shadow p-6">
    <h2 class="text-xl font-bold text-gray-700 mb-6">New Expense Entry</h2>

    <form method="POST" action="{{ route('expenses.store') }}">
        @csrf

        {{-- Transaction Type --}}
        <div class="mb-4">
            <label class="block text-gray-600 font-medium mb-1">
                Transaction Type
            </label>
            <select name="type"
                    class="w-full border rounded-lg px-3 py-2
                           focus:outline-none focus:ring-2 focus:ring-green-500">
                <option value="expense" {{ old('type') == 'expense' ? 'selected' : '' }}>
                    💸 Expense
                </option>
                <option value="income" {{ old('type') == 'income' ? 'selected' : '' }}>
                    💰 Income
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
                    class="w-full border rounded-lg px-3 py-2
                           focus:outline-none focus:ring-2 focus:ring-green-500">
                <option value="">Select category</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}"
                        {{ old('category_id') == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
            @error('category_id')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Amount with Currency --}}
        <div class="mb-4">
            <label class="block text-gray-600 font-medium mb-1">
                Amount
            </label>

            {{-- Currency badge --}}
            <div class="flex items-center gap-3 mb-2">
                <div class="bg-green-50 border border-green-300 rounded-lg
                            px-3 py-1 flex items-center gap-2">
                    <span class="text-sm font-bold text-green-700">
                        {{ $userCurrency }}
                    </span>
                    <span class="text-base font-bold text-green-700">
                        {{ $userSymbol }}
                    </span>
                </div>
                <span class="text-xs text-gray-400">
                    Current currency: <strong class="text-gray-600">{{ $userCurrency }}</strong>
                </span>
            </div>

            {{-- Amount input with symbol prefix --}}
            <div class="relative">
                <span class="absolute left-3 top-1/2 -translate-y-1/2
                             text-gray-500 font-semibold text-sm pointer-events-none">
                    {{ $userSymbol }}
                </span>
                <input type="number"
                       name="amount"
                       step="0.01"
                       min="0.01"
                       value="{{ old('amount') }}"
                       class="w-full border rounded-lg py-2 pr-3
                              focus:outline-none focus:ring-2 focus:ring-green-500"
                       style="padding-left: {{ strlen($userSymbol) > 1 ? '42px' : '28px' }};"
                       placeholder="0.00" />
            </div>

            @error('amount')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror

            {{-- Change currency link --}}
            <p class="text-xs text-gray-400 mt-1">
                Want a different currency?
                <a href="{{ route('profile.show') }}"
                   class="text-green-600 hover:underline font-medium">
                    Update in Profile →
                </a>
            </p>
        </div>

        {{-- Date --}}
        <div class="mb-4">
            <label class="block text-gray-600 font-medium mb-1">
                Date of Transaction
            </label>
            <input type="date"
                   name="expense_date"
                   value="{{ old('expense_date', date('Y-m-d')) }}"
                   class="w-full border rounded-lg px-3 py-2
                          focus:outline-none focus:ring-2 focus:ring-green-500" />
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
                   value="{{ old('note') }}"
                   class="w-full border rounded-lg px-3 py-2
                          focus:outline-none focus:ring-2 focus:ring-green-500"
                   placeholder="Describe this transaction" />
            @error('note')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Buttons --}}
        <div class="flex gap-3">
            <button type="submit"
                    class="bg-green-700 text-white px-6 py-2
                           rounded-lg hover:bg-green-800 transition">
                ✅ Confirm Entry
            </button>
            <a href="{{ route('dashboard') }}"
               class="bg-gray-300 text-gray-700 px-6 py-2
                      rounded-lg hover:bg-gray-400 transition">
                Cancel
            </a>
        </div>

    </form>
</div>

@endsection
