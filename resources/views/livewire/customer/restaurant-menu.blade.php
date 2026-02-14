<div>
    <!-- Restaurant Header -->
    <section class="bg-white shadow-sm">
        <div class="max-w-7xl mx-auto px-3 sm:px-6 lg:px-8 py-4 sm:py-8">
            <!-- Back Button -->
            <div class="mb-4 sm:mb-6">
                <a href="{{ route('home') }}" class="inline-flex items-center px-3 py-2 sm:px-4 sm:py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 hover:text-slate-900 rounded-lg transition-colors font-medium text-sm sm:text-base">
                    <svg class="w-4 h-4 sm:w-5 sm:h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    <span class="hidden sm:inline">Back to Restaurants</span>
                    <span class="sm:hidden">Back</span>
                </a>
            </div>
            
            <div class="flex flex-col space-y-4 sm:space-y-0 sm:flex-row sm:items-start md:items-center justify-between">
                <div class="flex-1">
                    <!-- Mobile: Hide breadcrumb, Desktop: Show breadcrumb -->
                    <nav class="hidden sm:flex mb-4" aria-label="Breadcrumb">
                        <ol class="inline-flex items-center space-x-1 md:space-x-3">
                            <li class="inline-flex items-center">
                                <a href="{{ route('home') }}" class="text-gray-700 hover:text-blue-600">Home</a>
                            </li>
                            <li>
                                <div class="flex items-center">
                                    <svg class="w-3 h-3 text-gray-400 mx-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m9 5 7 7-7 7"></path>
                                    </svg>
                                    <span class="text-gray-500">{{ $restaurant->name }}</span>
                                </div>
                            </li>
                        </ol>
                    </nav>
                    
                    <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 mb-2">{{ $restaurant->name }}</h1>
                    <p class="text-gray-600 mb-3 sm:mb-4 text-sm sm:text-base line-clamp-2 sm:line-clamp-none">{{ $restaurant->description }}</p>
                    
                    <!-- Mobile: Stack vertically, Desktop: Horizontal -->
                    <div class="flex flex-col sm:flex-row sm:flex-wrap sm:items-center gap-2 sm:gap-4 text-xs sm:text-sm text-gray-500">
                        <div class="flex items-center">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5 text-yellow-400 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                            </svg>
                            <span class="font-medium text-gray-700">4.8</span>
                            <span class="ml-1 hidden sm:inline">(120+ reviews)</span>
                            <span class="ml-1 sm:hidden">(120+)</span>
                        </div>
                        <span class="hidden sm:inline">•</span>
                        <span>25-35 min delivery</span>
                        <span class="hidden sm:inline">•</span>
                        <span class="text-green-600 font-medium">Open now</span>
                    </div>
                </div>
                
                <div class="w-full sm:w-auto">
                    <button wire:click="toggleCart" class="relative w-full sm:w-auto bg-blue-600 text-white px-4 sm:px-6 py-3 rounded-lg hover:bg-blue-700 transition-colors">
                        <div class="flex items-center justify-center space-x-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-1.5 6M7 13l-1.5 6m0 0h9M17 13v6a2 2 0 01-2 2H9a2 2 0 01-2-2v-6"></path>
                            </svg>
                            <span>Cart ({{ $this->getCartCount() }})</span>
                        </div>
                        @if($this->getCartCount() > 0)
                            <span class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full w-6 h-6 text-xs flex items-center justify-center">
                                {{ $this->getCartCount() }}
                            </span>
                        @endif
                    </button>
                </div>
            </div>
        </div>
    </section>

    <div class="max-w-7xl mx-auto px-3 sm:px-6 lg:px-8 py-4 sm:py-8">
        <div class="flex flex-col lg:flex-row gap-4 sm:gap-8">
            <!-- Mobile: Categories as horizontal scroll, Desktop: Sidebar -->
            <div class="lg:w-1/4">
                <!-- Mobile Categories (Horizontal Scroll) -->
                <div class="lg:hidden mb-6">
                    <h2 class="text-lg font-semibold mb-3 text-gray-900 px-1">Menu Categories</h2>
                    <div class="flex space-x-2 overflow-x-auto pb-2 px-1">
                        <button wire:click="selectCategory(null)" 
                            class="flex-shrink-0 px-4 py-2 rounded-full text-sm font-medium transition-colors {{ $selectedCategory === null ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                            All Items ({{ $menuItems->count() }})
                        </button>
                        @foreach ($categories as $category)
                            <button wire:click="selectCategory({{ $category->id }})" 
                                class="flex-shrink-0 px-4 py-2 rounded-full text-sm font-medium transition-colors {{ $selectedCategory == $category->id ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                                {{ $category->name }} ({{ $category->activeMenuItems->count() }})
                            </button>
                        @endforeach
                    </div>
                </div>

                <!-- Desktop Categories (Sidebar) -->
                <div class="hidden lg:block bg-white rounded-lg shadow-sm border p-6 sticky top-24">
                    <h2 class="text-lg font-semibold mb-4 text-gray-900">Menu Categories</h2>
                    <ul class="space-y-2">
                        <li>
                            <button wire:click="selectCategory(null)" 
                                class="w-full text-left px-3 py-2 rounded-lg transition-colors {{ $selectedCategory === null ? 'bg-blue-100 text-blue-700 font-medium' : 'hover:bg-gray-100 text-gray-700' }}">
                                All Items
                                <span class="text-sm text-gray-500 ml-2">({{ $menuItems->count() }})</span>
                            </button>
                        </li>
                        @foreach ($categories as $category)
                            <li>
                                <button wire:click="selectCategory({{ $category->id }})" 
                                    class="w-full text-left px-3 py-2 rounded-lg transition-colors {{ $selectedCategory == $category->id ? 'bg-blue-100 text-blue-700 font-medium' : 'hover:bg-gray-100 text-gray-700' }}">
                                    {{ $category->name }}
                                    <span class="text-sm text-gray-500 ml-2">({{ $category->activeMenuItems->count() }})</span>
                                </button>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>

            <!-- Menu Items -->
            <div class="lg:w-3/4">
                @if($selectedCategory)
                    @php $currentCategory = $categories->find($selectedCategory) @endphp
                    <div class="mb-4 sm:mb-6 px-1 lg:px-0">
                        <h2 class="text-xl sm:text-2xl font-bold text-gray-900">{{ $currentCategory->name }}</h2>
                        @if($currentCategory->description)
                            <p class="text-gray-600 mt-1 text-sm sm:text-base">{{ $currentCategory->description }}</p>
                        @endif
                    </div>
                @else
                    <div class="mb-4 sm:mb-6 px-1 lg:px-0">
                        <h2 class="text-xl sm:text-2xl font-bold text-gray-900">All Menu Items</h2>
                        <p class="text-gray-600 mt-1 text-sm sm:text-base">Browse our complete menu</p>
                    </div>
                @endif

                <!-- Mobile: Two columns, Desktop: Two columns -->
                <div class="grid grid-cols-2 lg:grid-cols-2 gap-2.5 sm:gap-6">
                    @forelse ($menuItems as $item)
                        <div class="bg-white rounded-lg shadow-sm border overflow-hidden hover:shadow-md transition-shadow">
                            @if($item->image)
                                <img src="{{ $item->image }}" alt="{{ $item->name }}" class="w-full h-28 sm:h-48 object-cover">
                            @else
                                <div class="w-full h-28 sm:h-48 bg-gradient-to-br from-gray-100 to-gray-200 flex items-center justify-center">
                                    <svg class="w-8 h-8 sm:w-16 sm:h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                            @endif
                            
                            <div class="p-2.5 sm:p-6">
                                <div class="flex justify-between items-start mb-1 sm:mb-2">
                                    <h3 class="text-sm sm:text-lg font-semibold text-gray-900 flex-1 pr-1 sm:pr-2 line-clamp-1 sm:line-clamp-none">{{ $item->name }}</h3>
                                    @if($item->is_featured)
                                        <span class="hidden sm:inline-block bg-yellow-100 text-yellow-800 text-xs px-2 py-1 rounded-full font-medium flex-shrink-0">Featured</span>
                                    @endif
                                </div>
                                
                                <p class="text-gray-600 text-xs sm:text-sm mb-2 sm:mb-4 line-clamp-2">{{ $item->description }}</p>
                                
                                <!-- Mobile: Compact layout, Desktop: Side by side -->
                                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-2 sm:mb-4 space-y-2 sm:space-y-0">
                                    <div>
                                        <span class="text-sm sm:text-2xl font-bold text-green-600">PKR {{ number_format($item->price, 0) }}</span>
                                        <div class="text-[10px] sm:text-xs text-gray-500 mt-0.5 sm:mt-1">
                                            <span>🕒 {{ $item->preparation_time }} min</span>
                                        </div>
                                    </div>
                                    
                                    <button wire:click="addToCart({{ $item->id }})" 
                                        class="w-full sm:w-auto bg-blue-600 text-white px-3 sm:px-6 py-1.5 sm:py-2 rounded-lg hover:bg-blue-700 transition-colors font-medium text-xs sm:text-base">
                                        Add to Cart
                                    </button>
                                </div>
                                
                                @if($item->ingredients && count($item->ingredients) > 0)
                                    <div class="hidden sm:block text-xs text-gray-500">
                                        <span class="font-medium">Ingredients:</span> {{ implode(', ', array_slice($item->ingredients, 0, 3)) }}
                                        @if(count($item->ingredients) > 3)
                                            <span>...</span>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="col-span-full text-center py-8 sm:py-12">
                            <div class="text-gray-400 text-4xl sm:text-6xl mb-4">🍽️</div>
                            <h3 class="text-lg sm:text-xl font-semibold text-gray-900 mb-2">No items found</h3>
                            <p class="text-gray-600 text-sm sm:text-base">Try selecting a different category</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Cart Sidebar -->
    @if($showCart)
        <div class="fixed inset-0 z-50 overflow-hidden">
            <div class="absolute inset-0 bg-black bg-opacity-50" wire:click="toggleCart"></div>
            <!-- Mobile: Bottom sheet, Desktop: Right sidebar -->
            <div class="absolute sm:right-0 sm:top-0 bottom-0 sm:bottom-auto left-0 sm:left-auto h-3/4 sm:h-full w-full sm:max-w-md bg-white shadow-xl rounded-t-2xl sm:rounded-none">
                <div class="flex flex-col h-full">
                    <!-- Cart Header -->
                    <div class="flex items-center justify-between p-4 sm:p-6 border-b">
                        <!-- Mobile: Drag handle -->
                        <div class="sm:hidden w-12 h-1 bg-gray-300 rounded-full mx-auto absolute top-2 left-1/2 transform -translate-x-1/2"></div>
                        <h2 class="text-lg font-semibold text-gray-900 mt-2 sm:mt-0">Your Cart</h2>
                        <button wire:click="toggleCart" class="text-gray-400 hover:text-gray-600 p-1">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>

                    <!-- Cart Items -->
                    <div class="flex-1 overflow-y-auto p-4 sm:p-6">
                        @if(empty($cart))
                            <div class="text-center py-8 sm:py-12">
                                <svg class="w-12 h-12 sm:w-16 sm:h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-1.5 6M7 13l-1.5 6m0 0h9M17 13v6a2 2 0 01-2 2H9a2 2 0 01-2-2v-6"></path>
                                </svg>
                                <p class="text-gray-500 text-sm sm:text-base">Your cart is empty</p>
                                <p class="text-xs sm:text-sm text-gray-400 mt-1">Add some delicious items to get started</p>
                            </div>
                        @else
                            <div class="space-y-3 sm:space-y-4">
                                @foreach($cart as $key => $item)
                                    <div class="flex items-center space-x-3 sm:space-x-4 p-3 sm:p-4 bg-gray-50 rounded-lg">
                                        <div class="flex-1 min-w-0">
                                            <h4 class="font-medium text-gray-900 text-sm sm:text-base truncate">{{ $item['name'] }}</h4>
                                            <p class="text-xs sm:text-sm text-gray-600">PKR {{ number_format($item['price'], 2) }} each</p>
                                        </div>
                                        <div class="flex items-center space-x-1 sm:space-x-2">
                                            <button wire:click="updateQuantity('{{ $key }}', {{ $item['quantity'] - 1 }})" 
                                                class="w-7 h-7 sm:w-8 sm:h-8 bg-white border rounded-full flex items-center justify-center hover:bg-gray-100">
                                                <svg class="w-3 h-3 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>
                                                </svg>
                                            </button>
                                            <span class="w-6 sm:w-8 text-center font-medium text-sm sm:text-base">{{ $item['quantity'] }}</span>
                                            <button wire:click="updateQuantity('{{ $key }}', {{ $item['quantity'] + 1 }})" 
                                                class="w-7 h-7 sm:w-8 sm:h-8 bg-white border rounded-full flex items-center justify-center hover:bg-gray-100">
                                                <svg class="w-3 h-3 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                                </svg>
                                            </button>
                                        </div>
                                        <button wire:click="removeFromCart('{{ $key }}')" 
                                            class="text-red-500 hover:text-red-700 p-1">
                                            <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                        </button>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>

                    <!-- Cart Footer -->
                    @if(!empty($cart))
                        <div class="border-t p-4 sm:p-6 bg-white">
                            <div class="flex justify-between items-center text-base sm:text-lg font-semibold mb-3 sm:mb-4">
                                <span>Total:</span>
                                <span class="text-green-600">PKR {{ number_format($cartTotal, 2) }}</span>
                            </div>
                            <button wire:click="proceedToCheckout" 
                                class="w-full bg-green-600 text-white py-3 rounded-lg font-semibold hover:bg-green-700 transition-colors text-sm sm:text-base">
                                Proceed to Checkout
                            </button>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @endif

    <!-- Mobile Fixed Cart Button (only show when cart has items and cart is closed) -->
    @if(!empty($cart) && !$showCart)
        <div class="fixed bottom-4 left-4 right-4 z-40 sm:hidden">
            <button wire:click="toggleCart" 
                class="w-full bg-gradient-to-r from-blue-600 to-blue-700 text-white px-6 py-4 rounded-2xl shadow-2xl hover:from-blue-700 hover:to-blue-800 transition-all duration-200 transform hover:scale-105">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <div class="relative">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-1.5 6M7 13l-1.5 6m0 0h9M17 13v6a2 2 0 01-2 2H9a2 2 0 01-2-2v-6"></path>
                            </svg>
                            <span class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full w-5 h-5 text-xs flex items-center justify-center font-bold">
                                {{ $this->getCartCount() }}
                            </span>
                        </div>
                        <span class="font-semibold">View Cart</span>
                    </div>
                    <div class="text-right">
                        <div class="font-bold text-lg">PKR {{ number_format($cartTotal, 2) }}</div>
                        <div class="text-xs opacity-90">{{ $this->getCartCount() }} item{{ $this->getCartCount() > 1 ? 's' : '' }}</div>
                    </div>
                </div>
            </button>
        </div>
    @endif

    @script
    <script>
        // Initialize cart count on page load
        document.addEventListener('DOMContentLoaded', function() {
            updateHeaderCartCount();
        });

        // Update cart count in header when cart changes
        $wire.on('cart-updated', () => {
            updateHeaderCartCount();
        });

        // Listen for toggle cart event from header
        window.addEventListener('toggle-cart', () => {
            $wire.toggleCart();
        });

        // Function to update header cart count
        function updateHeaderCartCount() {
            const count = $wire.getCartCount();
            
            // Dispatch event to update header
            window.dispatchEvent(new CustomEvent('cart-updated', { 
                detail: { count: count } 
            }));
            
            // Also store in session storage for persistence
            const cart = $wire.cart;
            if (cart && Object.keys(cart).length > 0) {
                sessionStorage.setItem('cart', JSON.stringify(cart));
            } else {
                sessionStorage.removeItem('cart');
            }
        }
    </script>
    @endscript
</div>