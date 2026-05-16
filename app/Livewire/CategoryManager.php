<?php

namespace App\Livewire;

use App\Models\Category;
use Livewire\Component;
use Livewire\WithPagination;

class CategoryManager extends Component
{
    use WithPagination;

    // Search
    public string $search = '';

    // Add form
    public string $name   = '';
    public string $status = 'active';

    // Edit form
    public ?int   $editingId     = null;
    public string $editName      = '';
    public string $editStatus    = 'active';

    // Delete
    public ?int   $deletingId   = null;
    public string $deletingName = '';

    // Modal flags
    public bool $showAddModal    = false;
    public bool $showDeleteModal = false;

    // Reset page when searching
    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    // Open add modal
    public function openAddModal(): void
    {
        $this->reset(['name', 'status']);
        $this->status       = 'active';
        $this->showAddModal = true;
    }

    // Save new category
    public function saveCategory(): void
    {
        $this->validate([
            'name'   => 'required|string|min:2|max:50|unique:categories,name',
            'status' => 'required|in:active,inactive',
        ]);

        Category::create([
            'name'   => trim($this->name),
            'status' => $this->status,
        ]);

        $this->reset(['name', 'status']);
        $this->showAddModal = false;
        $this->dispatch('notify', message: 'Category added!', type: 'success');
    }

    // Start inline edit
    public function startEdit(int $id): void
    {
        $cat = Category::findOrFail($id);
        $this->editingId  = $id;
        $this->editName   = $cat->name;
        $this->editStatus = $cat->status;
    }

    // Save inline edit
    public function updateCategory(): void
    {
        $this->validate([
            'editName'   => 'required|string|min:2|max:50|unique:categories,name,' . $this->editingId,
            'editStatus' => 'required|in:active,inactive',
        ]);

        Category::findOrFail($this->editingId)->update([
            'name'   => trim($this->editName),
            'status' => $this->editStatus,
        ]);

        $this->editingId = null;
        $this->dispatch('notify', message: 'Category updated!', type: 'success');
    }

    // Cancel edit
    public function cancelEdit(): void
    {
        $this->editingId = null;
    }

    // Toggle active/inactive
    public function toggleStatus(int $id): void
    {
        $cat = Category::findOrFail($id);
        $cat->update([
            'status' => $cat->status === 'active' ? 'inactive' : 'active',
        ]);
        $this->dispatch('notify', message: 'Status updated!', type: 'success');
    }

    // Open delete confirmation
    public function confirmDelete(int $id): void
    {
        $cat = Category::findOrFail($id);
        $this->deletingId      = $id;
        $this->deletingName    = $cat->name;
        $this->showDeleteModal = true;
    }

    // Delete category
    public function deleteCategory(): void
    {
        Category::findOrFail($this->deletingId)->delete();
        $this->deletingId      = null;
        $this->showDeleteModal = false;
        $this->dispatch('notify', message: 'Category deleted!', type: 'success');
    }

    public function render()
    {
        $categories = Category::withCount('expenses')
            ->when($this->search, fn($q) =>
                $q->where('name', 'like', '%' . $this->search . '%')
            )
            ->orderBy('name')
            ->paginate(10);

        return view('livewire.category-manager', compact('categories'));
    }
}
