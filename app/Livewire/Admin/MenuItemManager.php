<?php

namespace App\Livewire\Admin;

use App\Models\MenuItem;
use App\Models\MenuCategory;
use App\Models\Restaurant;
use Livewire\Component;
use Livewire\WithPagination;

class MenuItemManager extends Component
{
    use WithPagination;

    public $showForm = false;
    public $editingId = null;
    public $restaurant_id = '';
    public $menu_category_id = '';
    public $name = '';
    public $description = '';
    public $price = '';
    public $preparation_time = 15;
    public $is_available = true;
    public $is_featured = false;
    public $sort_order = 0;

    protected $rules = [
        'restaurant_id' => 'required|exists:restaurants,id',
        'menu_category_id' => 'required|exists:menu_categories,id',
        'name' => 'required|string|max:255',
        'description' => 'nullable|string',
        'price' => 'required|numeric|min:0',
        'preparation_time' => 'integer|min:1',
        'is_available' => 'boolean',
        'is_featured' => 'boolean',
        'sort_order' => 'integer|min:0',
    ];

    public function mount()
    {
        $this->authorize('view menu items');
    }

    public function render()
    {
        return view('livewire.admin.menu-item-manager', [
            'menuItems' => MenuItem::with(['restaurant', 'menuCategory'])->paginate(10),
            'restaurants' => Restaurant::where('is_active', true)->get(),
            'categories' => $this->restaurant_id ? 
                MenuCategory::where('restaurant_id', $this->restaurant_id)->where('is_active', true)->get() : 
                collect()
        ]);
    }

    public function updatedRestaurantId()
    {
        $this->menu_category_id = '';
    }

    public function create()
    {
        $this->authorize('create menu items');
        $this->resetForm();
        $this->showForm = true;
    }

    public function edit($id)
    {
        $this->authorize('edit menu items');
        $item = MenuItem::findOrFail($id);
        
        $this->editingId = $id;
        $this->restaurant_id = $item->restaurant_id;
        $this->menu_category_id = $item->menu_category_id;
        $this->name = $item->name;
        $this->description = $item->description;
        $this->price = $item->price;
        $this->preparation_time = $item->preparation_time;
        $this->is_available = $item->is_available;
        $this->is_featured = $item->is_featured;
        $this->sort_order = $item->sort_order;
        $this->showForm = true;
    }

    public function save()
    {
        $this->validate();

        if ($this->editingId) {
            $this->authorize('edit menu items');
            $item = MenuItem::findOrFail($this->editingId);
            $item->update($this->getFormData());
        } else {
            $this->authorize('create menu items');
            MenuItem::create($this->getFormData());
        }

        $this->resetForm();
        session()->flash('message', 'Menu item saved successfully!');
    }

    public function delete($id)
    {
        $this->authorize('delete menu items');
        MenuItem::findOrFail($id)->delete();
        session()->flash('message', 'Menu item deleted successfully!');
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
        $this->menu_category_id = '';
        $this->name = '';
        $this->description = '';
        $this->price = '';
        $this->preparation_time = 15;
        $this->is_available = true;
        $this->is_featured = false;
        $this->sort_order = 0;
        $this->resetValidation();
    }

    private function getFormData()
    {
        return [
            'restaurant_id' => $this->restaurant_id,
            'menu_category_id' => $this->menu_category_id,
            'name' => $this->name,
            'description' => $this->description,
            'price' => $this->price,
            'preparation_time' => $this->preparation_time,
            'is_available' => $this->is_available,
            'is_featured' => $this->is_featured,
            'sort_order' => $this->sort_order,
        ];
    }
}