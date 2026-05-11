<div>
    {{-- Success Message --}}
    @if(session('success'))
        <div class="bg-green-100 border border-green-400
                    text-green-800 px-4 py-3 rounded mb-4">
            ✅ {{ session('success') }}
        </div>
    @endif

    {{-- Search & Filter Bar --}}
    <div class="flex flex-col md:flex-row gap-3 mb-4">
        <input wire:model.live.debounce.300ms="search"
               type="text"
               placeholder="Search expenses..."
               class="border rounded-lg px-4 py-2 w-full" />

        <select wire:model.live="filterType"
                class="border rounded-lg px-3 py-2 md:w-48">
            <option value="all">All Types</option>
            <option value="income">Income</option>
            <option value="expense">Expense</option>
        </select>
    </div>

    {{-- Add New Button --}}
    <button wire:click="$set('showForm', true)"
            class="bg-green-700 text-white px-6 py-2
                   rounded-lg mb-4 hover:bg-green-800
                   w-full md:w-auto">
        + Add New Entry
    </button>

    {{-- Expense Form --}}
    @if($showForm)
    <div class="bg-white border rounded-xl p-4 md:p-6 shadow mb-6">
        <h3 class="font-bold text-lg mb-4">
            {{ $editingId ? 'Update Expense' : 'New Expense Entry' }}
        </h3>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

            {{-- Type --}}
            <div>
                <label class="block text-gray-600 mb-1">
                    Transaction Type
                </label>
                <select wire:model="type"
                        class="w-full border rounded-lg px-3 py-2
                               focus:outline-none focus:ring-2
                               focus:ring-green-500">
                    <option value="expense">Expense</option>
                    <option value="income">Income</option>
                </select>
                @error('type')
                    <p class="text-red-500 text-sm mt-1">
                        {{ $message }}
                    </p>
                @enderror
            </div>

            {{-- Category --}}
            <div>
                <label class="block text-gray-600 mb-1">
                    Category
                </label>
                <select wire:model="category_id"
                        class="w-full border rounded-lg px-3 py-2
                               focus:outline-none focus:ring-2
                               focus:ring-green-500">
                    <option value="">Select category</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}">
                            {{ $cat->name }}
                        </option>
                    @endforeach
                </select>
                @error('category_id')
                    <p class="text-red-500 text-sm mt-1">
                        {{ $message }}
                    </p>
                @enderror
            </div>

            {{-- Amount --}}
            <div>
                <label class="block text-gray-600 mb-1">
                    Amount ($)
                </label>
                <input wire:model.live="amount"
                       type="number"
                       step="0.01"
                       min="0.01"
                       class="w-full border rounded-lg px-3 py-2
                              focus:outline-none focus:ring-2
                              focus:ring-green-500"
                       placeholder="0.00" />
                @error('amount')
                    <p class="text-red-500 text-sm mt-1">
                        {{ $message }}
                    </p>
                @enderror
            </div>

            {{-- Date --}}
            <div>
                <label class="block text-gray-600 mb-1">
                    Date of Transaction
                </label>
                <input wire:model="expense_date"
                       type="date"
                       class="w-full border rounded-lg px-3 py-2
                              focus:outline-none focus:ring-2
                              focus:ring-green-500" />
                @error('expense_date')
                    <p class="text-red-500 text-sm mt-1">
                        {{ $message }}
                    </p>
                @enderror
            </div>

            {{-- Note --}}
            <div class="col-span-1 md:col-span-2">
                <label class="block text-gray-600 mb-1">
                    Note (Optional)
                </label>
                <input wire:model="note"
                       type="text"
                       class="w-full border rounded-lg px-3 py-2
                              focus:outline-none focus:ring-2
                              focus:ring-green-500"
                       placeholder="Describe this transaction" />
                @error('note')
                    <p class="text-red-500 text-sm mt-1">
                        {{ $message }}
                    </p>
                @enderror
            </div>

        </div>

        {{-- Form Buttons --}}
        <div class="flex flex-col md:flex-row gap-3 mt-4">
            <button wire:click="saveExpense"
                    wire:loading.attr="disabled"
                    class="bg-green-700 text-white px-6 py-2
                           rounded-lg hover:bg-green-800
                           w-full md:w-auto">
                <span wire:loading.remove>
                    {{ $editingId ? 'Save Changes' : 'Confirm Entry' }}
                </span>
                <span wire:loading>Saving...</span>
            </button>
            <button wire:click="resetForm"
                    class="bg-gray-300 text-gray-700 px-6 py-2
                           rounded-lg hover:bg-gray-400
                           w-full md:w-auto">
                Cancel
            </button>
        </div>
    </div>
    @endif

    {{-- Expenses Table --}}
    <div class="bg-white rounded-xl shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left min-w-[600px]">
                <thead class="bg-green-700 text-white">
                    <tr>
                        <th class="px-4 py-3">Date</th>
                        <th class="px-4 py-3">Category</th>
                        <th class="px-4 py-3">Type</th>
                        <th class="px-4 py-3">Amount</th>
                        <th class="px-4 py-3">Note</th>
                        <th class="px-4 py-3">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($expenses as $expense)
                    <tr class="border-b hover:bg-gray-50">
                        <td class="px-4 py-3 whitespace-nowrap">
                            {{ $expense->expense_date->format('d M Y') }}
                        </td>
                        <td class="px-4 py-3">
                            {{ $expense->category->name }}
                        </td>
                        <td class="px-4 py-3">
                            <span class="px-2 py-1 rounded text-xs font-bold
                                {{ $expense->type === 'income'
                                    ? 'bg-green-100 text-green-800'
                                    : 'bg-red-100 text-red-800' }}">
                                {{ ucfirst($expense->type) }}
                            </span>
                        </td>
                        <td class="px-4 py-3 font-mono whitespace-nowrap
                            {{ $expense->type === 'income'
                                ? 'text-green-600'
                                : 'text-red-600' }}">
                            {{ $expense->type === 'income' ? '+' : '-' }}
                            ${{ number_format($expense->amount, 2) }}
                        </td>
                        <td class="px-4 py-3 text-gray-500">
                            {{ $expense->note ?? '---' }}
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap">
                            <button
                                wire:click="editExpense({{ $expense->id }})"
                                class="bg-blue-500 text-white px-3 py-1
                                       rounded text-sm mr-1">
                                ✏️ Edit
                            </button>
                            <button
                                wire:click="deleteExpense({{ $expense->id }})"
                                wire:confirm="Delete this expense?"
                                class="bg-red-500 text-white px-3 py-1
                                       rounded text-sm">
                                🗑️ Delete
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-12">
                            <img src="{{ asset('images/empty.svg') }}"
                                 alt="No expenses"
                                 class="w-48 h-48 mx-auto mb-4
                                        opacity-80" />
                            <p class="text-gray-600 text-xl
                                      font-bold mb-2">
                                No Expenses Found!
                            </p>
                            <p class="text-gray-400 text-sm mb-6">
                                Start tracking your spending today
                            </p>
                            <button
                                wire:click="$set('showForm', true)"
                                class="bg-green-700 text-white px-8 py-3
                                       rounded-lg hover:bg-green-800
                                       font-medium">
                                + Add First Expense
                            </button>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div class="p-4">
            {{ $expenses->links() }}
        </div>
    </div>
</div>
