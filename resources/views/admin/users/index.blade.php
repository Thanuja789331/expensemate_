@extends('layouts.app')

@section('content')

<div class="bg-white rounded-xl shadow p-4 md:p-6">
    <h2 class="text-xl font-bold text-gray-700 mb-6">
        👥 User Account Control
    </h2>

    {{-- Desktop Table --}}
    <div class="overflow-x-auto">
        <table class="w-full text-left min-w-[600px]">
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

                    {{-- Name --}}
                    <td class="px-4 py-3">
                        <div class="flex items-center gap-2">
                            <div class="w-8 h-8 bg-green-600 rounded-full
                                        flex items-center justify-center
                                        text-white font-bold text-sm
                                        flex-shrink-0">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>
                            <span class="font-medium">{{ $user->name }}</span>
                        </div>
                    </td>

                    {{-- Email --}}
                    <td class="px-4 py-3 text-gray-500 text-sm">
                        {{ $user->email }}
                    </td>

                    {{-- Role --}}
                    <td class="px-4 py-3">
                        <form method="POST"
                              action="{{ route('admin.users.role', $user->id) }}"
                              class="inline">
                            @csrf
                            @method('PATCH')
                            <select name="role"
                                    onchange="this.form.submit()"
                                    class="border rounded px-2 py-1 text-sm
                                           focus:outline-none focus:ring-2
                                           focus:ring-green-500">
                                <option value="user"
                                    {{ $user->role === 'user'
                                        ? 'selected' : '' }}>
                                    User
                                </option>
                                <option value="admin"
                                    {{ $user->role === 'admin'
                                        ? 'selected' : '' }}>
                                    Admin
                                </option>
                            </select>
                        </form>
                    </td>

                    {{-- Status --}}
                    <td class="px-4 py-3">
                        <span class="px-2 py-1 rounded text-xs font-bold
                            {{ $user->is_active
                                ? 'bg-green-100 text-green-800'
                                : 'bg-red-100 text-red-800' }}">
                            {{ $user->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </td>

                    {{-- Actions --}}
                    <td class="px-4 py-3">
                        @if($user->id !== auth()->id())
                            <form method="POST"
                                  action="{{ route('admin.users.toggle',
                                            $user->id) }}"
                                  class="inline"
                                  onsubmit="return confirm('Are you sure?')">
                                @csrf
                                @method('PATCH')
                                <button class="px-3 py-1 rounded text-sm
                                               text-white whitespace-nowrap
                                    {{ $user->is_active
                                        ? 'bg-red-500 hover:bg-red-600'
                                        : 'bg-green-500 hover:bg-green-600' }}">
                                    {{ $user->is_active
                                        ? 'Deactivate'
                                        : 'Activate' }}
                                </button>
                            </form>
                        @else
                            <span class="text-gray-400 text-sm">
                                Current Admin
                            </span>
                        @endif
                    </td>

                </tr>
                @empty
                <tr>
                    <td colspan="5"
                        class="text-center py-8 text-gray-400">
                        No users found.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Mobile Cards View --}}
    <div class="md:hidden mt-4 space-y-4">
        @foreach($users as $user)
        <div class="bg-gray-50 rounded-lg p-4 border">

            {{-- User Header --}}
            <div class="flex items-center gap-3 mb-3">
                <div class="w-10 h-10 bg-green-600 rounded-full
                            flex items-center justify-center
                            text-white font-bold">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>
                <div>
                    <p class="font-bold">{{ $user->name }}</p>
                    <p class="text-gray-500 text-sm">{{ $user->email }}</p>
                </div>
            </div>

            {{-- User Details --}}
            <div class="flex gap-2 mb-3">
                <span class="px-2 py-1 rounded text-xs font-bold
                    {{ $user->role === 'admin'
                        ? 'bg-purple-100 text-purple-800'
                        : 'bg-gray-100 text-gray-800' }}">
                    {{ ucfirst($user->role) }}
                </span>
                <span class="px-2 py-1 rounded text-xs font-bold
                    {{ $user->is_active
                        ? 'bg-green-100 text-green-800'
                        : 'bg-red-100 text-red-800' }}">
                    {{ $user->is_active ? 'Active' : 'Inactive' }}
                </span>
            </div>

            {{-- Actions --}}
            @if($user->id !== auth()->id())
            <form method="POST"
                  action="{{ route('admin.users.toggle', $user->id) }}"
                  onsubmit="return confirm('Are you sure?')">
                @csrf
                @method('PATCH')
                <button class="w-full py-2 rounded text-sm text-white
                    {{ $user->is_active
                        ? 'bg-red-500 hover:bg-red-600'
                        : 'bg-green-500 hover:bg-green-600' }}">
                    {{ $user->is_active ? 'Deactivate' : 'Activate' }}
                </button>
            </form>
            @else
                <p class="text-center text-gray-400 text-sm">
                    Current Admin
                </p>
            @endif

        </div>
        @endforeach
    </div>

</div>

@endsection
