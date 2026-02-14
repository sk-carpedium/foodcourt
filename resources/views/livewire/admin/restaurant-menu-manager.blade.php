<div class="min-h-screen bg-gradient-to-br from-slate-50 to-blue-50 p-4 sm:p-6">
    <!-- Header -->
    <div class="mb-6 sm:mb-8">
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-4 sm:p-6">
            <h1 class="text-2xl sm:text-3xl font-bold bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent">
                Restaurant Menu Management
            </h1>
            <p class="text-slate-600 mt-2 text-sm sm:text-base">Manage Restaurant → Menu → Categories → Items hierarchy</p>
        </div>
    </div>

    <!-- Flash Messages -->
    @if (session()->has('message'))
        <div class="bg-gradient-to-r from-green-50 to-emerald-50 border border-green-200 text-green-800 px-4 sm:px-6 py-4 rounded-xl mb-4 sm:mb-6 shadow-sm">
            <div class="flex items-center">
                <svg class="w-5 h-5 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                </svg>
                <span class="text-sm sm:text-base">{{ session('message') }}</span>
            </div>
        </div>
    @endif

    @if (session()->has('error'))
        <div class="bg-gradient-to-r from-red-50 to-rose-50 border border-red-200 text-red-800 px-4 sm:px-6 py-4 rounded-xl mb-4 sm:mb-6 shadow-sm">
            <div class="flex items-center">
                <svg class="w-5 h-5 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                </svg>
                <span class="text-sm sm:text-base">{{ session('error') }}</span>
            </div>
        </div>
    @endif

    <!-- Mobile-Responsive Breadcrumb Navigation -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-4 sm:p-6 mb-6 sm:mb-8">
        <!-- Mobile: Vertical Stack -->
        <div class="sm:hidden space-y-3">
            <button wire:click="setActiveLevel('restaurants')" 
                class="w-full flex items-center justify-center px-4 py-3 rounded-xl transition-all duration-200 {{ $activeLevel === 'restaurants' ? 'bg-gradient-to-r from-blue-500 to-blue-600 text-white shadow-lg shadow-blue-500/25' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100 border border-slate-200' }}">
                <span class="text-lg mr-2">🏪</span>
                <span class="font-medium">Restaurants</span>
            </button>
            
            @if($selectedRestaurant && $restaurants->find($selectedRestaurant))
                <button wire:click="setActiveLevel('menus')" 
                    class="w-full flex items-center justify-center px-4 py-3 rounded-xl transition-all duration-200 {{ $activeLevel === 'menus' ? 'bg-gradient-to-r from-emerald-500 to-green-600 text-white shadow-lg shadow-emerald-500/25' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100 border border-slate-200' }}">
                    <span class="text-lg mr-2">📋</span>
                    <span class="font-medium">{{ $restaurants->find($selectedRestaurant)->name }} - Menus</span>
                </button>
            @endif

            @if($selectedMenu && $menus->find($selectedMenu))
                <button wire:click="setActiveLevel('categories')" 
                    class="w-full flex items-center justify-center px-4 py-3 rounded-xl transition-all duration-200 {{ $activeLevel === 'categories' ? 'bg-gradient-to-r from-purple-500 to-pink-600 text-white shadow-lg shadow-purple-500/25' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100 border border-slate-200' }}">
                    <span class="text-lg mr-2">📂</span>
                    <span class="font-medium">{{ $menus->find($selectedMenu)->name }} - Categories</span>
                </button>
            @endif

            @if($selectedCategory && $categories->find($selectedCategory))
                <button wire:click="setActiveLevel('items')" 
                    class="w-full flex items-center justify-center px-4 py-3 rounded-xl transition-all duration-200 {{ $activeLevel === 'items' ? 'bg-gradient-to-r from-orange-500 to-red-600 text-white shadow-lg shadow-orange-500/25' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100 border border-slate-200' }}">
                    <span class="text-lg mr-2">🍽️</span>
                    <span class="font-medium">{{ $categories->find($selectedCategory)->name }} - Items</span>
                </button>
            @endif
        </div>

        <!-- Desktop: Horizontal Navigation -->
        <nav class="hidden sm:flex items-center space-x-3 text-sm">
            <button wire:click="setActiveLevel('restaurants')" 
                class="flex items-center px-4 py-3 rounded-xl transition-all duration-200 {{ $activeLevel === 'restaurants' ? 'bg-gradient-to-r from-blue-500 to-blue-600 text-white shadow-lg shadow-blue-500/25' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }}">
                <span class="text-lg mr-2">🏪</span>
                <span class="font-medium">Restaurants</span>
            </button>
            
            @if($selectedRestaurant)
                <svg class="w-5 h-5 text-slate-400" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                </svg>
                <button wire:click="setActiveLevel('menus')" 
                    class="flex items-center px-4 py-3 rounded-xl transition-all duration-200 {{ $activeLevel === 'menus' ? 'bg-gradient-to-r from-emerald-500 to-green-600 text-white shadow-lg shadow-emerald-500/25' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }}">
                    <span class="text-lg mr-2">📋</span>
                    <span class="font-medium">Menus</span>
                    <span class="ml-2 text-xs bg-white/20 text-white px-2 py-1 rounded-full">{{ $menus->count() }}</span>
                </button>
            @endif
            
            @if($selectedMenu)
                <svg class="w-5 h-5 text-slate-400" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                </svg>
                <button wire:click="setActiveLevel('categories')" 
                    class="flex items-center px-4 py-3 rounded-xl transition-all duration-200 {{ $activeLevel === 'categories' ? 'bg-gradient-to-r from-purple-500 to-violet-600 text-white shadow-lg shadow-purple-500/25' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }}">
                    <span class="text-lg mr-2">📂</span>
                    <span class="font-medium">Categories</span>
                    <span class="ml-2 text-xs bg-white/20 text-white px-2 py-1 rounded-full">{{ $categories->count() }}</span>
                </button>
            @endif
            
            @if($selectedCategory)
                <svg class="w-5 h-5 text-slate-400" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                </svg>
                <button wire:click="setActiveLevel('items')" 
                    class="flex items-center px-4 py-3 rounded-xl transition-all duration-200 {{ $activeLevel === 'items' ? 'bg-gradient-to-r from-orange-500 to-amber-600 text-white shadow-lg shadow-orange-500/25' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }}">
                    <span class="text-lg mr-2">📄</span>
                    <span class="font-medium">Menu Items</span>
                    <span class="ml-2 text-xs bg-white/20 text-white px-2 py-1 rounded-full">{{ $items->count() }}</span>
                </button>
            @endif
        </nav>
        
        @if($selectedRestaurant || $selectedMenu || $selectedCategory)
            <div class="mt-4 pt-4 border-t border-slate-200">
                <div class="text-sm text-slate-600 bg-slate-50 rounded-lg p-3">
                    @if($selectedRestaurant && $restaurants->find($selectedRestaurant))
                        <span class="font-semibold text-slate-800">{{ $restaurants->find($selectedRestaurant)->name }}</span>
                        @if($selectedMenu && $menus->find($selectedMenu))
                            <span class="mx-2 text-slate-400">→</span>
                            <span class="font-semibold text-slate-800">{{ $menus->find($selectedMenu)->name }}</span>
                            @if($selectedCategory && $categories->find($selectedCategory))
                                <span class="mx-2 text-slate-400">→</span>
                                <span class="font-semibold text-slate-800">{{ $categories->find($selectedCategory)->name }}</span>
                            @endif
                        @endif
                    @endif
                </div>
            </div>
        @endif
    </div>

    <!-- Restaurants Level -->
    @if($activeLevel === 'restaurants')
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="bg-gradient-to-r from-blue-500 to-blue-600 p-6 text-white">
                <div class="flex justify-between items-center">
                    <div>
                        <h2 class="text-2xl font-bold">Restaurants</h2>
                        <p class="text-blue-100 mt-1">Manage your restaurant locations</p>
                    </div>
                    <button wire:click="createRestaurant" class="bg-white text-blue-600 px-6 py-3 rounded-xl hover:bg-blue-50 transition-colors font-semibold shadow-lg">
                        <span class="mr-2">+</span>Add Restaurant
                    </button>
                </div>
            </div>

            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @forelse($restaurants as $restaurant)
                        <div class="group bg-gradient-to-br from-white to-slate-50 border border-slate-200 rounded-xl p-6 hover:shadow-xl hover:shadow-blue-500/10 transition-all duration-300 cursor-pointer transform hover:-translate-y-1" 
                             wire:click="selectRestaurant({{ $restaurant->id }})">
                            <div class="flex justify-between items-start mb-4">
                                <div class="flex items-center">
                                    <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center text-white text-xl mr-3">
                                        🏪
                                    </div>
                                    <div>
                                        <h3 class="font-bold text-slate-900 group-hover:text-blue-600 transition-colors">{{ $restaurant->name }}</h3>
                                        <span class="inline-flex items-center px-3 py-1 text-xs font-medium rounded-full {{ $restaurant->is_active ? 'bg-emerald-100 text-emerald-800' : 'bg-red-100 text-red-800' }}">
                                            {{ $restaurant->is_active ? 'Active' : 'Inactive' }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <p class="text-sm text-slate-600 mb-4 line-clamp-2">{{ Str::limit($restaurant->description, 80) }}</p>
                            <div class="flex justify-between items-center">
                                <div class="text-xs text-slate-500 bg-slate-100 rounded-lg px-3 py-2">
                                    <span class="font-semibold">{{ $restaurant->menus_count }}</span> menus • 
                                    <span class="font-semibold">{{ $restaurant->menu_categories_count }}</span> categories • 
                                    <span class="font-semibold">{{ $restaurant->menu_items_count }}</span> items
                                </div>
                                <div class="flex space-x-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                    <button wire:click.stop="editRestaurant({{ $restaurant->id }})" class="text-blue-600 hover:text-blue-800 text-sm font-medium bg-blue-50 px-3 py-1 rounded-lg">
                                        Edit
                                    </button>
                                    <button wire:click.stop="deleteRestaurant({{ $restaurant->id }})" 
                                            onclick="return confirm('Are you sure? This will delete all menus, categories and items.')" 
                                            class="text-red-600 hover:text-red-800 text-sm font-medium bg-red-50 px-3 py-1 rounded-lg">
                                        Delete
                                    </button>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-full text-center py-16">
                            <div class="text-slate-400 text-6xl mb-4">🏪</div>
                            <h3 class="text-xl font-semibold text-slate-600 mb-2">No restaurants found</h3>
                            <p class="text-slate-500">Create your first restaurant to get started with menu management.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    @endif

    <!-- Menus Level -->
    @if($activeLevel === 'menus' && $selectedRestaurant)
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="bg-gradient-to-r from-emerald-500 to-green-600 p-6 text-white">
                <div class="flex justify-between items-center">
                    <div>
                        <h2 class="text-2xl font-bold">Menus</h2>
                        <p class="text-emerald-100 mt-1">{{ $restaurants->find($selectedRestaurant)->name }}</p>
                    </div>
                    <button wire:click="createMenu" class="bg-white text-emerald-600 px-6 py-3 rounded-xl hover:bg-emerald-50 transition-colors font-semibold shadow-lg">
                        <span class="mr-2">+</span>Add Menu
                    </button>
                </div>
            </div>

            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @forelse($menus as $menu)
                        <div class="group bg-gradient-to-br from-white to-emerald-50 border border-slate-200 rounded-xl p-6 hover:shadow-xl hover:shadow-emerald-500/10 transition-all duration-300 cursor-pointer transform hover:-translate-y-1" 
                             wire:click="selectMenu({{ $menu->id }})">
                            <div class="flex justify-between items-start mb-4">
                                <div class="flex items-center">
                                    <div class="w-12 h-12 bg-gradient-to-br from-emerald-500 to-green-600 rounded-xl flex items-center justify-center text-white text-xl mr-3">
                                        📋
                                    </div>
                                    <div>
                                        <h3 class="font-bold text-slate-900 group-hover:text-emerald-600 transition-colors">{{ $menu->name }}</h3>
                                        <span class="inline-flex items-center px-3 py-1 text-xs font-medium rounded-full {{ $menu->is_active ? 'bg-emerald-100 text-emerald-800' : 'bg-red-100 text-red-800' }}">
                                            {{ $menu->is_active ? 'Active' : 'Inactive' }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <p class="text-sm text-slate-600 mb-4 line-clamp-2">{{ Str::limit($menu->description, 80) }}</p>
                            <div class="flex justify-between items-center">
                                <div class="text-xs text-slate-500 bg-slate-100 rounded-lg px-3 py-2">
                                    <span class="font-semibold">{{ $menu->menu_categories_count }}</span> categories • 
                                    <span class="font-semibold">{{ $menu->menu_items_count }}</span> items
                                </div>
                                <div class="flex space-x-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                    <button wire:click.stop="editMenu({{ $menu->id }})" class="text-emerald-600 hover:text-emerald-800 text-sm font-medium bg-emerald-50 px-3 py-1 rounded-lg">
                                        Edit
                                    </button>
                                    <button wire:click.stop="deleteMenu({{ $menu->id }})" 
                                            onclick="return confirm('Are you sure? This will delete all categories and items in this menu.')" 
                                            class="text-red-600 hover:text-red-800 text-sm font-medium bg-red-50 px-3 py-1 rounded-lg">
                                        Delete
                                    </button>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-full text-center py-16">
                            <div class="text-slate-400 text-6xl mb-4">📋</div>
                            <h3 class="text-xl font-semibold text-slate-600 mb-2">No menus found</h3>
                            <p class="text-slate-500">Create your first menu for this restaurant.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    @endif

    <!-- Categories Level -->
    @if($activeLevel === 'categories' && $selectedMenu)
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="bg-gradient-to-r from-purple-500 to-violet-600 p-6 text-white">
                <div class="flex justify-between items-center">
                    <div>
                        <h2 class="text-2xl font-bold">Menu Categories</h2>
                        <p class="text-purple-100 mt-1">
                            {{ $restaurants->find($selectedRestaurant)->name }} → {{ $menus->find($selectedMenu)->name }}
                        </p>
                    </div>
                    <button wire:click="createCategory" class="bg-white text-purple-600 px-6 py-3 rounded-xl hover:bg-purple-50 transition-colors font-semibold shadow-lg">
                        <span class="mr-2">+</span>Add Category
                    </button>
                </div>
            </div>

            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @forelse($categories as $category)
                        <div class="group bg-gradient-to-br from-white to-purple-50 border border-slate-200 rounded-xl p-6 hover:shadow-xl hover:shadow-purple-500/10 transition-all duration-300 cursor-pointer transform hover:-translate-y-1" 
                             wire:click="selectCategory({{ $category->id }})">
                            <div class="flex justify-between items-start mb-4">
                                <div class="flex items-center">
                                    <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-violet-600 rounded-xl flex items-center justify-center text-white text-xl mr-3">
                                        📂
                                    </div>
                                    <div>
                                        <h3 class="font-bold text-slate-900 group-hover:text-purple-600 transition-colors">{{ $category->name }}</h3>
                                        <span class="inline-flex items-center px-3 py-1 text-xs font-medium rounded-full {{ $category->is_active ? 'bg-emerald-100 text-emerald-800' : 'bg-red-100 text-red-800' }}">
                                            {{ $category->is_active ? 'Active' : 'Inactive' }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <p class="text-sm text-slate-600 mb-4 line-clamp-2">{{ Str::limit($category->description, 80) }}</p>
                            <div class="flex justify-between items-center">
                                <div class="text-xs text-slate-500 bg-slate-100 rounded-lg px-3 py-2">
                                    <span class="font-semibold">{{ $category->menu_items_count }}</span> items • 
                                    Order: <span class="font-semibold">{{ $category->sort_order }}</span>
                                </div>
                                <div class="flex space-x-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                    <button wire:click.stop="editCategory({{ $category->id }})" class="text-purple-600 hover:text-purple-800 text-sm font-medium bg-purple-50 px-3 py-1 rounded-lg">
                                        Edit
                                    </button>
                                    <button wire:click.stop="deleteCategory({{ $category->id }})" 
                                            onclick="return confirm('Are you sure? This will delete all items in this category.')" 
                                            class="text-red-600 hover:text-red-800 text-sm font-medium bg-red-50 px-3 py-1 rounded-lg">
                                        Delete
                                    </button>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-full text-center py-16">
                            <div class="text-slate-400 text-6xl mb-4">📂</div>
                            <h3 class="text-xl font-semibold text-slate-600 mb-2">No categories found</h3>
                            <p class="text-slate-500">Create your first category for this menu.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    @endif

    <!-- Items Level -->
    @if($activeLevel === 'items' && $selectedCategory)
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="bg-gradient-to-r from-orange-500 to-amber-600 p-6 text-white">
                <div class="flex justify-between items-center">
                    <div>
                        <h2 class="text-2xl font-bold">Menu Items</h2>
                        <p class="text-orange-100 mt-1">
                            {{ $restaurants->find($selectedRestaurant)->name }} → {{ $menus->find($selectedMenu)->name }} → {{ $categories->find($selectedCategory)->name }}
                        </p>
                    </div>
                    <button wire:click="createItem" class="bg-white text-orange-600 px-6 py-3 rounded-xl hover:bg-orange-50 transition-colors font-semibold shadow-lg">
                        <span class="mr-2">+</span>Add Menu Item
                    </button>
                </div>
            </div>

            <div class="p-6">
                <div class="space-y-4">
                    @forelse($items as $item)
                        <div class="group bg-gradient-to-br from-white to-orange-50 border border-slate-200 rounded-xl p-6 hover:shadow-xl hover:shadow-orange-500/10 transition-all duration-300">
                            <div class="flex justify-between items-start">
                                <div class="flex-1">
                                    <div class="flex items-center space-x-3 mb-3">
                                        <div class="w-10 h-10 bg-gradient-to-br from-orange-500 to-amber-600 rounded-lg flex items-center justify-center text-white text-lg">
                                            📄
                                        </div>
                                        <div>
                                            <h3 class="font-bold text-slate-900 group-hover:text-orange-600 transition-colors">{{ $item->name }}</h3>
                                            <div class="flex items-center space-x-2 mt-1">
                                                @if($item->is_featured)
                                                    <span class="bg-gradient-to-r from-yellow-400 to-amber-500 text-white text-xs px-3 py-1 rounded-full font-medium">⭐ Featured</span>
                                                @endif
                                                <span class="inline-flex items-center px-3 py-1 text-xs font-medium rounded-full {{ $item->is_available ? 'bg-emerald-100 text-emerald-800' : 'bg-red-100 text-red-800' }}">
                                                    {{ $item->is_available ? '✅ Available' : '❌ Unavailable' }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <p class="text-sm text-slate-600 mb-4 leading-relaxed">{{ $item->description }}</p>
                                    <div class="flex items-center space-x-6 text-sm">
                                        <div class="flex items-center bg-emerald-100 text-emerald-800 px-3 py-2 rounded-lg">
                                            <span class="font-bold text-lg">PKR {{ number_format($item->price, 2) }}</span>
                                        </div>
                                        <div class="flex items-center text-slate-600 bg-slate-100 px-3 py-2 rounded-lg">
                                            <span class="mr-1">🕒</span>
                                            <span class="font-medium">{{ $item->preparation_time }} min</span>
                                        </div>
                                        <div class="flex items-center text-slate-600 bg-slate-100 px-3 py-2 rounded-lg">
                                            <span class="mr-1">📋</span>
                                            <span class="font-medium">Order: {{ $item->sort_order }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex space-x-2 ml-6 opacity-0 group-hover:opacity-100 transition-opacity">
                                    <button wire:click="editItem({{ $item->id }})" class="text-orange-600 hover:text-orange-800 text-sm font-medium bg-orange-50 px-4 py-2 rounded-lg">
                                        Edit
                                    </button>
                                    <button wire:click="deleteItem({{ $item->id }})" 
                                            onclick="return confirm('Are you sure?')" 
                                            class="text-red-600 hover:text-red-800 text-sm font-medium bg-red-50 px-4 py-2 rounded-lg">
                                        Delete
                                    </button>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-16">
                            <div class="text-slate-400 text-6xl mb-4">📄</div>
                            <h3 class="text-xl font-semibold text-slate-600 mb-2">No menu items found</h3>
                            <p class="text-slate-500">Add your first menu item to this category.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    @endif

    <!-- Universal Form Modal -->
    @if($showForm)
        <div class="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center z-50 p-4">
            <div class="bg-white rounded-2xl shadow-2xl max-w-lg w-full max-h-[90vh] overflow-y-auto">
                <div class="p-8">
                    <div class="flex justify-between items-center mb-6">
                        <div>
                            <h3 class="text-2xl font-bold text-slate-900">
                                {{ $editingId ? 'Edit' : 'Add' }} {{ ucfirst($formType) }}
                            </h3>
                            <p class="text-slate-600 mt-1">
                                @if($formType === 'restaurant') Manage restaurant information @endif
                                @if($formType === 'menu') Create a new menu for your restaurant @endif
                                @if($formType === 'category') Organize your menu with categories @endif
                                @if($formType === 'item') Add delicious items to your menu @endif
                            </p>
                        </div>
                        <button wire:click="cancelForm" class="text-slate-400 hover:text-slate-600 p-2 hover:bg-slate-100 rounded-xl transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>

                    <!-- Restaurant Form -->
                    @if($formType === 'restaurant')
                        <form wire:submit.prevent="saveRestaurant" class="space-y-6">
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-2">Restaurant Name *</label>
                                <input type="text" wire:model="restaurant_name" class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors bg-slate-50 focus:bg-white text-slate-900">
                                @error('restaurant_name') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-2">Description</label>
                                <textarea wire:model="restaurant_description" rows="3" class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors bg-slate-50 focus:bg-white text-slate-900"></textarea>
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-semibold text-slate-700 mb-2">Phone</label>
                                    <input type="text" wire:model="restaurant_phone" class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors bg-slate-50 focus:bg-white text-slate-900">
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-slate-700 mb-2">Email</label>
                                    <input type="email" wire:model="restaurant_email" class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors bg-slate-50 focus:bg-white text-slate-900">
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-2">Address</label>
                                <textarea wire:model="restaurant_address" rows="2" class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors bg-slate-50 focus:bg-white text-slate-900"></textarea>
                            </div>
                            <div class="flex items-center bg-slate-50 p-4 rounded-xl">
                                <input type="checkbox" wire:model="restaurant_is_active" class="rounded border-slate-300 text-blue-600 focus:ring-blue-500">
                                <label class="ml-3 text-sm font-medium text-slate-700">Restaurant is active and accepting orders</label>
                            </div>
                            <div class="flex space-x-4 pt-6">
                                <button type="submit" class="flex-1 bg-gradient-to-r from-blue-500 to-blue-600 text-white py-3 rounded-xl hover:from-blue-600 hover:to-blue-700 transition-all font-semibold shadow-lg shadow-blue-500/25">
                                    {{ $editingId ? 'Update Restaurant' : 'Create Restaurant' }}
                                </button>
                                <button type="button" wire:click="cancelForm" class="flex-1 bg-slate-500 text-white py-3 rounded-xl hover:bg-slate-600 transition-colors font-semibold">
                                    Cancel
                                </button>
                            </div>
                        </form>
                    @endif

                    <!-- Menu Form -->
                    @if($formType === 'menu')
                        <form wire:submit.prevent="saveMenu" class="space-y-6">
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-2">Menu Name *</label>
                                <input type="text" wire:model="menu_name" class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-colors bg-slate-50 focus:bg-white text-slate-900">
                                @error('menu_name') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-2">Description</label>
                                <textarea wire:model="menu_description" rows="3" class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-colors bg-slate-50 focus:bg-white text-slate-900"></textarea>
                            </div>
                            <div class="flex items-center bg-slate-50 p-4 rounded-xl">
                                <input type="checkbox" wire:model="menu_is_active" class="rounded border-slate-300 text-emerald-600 focus:ring-emerald-500">
                                <label class="ml-3 text-sm font-medium text-slate-700">Menu is active and visible to customers</label>
                            </div>
                            <div class="flex space-x-4 pt-6">
                                <button type="submit" class="flex-1 bg-gradient-to-r from-emerald-500 to-green-600 text-white py-3 rounded-xl hover:from-emerald-600 hover:to-green-700 transition-all font-semibold shadow-lg shadow-emerald-500/25">
                                    {{ $editingId ? 'Update Menu' : 'Create Menu' }}
                                </button>
                                <button type="button" wire:click="cancelForm" class="flex-1 bg-slate-500 text-white py-3 rounded-xl hover:bg-slate-600 transition-colors font-semibold">
                                    Cancel
                                </button>
                            </div>
                        </form>
                    @endif

                    <!-- Category Form -->
                    @if($formType === 'category')
                        <form wire:submit.prevent="saveCategory" class="space-y-6">
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-2">Category Name *</label>
                                <input type="text" wire:model="category_name" class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-colors bg-slate-50 focus:bg-white text-slate-900">
                                @error('category_name') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-2">Description</label>
                                <textarea wire:model="category_description" rows="3" class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-colors bg-slate-50 focus:bg-white text-slate-900"></textarea>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-2">Sort Order</label>
                                <input type="number" wire:model="category_sort_order" class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-colors bg-slate-50 focus:bg-white text-slate-900">
                                <p class="text-xs text-slate-500 mt-1">Lower numbers appear first in the menu</p>
                            </div>
                            <div class="flex items-center bg-slate-50 p-4 rounded-xl">
                                <input type="checkbox" wire:model="category_is_active" class="rounded border-slate-300 text-purple-600 focus:ring-purple-500">
                                <label class="ml-3 text-sm font-medium text-slate-700">Category is active and visible to customers</label>
                            </div>
                            <div class="flex space-x-4 pt-6">
                                <button type="submit" class="flex-1 bg-gradient-to-r from-purple-500 to-violet-600 text-white py-3 rounded-xl hover:from-purple-600 hover:to-violet-700 transition-all font-semibold shadow-lg shadow-purple-500/25">
                                    {{ $editingId ? 'Update Category' : 'Create Category' }}
                                </button>
                                <button type="button" wire:click="cancelForm" class="flex-1 bg-slate-500 text-white py-3 rounded-xl hover:bg-slate-600 transition-colors font-semibold">
                                    Cancel
                                </button>
                            </div>
                        </form>
                    @endif

                    <!-- Item Form -->
                    @if($formType === 'item')
                        <form wire:submit.prevent="saveItem" class="space-y-6">
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-2">Item Name *</label>
                                <input type="text" wire:model="item_name" class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-colors bg-slate-50 focus:bg-white text-slate-900">
                                @error('item_name') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-2">Description</label>
                                <textarea wire:model="item_description" rows="3" class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-colors bg-slate-50 focus:bg-white text-slate-900"></textarea></textarea>
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-semibold text-slate-700 mb-2">Price (PKR) *</label>
                                    <input type="number" step="0.01" wire:model="item_price" class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-colors bg-slate-50 focus:bg-white text-slate-900">
                                    @error('item_price') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-slate-700 mb-2">Prep Time (min)</label>
                                    <input type="number" wire:model="item_preparation_time" class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-colors bg-slate-50 focus:bg-white text-slate-900">
                                </div>
                            </div>
                            <div class="space-y-3">
                                <div class="flex items-center bg-slate-50 p-4 rounded-xl">
                                    <input type="checkbox" wire:model="item_is_available" class="rounded border-slate-300 text-orange-600 focus:ring-orange-500">
                                    <label class="ml-3 text-sm font-medium text-slate-700">Item is available for ordering</label>
                                </div>
                                <div class="flex items-center bg-slate-50 p-4 rounded-xl">
                                    <input type="checkbox" wire:model="item_is_featured" class="rounded border-slate-300 text-orange-600 focus:ring-orange-500">
                                    <label class="ml-3 text-sm font-medium text-slate-700">Feature this item (highlighted to customers)</label>
                                </div>
                            </div>
                            <div class="flex space-x-4 pt-6">
                                <button type="submit" class="flex-1 bg-gradient-to-r from-orange-500 to-amber-600 text-white py-3 rounded-xl hover:from-orange-600 hover:to-amber-700 transition-all font-semibold shadow-lg shadow-orange-500/25">
                                    {{ $editingId ? 'Update Item' : 'Create Item' }}
                                </button>
                                <button type="button" wire:click="cancelForm" class="flex-1 bg-slate-500 text-white py-3 rounded-xl hover:bg-slate-600 transition-colors font-semibold">
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