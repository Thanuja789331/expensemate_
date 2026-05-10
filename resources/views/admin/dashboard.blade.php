@extends('layouts.app')

@section('content')

<h2 class="text-2xl font-bold text-gray-700 mb-6">
    Admin Console — Welcome, {{ auth()->user()->name }}
</h2>

{{-- Stats Cards --}}
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">

    {{-- Total Users --}}
    <div class="bg-white rounded-xl shadow p-6 border-l-4 border-green-500">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm">Total Users</p>
                <p class="text-3xl font-bold text-green-600">
                    {{ $totalUsers }}
                </p>
            </div>
            <div class="bg-green-100 p-3 rounded-full">
                <svg class="w-8 h-8 text-green-600"
                     fill="none" stroke="currentColor"
                     viewBox="0 0 24 24">
                    <path stroke-linecap="round"
                          stroke-linejoin="round"
                          stroke-width="2"
                          d="M17 20h5v-2a3 3 0 00-5.356-1.857M17
                             20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7
                             20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126
                             -1.283.356-1.857m0 0a5.002 5.002 0
                             019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
            </div>
        </div>
    </div>

    {{-- Total Expenses --}}
    <div class="bg-white rounded-xl shadow p-6 border-l-4 border-red-500">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm">Total System Expenses</p>
                <p class="text-3xl font-bold text-red-600">
                    ${{ number_format($totalExpenses, 2) }}
                </p>
            </div>
            <div class="bg-red-100 p-3 rounded-full">
                <svg class="w-8 h-8 text-red-600"
                     fill="none" stroke="currentColor"
                     viewBox="0 0 24 24">
                    <path stroke-linecap="round"
                          stroke-linejoin="round"
                          stroke-width="2"
                          d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2
                             3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402
                             2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11
                             0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0
                             9 9 0 0118 0z"/>
                </svg>
            </div>
        </div>
    </div>

    {{-- Total Categories --}}
    <div class="bg-white rounded-xl shadow p-6 border-l-4 border-blue-500">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm">Total Categories</p>
                <p class="text-3xl font-bold text-blue-600">
                    {{ $totalCategories }}
                </p>
            </div>
            <div class="bg-blue-100 p-3 rounded-full">
                <svg class="w-8 h-8 text-blue-600"
                     fill="none" stroke="currentColor"
                     viewBox="0 0 24 24">
                    <path stroke-linecap="round"
                          stroke-linejoin="round"
                          stroke-width="2"
                          d="M7 7h.01M7 3h5c.512 0 1.024.195
                             1.414.586l7 7a2 2 0 010 2.828l-7 7a2
                             2 0 01-2.828 0l-7-7A1.994 1.994 0
                             013 12V7a4 4 0 014-4z"/>
                </svg>
            </div>
        </div>
    </div>

</div>

{{-- Quick Links --}}
<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
    <a href="{{ route('admin.categories.index') }}"
       class="bg-green-700 text-white rounded-xl shadow p-6
              hover:bg-green-800 text-center transition">
        <p class="text-xl font-bold">📂 Manage Categories</p>
        <p class="text-sm mt-1 text-green-200">
            Add, edit, delete categories
        </p>
    </a>
    <a href="{{ route('admin.users.index') }}"
       class="bg-blue-600 text-white rounded-xl shadow p-6
              hover:bg-blue-700 text-center transition">
        <p class="text-xl font-bold">👥 Manage Users</p>
        <p class="text-sm mt-1 text-blue-200">
            View and manage user accounts
        </p>
    </a>
</div>

{{-- Recent Users --}}
<div class="bg-white rounded-xl shadow p-6">
    <h3 class="text-lg font-bold text-gray-700 mb-4">
        Recent Users
    </h3>

    {{-- Scrollable on mobile --}}
    <div class="overflow-x-auto">
        <table class="w-full text-left min-w-[500px]">
            <thead class="bg-green-700 text-white">
                <tr>
                    <th class="px-4 py-3">Name</th>
                    <th class="px-4 py-3">Email</th>
                    <th class="px-4 py-3">Role</th>
                    <th class="px-4 py-3">Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($recentUsers as $user)
                <tr class="border-b hover:bg-gray-50">
                    <td class="px-4 py-3 font-medium">
                        {{ $user->name }}
                    </td>
                    <td class="px-4 py-3 text-gray-500 text-sm">
                        {{ $user->email }}
                    </td>
                    <td class="px-4 py-3">
                        <span class="px-2 py-1 rounded text-xs font-bold
                            {{ $user->role === 'admin'
                                ? 'bg-purple-100 text-purple-800'
                                : 'bg-gray-100 text-gray-800' }}">
                            {{ ucfirst($user->role) }}
                        </span>
                    </td>
                    <td class="px-4 py-3">
                        <span class="px-2 py-1 rounded text-xs font-bold
                            {{ $user->is_active
                                ? 'bg-green-100 text-green-800'
                                : 'bg-red-100 text-red-800' }}">
                            {{ $user->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

@endsection
