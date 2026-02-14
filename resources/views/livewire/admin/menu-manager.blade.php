<div class="p-6">
    <!-- Header -->
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Menu Management</h1>
        <p class="text-gray-600 mt-2">Manage restaurants, categories, and menu items in one place</p>
    </div>

    <!-- Flash Messages -->
    @if (session()->has('message'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
            {{ session('message') }}
        </div>
    @endif

    @if (session()->has('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
            {{ session('error') }}
        </div>
    @endif

    <!-- Navigation Breadcrumb -->
    <div class="bg-white rounded-lg shadow-sm border p-4 mb-6">
        <nav class="flex items-center space-x-2 text-sm">
            <button wire:click="setActiveTab('restaurants')" 
                class="flex items-center px-3 py-2 rounded-md transition-colors {{ $activeTab === 'restaurants' ? 'bg-blue-100 text-blue-700 font-medium' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-100' }}">
                🏪 Restaurants
            </button>
            
            @if($selectedRestaurant)
                <span class="text-gray-400">→</span>
                <button wire:click="setActiveTab('categories')" 
                    class="flex items-center px-3 py-2 rounded-md transition-colors {{ $activeTab === 'categories' ? 'bg-blue-100 text-blue-700 font-medium' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-100' }}">
                    📋 Categories
                    <span class="ml-1 text-xs bg-gray-200 text-gray-600 px-2 py-1 rounded-full">{{ $categories->count() }}</span>
                </button>
            @endif
            
            @if($selectedCategory)
                <span class="text-gray-400">→</span>
                <button wire:click="setActiveTab('items')" 
                    class="flex items-center px-3 py-2 rounded-md transition-colors {{ $activeTab === 'items' ? 'bg-blue-100 text-blue-700 font-medium' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-100' }}">
                    📄 Menu Items
                    <span class="ml-1 text-xs bg-gray-200 text-gray-600 px-2 py-1 rounded-full">{{ $items->count() }}</span>
                </button>
            @endif
        </nav>
        
        @if($selectedRestaurant || $selectedCategory)
            <div class="mt-3 pt-3 border-t border-gray-200">
                <div class="text-sm text-gray-600">
                    @if($selectedRestaurant && $restaurants->find($selectedRestaurant))
                        <span class="font-medium">{{ $restaurants->find($selectedRestaurant)->name }}</span>
                        @if($selectedCategory && $categories->find($selectedCategory))
                            <span class="mx-2">→</span>
                            <span class="font-medium">{{ $categories->find($selectedCategory)->name }}</span>
                        @endif
                    @endif
                </div>
            </div>
        @endif
    </div>

    <!-- Restaurants Tab -->
    @if($activeTab === 'restaurants')
        <div class="bg-white rounded-lg shadow-sm border">
            <div class="p-6 border-b border-gray-200">
                <div class="flex justify-between items-center">
                    <h2 class="text-xl font-semibold">Restaurants</h2>
                    <button wire:click="createRestaurant" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                        + Add Restaurant
                    </button>
                </div>
            </div>

            <!-- Restaurant Form -->
            @if($showRestaurantForm)
                <div class="p-6 border-b border-gray-200 bg-gray-50">
                    <h3 class="text-lg font-medium mb-4">{{ $editingRestaurantId ? 'Edit Restaurant' : 'Add Restaurant' }}</h3>
                    <form wire:submit.prevent="saveRestaurant" class="space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Restaurant Name *</label>
                                <input type="text" wire:model="restaurant_name" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                @error('restaurant_name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Phone</label>
                                <input type="text" wire:model="restaurant_phone" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                @error('restaurant_phone') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                                <input type="email" wire:model="restaurant_email" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                @error('restaurant_email') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                            <div class="flex items-center mt-6">
                                <label class="flex items-center">
                                    <input type="checkbox" wire:model="restaurant_is_active" class="rounded border-gray-300">
                                    <span class="ml-2 text-sm text-gray-700">Active</span>
                                </label>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                            <textarea wire:model="restaurant_description" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"></textarea>
                            @error('restaurant_description') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Address</label>
                            <textarea wire:model="restaurant_address" rows="2" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"></textarea>
                            @error('restaurant_address') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <div class="flex space-x-3">
                            <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700">
                                {{ $editingRestaurantId ? 'Update' : 'Create' }}
                            </button>
                            <button type="button" wire:click="cancelRestaurant" class="bg-gray-500 text-white px-6 py-2 rounded-lg hover:bg-gray-600">
                                Cancel
                            </button>
                        </div>
                    </form>
                </div>
            @endif

            <!-- Restaurants List -->
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @forelse($restaurants as $restaurant)
                        <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow cursor-pointer" 
                             wire:click="selectRestaurant({{ $restaurant->id }})">
                            <div class="flex justify-between items-start mb-2">
                                <h3 class="font-semibold text-gray-900">{{ $restaurant->name }}</h3>
                                <span class="px-2 py-1 text-xs rounded-full {{ $restaurant->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $restaurant->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </div>
                            <p class="text-sm text-gray-600 mb-3">{{ Str::limit($restaurant->description, 80) }}</p>
                            <div class="flex justify-between items-center">
                                <div class="text-xs text-gray-500">
                                    {{ $restaurant->menuCategories->count() }} categories
                                </div>
                                <div class="flex space-x-2">
                                    <button wire:click.stop="editRestaurant({{ $restaurant->id }})" class="text-blue-600 hover:text-blue-800 text-sm">
                                        Edit
                                    </button>
                                    <button wire:click.stop="deleteRestaurant({{ $restaurant->id }})" 
                                            onclick="return confirm('Are you sure? This will delete all categories and items.')" 
                                            class="text-red-600 hover:text-red-800 text-sm">
                                        Delete
                                    </button>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-full text-center py-8">
                            <div class="text-gray-400 text-4xl mb-2">🏪</div>
                            <p class="text-gray-600">No restaurants found. Create your first restaurant to get started.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    @endif

    <!-- Categories Tab -->
    @if($activeTab === 'categories' && $selectedRestaurant)
        <div class="bg-white rounded-lg shadow-sm border">
            <div class="p-6 border-b border-gray-200">
                <div class="flex justify-between items-center">
                    <div>
                        <h2 class="text-xl font-semibold">Menu Categories</h2>
                        <p class="text-sm text-gray-600">{{ $restaurants->find($selectedRestaurant)->name }}</p>
                    </div>
                    <button wire:click="createCategory" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                        + Add Category
                    </button>
                </div>
            </div>

            <!-- Category Form -->
            @if($showCategoryForm)
                <div class="p-6 border-b border-gray-200 bg-gray-50">
                    <h3 class="text-lg font-medium mb-4">{{ $editingCategoryId ? 'Edit Category' : 'Add Category' }}</h3>
                    <form wire:submit.prevent="saveCategory" class="space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Category Name *</label>
                                <input type="text" wire:model="category_name" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                @error('category_name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Sort Order</label>
                                <input type="number" wire:model="category_sort_order" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                @error('category_sort_order') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                            <textarea wire:model="category_description" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"></textarea>
                            @error('category_description') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <div class="flex items-center">
                            <label class="flex items-center">
                                <input type="checkbox" wire:model="category_is_active" class="rounded border-gray-300">
                                <span class="ml-2 text-sm text-gray-700">Active</span>
                            </label>
                        </div>

                        <div class="flex space-x-3">
                            <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700">
                                {{ $editingCategoryId ? 'Update' : 'Create' }}
                            </button>
                            <button type="button" wire:click="cancelCategory" class="bg-gray-500 text-white px-6 py-2 rounded-lg hover:bg-gray-600">
                                Cancel
                            </button>
                        </div>
                    </form>
                </div>
            @endif

            <!-- Categories List -->
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @forelse($categories as $category)
                        <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow cursor-pointer" 
                             wire:click="selectCategory({{ $category->id }})">
                            <div class="flex justify-between items-start mb-2">
                                <h3 class="font-semibold text-gray-900">{{ $category->name }}</h3>
                                <span class="px-2 py-1 text-xs rounded-full {{ $category->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $category->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </div>
                            <p class="text-sm text-gray-600 mb-3">{{ Str::limit($category->description, 80) }}</p>
                            <div class="flex justify-between items-center">
                                <div class="text-xs text-gray-500">
                                    {{ $category->menuItems->count() }} items • Order: {{ $category->sort_order }}
                                </div>
                                <div class="flex space-x-2">
                                    <button wire:click.stop="editCategory({{ $category->id }})" class="text-blue-600 hover:text-blue-800 text-sm">
                                        Edit
                                    </button>
                                    <button wire:click.stop="deleteCategory({{ $category->id }})" 
                                            onclick="return confirm('Are you sure? This will delete all items in this category.')" 
                                            class="text-red-600 hover:text-red-800 text-sm">
                                        Delete
                                    </button>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-full text-center py-8">
                            <div class="text-gray-400 text-4xl mb-2">📋</div>
                            <p class="text-gray-600">No categories found. Create your first category to organize menu items.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    @endif

    <!-- Items Tab -->
    @if($activeTab === 'items' && $selectedCategory)
        <div class="bg-white rounded-lg shadow-sm border">
            <div class="p-6 border-b border-gray-200">
                <div class="flex justify-between items-center">
                    <div>
                        <h2 class="text-xl font-semibold">Menu Items</h2>
                        <p class="text-sm text-gray-600">
                            {{ $restaurants->find($selectedRestaurant)->name }} → {{ $categories->find($selectedCategory)->name }}
                        </p>
                    </div>
                    <button wire:click="createItem" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                        + Add Menu Item
                    </button>
                </div>
            </div>

            <!-- Item Form -->
            @if($showItemForm)
                <div class="p-6 border-b border-gray-200 bg-gray-50">
                    <h3 class="text-lg font-medium mb-4">{{ $editingItemId ? 'Edit Menu Item' : 'Add Menu Item' }}</h3>
                    <form wire:submit.prevent="saveItem" class="space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Item Name *</label>
                                <input type="text" wire:model="item_name" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                @error('item_name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Price (PKR) *</label>
                                <input type="number" step="0.01" wire:model="item_price" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                @error('item_price') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                            <textarea wire:model="item_description" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"></textarea>
                            @error('item_description') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Prep Time (min)</label>
                                <input type="number" wire:model="item_preparation_time" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                @error('item_preparation_time') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Sort Order</label>
                                <input type="number" wire:model="item_sort_order" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                @error('item_sort_order') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                            <div class="flex flex-col space-y-2 mt-6">
                                <label class="flex items-center">
                                    <input type="checkbox" wire:model="item_is_available" class="rounded border-gray-300">
                                    <span class="ml-2 text-sm text-gray-700">Available</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" wire:model="item_is_featured" class="rounded border-gray-300">
                                    <span class="ml-2 text-sm text-gray-700">Featured</span>
                                </label>
                            </div>
                        </div>

                        <div class="flex space-x-3">
                            <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700">
                                {{ $editingItemId ? 'Update' : 'Create' }}
                            </button>
                            <button type="button" wire:click="cancelItem" class="bg-gray-500 text-white px-6 py-2 rounded-lg hover:bg-gray-600">
                                Cancel
                            </button>
                        </div>
                    </form>
                </div>
            @endif

            <!-- Items List -->
            <div class="p-6">
                <div class="space-y-4">
                    @forelse($items as $item)
                        <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow">
                            <div class="flex justify-between items-start">
                                <div class="flex-1">
                                    <div class="flex items-center space-x-2 mb-2">
                                        <h3 class="font-semibold text-gray-900">{{ $item->name }}</h3>
                                        @if($item->is_featured)
                                            <span class="bg-yellow-100 text-yellow-800 text-xs px-2 py-1 rounded-full">Featured</span>
                                        @endif
                                        <span class="px-2 py-1 text-xs rounded-full {{ $item->is_available ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                            {{ $item->is_available ? 'Available' : 'Unavailable' }}
                                        </span>
                                    </div>
                                    <p class="text-sm text-gray-600 mb-2">{{ $item->description }}</p>
                                    <div class="flex items-center space-x-4 text-sm text-gray-500">
                                        <span class="font-semibold text-green-600">PKR {{ number_format($item->price, 2) }}</span>
                                        <span>🕒 {{ $item->preparation_time }} min</span>
                                        <span>Order: {{ $item->sort_order }}</span>
                                    </div>
                                </div>
                                <div class="flex space-x-2 ml-4">
                                    <button wire:click="editItem({{ $item->id }})" class="text-blue-600 hover:text-blue-800 text-sm">
                                        Edit
                                    </button>
                                    <button wire:click="deleteItem({{ $item->id }})" 
                                            onclick="return confirm('Are you sure?')" 
                                            class="text-red-600 hover:text-red-800 text-sm">
                                        Delete
                                    </button>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-8">
                            <div class="text-gray-400 text-4xl mb-2">📄</div>
                            <p class="text-gray-600">No menu items found. Add your first menu item to this category.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    @endif
</div>