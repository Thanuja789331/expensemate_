<div>

    {{-- Toast Notification --}}
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

    {{-- Header: Search + Add Button --}}
    <div style="display:flex; flex-wrap:wrap; gap:12px;
                justify-content:space-between; align-items:center; margin-bottom:16px;">

        {{-- Search --}}
        <div style="position:relative; flex:1; min-width:200px; max-width:360px;">
            <svg style="position:absolute; left:10px; top:50%; transform:translateY(-50%);
                        width:16px; height:16px; color:#9ca3af;"
                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M21 21l-4.35-4.35M17 11A6 6 0 1 1 5 11a6 6 0 0 1 12 0z"/>
            </svg>
            <input
                type="text"
                wire:model.live.debounce.300ms="search"
                placeholder="Search categories..."
                style="width:100%; padding:8px 12px 8px 34px; border:1px solid #e5e7eb;
                       border-radius:8px; font-size:14px; outline:none; box-sizing:border-box;"
                onfocus="this.style.borderColor='#1B6B4A'; this.style.boxShadow='0 0 0 2px rgba(27,107,74,0.15)'"
                onblur="this.style.borderColor='#e5e7eb'; this.style.boxShadow='none'"
            />
        </div>

        {{-- Add Button --}}
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
            Add Category
        </button>
    </div>

    {{-- Loading indicator --}}
    <div wire:loading wire:target="search"
         style="font-size:13px; color:#1B6B4A; margin-bottom:8px;">
        ⏳ Searching...
    </div>

    {{-- Table --}}
    <div style="background:white; border-radius:12px; overflow:hidden;
                border:1px solid #f1f5f9; box-shadow:0 1px 4px rgba(0,0,0,0.06);">
        <div style="overflow-x:auto;">
            <table style="width:100%; border-collapse:collapse; font-size:14px;">
                <thead>
                    <tr style="background:#f8fafc; border-bottom:1px solid #f1f5f9;">
                        <th style="padding:12px 16px; text-align:left; font-size:11px;
                                   font-weight:600; color:#9ca3af; text-transform:uppercase;
                                   letter-spacing:0.05em;">#</th>
                        <th style="padding:12px 16px; text-align:left; font-size:11px;
                                   font-weight:600; color:#9ca3af; text-transform:uppercase;
                                   letter-spacing:0.05em;">Name</th>
                        <th style="padding:12px 16px; text-align:left; font-size:11px;
                                   font-weight:600; color:#9ca3af; text-transform:uppercase;
                                   letter-spacing:0.05em;">Expenses</th>
                        <th style="padding:12px 16px; text-align:left; font-size:11px;
                                   font-weight:600; color:#9ca3af; text-transform:uppercase;
                                   letter-spacing:0.05em;">Status</th>
                        <th style="padding:12px 16px; text-align:right; font-size:11px;
                                   font-weight:600; color:#9ca3af; text-transform:uppercase;
                                   letter-spacing:0.05em;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($categories as $index => $category)
                    <tr wire:key="cat-{{ $category->id }}"
                        style="border-bottom:1px solid #f9fafb;"
                        onmouseover="this.style.background='#fafafa'"
                        onmouseout="this.style.background='white'">

                        {{-- Row number --}}
                        <td style="padding:14px 16px; color:#9ca3af;">
                            {{ $categories->firstItem() + $index }}
                        </td>

                        {{-- Name --}}
                        <td style="padding:14px 16px;">
                            @if ($editingId === $category->id)
                                <input
                                    type="text"
                                    wire:model.live="editName"
                                    wire:keydown.enter="updateCategory"
                                    wire:keydown.escape="cancelEdit"
                                    style="border:1px solid #1B6B4A; border-radius:6px;
                                           padding:5px 10px; font-size:13px; width:160px; outline:none;"
                                    autofocus
                                />
                                @error('editName')
                                    <div style="color:#ef4444; font-size:11px; margin-top:3px;">
                                        {{ $message }}
                                    </div>
                                @enderror
                            @else
                                <span style="font-weight:500; color:#1e293b;">
                                    {{ $category->name }}
                                </span>
                            @endif
                        </td>

                        {{-- Expense count --}}
                        <td style="padding:14px 16px; color:#64748b;">
                            {{ $category->expenses_count }}
                        </td>

                        {{-- Status --}}
                        <td style="padding:14px 16px;">
                            @if ($editingId === $category->id)
                                <select
                                    wire:model.live="editStatus"
                                    style="border:1px solid #e5e7eb; border-radius:6px;
                                           padding:5px 8px; font-size:13px; outline:none;"
                                >
                                    <option value="active">Active</option>
                                    <option value="inactive">Inactive</option>
                                </select>
                            @else
                                <button
                                    wire:click="toggleStatus({{ $category->id }})"
                                    style="display:inline-flex; align-items:center; gap:5px;
                                           padding:4px 10px; border-radius:999px; border:none;
                                           font-size:12px; font-weight:500; cursor:pointer;
                                           {{ $category->status === 'active'
                                               ? 'background:#dcfce7; color:#15803d;'
                                               : 'background:#f1f5f9; color:#64748b;' }}"
                                >
                                    <span style="width:6px; height:6px; border-radius:50%; display:inline-block;
                                                 {{ $category->status === 'active'
                                                     ? 'background:#16a34a;'
                                                     : 'background:#94a3b8;' }}">
                                    </span>
                                    {{ ucfirst($category->status) }}
                                </button>
                            @endif
                        </td>

                        {{-- Actions --}}
                        <td style="padding:14px 16px; text-align:right;">
                            <div style="display:flex; justify-content:flex-end; gap:6px;">
                                @if ($editingId === $category->id)
                                    <button
                                        wire:click="updateCategory"
                                        style="padding:5px 12px; background:#1B6B4A; color:white;
                                               border:none; border-radius:6px; font-size:12px;
                                               font-weight:500; cursor:pointer;"
                                        onmouseover="this.style.background='#155a3c'"
                                        onmouseout="this.style.background='#1B6B4A'"
                                    >Save</button>
                                    <button
                                        wire:click="cancelEdit"
                                        style="padding:5px 12px; background:#f1f5f9; color:#64748b;
                                               border:none; border-radius:6px; font-size:12px;
                                               font-weight:500; cursor:pointer;"
                                        onmouseover="this.style.background='#e2e8f0'"
                                        onmouseout="this.style.background='#f1f5f9'"
                                    >Cancel</button>
                                @else
                                    <button
                                        wire:click="startEdit({{ $category->id }})"
                                        title="Edit"
                                        style="padding:6px; background:none; border:none;
                                               color:#94a3b8; cursor:pointer; border-radius:6px;"
                                        onmouseover="this.style.color='#1B6B4A'; this.style.background='#f0fdf4'"
                                        onmouseout="this.style.color='#94a3b8'; this.style.background='none'"
                                    >
                                        <svg style="width:16px; height:16px;" fill="none"
                                             stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </button>
                                    <button
                                        wire:click="confirmDelete({{ $category->id }})"
                                        title="Delete"
                                        style="padding:6px; background:none; border:none;
                                               color:#94a3b8; cursor:pointer; border-radius:6px;"
                                        onmouseover="this.style.color='#ef4444'; this.style.background='#fef2f2'"
                                        onmouseout="this.style.color='#94a3b8'; this.style.background='none'"
                                    >
                                        <svg style="width:16px; height:16px;" fill="none"
                                             stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                @endif
                            </div>
                        </td>

                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" style="padding:48px 16px; text-align:center; color:#94a3b8;">
                            <svg style="width:40px; height:40px; margin:0 auto 12px; color:#e2e8f0;"
                                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                      d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                            </svg>
                            <p style="font-size:14px; font-weight:500; margin:0 0 4px;">No categories found</p>
                            <p style="font-size:12px; margin:0;">Add your first category to get started</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if ($categories->hasPages())
        <div style="padding:12px 16px; border-top:1px solid #f1f5f9;">
            {{ $categories->links() }}
        </div>
        @endif
    </div>

    {{-- ===== ADD MODAL ===== --}}
    @if ($showAddModal)
    <div style="position:fixed; inset:0; background:rgba(0,0,0,0.45); z-index:50;
                display:flex; align-items:center; justify-content:center; padding:16px;">
        <div style="background:white; border-radius:16px; padding:24px;
                    width:100%; max-width:420px; box-shadow:0 20px 60px rgba(0,0,0,0.2);"
             wire:click.stop>

            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px;">
                <h3 style="font-size:17px; font-weight:700; color:#1e293b; margin:0;">
                    Add Category
                </h3>
                <button wire:click="$set('showAddModal', false)"
                        style="background:none; border:none; color:#94a3b8; cursor:pointer; padding:4px;">
                    <svg style="width:20px; height:20px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <div style="margin-bottom:16px;">
                <label style="display:block; font-size:13px; font-weight:500; color:#374151; margin-bottom:6px;">
                    Category Name
                </label>
                <input
                    type="text"
                    wire:model.live="name"
                    placeholder="e.g. Food, Transport..."
                    style="width:100%; padding:9px 12px; border:1px solid #e5e7eb; border-radius:8px;
                           font-size:14px; outline:none; box-sizing:border-box;"
                    onfocus="this.style.borderColor='#1B6B4A'; this.style.boxShadow='0 0 0 2px rgba(27,107,74,0.15)'"
                    onblur="this.style.borderColor='#e5e7eb'; this.style.boxShadow='none'"
                    autofocus
                />
                @error('name')
                    <p style="color:#ef4444; font-size:12px; margin:4px 0 0;">{{ $message }}</p>
                @enderror
            </div>

            <div style="margin-bottom:24px;">
                <label style="display:block; font-size:13px; font-weight:500; color:#374151; margin-bottom:6px;">
                    Status
                </label>
                <select
                    wire:model="status"
                    style="width:100%; padding:9px 12px; border:1px solid #e5e7eb; border-radius:8px;
                           font-size:14px; outline:none; box-sizing:border-box; background:white;"
                >
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                </select>
                @error('status')
                    <p style="color:#ef4444; font-size:12px; margin:4px 0 0;">{{ $message }}</p>
                @enderror
            </div>

            <div style="display:flex; justify-content:flex-end; gap:10px;">
                <button
                    wire:click="$set('showAddModal', false)"
                    style="padding:9px 18px; background:#f1f5f9; color:#64748b; border:none;
                           border-radius:8px; font-size:14px; font-weight:500; cursor:pointer;"
                    onmouseover="this.style.background='#e2e8f0'"
                    onmouseout="this.style.background='#f1f5f9'"
                >Cancel</button>
                <button
                    wire:click="saveCategory"
                    wire:loading.attr="disabled"
                    style="padding:9px 18px; background:#1B6B4A; color:white; border:none;
                           border-radius:8px; font-size:14px; font-weight:500; cursor:pointer;"
                    onmouseover="this.style.background='#155a3c'"
                    onmouseout="this.style.background='#1B6B4A'"
                >
                    <span wire:loading.remove wire:target="saveCategory">Save Category</span>
                    <span wire:loading wire:target="saveCategory">Saving...</span>
                </button>
            </div>

        </div>
    </div>
    @endif

    {{-- ===== DELETE MODAL ===== --}}
    @if ($showDeleteModal)
    <div style="position:fixed; inset:0; background:rgba(0,0,0,0.45); z-index:50;
                display:flex; align-items:center; justify-content:center; padding:16px;">
        <div style="background:white; border-radius:16px; padding:24px;
                    width:100%; max-width:380px; box-shadow:0 20px 60px rgba(0,0,0,0.2);"
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
                        Delete Category?
                    </h3>
                    <p style="font-size:13px; color:#64748b; margin:0;">
                        "<strong>{{ $deletingName }}</strong>" will be permanently deleted.
                        This cannot be undone.
                    </p>
                </div>
            </div>

            <div style="display:flex; justify-content:flex-end; gap:10px;">
                <button
                    wire:click="$set('showDeleteModal', false)"
                    style="padding:9px 18px; background:#f1f5f9; color:#64748b; border:none;
                           border-radius:8px; font-size:14px; font-weight:500; cursor:pointer;"
                    onmouseover="this.style.background='#e2e8f0'"
                    onmouseout="this.style.background='#f1f5f9'"
                >Cancel</button>
                <button
                    wire:click="deleteCategory"
                    style="padding:9px 18px; background:#ef4444; color:white; border:none;
                           border-radius:8px; font-size:14px; font-weight:500; cursor:pointer;"
                    onmouseover="this.style.background='#dc2626'"
                    onmouseout="this.style.background='#ef4444'"
                >Delete</button>
            </div>

        </div>
    </div>
    @endif

</div>
