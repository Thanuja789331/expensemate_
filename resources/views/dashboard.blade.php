@extends('layouts.app')

@section('content')

{{-- Welcome Banner --}}
<div class="relative rounded-xl overflow-hidden mb-8 h-48">
    <img src="{{ asset('images/banner.jpg') }}"
         alt="Dashboard Banner"
         class="w-full h-full object-cover" />
    <div class="absolute inset-0 bg-green-800
                bg-opacity-75 flex items-center px-8">
        <div class="text-white">
            <p class="text-green-300 text-sm font-medium mb-1">
                {{ now()->format('l, d F Y') }}
            </p>
            <h1 class="text-3xl font-bold mb-1">
                Welcome back, {{ auth()->user()->name }}! 👋
            </h1>
            <p class="text-green-200">
                Here's your financial summary for today
            </p>
        </div>
    </div>
</div>

{{-- Summary Cards --}}
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">

    {{-- Total Income --}}
    <div class="bg-white rounded-xl shadow p-6
                border-l-4 border-green-500
                hover:shadow-lg transition">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm font-medium">
                    Total Income
                </p>
                <p class="text-2xl font-bold text-green-600 mt-1">
                    ${{ number_format($totalIncome, 2) }}
                </p>
                <p class="text-green-500 text-xs mt-1">
                    ↑ All time income
                </p>
            </div>
            <div class="bg-green-100 p-4 rounded-full">
                <svg class="w-8 h-8 text-green-600"
                     fill="none" stroke="currentColor"
                     viewBox="0 0 24 24">
                    <path stroke-linecap="round"
                          stroke-linejoin="round"
                          stroke-width="2"
                          d="M12 4v16m8-8H4"/>
                </svg>
            </div>
        </div>
    </div>

    {{-- Total Expense --}}
    <div class="bg-white rounded-xl shadow p-6
                border-l-4 border-red-500
                hover:shadow-lg transition">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm font-medium">
                    Total Expense
                </p>
                <p class="text-2xl font-bold text-red-600 mt-1">
                    ${{ number_format($totalExpense, 2) }}
                </p>
                <p class="text-red-500 text-xs mt-1">
                    ↓ All time expense
                </p>
            </div>
            <div class="bg-red-100 p-4 rounded-full">
                <svg class="w-8 h-8 text-red-600"
                     fill="none" stroke="currentColor"
                     viewBox="0 0 24 24">
                    <path stroke-linecap="round"
                          stroke-linejoin="round"
                          stroke-width="2"
                          d="M20 12H4"/>
                </svg>
            </div>
        </div>
    </div>

    {{-- Balance --}}
    <div class="bg-white rounded-xl shadow p-6
                border-l-4 border-blue-500
                hover:shadow-lg transition">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm font-medium">
                    Current Balance
                </p>
                <p class="text-2xl font-bold mt-1
                    {{ $balance >= 0
                        ? 'text-blue-600'
                        : 'text-red-600' }}">
                    ${{ number_format($balance, 2) }}
                </p>
                <p class="text-xs mt-1
                    {{ $balance >= 0
                        ? 'text-blue-500'
                        : 'text-red-500' }}">
                    {{ $balance >= 0
                        ? '✅ Positive balance'
                        : '⚠️ Negative balance' }}
                </p>
            </div>
            <div class="bg-blue-100 p-4 rounded-full">
                <svg class="w-8 h-8 text-blue-600"
                     fill="none" stroke="currentColor"
                     viewBox="0 0 24 24">
                    <path stroke-linecap="round"
                          stroke-linejoin="round"
                          stroke-width="2"
                          d="M3 10h18M7 15h1m4 0h1m-7
                             4h12a3 3 0 003-3V8a3 3 0
                             00-3-3H6a3 3 0 00-3 3v8
                             a3 3 0 003 3z"/>
                </svg>
            </div>
        </div>
    </div>

</div>

{{-- Quick Actions --}}
<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
    <a href="{{ route('expenses.create') }}"
       class="bg-green-700 text-white rounded-xl p-4
              text-center hover:bg-green-800 transition">
        <span class="text-2xl block mb-1">➕</span>
        <span class="text-sm font-medium">Add Expense</span>
    </a>
    <a href="{{ route('expenses.index') }}"
       class="bg-blue-600 text-white rounded-xl p-4
              text-center hover:bg-blue-700 transition">
        <span class="text-2xl block mb-1">📋</span>
        <span class="text-sm font-medium">View All</span>
    </a>
    <a href="{{ route('summary') }}"
       class="bg-purple-600 text-white rounded-xl p-4
              text-center hover:bg-purple-700 transition">
        <span class="text-2xl block mb-1">📊</span>
        <span class="text-sm font-medium">Summary</span>
    </a>
    <a href="{{ route('profile.show') }}"
       class="bg-orange-500 text-white rounded-xl p-4
              text-center hover:bg-orange-600 transition">
        <span class="text-2xl block mb-1">👤</span>
        <span class="text-sm font-medium">Profile</span>
    </a>
</div>

{{-- Livewire Expense Manager --}}
@livewire('expense-manager')

@endsection
