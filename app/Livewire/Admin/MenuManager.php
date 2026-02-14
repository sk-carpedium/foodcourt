<?php

namespace App\Livewire\Admin;

use App\Models\Restaurant;
use App\Models\MenuCategory;
use App\Models\MenuItem;
use Livewire\Component;
use Livewire\WithPagination;

class MenuManager extends Component
{
    use WithPagination;

    public $selectedRestaurant = null;
    public $selectedCategory = null;
    public $activeTab = 'restaurants'; // restaurants, categories, items
    
    // Restaurant form
    public $showRestaurantForm = false;
    public $editingRestaurantId = null;
    public $restaurant_name = '';
    public $restaurant_description = '';
    public $restaurant_phone = '';
    public $restaurant_email = '';
    public $restaurant_address = '';
    public $restaurant_is_active = true;

    // Category form
    public $showCategoryForm = false;
    public $editingCategoryId = null;
    public $category_name = '';
    public $category_description = '';
    public $category_sort_order = 0;
    public $category_is_active = true;

    // Item form
    public $showItemForm = false;
    public $editingItemId = null;
    public $item_name = '';
    public $item_description = '';
    public $item_price = '';
    public $item_preparation_time = 15;
    public $item_is_available = true;
    public $item_is_featured = false;
    public $item_sort_order = 0;

    protected $rules = [
        'restaurant_name' => 'required|string|max:255',
        'restaurant_description' => 'nullable|string',
        'restaurant_phone' => 'nullable|string',
        'restaurant_email' => 'nullable|email',
        'restaurant_address' => 'nullable|string',
        'restaurant_is_active' => 'boolean',
        
        'category_name' => 'required|string|max:255',
        'category_description' => 'nullable|string',
        'category_sort_order' => 'integer|min:0',
        'category_is_active' => 'boolean',
        
        'item_name' => 'required|string|max:255',
        'item_description' => 'nullable|string',
        'item_price' => 'required|numeric|min:0',
        'item_preparation_time' => 'integer|min:1',
        'item_is_available' => 'boolean',
        'item_is_featured' => 'boolean',
        'item_sort_order' => 'integer|min:0',
    ];

    public function mount()
    {
        $this->authorize('view restaurants');
    }

    public function render()
    {
        $restaurants = Restaurant::orderBy('name')->get();
        $categories = $this->selectedRestaurant ? 
            MenuCategory::where('restaurant_id', $this->selectedRestaurant)->orderBy('sort_order')->get() : 
            collect();
        $items = $this->selectedCategory ? 
            MenuItem::where('menu_category_id', $this->selectedCategory)->orderBy('sort_order')->get() : 
            collect();

        return view('livewire.admin.menu-manager', [
            'restaurants' => $restaurants,
            'categories' => $categories,
            'items' => $items
        ]);
    }

    public function selectRestaurant($restaurantId)
    {
        $this->selectedRestaurant = $restaurantId;
        $this->selectedCategory = null;
        $this->activeTab = 'categories';
    }

    public function selectCategory($categoryId)
    {
        $this->selectedCategory = $categoryId;
        $this->activeTab = 'items';
    }

    public function setActiveTab($tab)
    {
        $this->activeTab = $tab;
        if ($tab === 'restaurants') {
            $this->selectedRestaurant = null;
            $this->selectedCategory = null;
        } elseif ($tab === 'categories') {
            $this->selectedCategory = null;
        }
    }

    // Restaurant CRUD
    public function createRestaurant()
    {
        $this->authorize('create restaurants');
        $this->resetRestaurantForm();
        $this->showRestaurantForm = true;
    }

    public function editRestaurant($id)
    {
        $this->authorize('edit restaurants');
        $restaurant = Restaurant::findOrFail($id);
        
        $this->editingRestaurantId = $id;
        $this->restaurant_name = $restaurant->name;
        $this->restaurant_description = $restaurant->description;
        $this->restaurant_phone = $restaurant->phone;
        $this->restaurant_email = $restaurant->email;
        $this->restaurant_address = $restaurant->address;
        $this->restaurant_is_active = $restaurant->is_active;
        $this->showRestaurantForm = true;
    }

    public function saveRestaurant()
    {
        $this->validate([
            'restaurant_name' => 'required|string|max:255',
            'restaurant_description' => 'nullable|string',
            'restaurant_phone' => 'nullable|string',
            'restaurant_email' => 'nullable|email',
            'restaurant_address' => 'nullable|string',
            'restaurant_is_active' => 'boolean',
        ]);

        $data = [
            'name' => $this->restaurant_name,
            'description' => $this->restaurant_description,
            'phone' => $this->restaurant_phone,
            'email' => $this->restaurant_email,
            'address' => $this->restaurant_address,
            'is_active' => $this->restaurant_is_active,
        ];

        if ($this->editingRestaurantId) {
            $this->authorize('edit restaurants');
            Restaurant::findOrFail($this->editingRestaurantId)->update($data);
            session()->flash('message', 'Restaurant updated successfully!');
        } else {
            $this->authorize('create restaurants');
            Restaurant::create($data);
            session()->flash('message', 'Restaurant created successfully!');
        }

        $this->resetRestaurantForm();
    }

