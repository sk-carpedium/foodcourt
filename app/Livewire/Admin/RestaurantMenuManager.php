<?php

namespace App\Livewire\Admin;

use App\Models\Restaurant;
use App\Models\Menu;
use App\Models\MenuCategory;
use App\Models\MenuItem;
use Livewire\Component;

class RestaurantMenuManager extends Component
{
    public $selectedRestaurant = null;
    public $selectedMenu = null;
    public $selectedCategory = null;
    public $activeLevel = 'restaurants'; // restaurants, menus, categories, items
    
    // Forms
    public $showForm = false;
    public $editingId = null;
    public $formType = ''; // restaurant, menu, category, item
    
    // Restaurant form
    public $restaurant_name = '';
    public $restaurant_description = '';
    public $restaurant_phone = '';
    public $restaurant_email = '';
    public $restaurant_address = '';
    public $restaurant_is_active = true;
    
    // Menu form
    public $menu_name = '';
    public $menu_description = '';
    public $menu_is_active = true;
    
    // Category form
    public $category_name = '';
    public $category_description = '';
    public $category_sort_order = 0;
    public $category_is_active = true;
    
    // Item form
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
        
        'menu_name' => 'required|string|max:255',
        'menu_description' => 'nullable|string',
        'menu_is_active' => 'boolean',
        
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
        if (!auth()->user()->can('view restaurants')) {
            abort(403, 'This action is unauthorized.');
        }
    }

    public function render()
    {
        $restaurants = Restaurant::withCount(['menus', 'menuCategories', 'menuItems'])->get();
        $menus = $this->selectedRestaurant ? 
            Menu::where('restaurant_id', $this->selectedRestaurant)->withCount(['menuCategories', 'menuItems'])->get() : 
            collect();
        $categories = $this->selectedMenu ? 
            MenuCategory::where('menu_id', $this->selectedMenu)->withCount('menuItems')->get() : 
            collect();
        $items = $this->selectedCategory ? 
            MenuItem::where('menu_category_id', $this->selectedCategory)->get() : 
            collect();

        return view('livewire.admin.restaurant-menu-manager', [
            'restaurants' => $restaurants,
            'menus' => $menus,
            'categories' => $categories,
            'items' => $items
        ]);
    }

    public function selectRestaurant($restaurantId)
    {
        $this->selectedRestaurant = $restaurantId;
        $this->selectedMenu = null;
        $this->selectedCategory = null;
        $this->activeLevel = 'menus';
    }

    public function selectMenu($menuId)
    {
        $this->selectedMenu = $menuId;
        $this->selectedCategory = null;
        $this->activeLevel = 'categories';
    }

    public function selectCategory($categoryId)
    {
        $this->selectedCategory = $categoryId;
        $this->activeLevel = 'items';
    }

    public function setActiveLevel($level)
    {
        $this->activeLevel = $level;
        if ($level === 'restaurants') {
            $this->selectedRestaurant = null;
            $this->selectedMenu = null;
            $this->selectedCategory = null;
        } elseif ($level === 'menus') {
            $this->selectedMenu = null;
            $this->selectedCategory = null;
        } elseif ($level === 'categories') {
            $this->selectedCategory = null;
        }
    }

    // Restaurant CRUD
    public function createRestaurant()
    {
        $this->authorize('create restaurants');
        $this->resetForms();
        $this->formType = 'restaurant';
        $this->showForm = true;
    }

    public function editRestaurant($id)
    {
        $this->authorize('edit restaurants');
        $restaurant = Restaurant::findOrFail($id);
        
        $this->editingId = $id;
        $this->formType = 'restaurant';
        $this->restaurant_name = $restaurant->name;
        $this->restaurant_description = $restaurant->description;
        $this->restaurant_phone = $restaurant->phone;
        $this->restaurant_email = $restaurant->email;
        $this->restaurant_address = $restaurant->address;
        $this->restaurant_is_active = $restaurant->is_active;
        $this->showForm = true;
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

        if ($this->editingId) {
            Restaurant::findOrFail($this->editingId)->update($data);
            session()->flash('message', 'Restaurant updated successfully!');
        } else {
            Restaurant::create($data);
            session()->flash('message', 'Restaurant created successfully!');
        }

        $this->resetForms();
    }

    public function deleteRestaurant($id)
    {
        $this->authorize('delete restaurants');
        Restaurant::findOrFail($id)->delete();
        session()->flash('message', 'Restaurant deleted successfully!');
    }

    // Menu CRUD
    public function createMenu()
    {
        if (!$this->selectedRestaurant) {
            session()->flash('error', 'Please select a restaurant first.');
            return;
        }
        
        $this->authorize('create menu categories');
        $this->resetForms();
        $this->formType = 'menu';
        $this->showForm = true;
    }

    public function editMenu($id)
    {
        $this->authorize('edit menu categories');
        $menu = Menu::findOrFail($id);
        
        $this->editingId = $id;
        $this->formType = 'menu';
        $this->menu_name = $menu->name;
        $this->menu_description = $menu->description;
        $this->menu_is_active = $menu->is_active;
        $this->showForm = true;
    }

    public function saveMenu()
    {
        $this->validate([
            'menu_name' => 'required|string|max:255',
            'menu_description' => 'nullable|string',
            'menu_is_active' => 'boolean',
        ]);

        $data = [
            'restaurant_id' => $this->selectedRestaurant,
            'name' => $this->menu_name,
            'description' => $this->menu_description,
            'is_active' => $this->menu_is_active,
        ];

        if ($this->editingId) {
            Menu::findOrFail($this->editingId)->update($data);
            session()->flash('message', 'Menu updated successfully!');
        } else {
            Menu::create($data);
            session()->flash('message', 'Menu created successfully!');
        }

        $this->resetForms();
    }

    public function deleteMenu($id)
    {
        $this->authorize('delete menu categories');
        Menu::findOrFail($id)->delete();
        session()->flash('message', 'Menu deleted successfully!');
    }

    // Category CRUD
    public function createCategory()
    {
        if (!$this->selectedMenu) {
            session()->flash('error', 'Please select a menu first.');
            return;
        }
        
        $this->authorize('create menu categories');
        $this->resetForms();
        $this->formType = 'category';
        $this->showForm = true;
    }

    public function editCategory($id)
    {
        $this->authorize('edit menu categories');
        $category = MenuCategory::findOrFail($id);
        
        $this->editingId = $id;
        $this->formType = 'category';
        $this->category_name = $category->name;
        $this->category_description = $category->description;
        $this->category_sort_order = $category->sort_order;
        $this->category_is_active = $category->is_active;
        $this->showForm = true;
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
            'menu_id' => $this->selectedMenu,
            'name' => $this->category_name,
            'description' => $this->category_description,
            'sort_order' => $this->category_sort_order,
            'is_active' => $this->category_is_active,
        ];

        if ($this->editingId) {
            MenuCategory::findOrFail($this->editingId)->update($data);
            session()->flash('message', 'Category updated successfully!');
        } else {
            MenuCategory::create($data);
            session()->flash('message', 'Category created successfully!');
        }

        $this->resetForms();
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
        $this->resetForms();
        $this->formType = 'item';
        $this->showForm = true;
    }

    public function editItem($id)
    {
        $this->authorize('edit menu items');
        $item = MenuItem::findOrFail($id);
        
        $this->editingId = $id;
        $this->formType = 'item';
        $this->item_name = $item->name;
        $this->item_description = $item->description;
        $this->item_price = $item->price;
        $this->item_preparation_time = $item->preparation_time;
        $this->item_is_available = $item->is_available;
        $this->item_is_featured = $item->is_featured;
        $this->item_sort_order = $item->sort_order;
        $this->showForm = true;
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
            'menu_id' => $this->selectedMenu,
            'menu_category_id' => $this->selectedCategory,
            'name' => $this->item_name,
            'description' => $this->item_description,
            'price' => $this->item_price,
            'preparation_time' => $this->item_preparation_time,
            'is_available' => $this->item_is_available,
            'is_featured' => $this->item_is_featured,
            'sort_order' => $this->item_sort_order,
        ];

        if ($this->editingId) {
            MenuItem::findOrFail($this->editingId)->update($data);
            session()->flash('message', 'Menu item updated successfully!');
        } else {
            MenuItem::create($data);
            session()->flash('message', 'Menu item created successfully!');
        }

        $this->resetForms();
    }

    public function deleteItem($id)
    {
        $this->authorize('delete menu items');
        MenuItem::findOrFail($id)->delete();
        session()->flash('message', 'Menu item deleted successfully!');
    }

    private function resetForms()
    {
        $this->showForm = false;
        $this->editingId = null;
        $this->formType = '';
        
        // Restaurant form
        $this->restaurant_name = '';
        $this->restaurant_description = '';
        $this->restaurant_phone = '';
        $this->restaurant_email = '';
        $this->restaurant_address = '';
        $this->restaurant_is_active = true;
        
        // Menu form
        $this->menu_name = '';
        $this->menu_description = '';
        $this->menu_is_active = true;
        
        // Category form
        $this->category_name = '';
        $this->category_description = '';
        $this->category_sort_order = 0;
        $this->category_is_active = true;
        
        // Item form
        $this->item_name = '';
        $this->item_description = '';
        $this->item_price = '';
        $this->item_preparation_time = 15;
        $this->item_is_available = true;
        $this->item_is_featured = false;
        $this->item_sort_order = 0;
        
        $this->resetValidation();
    }

    public function cancelForm()
    {
        $this->resetForms();
    }
}