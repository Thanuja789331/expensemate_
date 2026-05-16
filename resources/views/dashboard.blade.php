@extends('layouts.app')

@section('content')

{{-- Welcome Banner --}}
<div class="relative rounded-xl overflow-hidden mb-6 h-48">
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

{{-- Livewire Dashboard Stats (live, auto-refresh 30s) --}}
<div class="mb-6">
    @livewire('dashboard-stats')
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
