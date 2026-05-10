@extends('layouts.app')

@section('content')

<div class="bg-white rounded-xl shadow p-4 md:p-6">
    <h2 class="text-xl font-bold text-gray-700 mb-6">
        📂 Manage Categories
    </h2>

    {{-- Add New Category Form --}}
    <div class="bg-gray-50 rounded-lg p-4 mb-6">
        <h3 class="font-bold text-gray-600 mb-3">+ Add New Category</h3>
        <form method="POST" action="{{ route('admin.categories.store') }}">
            @csrf
            <div class="flex flex-col md:flex-row gap-3">
                <div class="flex-1">
                    <input type="text"
                           name="name"
                           value="{{ old('name') }}"
                           placeholder="Category name (e.g. Groceries)"
                           class="w-full border rounded-lg px-3 py-2
                                  focus:outline-none focus:ring-2
                                  focus:ring-green-500" />
                    @error('name')
                        <p class="text-red-500 text-sm mt-1">
                            {{ $message }}
                        </p>
                    @enderror
                </div>
                <div>
                    <select name="status"
                            class="w-full md:w-auto border rounded-lg
                                   px-3 py-2 focus:outline-none
                                   focus:ring-2 focus:ring-green-500">
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                </div>
                <button type="submit"
                        class="bg-green-700 text-white px-6 py-2
                               rounded-lg hover:bg-green-800 w-full
                               md:w-auto">
                    Create Category
                </button>
            </div>
        </form>
    </div>

    {{-- Categories Table --}}
    <div class="overflow-x-auto">
        <table class="w-full text-left min-w-[500px]">
            <thead class="bg-green-700 text-white">
                <tr>
                    <th class="px-4 py-3">Category Name</th>
                    <th class="px-4 py-3">Status</th>
                    <th class="px-4 py-3">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($categories as $category)
                <tr class="border-b hover:bg-gray-50">
                    <td class="px-4 py-3 font-medium">
                        {{ $category->name }}
                    </td>
                    <td class="px-4 py-3">
                        <span class="px-2 py-1 rounded text-xs font-bold
                            {{ $category->status === 'active'
                                ? 'bg-green-100 text-green-800'
                                : 'bg-red-100 text-red-800' }}">
                            {{ ucfirst($category->status) }}
                        </span>
                    </td>
                    <td class="px-4 py-3">
                        {{-- Edit Form --}}
                        <form method="POST"
                              action="{{ route('admin.categories.update',
                                        $category->id) }}"
                              class="flex flex-wrap gap-2">
                            @csrf
                            @method('PUT')
                            <input type="text"
                                   name="name"
                                   value="{{ $category->name }}"
                                   class="border rounded px-2 py-1
                                          text-sm w-28" />
                            <select name="status"
                                    class="border rounded px-2 py-1 text-sm">
                                <option value="active"
                                    {{ $category->status === 'active'
                                        ? 'selected' : '' }}>
                                    Active
                                </option>
                                <option value="inactive"
                                    {{ $category->status === 'inactive'
                                        ? 'selected' : '' }}>
                                    Inactive
                                </option>
                            </select>
                            <button type="submit"
                                    class="bg-blue-500 text-white px-3
                                           py-1 rounded text-sm">
                                Save
                            </button>
                        </form>

                        {{-- Delete Form --}}
                        <form method="POST"
                              action="{{ route('admin.categories.destroy',
                                        $category->id) }}"
                              class="inline mt-2"
                              onsubmit="return confirm('Delete this category?')">
                            @csrf
                            @method('DELETE')
                            <button class="bg-red-500 text-white px-3
                                          py-1 rounded text-sm mt-1">
                                Delete
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="3"
                        class="text-center py-8 text-gray-400">
                        No categories found.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection
