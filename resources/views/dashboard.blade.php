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

{{-- Livewire Expense Manager --}}
@livewire('expense-manager')

@endsection
