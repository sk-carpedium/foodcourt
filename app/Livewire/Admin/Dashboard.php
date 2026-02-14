<?php

namespace App\Livewire\Admin;

use App\Models\Restaurant;
use App\Models\MenuCategory;
use App\Models\MenuItem;
use App\Models\Order;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class Dashboard extends Component
{
    use WithPagination;

    public $activeSection = 'overview'; // overview, restaurants, orders, users
    public $selectedRestaurant = null;
    public $selectedCategory = null;
    
    // Quick stats
    public $stats = [];
    
    // Forms
    public $showForm = false;
    public $editingId = null;
    public $formType = ''; // restaurant, category, item, user
    
    // Restaurant form
    public $restaurant_name = '';
    public $restaurant_description = '';
    public $restaurant_phone = '';
    public $restaurant_email = '';
    public $restaurant_address = '';
    public $restaurant_is_active = true;
    
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
    
    // User form
    public $user_name = '';
    public $user_email = '';
    public $user_password = '';
    public $user_role = 'customer';
    public $user_restaurant_id = '';

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
        
        'user_name' => 'required|string|max:255',
        'user_email' => 'required|email|unique:users,email',
        'user_password' => 'required|string|min:8',
        'user_role' => 'required|string',
        'user_restaurant_id' => 'nullable|exists:restaurants,id',
    ];

    public function mount()
    {
        $this->authorize('view restaurants');
        $this->loadStats();
    }

    public function render()
    {
        $data = [
            'restaurants' => Restaurant::withCount(['menuCategories', 'menuItems', 'orders'])->get(),
            'categories' => $this->selectedRestaurant ? 
                MenuCategory::where('restaurant_id', $this->selectedRestaurant)->withCount('menuItems')->get() : 
                collect(),
            'items' => $this->selectedCategory ? 
                MenuItem::where('menu_category_id', $this->selectedCategory)->get() : 
                collect(),
            'recentOrders' => Order::with(['restaurant', 'orderItems'])->latest()->take(10)->get(),
            'users' => User::with('roles')->latest()->take(20)->get(),
            'stats' => $this->stats
        ];

        return view('livewire.admin.dashboard', $data);
    }

    public function loadStats()
    {
        $this->stats = [
            'restaurants' => Restaurant::count(),
            'categories' => MenuCategory::count(),
            'items' => MenuItem::count(),
            'orders_today' => Order::whereDate('created_at', today())->count(),
            'orders_pending' => Order::whereIn('status', ['pending', 'confirmed'])->count(),
            'revenue_today' => Order::whereDate('created_at', today())->sum('total_amount'),
            'users' => User::count(),
        ];
    }

    public function setActiveSection($section)
    {
        $this->activeSection = $section;
        $this->resetForms();
    }

    public function selectRestaurant($restaurantId)
    {
        $this->selectedRestaurant = $restaurantId;
        $this->selectedCategory = null;
    }

    public function selectCategory($categoryId)
    {
        $this->selectedCategory = $categoryId;
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
        $this->loadStats();
    }

    public function deleteRestaurant($id)
    {
        $this->authorize('delete restaurants');
        Restaurant::findOrFail($id)->delete();
        session()->flash('message', 'Restaurant deleted successfully!');
        $this->loadStats();
    }

    // Category CRUD
    public function createCategory()
    {
        if (!$this->selectedRestaurant) {
            session()->flash('error', 'Please select a restaurant first.');
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
        $this->loadStats();
    }

    public function deleteCategory($id)
    {
        $this->authorize('delete menu categories');
        MenuCategory::findOrFail($id)->delete();
        session()->flash('message', 'Category deleted successfully!');
        $this->loadStats();
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
        $this->loadStats();
    }

    public function deleteItem($id)
    {
        $this->authorize('delete menu items');
        MenuItem::findOrFail($id)->delete();
        session()->flash('message', 'Menu item deleted successfully!');
        $this->loadStats();
    }

    // User CRUD
    public function createUser()
    {
        $this->authorize('create users');
        $this->resetForms();
        $this->formType = 'user';
        $this->showForm = true;
    }

    public function editUser($id)
    {
        $this->authorize('edit users');
        $user = User::findOrFail($id);
        
        $this->editingId = $id;
        $this->formType = 'user';
        $this->user_name = $user->name;
        $this->user_email = $user->email;
        $this->user_role = $user->roles->first()->name ?? 'customer';
        $this->user_restaurant_id = $user->restaurant_id;
        $this->showForm = true;
    }

    public function saveUser()
    {
        $rules = [
            'user_name' => 'required|string|max:255',
            'user_role' => 'required|string',
            'user_restaurant_id' => 'nullable|exists:restaurants,id',
        ];

        if ($this->editingId) {
            $rules['user_email'] = 'required|email|unique:users,email,' . $this->editingId;
            if ($this->user_password) {
                $rules['user_password'] = 'string|min:8';
            }
        } else {
            $rules['user_email'] = 'required|email|unique:users,email';
            $rules['user_password'] = 'required|string|min:8';
        }

        $this->validate($rules);

        $data = [
            'name' => $this->user_name,
            'email' => $this->user_email,
            'restaurant_id' => $this->user_restaurant_id,
        ];

        if ($this->user_password) {
            $data['password'] = bcrypt($this->user_password);
        }

        if ($this->editingId) {
            $user = User::findOrFail($this->editingId);
            $user->update($data);
            $user->syncRoles([$this->user_role]);
            session()->flash('message', 'User updated successfully!');
        } else {
            $user = User::create($data);
            $user->assignRole($this->user_role);
            session()->flash('message', 'User created successfully!');
        }

        $this->resetForms();
        $this->loadStats();
    }

    public function deleteUser($id)
    {
        $this->authorize('delete users');
        User::findOrFail($id)->delete();
        session()->flash('message', 'User deleted successfully!');
        $this->loadStats();
    }

    // Order management
    public function updateOrderStatus($orderId, $status)
    {
        $this->authorize('edit orders');
        $order = Order::findOrFail($orderId);
        $order->update(['status' => $status]);
        session()->flash('message', 'Order status updated successfully!');
        $this->loadStats();
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
        
        // User form
        $this->user_name = '';
        $this->user_email = '';
        $this->user_password = '';
        $this->user_role = 'customer';
        $this->user_restaurant_id = '';
        
        $this->resetValidation();
    }

    public function cancelForm()
    {
        $this->resetForms();
    }
}