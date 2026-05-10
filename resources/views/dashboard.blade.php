@extends('layouts.app')

@section('content')

{{-- Summary Cards --}}
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">

    {{-- Total Income --}}
    <div class="bg-white rounded-xl shadow p-6 border-l-4 border-green-500">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm">Total Income</p>
                <p class="text-2xl font-bold text-green-600">
                    ${{ number_format($totalIncome, 2) }}
                </p>
            </div>
            <div class="bg-green-100 p-3 rounded-full">
                <svg class="w-8 h-8 text-green-600"
                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          stroke-width="2"
                          d="M12 4v16m8-8H4"/>
                </svg>
            </div>
        </div>
    </div>

    {{-- Total Expense --}}
    <div class="bg-white rounded-xl shadow p-6 border-l-4 border-red-500">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm">Total Expense</p>
                <p class="text-2xl font-bold text-red-600">
                    ${{ number_format($totalExpense, 2) }}
                </p>
            </div>
            <div class="bg-red-100 p-3 rounded-full">
                <svg class="w-8 h-8 text-red-600"
                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          stroke-width="2"
                          d="M20 12H4"/>
                </svg>
            </div>
        </div>
    </div>

    {{-- Balance --}}
    <div class="bg-white rounded-xl shadow p-6 border-l-4 border-blue-500">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm">Balance</p>
                <p class="text-2xl font-bold text-blue-600">
                    ${{ number_format($balance, 2) }}
                </p>
            </div>
            <div class="bg-blue-100 p-3 rounded-full">
                <svg class="w-8 h-8 text-blue-600"
                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          stroke-width="2"
                          d="M3 6l3 1m0 0l-3 9a5.002 5.002 0
                             006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3
                             1l-3 9a5.002 5.002 0 006.001 0M18
                             7l3 9m-3-9l-6-2m0-2v2m0 16V5m0
                             16H9m3 0h3"/>
                </svg>
            </div>
        </div>
    </div>

</div>

{{-- Livewire Expense Manager --}}
@livewire('expense-manager')

@endsection
