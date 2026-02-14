<?php

namespace App\Livewire\Admin;

use App\Models\MenuCategory;
use App\Models\Restaurant;
use Livewire\Component;
use Livewire\WithPagination;

class MenuCategoryManager extends Component
{
    use WithPagination;

    public $showForm = false;
    public $editingId = null;
    public $restaurant_id = '';
    public $name = '';
    public $description = '';
    public $sort_order = 0;
    public $is_active = true;

    protected $rules = [
        'restaurant_id' => 'required|exists:restaurants,id',
        'name' => 'required|string|max:255',
        'description' => 'nullable|string',
        'sort_order' => 'integer|min:0',
        'is_active' => 'boolean',
    ];

    public function mount()
    {
        $this->authorize('view menu categories');
    }

    public function render()
    {
        return view('livewire.admin.menu-category-manager', [
            'categories' => MenuCategory::with('restaurant')->paginate(10),
            'restaurants' => Restaurant::where('is_active', true)->get()
        ]);
    }

    public function create()
    {
        $this->authorize('create menu categories');
        $this->resetForm();
        $this->showForm = true;
    }

    public function edit($id)
    {
        $this->authorize('edit menu categories');
        $category = MenuCategory::findOrFail($id);
        
        $this->editingId = $id;
        $this->restaurant_id = $category->restaurant_id;
        $this->name = $category->name;
        $this->description = $category->description;
        $this->sort_order = $category->sort_order;
        $this->is_active = $category->is_active;
        $this->showForm = true;
    }

    public function save()
    {
        $this->validate();

        if ($this->editingId) {
            $this->authorize('edit menu categories');
            $category = MenuCategory::findOrFail($this->editingId);
            $category->update($this->getFormData());
        } else {
            $this->authorize('create menu categories');
            MenuCategory::create($this->getFormData());
        }

        $this->resetForm();
        session()->flash('message', 'Menu category saved successfully!');
    }

    public function delete($id)
    {
        $this->authorize('delete menu categories');
        MenuCategory::findOrFail($id)->delete();
        session()->flash('message', 'Menu category deleted successfully!');
    }

    public function cancel()
    {
        $this->resetForm();
    }

    private function resetForm()
    {
        $this->showForm = false;
        $this->editingId = null;
        $this->restaurant_id = '';
        $this->name = '';
        $this->description = '';
        $this->sort_order = 0;
        $this->is_active = true;
        $this->resetValidation();
    }

    private function getFormData()
    {
        return [
            'restaurant_id' => $this->restaurant_id,
            'name' => $this->name,
            'description' => $this->description,
            'sort_order' => $this->sort_order,
            'is_active' => $this->is_active,
        ];
    }
}