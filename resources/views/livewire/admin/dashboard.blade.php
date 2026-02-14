<div class="min-h-screen bg-gradient-to-br from-gray-50 via-blue-50 to-indigo-50 p-4 sm:p-6 space-y-4 sm:space-y-6">
    <!-- Header -->
    <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-6 mb-6 relative overflow-hidden">
        <div class="absolute inset-0 opacity-5">
            <div class="absolute top-4 right-4 w-32 h-32 bg-gradient-to-br from-blue-400 to-indigo-400 rounded-full"></div>
            <div class="absolute bottom-4 left-4 w-24 h-24 bg-gradient-to-br from-purple-400 to-pink-400 rounded-full"></div>
        </div>
        <div class="relative flex flex-col sm:flex-row sm:justify-between sm:items-center space-y-4 sm:space-y-0">
            <div>
                <h1 class="text-3xl sm:text-4xl font-bold bg-gradient-to-r from-blue-600 via-indigo-600 to-purple-600 bg-clip-text text-transparent">Restaurant Management</h1>
                <p class="text-gray-600 mt-2 text-sm sm:text-base font-medium">Complete backend management system</p>
            </div>
            <div class="text-xs sm:text-sm text-gray-500 font-medium">
                Last updated: {{ now()->format('M d, Y H:i') }}
            </div>
        </div>
    </div>

    <!-- Flash Messages -->
    @if (session()->has('message'))
        <div class="bg-gradient-to-r from-green-50 to-emerald-50 border-l-4 border-green-500 text-green-800 px-4 py-3 rounded-lg mb-4 shadow-md flex items-center">
            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
            </svg>
            <span class="font-semibold">{{ session('message') }}</span>
        </div>
    @endif

    @if (session()->has('error'))
        <div class="bg-gradient-to-r from-red-50 to-rose-50 border-l-4 border-red-500 text-red-800 px-4 py-3 rounded-lg mb-4 shadow-md flex items-center">
            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
            </svg>
            <span class="font-semibold">{{ session('error') }}</span>
        </div>
    @endif

    <!-- Mobile-Responsive Navigation Tabs -->
    <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
        <div class="border-b border-gray-200">
            <!-- Mobile: Dropdown Navigation -->
            <div class="sm:hidden px-4 py-3">
                <select wire:model.live="activeSection" class="w-full rounded-lg border-2 border-gray-300 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 px-4 py-2.5 text-gray-900 bg-white font-semibold">
                    <option value="overview">📊 Overview</option>
                    <option value="restaurants">🏪 Restaurants & Menu</option>
                    <option value="orders">📋 Orders</option>
                    <option value="users">👥 Users</option>
                </select>
            </div>
            
            <!-- Desktop: Tab Navigation -->
            <nav class="hidden sm:flex space-x-8 px-6" aria-label="Tabs">
                <button wire:click="setActiveSection('overview')" 
                    class="py-4 px-1 border-b-2 font-semibold text-sm transition-colors {{ $activeSection === 'overview' ? 'border-blue-600 text-blue-600' : 'border-transparent text-gray-600 hover:text-gray-900 hover:border-gray-300' }}">
                    📊 Overview
                </button>
                <button wire:click="setActiveSection('restaurants')" 
                    class="py-4 px-1 border-b-2 font-semibold text-sm transition-colors {{ $activeSection === 'restaurants' ? 'border-blue-600 text-blue-600' : 'border-transparent text-gray-600 hover:text-gray-900 hover:border-gray-300' }}">
                    🏪 Restaurants & Menu
                </button>
                <button wire:click="setActiveSection('orders')" 
                    class="py-4 px-1 border-b-2 font-semibold text-sm transition-colors {{ $activeSection === 'orders' ? 'border-blue-600 text-blue-600' : 'border-transparent text-gray-600 hover:text-gray-900 hover:border-gray-300' }}">
                    📋 Orders
                </button>
                <button wire:click="setActiveSection('users')" 
                    class="py-4 px-1 border-b-2 font-semibold text-sm transition-colors {{ $activeSection === 'users' ? 'border-blue-600 text-blue-600' : 'border-transparent text-gray-600 hover:text-gray-900 hover:border-gray-300' }}">
                    👥 Users
                </button>
            </nav>
        </div>
    </div>

    <!-- Overview Section -->
    @if($activeSection === 'overview')
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Stats Cards -->
            <div class="bg-gradient-to-br from-white to-blue-50 rounded-xl shadow-lg border-2 border-blue-100 p-6 hover:shadow-xl transition-shadow">
                <div class="flex items-center">
                    <div class="p-3 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl shadow-md">
                        <span class="text-2xl">🏪</span>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-semibold text-gray-600 uppercase tracking-wide">Restaurants</p>
                        <p class="text-3xl font-bold text-gray-900 mt-1">{{ $stats['restaurants'] }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-br from-white to-green-50 rounded-xl shadow-lg border-2 border-green-100 p-6 hover:shadow-xl transition-shadow">
                <div class="flex items-center">
                    <div class="p-3 bg-gradient-to-br from-green-500 to-green-600 rounded-xl shadow-md">
                        <span class="text-2xl">📄</span>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-semibold text-gray-600 uppercase tracking-wide">Menu Items</p>
                        <p class="text-3xl font-bold text-gray-900 mt-1">{{ $stats['items'] }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-br from-white to-yellow-50 rounded-xl shadow-lg border-2 border-yellow-100 p-6 hover:shadow-xl transition-shadow">
                <div class="flex items-center">
                    <div class="p-3 bg-gradient-to-br from-yellow-500 to-yellow-600 rounded-xl shadow-md">
                        <span class="text-2xl">📋</span>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-semibold text-gray-600 uppercase tracking-wide">Orders Today</p>
                        <p class="text-3xl font-bold text-gray-900 mt-1">{{ $stats['orders_today'] }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-br from-white to-purple-50 rounded-xl shadow-lg border-2 border-purple-100 p-6 hover:shadow-xl transition-shadow">
                <div class="flex items-center">
                    <div class="p-3 bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl shadow-md">
                        <span class="text-2xl">💰</span>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-semibold text-gray-600 uppercase tracking-wide">Revenue Today</p>
                        <p class="text-3xl font-bold text-green-700 mt-1">PKR {{ number_format($stats['revenue_today'], 2) }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-6">
            <h2 class="text-xl font-bold text-gray-900 mb-6 pb-3 border-b border-gray-200">Quick Actions</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <button wire:click="createRestaurant" class="group p-6 border-2 border-dashed border-gray-300 rounded-xl hover:border-blue-500 hover:bg-gradient-to-br hover:from-blue-50 hover:to-indigo-50 transition-all duration-200 hover:shadow-md">
                    <div class="text-center">
                        <span class="text-4xl mb-3 block group-hover:scale-110 transition-transform">🏪</span>
                        <span class="text-sm font-semibold text-gray-700 group-hover:text-blue-700">Add Restaurant</span>
                    </div>
                </button>
                <button wire:click="createUser" class="group p-6 border-2 border-dashed border-gray-300 rounded-xl hover:border-green-500 hover:bg-gradient-to-br hover:from-green-50 hover:to-emerald-50 transition-all duration-200 hover:shadow-md">
                    <div class="text-center">
                        <span class="text-4xl mb-3 block group-hover:scale-110 transition-transform">👥</span>
                        <span class="text-sm font-semibold text-gray-700 group-hover:text-green-700">Add User</span>
                    </div>
                </button>
                <button wire:click="setActiveSection('orders')" class="group p-6 border-2 border-dashed border-gray-300 rounded-xl hover:border-yellow-500 hover:bg-gradient-to-br hover:from-yellow-50 hover:to-amber-50 transition-all duration-200 hover:shadow-md">
                    <div class="text-center">
                        <span class="text-4xl mb-3 block group-hover:scale-110 transition-transform">📋</span>
                        <span class="text-sm font-semibold text-gray-700 group-hover:text-yellow-700">View Orders</span>
                    </div>
                </button>
                <button wire:click="setActiveSection('restaurants')" class="group p-6 border-2 border-dashed border-gray-300 rounded-xl hover:border-purple-500 hover:bg-gradient-to-br hover:from-purple-50 hover:to-pink-50 transition-all duration-200 hover:shadow-md">
                    <div class="text-center">
                        <span class="text-4xl mb-3 block group-hover:scale-110 transition-transform">📄</span>
                        <span class="text-sm font-semibold text-gray-700 group-hover:text-purple-700">Manage Menu</span>
                    </div>
                </button>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-6">
                <h2 class="text-xl font-bold text-gray-900 mb-6 pb-3 border-b border-gray-200">Recent Orders</h2>
                <div class="space-y-3">
                    @forelse($recentOrders->take(5) as $order)
                        <div class="flex justify-between items-center p-4 bg-gradient-to-r from-gray-50 to-gray-100 rounded-xl border border-gray-200 hover:shadow-md transition-shadow">
                            <div>
                                <p class="font-bold text-gray-900">{{ $order->order_number }}</p>
                                <p class="text-sm text-gray-700 font-medium mt-1">{{ $order->restaurant->name }}</p>
                            </div>
                            <div class="text-right">
                                <p class="font-bold text-green-700">PKR {{ number_format($order->total_amount, 2) }}</p>
                                <span class="text-xs px-3 py-1.5 rounded-full font-bold {{ $order->status === 'completed' ? 'bg-green-100 text-green-800 border border-green-200' : 'bg-yellow-100 text-yellow-800 border border-yellow-200' }}">
                                    {{ ucfirst($order->status) }}
                                </span>
                            </div>
                        </div>
                    @empty
                        <p class="text-gray-500 text-center py-8 font-medium">No recent orders</p>
                    @endforelse
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-6">
                <h2 class="text-xl font-bold text-gray-900 mb-6 pb-3 border-b border-gray-200">System Status</h2>
                <div class="space-y-4">
                    <div class="flex justify-between items-center p-3 bg-gradient-to-r from-gray-50 to-gray-100 rounded-lg border border-gray-200">
                        <span class="text-gray-700 font-semibold">Pending Orders</span>
                        <span class="font-bold text-lg {{ $stats['orders_pending'] > 0 ? 'text-red-600' : 'text-green-600' }}">
                            {{ $stats['orders_pending'] }}
                        </span>
                    </div>
                    <div class="flex justify-between items-center p-3 bg-gradient-to-r from-gray-50 to-gray-100 rounded-lg border border-gray-200">
                        <span class="text-gray-700 font-semibold">Active Restaurants</span>
                        <span class="font-bold text-lg text-green-600">{{ $restaurants->where('is_active', true)->count() }}</span>
                    </div>
                    <div class="flex justify-between items-center p-3 bg-gradient-to-r from-gray-50 to-gray-100 rounded-lg border border-gray-200">
                        <span class="text-gray-700 font-semibold">Total Users</span>
                        <span class="font-bold text-lg text-blue-600">{{ $stats['users'] }}</span>
                    </div>
                    <div class="flex justify-between items-center p-3 bg-gradient-to-r from-gray-50 to-gray-100 rounded-lg border border-gray-200">
                        <span class="text-gray-700 font-semibold">Menu Categories</span>
                        <span class="font-bold text-lg text-purple-600">{{ $stats['categories'] }}</span>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Restaurants & Menu Section -->
    @if($activeSection === 'restaurants')
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Restaurants -->
            <div class="bg-white rounded-xl shadow-lg border border-gray-200">
                <div class="p-4 border-b border-gray-200 bg-gradient-to-r from-gray-50 to-gray-100">
                    <div class="flex justify-between items-center">
                        <h2 class="text-lg font-bold text-gray-900">Restaurants</h2>
                        <button wire:click="createRestaurant" class="bg-gradient-to-r from-blue-600 to-indigo-600 text-white px-4 py-2 rounded-lg text-sm font-semibold hover:from-blue-700 hover:to-indigo-700 transition-all shadow-md hover:shadow-lg">
                            + Add
                        </button>
                    </div>
                </div>
                <div class="p-4 max-h-96 overflow-y-auto">
                    @forelse($restaurants as $restaurant)
                        <div class="p-4 border-2 rounded-xl mb-3 cursor-pointer hover:bg-gradient-to-r hover:from-blue-50 hover:to-indigo-50 transition-all {{ $selectedRestaurant == $restaurant->id ? 'bg-gradient-to-r from-blue-50 to-indigo-50 border-blue-300 shadow-md' : 'border-gray-200' }}" 
                             wire:click="selectRestaurant({{ $restaurant->id }})">
                            <div class="flex justify-between items-start">
                                <div class="flex-1">
                                    <h3 class="font-bold text-gray-900">{{ $restaurant->name }}</h3>
                                    <p class="text-sm text-gray-700 font-medium mt-1">{{ $restaurant->menu_categories_count }} categories</p>
                                </div>
                                <div class="flex space-x-2">
                                    <button wire:click.stop="editRestaurant({{ $restaurant->id }})" class="text-blue-700 hover:text-blue-900 font-semibold text-xs hover:underline">
                                        Edit
                                    </button>
                                    <button wire:click.stop="deleteRestaurant({{ $restaurant->id }})" 
                                            onclick="return confirm('Delete restaurant and all its data?')" 
                                            class="text-red-700 hover:text-red-900 font-semibold text-xs hover:underline">
                                        Del
                                    </button>
                                </div>
                            </div>
                        </div>
                    @empty
                        <p class="text-gray-500 text-center py-4">No restaurants</p>
                    @endforelse
                </div>
            </div>

            <!-- Categories -->
            <div class="bg-white rounded-xl shadow-lg border border-gray-200">
                <div class="p-4 border-b border-gray-200 bg-gradient-to-r from-gray-50 to-gray-100">
                    <div class="flex justify-between items-center">
                        <h2 class="text-lg font-bold text-gray-900">Categories</h2>
                        @if($selectedRestaurant)
                            <button wire:click="createCategory" class="bg-gradient-to-r from-green-600 to-emerald-600 text-white px-4 py-2 rounded-lg text-sm font-semibold hover:from-green-700 hover:to-emerald-700 transition-all shadow-md hover:shadow-lg">
                                + Add
                            </button>
                        @endif
                    </div>
                </div>
                <div class="p-4 max-h-96 overflow-y-auto">
                    @if($selectedRestaurant)
                        @forelse($categories as $category)
                            <div class="p-4 border-2 rounded-xl mb-3 cursor-pointer hover:bg-gradient-to-r hover:from-green-50 hover:to-emerald-50 transition-all {{ $selectedCategory == $category->id ? 'bg-gradient-to-r from-green-50 to-emerald-50 border-green-300 shadow-md' : 'border-gray-200' }}" 
                                 wire:click="selectCategory({{ $category->id }})">
                                <div class="flex justify-between items-start">
                                    <div class="flex-1">
                                        <h3 class="font-bold text-gray-900">{{ $category->name }}</h3>
                                        <p class="text-sm text-gray-700 font-medium mt-1">{{ $category->menu_items_count }} items</p>
                                    </div>
                                    <div class="flex space-x-2">
                                        <button wire:click.stop="editCategory({{ $category->id }})" class="text-blue-700 hover:text-blue-900 font-semibold text-xs hover:underline">
                                            Edit
                                        </button>
                                        <button wire:click.stop="deleteCategory({{ $category->id }})" 
                                                onclick="return confirm('Delete category and all items?')" 
                                                class="text-red-700 hover:text-red-900 font-semibold text-xs hover:underline">
                                            Del
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <p class="text-gray-500 text-center py-4">No categories</p>
                        @endforelse
                    @else
                        <p class="text-gray-500 text-center py-4">Select a restaurant first</p>
                    @endif
                </div>
            </div>

            <!-- Menu Items -->
            <div class="bg-white rounded-xl shadow-lg border border-gray-200">
                <div class="p-4 border-b border-gray-200 bg-gradient-to-r from-gray-50 to-gray-100">
                    <div class="flex justify-between items-center">
                        <h2 class="text-lg font-bold text-gray-900">Menu Items</h2>
                        @if($selectedCategory)
                            <button wire:click="createItem" class="bg-gradient-to-r from-purple-600 to-pink-600 text-white px-4 py-2 rounded-lg text-sm font-semibold hover:from-purple-700 hover:to-pink-700 transition-all shadow-md hover:shadow-lg">
                                + Add
                            </button>
                        @endif
                    </div>
                </div>
                <div class="p-4 max-h-96 overflow-y-auto">
                    @if($selectedCategory)
                        @forelse($items as $item)
                            <div class="p-4 border-2 border-gray-200 rounded-xl mb-3 hover:bg-gradient-to-r hover:from-purple-50 hover:to-pink-50 transition-all">
                                <div class="flex justify-between items-start">
                                    <div class="flex-1">
                                        <h3 class="font-bold text-gray-900">{{ $item->name }}</h3>
                                        <p class="text-sm text-green-700 font-bold mt-1">PKR {{ number_format($item->price, 2) }}</p>
                                    </div>
                                    <div class="flex space-x-2">
                                        <button wire:click="editItem({{ $item->id }})" class="text-blue-700 hover:text-blue-900 font-semibold text-xs hover:underline">
                                            Edit
                                        </button>
                                        <button wire:click="deleteItem({{ $item->id }})" 
                                                onclick="return confirm('Delete this item?')" 
                                                class="text-red-700 hover:text-red-900 font-semibold text-xs hover:underline">
                                            Del
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <p class="text-gray-500 text-center py-4">No menu items</p>
                        @endforelse
                    @else
                        <p class="text-gray-500 text-center py-4">Select a category first</p>
                    @endif
                </div>
            </div>
        </div>
    @endif

    <!-- Orders Section -->
    @if($activeSection === 'orders')
        <div class="bg-white rounded-xl shadow-lg border border-gray-200">
            <div class="p-6 border-b border-gray-200 bg-gradient-to-r from-gray-50 to-gray-100">
                <h2 class="text-xl font-bold text-gray-900">Recent Orders</h2>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    @forelse($recentOrders as $order)
                        <div class="border-2 border-gray-200 rounded-xl p-5 bg-gradient-to-r from-white to-gray-50 hover:shadow-md transition-shadow">
                            <div class="flex justify-between items-start">
                                <div>
                                    <h3 class="font-bold text-gray-900">{{ $order->order_number }}</h3>
                                    <p class="text-sm text-gray-700 font-medium mt-1">{{ $order->restaurant->name }}</p>
                                    <p class="text-sm text-gray-600 font-medium">{{ $order->created_at->format('M d, Y H:i') }}</p>
                                </div>
                                <div class="text-right">
                                    <p class="font-bold text-green-700 text-lg">PKR {{ number_format($order->total_amount, 2) }}</p>
                                    <select wire:change="updateOrderStatus({{ $order->id }}, $event.target.value)" 
                                            class="text-sm border-2 border-gray-300 rounded-lg px-3 py-1.5 mt-2 bg-white text-gray-900 font-semibold focus:border-blue-500 focus:ring-2 focus:ring-blue-200">
                                        <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="confirmed" {{ $order->status === 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                                        <option value="preparing" {{ $order->status === 'preparing' ? 'selected' : '' }}>Preparing</option>
                                        <option value="ready" {{ $order->status === 'ready' ? 'selected' : '' }}>Ready</option>
                                        <option value="completed" {{ $order->status === 'completed' ? 'selected' : '' }}>Completed</option>
                                    </select>
                                </div>
                            </div>
                            <div class="mt-4 pt-4 border-t border-gray-200">
                                <div class="text-sm text-gray-700 font-medium">
                                    @foreach($order->orderItems as $item)
                                        <span>{{ $item->quantity }}x {{ $item->item_name }}</span>
                                        @if(!$loop->last), @endif
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @empty
                        <p class="text-gray-500 text-center py-8 font-medium">No orders found</p>
                    @endforelse
                </div>
            </div>
        </div>
    @endif

    <!-- Users Section -->
    @if($activeSection === 'users')
        <div class="bg-white rounded-xl shadow-lg border border-gray-200">
            <div class="p-6 border-b border-gray-200 bg-gradient-to-r from-gray-50 to-gray-100">
                <div class="flex justify-between items-center">
                    <h2 class="text-xl font-bold text-gray-900">Users</h2>
                    <button wire:click="createUser" class="bg-gradient-to-r from-blue-600 to-indigo-600 text-white px-6 py-2.5 rounded-lg font-semibold hover:from-blue-700 hover:to-indigo-700 transition-all shadow-md hover:shadow-lg">
                        + Add User
                    </button>
                </div>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    @forelse($users as $user)
                        <div class="border-2 border-gray-200 rounded-xl p-5 bg-gradient-to-r from-white to-gray-50 hover:shadow-md transition-shadow">
                            <div class="flex justify-between items-start">
                                <div>
                                    <h3 class="font-bold text-gray-900">{{ $user->name }}</h3>
                                    <p class="text-sm text-gray-700 font-medium mt-1">{{ $user->email }}</p>
                                    <div class="flex items-center space-x-2 mt-2">
                                        <span class="text-xs px-3 py-1.5 bg-blue-100 text-blue-800 rounded-full font-bold border border-blue-200">
                                            {{ $user->roles->first()->name ?? 'No role' }}
                                        </span>
                                        @if($user->restaurant)
                                            <span class="text-xs px-3 py-1.5 bg-green-100 text-green-800 rounded-full font-bold border border-green-200">
                                                {{ $user->restaurant->name }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="flex space-x-3">
                                    <button wire:click="editUser({{ $user->id }})" class="text-blue-700 hover:text-blue-900 font-semibold text-sm hover:underline">
                                        Edit
                                    </button>
                                    <button wire:click="deleteUser({{ $user->id }})" 
                                            onclick="return confirm('Delete this user?')" 
                                            class="text-red-700 hover:text-red-900 font-semibold text-sm hover:underline">
                                        Delete
                                    </button>
                                </div>
                            </div>
                        </div>
                    @empty
                        <p class="text-gray-500 text-center py-8 font-medium">No users found</p>
                    @endforelse
                </div>
            </div>
        </div>
    @endif

    <!-- Universal Form Modal -->
    @if($showForm)
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white rounded-xl shadow-2xl max-w-md w-full mx-4 max-h-screen overflow-y-auto border-2 border-gray-200">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-6 pb-4 border-b border-gray-200">
                        <h3 class="text-xl font-bold text-gray-900">
                            {{ $editingId ? 'Edit' : 'Add' }} 
                            {{ ucfirst($formType) }}
                        </h3>
                        <button wire:click="cancelForm" class="text-gray-400 hover:text-gray-600 transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>

                    <!-- Restaurant Form -->
                    @if($formType === 'restaurant')
                        <form wire:submit.prevent="saveRestaurant" class="space-y-4">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Name *</label>
                                <input type="text" wire:model="restaurant_name" class="w-full px-4 py-2.5 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-200 focus:border-blue-500 text-gray-900 bg-white">
                                @error('restaurant_name') <span class="text-red-600 text-sm font-medium mt-1 block">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Description</label>
                                <textarea wire:model="restaurant_description" rows="3" class="w-full px-4 py-2.5 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-200 focus:border-blue-500 text-gray-900 bg-white"></textarea>
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Phone</label>
                                    <input type="text" wire:model="restaurant_phone" class="w-full px-4 py-2.5 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-200 focus:border-blue-500 text-gray-900 bg-white">
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Email</label>
                                    <input type="email" wire:model="restaurant_email" class="w-full px-4 py-2.5 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-200 focus:border-blue-500 text-gray-900 bg-white">
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Address</label>
                                <textarea wire:model="restaurant_address" rows="2" class="w-full px-4 py-2.5 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-200 focus:border-blue-500 text-gray-900 bg-white"></textarea>
                            </div>
                            <div class="flex items-center">
                                <input type="checkbox" wire:model="restaurant_is_active" class="rounded border-gray-300">
                                <label class="ml-2 text-sm text-gray-700">Active</label>
                            </div>
                            <div class="flex space-x-3 pt-4">
                                <button type="submit" class="flex-1 bg-gradient-to-r from-blue-600 to-indigo-600 text-white py-2.5 rounded-lg hover:from-blue-700 hover:to-indigo-700 transition-all font-semibold shadow-md hover:shadow-lg">
                                    {{ $editingId ? 'Update' : 'Create' }}
                                </button>
                                <button type="button" wire:click="cancelForm" class="flex-1 bg-gray-500 text-white py-2.5 rounded-lg hover:bg-gray-600 transition-all font-semibold shadow-md hover:shadow-lg">
                                    Cancel
                                </button>
                            </div>
                        </form>
                    @endif

                    <!-- Category Form -->
                    @if($formType === 'category')
                        <form wire:submit.prevent="saveCategory" class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Name *</label>
                                <input type="text" wire:model="category_name" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                @error('category_name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                                <textarea wire:model="category_description" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"></textarea>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Sort Order</label>
                                <input type="number" wire:model="category_sort_order" class="w-full px-4 py-2.5 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-200 focus:border-blue-500 text-gray-900 bg-white">
                            </div>
                            <div class="flex items-center">
                                <input type="checkbox" wire:model="category_is_active" class="rounded border-gray-300">
                                <label class="ml-2 text-sm text-gray-700">Active</label>
                            </div>
                            <div class="flex space-x-3 pt-4">
                                <button type="submit" class="flex-1 bg-gradient-to-r from-green-600 to-emerald-600 text-white py-2.5 rounded-lg hover:from-green-700 hover:to-emerald-700 transition-all font-semibold shadow-md hover:shadow-lg">
                                    {{ $editingId ? 'Update' : 'Create' }}
                                </button>
                                <button type="button" wire:click="cancelForm" class="flex-1 bg-gray-500 text-white py-2 rounded-lg hover:bg-gray-600">
                                    Cancel
                                </button>
                            </div>
                        </form>
                    @endif

                    <!-- Item Form -->
                    @if($formType === 'item')
                        <form wire:submit.prevent="saveItem" class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Name *</label>
                                <input type="text" wire:model="item_name" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                @error('item_name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                                <textarea wire:model="item_description" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"></textarea>
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Price (PKR) *</label>
                                    <input type="number" step="0.01" wire:model="item_price" class="w-full px-4 py-2.5 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-200 focus:border-blue-500 text-gray-900 bg-white">
                                    @error('item_price') <span class="text-red-600 text-sm font-medium mt-1 block">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Prep Time (min)</label>
                                <input type="number" wire:model="item_preparation_time" class="w-full px-4 py-2.5 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-200 focus:border-blue-500 text-gray-900 bg-white">
                                </div>
                            </div>
                            <div class="flex space-x-4">
                                <label class="flex items-center">
                                    <input type="checkbox" wire:model="item_is_available" class="rounded border-gray-300">
                                    <span class="ml-2 text-sm text-gray-700">Available</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" wire:model="item_is_featured" class="rounded border-gray-300">
                                    <span class="ml-2 text-sm text-gray-700">Featured</span>
                                </label>
                            </div>
                            <div class="flex space-x-3 pt-4">
                                <button type="submit" class="flex-1 bg-gradient-to-r from-purple-600 to-pink-600 text-white py-2.5 rounded-lg hover:from-purple-700 hover:to-pink-700 transition-all font-semibold shadow-md hover:shadow-lg">
                                    {{ $editingId ? 'Update' : 'Create' }}
                                </button>
                                <button type="button" wire:click="cancelForm" class="flex-1 bg-gray-500 text-white py-2 rounded-lg hover:bg-gray-600">
                                    Cancel
                                </button>
                            </div>
                        </form>
                    @endif

                    <!-- User Form -->
                    @if($formType === 'user')
                        <form wire:submit.prevent="saveUser" class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Name *</label>
                                <input type="text" wire:model="user_name" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                @error('user_name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Email *</label>
                                <input type="email" wire:model="user_email" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                @error('user_email') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Password {{ $editingId ? '(leave blank to keep current)' : '*' }}</label>
                                <input type="password" wire:model="user_password" class="w-full px-4 py-2.5 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-200 focus:border-blue-500 text-gray-900 bg-white">
                                @error('user_password') <span class="text-red-600 text-sm font-medium mt-1 block">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Role *</label>
                                <select wire:model="user_role" class="w-full px-4 py-2.5 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-200 focus:border-blue-500 text-gray-900 bg-white font-semibold">
                                    <option value="customer">Customer</option>
                                    <option value="waiter">Waiter</option>
                                    <option value="kitchen">Kitchen</option>
                                    <option value="admin">Admin</option>
                                    <option value="super-admin">Super Admin</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Restaurant</label>
                                <select wire:model="user_restaurant_id" class="w-full px-4 py-2.5 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-200 focus:border-blue-500 text-gray-900 bg-white font-semibold">
                                    <option value="">No restaurant (Access to all restaurants)</option>
                                    @foreach($restaurants as $restaurant)
                                        <option value="{{ $restaurant->id }}">{{ $restaurant->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="flex space-x-3 pt-4">
                                <button type="submit" class="flex-1 bg-gradient-to-r from-blue-600 to-indigo-600 text-white py-2.5 rounded-lg hover:from-blue-700 hover:to-indigo-700 transition-all font-semibold shadow-md hover:shadow-lg">
                                    {{ $editingId ? 'Update' : 'Create' }}
                                </button>
                                <button type="button" wire:click="cancelForm" class="flex-1 bg-gray-500 text-white py-2.5 rounded-lg hover:bg-gray-600 transition-all font-semibold shadow-md hover:shadow-lg">
                                    Cancel
                                </button>
                            </div>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    @endif
</div>