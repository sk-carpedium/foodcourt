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
    public $activeLevel = 'restaurants'; // restaurants, items
    
    // Forms
    public $showForm = false;
    public $editingId = null;
    public $formType = ''; // restaurant, item
    
    // Restaurant form
    public $restaurant_name = '';
    public $restaurant_description = '';
    public $restaurant_phone = '';
    public $restaurant_email = '';
    public $restaurant_address = '';
    public $restaurant_is_active = true;
    
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
        $restaurants = Restaurant::withCount('menuItems')->get();
        
        $items = $this->selectedRestaurant ? 
            MenuItem::where('restaurant_id', $this->selectedRestaurant)
                ->orderBy('sort_order')
                ->orderBy('name')
                ->get() : 
            collect();

        return view('livewire.admin.restaurant-menu-manager', [
            'restaurants' => $restaurants,
            'items' => $items,
        ]);
    }

    public function selectRestaurant($restaurantId)
    {
        $this->selectedRestaurant = $restaurantId;
        $this->activeLevel = 'items';
    }

    public function setActiveLevel($level)
    {
        $this->activeLevel = $level;
        if ($level === 'restaurants') {
            $this->selectedRestaurant = null;
        }
    }

    /**
     * Get or create a default menu and category for the restaurant.
     * This keeps the DB structure intact while simplifying the admin UI.
     */
    private function getDefaultMenuAndCategory($restaurantId)
    {
        $restaurant = Restaurant::findOrFail($restaurantId);
        
        // Get or create default menu
        $menu = Menu::firstOrCreate(
            ['restaurant_id' => $restaurantId, 'name' => 'Default Menu'],
            ['description' => 'Main menu', 'is_active' => true]
        );
        
        // Get or create default category
        $category = MenuCategory::firstOrCreate(
            ['menu_id' => $menu->id, 'name' => 'General'],
            ['restaurant_id' => $restaurantId, 'description' => 'General items', 'is_active' => true, 'sort_order' => 0]
        );

        return [$menu, $category];
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
            $restaurant = Restaurant::create($data);
            // Auto-create default menu & category
            $this->getDefaultMenuAndCategory($restaurant->id);
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

    // Item CRUD
    public function createItem()
    {
        if (!$this->selectedRestaurant) {
            session()->flash('error', 'Please select a restaurant first.');
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

        [$menu, $category] = $this->getDefaultMenuAndCategory($this->selectedRestaurant);

        $data = [
            'restaurant_id' => $this->selectedRestaurant,
            'menu_id' => $menu->id,
            'menu_category_id' => $category->id,
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
