@extends('layouts.app')

@section('content')

<h2 class="text-2xl font-bold text-gray-700 mb-6">
    Admin Console — Welcome, {{ auth()->user()->name }}
</h2>

{{-- Stats Cards --}}
<div class="grid grid-cols-3 gap-6 mb-8">

    {{-- Total Users --}}
    <div class="bg-white rounded-xl shadow p-6 border-l-4 border-green-500">
        <p class="text-gray-500 text-sm">Total Users</p>
        <p class="text-3xl font-bold text-green-600">{{ $totalUsers }}</p>
    </div>

    {{-- Total Expenses --}}
    <div class="bg-white rounded-xl shadow p-6 border-l-4 border-red-500">
        <p class="text-gray-500 text-sm">Total System Expenses</p>
        <p class="text-3xl font-bold text-red-600">
            ${{ number_format($totalExpenses, 2) }}
        </p>
    </div>

    {{-- Total Categories --}}
    <div class="bg-white rounded-xl shadow p-6 border-l-4 border-blue-500">
        <p class="text-gray-500 text-sm">Total Categories</p>
        <p class="text-3xl font-bold text-blue-600">{{ $totalCategories }}</p>
    </div>

</div>

{{-- Quick Links --}}
<div class="grid grid-cols-2 gap-6 mb-8">
    <a href="{{ route('admin.categories.index') }}"
       class="bg-green-700 text-white rounded-xl shadow p-6 hover:bg-green-800 text-center">
        <p class="text-xl font-bold">📂 Manage Categories</p>
        <p class="text-sm mt-1">Add, edit, delete categories</p>
    </a>
    <a href="{{ route('admin.users.index') }}"
       class="bg-blue-600 text-white rounded-xl shadow p-6 hover:bg-blue-700 text-center">
        <p class="text-xl font-bold">👥 Manage Users</p>
        <p class="text-sm mt-1">View and manage user accounts</p>
    </a>
</div>

{{-- Recent Users --}}
<div class="bg-white rounded-xl shadow p-6">
    <h3 class="text-lg font-bold text-gray-700 mb-4">Recent Users</h3>
    <table class="w-full text-left">
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
                <td class="px-4 py-3">{{ $user->name }}</td>
                <td class="px-4 py-3">{{ $user->email }}</td>
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

@endsection
