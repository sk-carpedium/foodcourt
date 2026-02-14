<x-layouts.app :title="__('Dashboard')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <div class="bg-white rounded-lg shadow p-4 sm:p-6">
            <h1 class="text-xl sm:text-2xl font-bold mb-4 sm:mb-6">Restaurant Management System</h1>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
                @can('view restaurants')
                    <div class="bg-blue-50 rounded-lg p-4">
                        <h3 class="text-lg font-semibold text-blue-900 mb-2">Admin Portal</h3>
                        <p class="text-blue-700 text-sm mb-4">Manage restaurants, menus, and categories</p>
                        <div class="space-y-2">
                            <a href="{{ route('admin.restaurants') }}" class="block bg-blue-500 text-white text-center py-2 px-4 rounded hover:bg-blue-600 text-sm">
                                Restaurants
                            </a>
                            <a href="{{ route('admin.menu-categories') }}" class="block bg-blue-500 text-white text-center py-2 px-4 rounded hover:bg-blue-600 text-sm">
                                Menu Categories
                            </a>
                            <a href="{{ route('admin.menu-items') }}" class="block bg-blue-500 text-white text-center py-2 px-4 rounded hover:bg-blue-600 text-sm">
                                Menu Items
                            </a>
                        </div>
                    </div>
                @endcan

                @can('take orders')
                    <div class="bg-green-50 rounded-lg p-4">
                        <h3 class="text-lg font-semibold text-green-900 mb-2">Waiter Portal</h3>
                        <p class="text-green-700 text-sm mb-4">Manage orders and serve customers</p>
                        <div class="space-y-2">
                            <a href="{{ route('waiter.orders') }}" class="block bg-green-500 text-white text-center py-2 px-4 rounded hover:bg-green-600 text-sm">
                                Order Management
                            </a>
                        </div>
                    </div>
                @endcan

                @can('view kitchen orders')
                    <div class="bg-orange-50 rounded-lg p-4">
                        <h3 class="text-lg font-semibold text-orange-900 mb-2">Kitchen Portal</h3>
                        <p class="text-orange-700 text-sm mb-4">View and prepare orders</p>
                        <div class="space-y-2">
                            <a href="{{ route('kitchen.orders') }}" class="block bg-orange-500 text-white text-center py-2 px-4 rounded hover:bg-orange-600">
                                Order Queue
                            </a>
                        </div>
                    </div>
                @endcan

                <div class="bg-purple-50 rounded-lg p-4">
                    <h3 class="text-lg font-semibold text-purple-900 mb-2">Customer Portal</h3>
                    <p class="text-purple-700 text-sm mb-4">Browse menu and place orders</p>
                    <div class="space-y-2">
                        <a href="{{ route('menu', 'the-gourmet-kitchen') }}" class="block bg-purple-500 text-white text-center py-2 px-4 rounded hover:bg-purple-600">
                            View Menu
                        </a>
                    </div>
                </div>
            </div>

            @if(auth()->user()->hasRole('super-admin'))
                <div class="mt-6 p-4 bg-gray-50 rounded-lg">
                    <h3 class="text-lg font-semibold mb-2">Super Admin Tools</h3>
                    <div class="flex space-x-4">
                        <a href="{{ route('users.index') }}" class="bg-gray-600 text-white px-4 py-2 rounded hover:bg-gray-700">
                            User Management
                        </a>
                    </div>
                </div>
            @endif
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-semibold mb-4">Quick Stats</h2>
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div class="bg-blue-100 p-4 rounded-lg">
                    <h3 class="text-lg font-semibold text-blue-900">Total Restaurants</h3>
                    <p class="text-2xl font-bold text-blue-600">{{ \App\Models\Restaurant::count() }}</p>
                </div>
                <div class="bg-green-100 p-4 rounded-lg">
                    <h3 class="text-lg font-semibold text-green-900">Menu Items</h3>
                    <p class="text-2xl font-bold text-green-600">{{ \App\Models\MenuItem::count() }}</p>
                </div>
                <div class="bg-orange-100 p-4 rounded-lg">
                    <h3 class="text-lg font-semibold text-orange-900">Today's Orders</h3>
                    <p class="text-2xl font-bold text-orange-600">{{ \App\Models\Order::whereDate('created_at', today())->count() }}</p>
                </div>
                <div class="bg-purple-100 p-4 rounded-lg">
                    <h3 class="text-lg font-semibold text-purple-900">Active Users</h3>
                    <p class="text-2xl font-bold text-purple-600">{{ \App\Models\User::count() }}</p>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
