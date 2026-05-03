@extends('layouts.app')

@section('content')

<div class="bg-white rounded-xl shadow p-6">
    <h2 class="text-xl font-bold text-gray-700 mb-6">
        User Account Control
    </h2>

    <table class="w-full text-left">
        <thead class="bg-green-700 text-white">
            <tr>
                <th class="px-4 py-3">Name</th>
                <th class="px-4 py-3">Email</th>
                <th class="px-4 py-3">Role</th>
                <th class="px-4 py-3">Status</th>
                <th class="px-4 py-3">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($users as $user)
            <tr class="border-b hover:bg-gray-50">
                <td class="px-4 py-3 font-medium">{{ $user->name }}</td>
                <td class="px-4 py-3 text-gray-500">{{ $user->email }}</td>
                <td class="px-4 py-3">

                    {{-- Change Role Form --}}
                    <form method="POST"
                          action="{{ route('admin.users.role', $user->id) }}"
                          class="inline">
                        @csrf
                        @method('PATCH')
                        <select name="role"
                                onchange="this.form.submit()"
                                class="border rounded px-2 py-1 text-sm">
                            <option value="user"
                                {{ $user->role === 'user' ? 'selected' : '' }}>
                                User
                            </option>
                            <option value="admin"
                                {{ $user->role === 'admin' ? 'selected' : '' }}>
                                Admin
                            </option>
                        </select>
                    </form>

                </td>
                <td class="px-4 py-3">
                    <span class="px-2 py-1 rounded text-xs font-bold
                        {{ $user->is_active
                            ? 'bg-green-100 text-green-800'
                            : 'bg-red-100 text-red-800' }}">
                        {{ $user->is_active ? 'Active' : 'Inactive' }}
                    </span>
                </td>
                <td class="px-4 py-3">

                    {{-- Toggle Active/Inactive --}}
                    @if($user->id !== auth()->id())
                        <form method="POST"
                              action="{{ route('admin.users.toggle', $user->id) }}"
                              class="inline"
                              onsubmit="return confirm('Are you sure?')">
                            @csrf
                            @method('PATCH')
                            <button class="px-3 py-1 rounded text-sm text-white
                                {{ $user->is_active
                                    ? 'bg-red-500 hover:bg-red-600'
                                    : 'bg-green-500 hover:bg-green-600' }}">
                                {{ $user->is_active ? 'Deactivate' : 'Activate' }}
                            </button>
                        </form>
                    @else
                        <span class="text-gray-400 text-sm">Current Admin</span>
                    @endif

                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="text-center py-8 text-gray-400">
                    No users found.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

@endsection
