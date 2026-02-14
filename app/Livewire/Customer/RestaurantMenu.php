<?php

namespace App\Livewire\Customer;

use App\Models\Restaurant;
use App\Models\MenuCategory;
use App\Models\MenuItem;
use Livewire\Component;

class RestaurantMenu extends Component
{
    public $restaurant;
    public $selectedCategory = null;
    public $cart = [];
    public $showCart = false;

    public function mount($restaurantSlug)
    {
        $this->restaurant = Restaurant::where('slug', $restaurantSlug)
            ->where('is_active', true)
            ->firstOrFail();
        
        $this->cart = session()->get('cart', []);
        
        // Dispatch initial cart count
        $this->dispatch('cart-updated');
    }

    public function render()
    {
        // Get active menus for this restaurant
        $menus = $this->restaurant->menus()->where('is_active', true)->get();
        
        // Get categories from all active menus
        $categories = MenuCategory::whereIn('menu_id', $menus->pluck('id'))
            ->where('is_active', true)
            ->with(['activeMenuItems' => function($query) {
                $query->orderBy('sort_order');
            }])
            ->orderBy('sort_order')
            ->get();

        $menuItems = collect();
        if ($this->selectedCategory) {
            $menuItems = MenuItem::where('menu_category_id', $this->selectedCategory)
                ->where('is_available', true)
                ->orderBy('sort_order')
                ->get();
        } else {
            // Get all items from active menus
            $menuItems = MenuItem::whereIn('menu_id', $menus->pluck('id'))
                ->where('is_available', true)
                ->orderBy('sort_order')
                ->get();
        }

        return view('livewire.customer.restaurant-menu', [
            'categories' => $categories,
            'menuItems' => $menuItems,
            'cartTotal' => $this->getCartTotal(),
            'menus' => $menus
        ]);
    }

    public function selectCategory($categoryId = null)
    {
        $this->selectedCategory = $categoryId;
    }

    public function addToCart($itemId)
    {
        $item = MenuItem::findOrFail($itemId);
        
        $cartKey = $itemId;
        if (isset($this->cart[$cartKey])) {
            $this->cart[$cartKey]['quantity']++;
        } else {
            $this->cart[$cartKey] = [
                'id' => $item->id,
                'name' => $item->name,
                'price' => $item->price,
                'quantity' => 1,
            ];
        }

        session()->put('cart', $this->cart);
        $this->dispatch('cart-updated');
    }

    public function removeFromCart($cartKey)
    {
        unset($this->cart[$cartKey]);
        session()->put('cart', $this->cart);
        $this->dispatch('cart-updated');
    }

    public function updateQuantity($cartKey, $quantity)
    {
        if ($quantity <= 0) {
            $this->removeFromCart($cartKey);
        } else {
            $this->cart[$cartKey]['quantity'] = $quantity;
            session()->put('cart', $this->cart);
        }
        $this->dispatch('cart-updated');
    }

    public function toggleCart()
    {
        $this->showCart = !$this->showCart;
    }

    public function getCartTotal()
    {
        return collect($this->cart)->sum(function ($item) {
            return $item['price'] * $item['quantity'];
        });
    }

    public function getCartCount()
    {
        return collect($this->cart)->sum('quantity');
    }

    public function proceedToCheckout()
    {
        if (empty($this->cart)) {
            session()->flash('error', 'Your cart is empty!');
            return;
        }

        return redirect()->route('checkout', $this->restaurant->slug);
    }
}