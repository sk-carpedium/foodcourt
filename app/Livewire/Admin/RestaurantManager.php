<?php

namespace App\Livewire\Admin;

use App\Models\Restaurant;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class RestaurantManager extends Component
{
    use WithPagination;

    public $showForm = false;
    public $editingId = null;
    public $name = '';
    public $description = '';
    public $phone = '';
    public $email = '';
    public $address = '';
    public $is_active = true;
    public $createdKitchenUser = null;

    protected $rules = [
        'name' => 'required|string|max:255',
        'description' => 'nullable|string',
        'phone' => 'nullable|string|max:20',
        'email' => 'nullable|email|max:255',
        'address' => 'nullable|string',
        'is_active' => 'boolean',
    ];

    public function mount()
    {
        $this->authorize('view restaurants');
    }

    public function render()
    {
        $restaurants = Restaurant::with(['users' => function($query) {
            $query->whereHas('roles', function($roleQuery) {
                $roleQuery->where('name', 'kitchen');
            });
        }])->paginate(10);

        return view('livewire.admin.restaurant-manager', [
            'restaurants' => $restaurants
        ]);
    }

    public function create()
    {
        $this->authorize('create restaurants');
        $this->resetForm();
        $this->showForm = true;
    }

    public function edit($id)
    {
        $this->authorize('edit restaurants');
        $restaurant = Restaurant::findOrFail($id);
        
        $this->editingId = $id;
        $this->name = $restaurant->name;
        $this->description = $restaurant->description;
        $this->phone = $restaurant->phone;
        $this->email = $restaurant->email;
        $this->address = $restaurant->address;
        $this->is_active = $restaurant->is_active;
        $this->showForm = true;
    }

    public function save()
    {
        $this->validate();

        if ($this->editingId) {
            $this->authorize('edit restaurants');
            $restaurant = Restaurant::findOrFail($this->editingId);
            $restaurant->update($this->getFormData());
            session()->flash('message', 'Restaurant updated successfully!');
        } else {
            $this->authorize('create restaurants');
            $restaurant = Restaurant::create($this->getFormData());
            
            // Automatically create a kitchen user for the new restaurant
            $kitchenUser = $this->createKitchenUser($restaurant);
            $this->createdKitchenUser = $kitchenUser;
            
            session()->flash('message', 'Restaurant created successfully! Kitchen user has been automatically created.');
        }

        $this->resetForm();
    }

    private function createKitchenUser(Restaurant $restaurant)
    {
        // Generate kitchen user credentials
        $restaurantSlug = Str::slug($restaurant->name);
        $kitchenEmail = "kitchen@{$restaurantSlug}.com";
        $kitchenName = "Kitchen - {$restaurant->name}";
        $defaultPassword = 'password';
        
        // Check if email already exists, if so, add a number
        $counter = 1;
        $originalEmail = $kitchenEmail;
        while (User::where('email', $kitchenEmail)->exists()) {
            $kitchenEmail = str_replace('.com', "-{$counter}.com", $originalEmail);
            $counter++;
        }
        
        // Create the kitchen user
        $kitchenUser = User::create([
            'name' => $kitchenName,
            'email' => $kitchenEmail,
            'password' => Hash::make($defaultPassword),
            'restaurant_id' => $restaurant->id,
            'email_verified_at' => now(),
        ]);
        
        // Assign kitchen role
        $kitchenUser->assignRole('kitchen');
        
        // Log the credentials for admin reference
        \Log::info("Kitchen user created for restaurant '{$restaurant->name}'", [
            'restaurant_id' => $restaurant->id,
            'kitchen_email' => $kitchenEmail,
            'kitchen_password' => $defaultPassword
        ]);
        
        // Return user data for display
        return [
            'name' => $kitchenName,
            'email' => $kitchenEmail,
            'password' => $defaultPassword,
            'restaurant_name' => $restaurant->name
        ];
    }

    public function delete($id)
    {
        $this->authorize('delete restaurants');
        Restaurant::findOrFail($id)->delete();
        session()->flash('message', 'Restaurant deleted successfully!');
    }

    public function createKitchenUserForRestaurant($restaurantId)
    {
        $this->authorize('create users');
        $restaurant = Restaurant::findOrFail($restaurantId);
        
        // Check if kitchen user already exists
        $existingKitchenUser = User::where('restaurant_id', $restaurantId)
            ->whereHas('roles', function($query) {
                $query->where('name', 'kitchen');
            })->first();
            
        if ($existingKitchenUser) {
            session()->flash('message', 'Kitchen user already exists for this restaurant!');
            return;
        }
        
        $kitchenUser = $this->createKitchenUser($restaurant);
        $this->createdKitchenUser = $kitchenUser;
        
        session()->flash('message', 'Kitchen user created successfully for ' . $restaurant->name . '!');
    }

    public function cancel()
    {
        $this->resetForm();
    }

    private function resetForm()
    {
        $this->showForm = false;
        $this->editingId = null;
        $this->name = '';
        $this->description = '';
        $this->phone = '';
        $this->email = '';
        $this->address = '';
        $this->is_active = true;
        $this->createdKitchenUser = null;
        $this->resetValidation();
    }

    private function getFormData()
    {
        return [
            'name' => $this->name,
            'description' => $this->description,
            'phone' => $this->phone,
            'email' => $this->email,
            'address' => $this->address,
            'is_active' => $this->is_active,
        ];
    }
}