    public function deleteRestaurant($id)
    {
        $this->authorize('delete restaurants');
        Restaurant::findOrFail($id)->delete();
        session()->flash('message', 'Restaurant deleted successfully!');
    }

    // Category CRUD
    public function createCategory()
    {
        if (!$this->selectedRestaurant) {
            session()->flash('error', 'Please select a restaurant first.');
            return;
        }
        
        $this->authorize('create menu categories');
        $this->resetCategoryForm();
        $this->showCategoryForm = true;
    }

    public function editCategory($id)
    {
        $this->authorize('edit menu categories');
        $category = MenuCategory::findOrFail($id);
        
        $this->editingCategoryId = $id;
        $this->category_name = $category->name;
        $this->category_description = $category->description;
        $this->category_sort_order = $category->sort_order;
        $this->category_is_active = $category->is_active;
        $this->showCategoryForm = true;
    }

    public function saveCategory()
    {
        $this->validate([
            'category_name' => 'required|string|max:255',
            'category_description' => 'nullable|string',
            'category_sort_order' => 'integer|min:0',
            'category_is_active' => 'boolean',
        ]);

        $data = [
            'restaurant_id' => $this->selectedRestaurant,
            'name' => $this->category_name,
            'description' => $this->category_description,
            'sort_order' => $this->category_sort_order,
            'is_active' => $this->category_is_active,
        ];

        if ($this->editingCategoryId) {
            $this->authorize('edit menu categories');
            MenuCategory::findOrFail($this->editingCategoryId)->update($data);
            session()->flash('message', 'Category updated successfully!');
        } else {
            $this->authorize('create menu categories');
            MenuCategory::create($data);
            session()->flash('message', 'Category created successfully!');
        }

        $this->resetCategoryForm();
    }

    public function deleteCategory($id)
    {
        $this->authorize('delete menu categories');
        MenuCategory::findOrFail($id)->delete();
        session()->flash('message', 'Category deleted successfully!');
    }

    // Item CRUD
    public function createItem()
    {
        if (!$this->selectedCategory) {
            session()->flash('error', 'Please select a category first.');
            return;
        }
        
        $this->authorize('create menu items');
        $this->resetItemForm();
        $this->showItemForm = true;
    }

    public function editItem($id)
    {
        $this->authorize('edit menu items');
        $item = MenuItem::findOrFail($id);
        
        $this->editingItemId = $id;
        $this->item_name = $item->name;
        $this->item_description = $item->description;
        $this->item_price = $item->price;
        $this->item_preparation_time = $item->preparation_time;
        $this->item_is_available = $item->is_available;
        $this->item_is_featured = $item->is_featured;
        $this->item_sort_order = $item->sort_order;
        $this->showItemForm = true;
    }

    public function saveItem()
    {
        $this->validate([
            'item_name' => 'required|string|max:255',
            'item_description' => 'nullable|string',
            'item_price' => 'required|numeric|min:0',
            'item_preparation_time' => 'integer|min:1',
            'item_is_available' => 'boolean',
            'item_is_featured' => 'boolean',
            'item_sort_order' => 'integer|min:0',
        ]);

        $data = [
            'restaurant_id' => $this->selectedRestaurant,
            'menu_category_id' => $this->selectedCategory,
            'name' => $this->item_name,
            'description' => $this->item_description,
            'price' => $this->item_price,
            'preparation_time' => $this->item_preparation_time,
            'is_available' => $this->item_is_available,
            'is_featured' => $this->item_is_featured,
            'sort_order' => $this->item_sort_order,
        ];

        if ($this->editingItemId) {
            $this->authorize('edit menu items');
            MenuItem::findOrFail($this->editingItemId)->update($data);
            session()->flash('message', 'Menu item updated successfully!');
        } else {
            $this->authorize('create menu items');
            MenuItem::create($data);
            session()->flash('message', 'Menu item created successfully!');
        }

        $this->resetItemForm();
    }

    public function deleteItem($id)
    {
        $this->authorize('delete menu items');
        MenuItem::findOrFail($id)->delete();
        session()->flash('message', 'Menu item deleted successfully!');
    }

    // Form reset methods
    private function resetRestaurantForm()
    {
        $this->showRestaurantForm = false;
        $this->editingRestaurantId = null;
        $this->restaurant_name = '';
        $this->restaurant_description = '';
        $this->restaurant_phone = '';
        $this->restaurant_email = '';
        $this->restaurant_address = '';
        $this->restaurant_is_active = true;
        $this->resetValidation();
    }

    private function resetCategoryForm()
    {
        $this->showCategoryForm = false;
        $this->editingCategoryId = null;
        $this->category_name = '';
        $this->category_description = '';
        $this->category_sort_order = 0;
        $this->category_is_active = true;
        $this->resetValidation();
    }

    private function resetItemForm()
    {
        $this->showItemForm = false;
        $this->editingItemId = null;
        $this->item_name = '';
        $this->item_description = '';
        $this->item_price = '';
        $this->item_preparation_time = 15;
        $this->item_is_available = true;
        $this->item_is_featured = false;
        $this->item_sort_order = 0;
        $this->resetValidation();
    }

    public function cancelRestaurant()
    {
        $this->resetRestaurantForm();
    }

    public function cancelCategory()
    {
        $this->resetCategoryForm();
    }

    public function cancelItem()
    {
        $this->resetItemForm();
    }
}