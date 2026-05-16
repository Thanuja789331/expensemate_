<div style="overflow-x:hidden; width:100%; box-sizing:border-box;">

    {{-- Toast --}}
    <div
        x-data="{ show: false, message: '', type: 'success' }"
        x-on:notify.window="
            message = $event.detail.message;
            type    = $event.detail.type;
            show    = true;
            setTimeout(() => show = false, 3000)
        "
        x-show="show"
        x-transition.opacity
        x-cloak
        :style="type === 'success'
            ? 'background:#f0fdf4; border-left:4px solid #1B6B4A; color:#1B6B4A;'
            : 'background:#fef2f2; border-left:4px solid #ef4444; color:#ef4444;'"
        style="padding:12px 16px; border-radius:8px; margin-bottom:16px;
               font-size:14px; font-weight:500;"
    >
        <span x-text="message"></span>
    </div>

    {{-- Header --}}
    <div style="display:flex; flex-wrap:wrap; gap:12px;
                justify-content:space-between; align-items:center; margin-bottom:20px;">
        <div>
            <h2 style="font-size:18px; font-weight:700; color:#1e293b;
                       margin:0 0 4px;" class="dark:text-white">
                💰 Budget Manager
            </h2>
            <p style="font-size:13px; color:#64748b; margin:0;">
                Set monthly budgets and track your spending
            </p>
        </div>
        <button
            wire:click="openAddModal"
            style="display:inline-flex; align-items:center; gap:6px;
                   padding:9px 18px; background:#1B6B4A; color:white;
                   border:none; border-radius:8px; font-size:14px;
                   font-weight:500; cursor:pointer; white-space:nowrap;"
            onmouseover="this.style.background='#155a3c'"
            onmouseout="this.style.background='#1B6B4A'"
        >
            <svg style="width:16px; height:16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Add Budget
        </button>
    </div>

    {{-- Budget Cards --}}
    @if ($budgets->count() > 0)
    <div style="display:grid; grid-template-columns:repeat(auto-fill, minmax(280px, 1fr));
                gap:16px; margin-bottom:20px;">

        @foreach ($budgets as $budget)
        <div wire:key="budget-{{ $budget->id }}"
             style="background:white; border-radius:12px; padding:16px;
                    border:1px solid #f1f5f9; box-shadow:0 1px 4px rgba(0,0,0,0.06);
                    box-sizing:border-box; width:100%; overflow:hidden;">

            @if ($editingId === $budget->id)
            {{-- Edit Mode --}}
            <div style="margin-bottom:12px;">
                <label style="font-size:12px; color:#64748b; display:block; margin-bottom:4px;">
                    Budget Amount ($)
                </label>
                <input type="number" wire:model.live="editLimitAmount" step="0.01"
                       style="width:100%; padding:8px 10px; border:1px solid #1B6B4A;
                              border-radius:6px; font-size:14px; outline:none; box-sizing:border-box;" />
                @error('editLimitAmount')
                    <p style="color:#ef4444; font-size:11px; margin:3px 0 0;">{{ $message }}</p>
                @enderror
            </div>
            <div style="display:grid; grid-template-columns:1fr 1fr; gap:8px; margin-bottom:12px;">
                <div>
                    <label style="font-size:12px; color:#64748b; display:block; margin-bottom:4px;">Month</label>
                    <select wire:model.live="editMonth"
                            style="width:100%; padding:8px; border:1px solid #e5e7eb;
                                   border-radius:6px; font-size:13px; outline:none; box-sizing:border-box;">
                        @foreach ($months as $num => $name)
                            <option value="{{ $num }}">{{ $name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label style="font-size:12px; color:#64748b; display:block; margin-bottom:4px;">Year</label>
                    <select wire:model.live="editYear"
                            style="width:100%; padding:8px; border:1px solid #e5e7eb;
                                   border-radius:6px; font-size:13px; outline:none; box-sizing:border-box;">
                        @foreach (range(date('Y') - 2, date('Y') + 2) as $y)
                            <option value="{{ $y }}">{{ $y }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div style="display:flex; gap:8px;">
                <button wire:click="updateBudget"
                        style="flex:1; padding:8px; background:#1B6B4A; color:white;
                               border:none; border-radius:8px; font-size:13px; font-weight:500; cursor:pointer;">
                    Save
                </button>
                <button wire:click="cancelEdit"
                        style="flex:1; padding:8px; background:#f1f5f9; color:#64748b;
                               border:none; border-radius:8px; font-size:13px; font-weight:500; cursor:pointer;">
                    Cancel
                </button>
            </div>

            @else
            {{-- Display Mode --}}

            {{-- Category name + buttons --}}
            <div style="display:flex; justify-content:space-between;
                        align-items:flex-start; margin-bottom:12px;">
                <div style="min-width:0; flex:1; padding-right:8px;">
                    <div style="font-size:15px; font-weight:700; color:#1e293b;
                                margin-bottom:2px; white-space:nowrap;
                                overflow:hidden; text-overflow:ellipsis;">
                        {{ $budget->category->name ?? 'Unknown' }}
                    </div>
                    <div style="font-size:12px; color:#94a3b8;">
                        {{ $months[str_pad($budget->period_month, 2, '0', STR_PAD_LEFT)] ?? '' }}
                        {{ $budget->period_year }}
                    </div>
                </div>
                <div style="display:flex; gap:2px; flex-shrink:0;">
                    <button wire:click="startEdit({{ $budget->id }})" title="Edit"
                            style="padding:5px; background:none; border:none;
                                   color:#94a3b8; cursor:pointer; border-radius:6px;"
                            onmouseover="this.style.color='#1B6B4A'; this.style.background='#f0fdf4'"
                            onmouseout="this.style.color='#94a3b8'; this.style.background='none'">
                        <svg style="width:15px; height:15px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                    </button>
                    <button wire:click="confirmDelete({{ $budget->id }})" title="Delete"
                            style="padding:5px; background:none; border:none;
                                   color:#94a3b8; cursor:pointer; border-radius:6px;"
                            onmouseover="this.style.color='#ef4444'; this.style.background='#fef2f2'"
                            onmouseout="this.style.color='#94a3b8'; this.style.background='none'">
                        <svg style="width:15px; height:15px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                    </button>
                </div>
            </div>

            {{-- Budget / Spent / Left boxes --}}
            @php $remaining = $budget->limit_amount - $budget->spent; @endphp
            <div style="display:grid; grid-template-columns:1fr 1fr 1fr;
                        gap:6px; margin-bottom:12px;">

                <div style="text-align:center; background:#f0fdf4;
                            border-radius:8px; padding:8px 4px;">
                    <div style="font-size:9px; color:#64748b; margin-bottom:2px;
                                text-transform:uppercase; font-weight:700; letter-spacing:0.03em;">
                        Budget
                    </div>
                    <div style="font-size:12px; font-weight:700; color:#1B6B4A;
                                overflow:hidden; text-overflow:ellipsis; white-space:nowrap;">
                        ${{ number_format($budget->limit_amount, 0) }}
                    </div>
                </div>

                <div style="text-align:center; background:#fff7ed;
                            border-radius:8px; padding:8px 4px;">
                    <div style="font-size:9px; color:#64748b; margin-bottom:2px;
                                text-transform:uppercase; font-weight:700; letter-spacing:0.03em;">
                        Spent
                    </div>
                    <div style="font-size:12px; font-weight:700;
                                overflow:hidden; text-overflow:ellipsis; white-space:nowrap;
                                color:{{ $budget->spent > $budget->limit_amount ? '#ef4444' : '#ea580c' }};">
                        ${{ number_format($budget->spent, 0) }}
                    </div>
                </div>

                <div style="text-align:center; border-radius:8px; padding:8px 4px;
                            background:{{ $remaining < 0 ? '#fef2f2' : '#f0fdf4' }};">
                    <div style="font-size:9px; color:#64748b; margin-bottom:2px;
                                text-transform:uppercase; font-weight:700; letter-spacing:0.03em;">
                        Left
                    </div>
                    <div style="font-size:12px; font-weight:700;
                                overflow:hidden; text-overflow:ellipsis; white-space:nowrap;
                                color:{{ $remaining < 0 ? '#ef4444' : '#1B6B4A' }};">
                        {{ $remaining < 0 ? '-' : '' }}${{ number_format(abs($remaining), 0) }}
                    </div>
                </div>

            </div>

            {{-- Progress bar --}}
            <div style="background:#f1f5f9; border-radius:999px; height:8px;
                        overflow:hidden; margin-bottom:5px;">
                <div style="height:100%; border-radius:999px; transition:width 0.3s;
                            width:{{ $budget->percent }}%;
                            background:{{ $budget->percent >= 100
                                ? '#ef4444'
                                : ($budget->percent >= 75 ? '#f59e0b' : '#1B6B4A') }};">
                </div>
            </div>

            <div style="display:flex; justify-content:space-between; margin-bottom:10px;">
                <span style="font-size:11px; color:#94a3b8;">0%</span>
                <span style="font-size:11px; font-weight:600;
                             color:{{ $budget->percent >= 100
                                 ? '#ef4444'
                                 : ($budget->percent >= 75 ? '#f59e0b' : '#1B6B4A') }};">
                    {{ $budget->percent }}% used
                </span>
                <span style="font-size:11px; color:#94a3b8;">100%</span>
            </div>

            {{-- Warnings --}}
            @if ($budget->percent >= 100)
            <div style="background:#fef2f2; border:1px solid #fecaca; border-radius:8px;
                        padding:7px 12px; font-size:12px; color:#ef4444; font-weight:500;">
                ⚠️ Over budget!
            </div>
            @elseif ($budget->percent >= 75)
            <div style="background:#fffbeb; border:1px solid #fde68a; border-radius:8px;
                        padding:7px 12px; font-size:12px; color:#d97706; font-weight:500;">
                ⚡ Approaching limit
            </div>
            @endif

            @endif
        </div>
        @endforeach
    </div>

    {{-- Pagination --}}
    @if ($budgets->hasPages())
    <div style="margin-bottom:20px;">{{ $budgets->links() }}</div>
    @endif

    @else
    {{-- Empty state --}}
    <div style="background:white; border-radius:12px; padding:48px 24px; text-align:center;
                border:1px solid #f1f5f9; box-shadow:0 1px 4px rgba(0,0,0,0.06); margin-bottom:20px;">
        <svg style="width:48px; height:48px; margin:0 auto 16px; color:#e2e8f0;"
             fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                  d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        <p style="font-size:15px; font-weight:600; color:#64748b; margin:0 0 6px;">No budgets yet</p>
        <p style="font-size:13px; color:#94a3b8; margin:0 0 20px;">Add your first budget to start tracking spending</p>
        <button wire:click="openAddModal"
                style="padding:9px 20px; background:#1B6B4A; color:white; border:none;
                       border-radius:8px; font-size:14px; font-weight:500; cursor:pointer;">
            + Add Your First Budget
        </button>
    </div>
    @endif

    {{-- ===== ADD MODAL ===== --}}
    @if ($showAddModal)
    <div style="position:fixed; inset:0; background:rgba(0,0,0,0.5); z-index:50;
                display:flex; align-items:center; justify-content:center; padding:16px;">
        <div style="background:white; border-radius:16px; padding:24px; width:100%;
                    max-width:440px; box-shadow:0 20px 60px rgba(0,0,0,0.2); box-sizing:border-box;"
             wire:click.stop>

            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px;">
                <h3 style="font-size:17px; font-weight:700; color:#1e293b; margin:0;">Add Budget</h3>
                <button wire:click="$set('showAddModal', false)"
                        style="background:none; border:none; color:#94a3b8; cursor:pointer; padding:4px;">
                    <svg style="width:20px; height:20px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <div style="margin-bottom:16px;">
                <label style="display:block; font-size:13px; font-weight:500; color:#374151; margin-bottom:6px;">
                    Category
                </label>
                <select wire:model="category_id"
                        style="width:100%; padding:9px 12px; border:1px solid #e5e7eb; border-radius:8px;
                               font-size:14px; outline:none; box-sizing:border-box; background:white;">
                    <option value="">Select a category...</option>
                    @foreach ($categories as $cat)
                        <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                    @endforeach
                </select>
                @error('category_id')
                    <p style="color:#ef4444; font-size:12px; margin:4px 0 0;">{{ $message }}</p>
                @enderror
            </div>

            <div style="margin-bottom:16px;">
                <label style="display:block; font-size:13px; font-weight:500; color:#374151; margin-bottom:6px;">
                    Budget Amount ($)
                </label>
                <input type="number" wire:model.live="limit_amount"
                       placeholder="e.g. 500.00" step="0.01" min="1"
                       style="width:100%; padding:9px 12px; border:1px solid #e5e7eb; border-radius:8px;
                              font-size:14px; outline:none; box-sizing:border-box;"
                       onfocus="this.style.borderColor='#1B6B4A'"
                       onblur="this.style.borderColor='#e5e7eb'" />
                @error('limit_amount')
                    <p style="color:#ef4444; font-size:12px; margin:4px 0 0;">{{ $message }}</p>
                @enderror
            </div>

            <div style="display:grid; grid-template-columns:1fr 1fr; gap:12px; margin-bottom:24px;">
                <div>
                    <label style="display:block; font-size:13px; font-weight:500; color:#374151; margin-bottom:6px;">
                        Month
                    </label>
                    <select wire:model="month"
                            style="width:100%; padding:9px 12px; border:1px solid #e5e7eb; border-radius:8px;
                                   font-size:14px; outline:none; box-sizing:border-box; background:white;">
                        @foreach ($months as $num => $name)
                            <option value="{{ $num }}">{{ $name }}</option>
                        @endforeach
                    </select>
                    @error('month')
                        <p style="color:#ef4444; font-size:12px; margin:4px 0 0;">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label style="display:block; font-size:13px; font-weight:500; color:#374151; margin-bottom:6px;">
                        Year
                    </label>
                    <select wire:model="year"
                            style="width:100%; padding:9px 12px; border:1px solid #e5e7eb; border-radius:8px;
                                   font-size:14px; outline:none; box-sizing:border-box; background:white;">
                        @foreach (range(date('Y') - 2, date('Y') + 2) as $y)
                            <option value="{{ $y }}">{{ $y }}</option>
                        @endforeach
                    </select>
                    @error('year')
                        <p style="color:#ef4444; font-size:12px; margin:4px 0 0;">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div style="display:flex; justify-content:flex-end; gap:10px;">
                <button wire:click="$set('showAddModal', false)"
                        style="padding:9px 18px; background:#f1f5f9; color:#64748b; border:none;
                               border-radius:8px; font-size:14px; font-weight:500; cursor:pointer;">
                    Cancel
                </button>
                <button wire:click="saveBudget" wire:loading.attr="disabled"
                        style="padding:9px 18px; background:#1B6B4A; color:white; border:none;
                               border-radius:8px; font-size:14px; font-weight:500; cursor:pointer;">
                    <span wire:loading.remove wire:target="saveBudget">Save Budget</span>
                    <span wire:loading wire:target="saveBudget">Saving...</span>
                </button>
            </div>
        </div>
    </div>
    @endif

    {{-- ===== DELETE MODAL ===== --}}
    @if ($showDeleteModal)
    <div style="position:fixed; inset:0; background:rgba(0,0,0,0.5); z-index:50;
                display:flex; align-items:center; justify-content:center; padding:16px;">
        <div style="background:white; border-radius:16px; padding:24px; width:100%;
                    max-width:380px; box-shadow:0 20px 60px rgba(0,0,0,0.2); box-sizing:border-box;"
             wire:click.stop>

            <div style="display:flex; gap:16px; align-items:flex-start; margin-bottom:20px;">
                <div style="width:44px; height:44px; border-radius:50%; background:#fef2f2;
                            display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                    <svg style="width:22px; height:22px; color:#ef4444;"
                         fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
                    </svg>
                </div>
                <div>
                    <h3 style="font-size:16px; font-weight:700; color:#1e293b; margin:0 0 6px;">
                        Delete Budget?
                    </h3>
                    <p style="font-size:13px; color:#64748b; margin:0;">
                        This budget will be permanently deleted.
                    </p>
                </div>
            </div>

            <div style="display:flex; justify-content:flex-end; gap:10px;">
                <button wire:click="$set('showDeleteModal', false)"
                        style="padding:9px 18px; background:#f1f5f9; color:#64748b; border:none;
                               border-radius:8px; font-size:14px; font-weight:500; cursor:pointer;">
                    Cancel
                </button>
                <button wire:click="deleteBudget"
                        style="padding:9px 18px; background:#ef4444; color:white; border:none;
                               border-radius:8px; font-size:14px; font-weight:500; cursor:pointer;">
                    Delete
                </button>
            </div>
        </div>
    </div>
    @endif

    {{-- Dark mode styles --}}
    <style>
        .dark .budget-card { background:#1e293b !important; border-color:#334155 !important; }
        .dark .budget-card .cat-name { color:#f1f5f9 !important; }
        .dark .budget-card .cat-date { color:#94a3b8 !important; }
    </style>

</div>
