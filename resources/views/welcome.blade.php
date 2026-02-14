<x-layouts.guest title="FoodHub - Order Food Online">
    <!-- Hero Section with Search -->
    <section class="relative bg-gradient-to-br from-pink-500 via-red-500 to-orange-500 text-white overflow-hidden">
        <!-- Background Pattern -->
        <div class="absolute inset-0 opacity-10">
            <div class="absolute top-10 left-10 w-32 h-32 bg-white rounded-full"></div>
            <div class="absolute top-32 right-20 w-20 h-20 bg-yellow-300 rounded-full"></div>
            <div class="absolute bottom-20 left-1/4 w-16 h-16 bg-pink-300 rounded-full"></div>
            <div class="absolute bottom-32 right-1/3 w-24 h-24 bg-orange-300 rounded-full"></div>
        </div>
        
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 lg:py-24">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                <!-- Left Content -->
                <div class="text-center lg:text-left">
                    <h1 class="text-4xl lg:text-6xl font-bold mb-6 leading-tight">
                        Your favorite food
                        <span class="block text-yellow-300">served fresh</span>
                    </h1>
                    <p class="text-xl lg:text-2xl mb-8 text-pink-100 leading-relaxed">
                        Order from our restaurants and enjoy fresh meals at your table!
                    </p>
                    
                    <!-- Quick Stats -->
                    <div class="flex flex-wrap justify-center lg:justify-start gap-8 text-center mb-8">
                        <div>
                            <div class="text-3xl font-bold text-yellow-300">9</div>
                            <div class="text-pink-100">Restaurants</div>
                        </div>
                        <div>
                            <div class="text-3xl font-bold text-yellow-300">20min</div>
                            <div class="text-pink-100">Avg Preparation</div>
                        </div>
                    </div>
                    
                    <!-- Browse All Restaurants Button -->
                    <div class="text-center lg:text-left">
                        <a href="#restaurants" class="inline-block bg-gradient-to-r from-yellow-400 to-orange-500 text-gray-900 px-8 py-4 rounded-2xl font-bold text-lg hover:from-yellow-500 hover:to-orange-600 transition-all duration-200 shadow-2xl hover:shadow-3xl transform hover:scale-105">
                            🍽️ Browse All Restaurants
                        </a>
                    </div>
                </div>
                
                <!-- Right Content - Food Image -->
                <div class="relative">
                    <div class="relative z-10">
                        <div class="bg-white rounded-3xl p-8 shadow-2xl transform rotate-3 hover:rotate-0 transition-transform duration-300">
                            <div class="text-8xl text-center mb-4">🍕</div>
                            <div class="text-center">
                                <h3 class="text-xl font-bold text-gray-800 mb-2">For Advertisement</h3>
                                <div class="flex items-center justify-center space-x-1 mb-3">
                                    <span class="text-yellow-400">⭐⭐⭐⭐⭐</span>
                                    <span class="text-gray-600 text-sm">(4.8)</span>
                                </div>
                                <div class="text-2xl font-bold text-red-500">CAll on 000000000</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Categories Section -->
    <section class="py-12 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-8 text-center">What's on your mind?</h2>
            
            <div class="grid grid-cols-3 md:grid-cols-6 lg:grid-cols-8 gap-4">
                @php
                $categories = [
                    ['name' => 'KABABJEES Restaurant', 'emoji' => '🍕', 'color' => 'from-red-400 to-red-600'],
                    ['name' => 'KABABJEES Restaurant', 'emoji' => '🍕', 'color' => 'from-red-400 to-red-600'],
                    ['name' => 'KABABJEES Restaurant', 'emoji' => '🍕', 'color' => 'from-red-400 to-red-600'],
                    ['name' => 'KABABJEES Restaurant', 'emoji' => '🍕', 'color' => 'from-red-400 to-red-600'],
                    ['name' => 'KABABJEES Restaurant', 'emoji' => '🍕', 'color' => 'from-red-400 to-red-600'],
                    ['name' => 'KABABJEES Restaurant', 'emoji' => '🍕', 'color' => 'from-red-400 to-red-600'],
                    ['name' => 'KABABJEES Restaurant', 'emoji' => '🍕', 'color' => 'from-red-400 to-red-600'],
                    ['name' => 'KABABJEES Restaurant', 'emoji' => '🍕', 'color' => 'from-red-400 to-red-600'],
                    ['name' => 'KABABJEES Restaurant', 'emoji' => '🍕', 'color' => 'from-red-400 to-red-600'],
                    
                ];
                @endphp
                
                @foreach($categories as $category)
                <div class="group cursor-pointer">
                    <div class="bg-gradient-to-br {{ $category['color'] }} rounded-2xl p-4 text-center shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-200">
                        <div class="text-3xl mb-2">{{ $category['emoji'] }}</div>
                        <div class="text-white font-semibold text-sm">{{ $category['name'] }}</div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Restaurants Section -->
    <section id="restaurants" class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-4xl font-bold text-gray-900 mb-4">Our Restaurants</h2>
                <p class="text-xl text-gray-600">Discover amazing dining experiences</p>
            </div>

            <div class="grid grid-cols-3 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @forelse(\App\Models\Restaurant::where('is_active', true)->take(8)->get() as $restaurant)
                    <div class="bg-white rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 overflow-hidden group border border-gray-100">
                        <!-- Restaurant Image -->
                        <div class="relative h-48 overflow-hidden">
                            @if($restaurant->image)
                                <img src="{{ $restaurant->image }}" alt="{{ $restaurant->name }}" 
                                     class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">
                            @else
                                <div class="w-full h-full bg-gradient-to-br from-red-400 via-pink-500 to-purple-600 flex items-center justify-center">
                                    <span class="text-white text-4xl font-bold">{{ substr($restaurant->name, 0, 1) }}</span>
                                </div>
                            @endif
                            
                            <!-- Badges -->
                            <div class="absolute top-3 left-3">
                                <span class="bg-green-500 text-white px-2 py-1 rounded-full text-xs font-semibold">
                                    ⚡ Fresh & Hot
                                </span>
                            </div>
                            <div class="absolute top-3 right-3">
                                <span class="bg-blue-500 text-white px-2 py-1 rounded-full text-xs font-semibold">
                                    ⭐ Popular
                                </span>
                            </div>
                            
                            <!-- Favorite Button -->
                            <button class="absolute bottom-3 right-3 w-10 h-10 bg-white rounded-full shadow-lg flex items-center justify-center hover:bg-red-50 transition-colors group">
                                <svg class="w-5 h-5 text-gray-400 group-hover:text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                </svg>
                            </button>
                        </div>
                        
                        <!-- Restaurant Info -->
                        <div class="p-4">
                            <div class="flex justify-between items-start mb-2">
                                <h3 class="font-bold text-lg text-gray-900 truncate">{{ $restaurant->name }}</h3>
                                <div class="flex items-center space-x-1 bg-green-100 px-2 py-1 rounded-lg">
                                    <span class="text-green-600 text-sm font-semibold">4.3</span>
                                    <svg class="w-3 h-3 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                    </svg>
                                </div>
                            </div>
                            
                            <p class="text-gray-600 text-sm mb-3 line-clamp-2">{{ $restaurant->description ?: 'Delicious food, fresh ingredients' }}</p>
                            
                            <!-- Restaurant Details -->
                            <div class="flex items-center justify-between text-sm text-gray-500 mb-4">
                                <div class="flex items-center space-x-4">
                                    <span class="flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        15-25 min
                                    </span>
                                    <span class="flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        </svg>
                                        Dine-in
                                    </span>
                                </div>
                            </div>
                            
                            <!-- Order Button -->
                            <a href="{{ route('menu', $restaurant->slug) }}" 
                               class="block w-full bg-gradient-to-r from-red-500 to-pink-500 text-white text-center py-3 rounded-xl font-semibold hover:from-red-600 hover:to-pink-600 transition-all duration-200 shadow-lg hover:shadow-xl">
                                Order Now
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full text-center py-16">
                        <div class="text-gray-400 text-8xl mb-6">🍽️</div>
                        <h3 class="text-2xl font-bold text-gray-900 mb-4">No restaurants available</h3>
                        <p class="text-gray-600 text-lg">We're working hard to bring you amazing restaurants!</p>
                    </div>
                @endforelse
            </div>
            
            <!-- View All Restaurants Button -->
            @if(\App\Models\Restaurant::where('is_active', true)->count() > 8)
                <div class="text-center mt-12">
                    <a href="#" class="inline-block bg-gradient-to-r from-pink-500 via-red-500 to-orange-500 text-white px-12 py-4 rounded-2xl font-bold text-lg hover:from-pink-600 hover:via-red-600 hover:to-orange-600 transition-all duration-200 shadow-2xl hover:shadow-3xl transform hover:scale-105">
                        🏪 View All {{ \App\Models\Restaurant::where('is_active', true)->count() }} Restaurants
                    </a>
                </div>
            @endif
        </div>
    </section>

    <!-- Features Section -->
    <section class="py-16 bg-gradient-to-br from-orange-50 to-red-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-gray-900 mb-4">Why choose FoodHub?</h2>
                <p class="text-xl text-gray-600">We make dining simple and delightful</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="text-center group">
                    <div class="w-20 h-20 bg-gradient-to-br from-red-400 to-pink-500 rounded-3xl flex items-center justify-center mx-auto mb-6 group-hover:scale-110 transition-transform duration-200 shadow-lg">
                        <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold mb-3 text-gray-900">Quick Service</h3>
                    <p class="text-gray-600 leading-relaxed">Get your food prepared fresh and served quickly with our efficient kitchen operations</p>
                </div>
                
                <div class="text-center group">
                    <div class="w-20 h-20 bg-gradient-to-br from-green-400 to-teal-500 rounded-3xl flex items-center justify-center mx-auto mb-6 group-hover:scale-110 transition-transform duration-200 shadow-lg">
                        <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold mb-3 text-gray-900">Quality Guaranteed</h3>
                    <p class="text-gray-600 leading-relaxed">Fresh ingredients, top-rated restaurants, and quality you can taste in every bite</p>
                </div>
                
                <div class="text-center group">
                    <div class="w-20 h-20 bg-gradient-to-br from-purple-400 to-pink-500 rounded-3xl flex items-center justify-center mx-auto mb-6 group-hover:scale-110 transition-transform duration-200 shadow-lg">
                        <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold mb-3 text-gray-900">Comfortable Dining</h3>
                    <p class="text-gray-600 leading-relaxed">Enjoy your meals in a comfortable atmosphere with excellent service and ambiance</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer CTA -->
    <section class="py-16 bg-gray-900 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-4xl font-bold mb-4">Hungry? You're in the right place</h2>
            <p class="text-xl text-gray-300 mb-8">Join thousands of happy customers enjoying fresh meals at our restaurants</p>
            <a href="#restaurants" class="inline-block bg-gradient-to-r from-red-500 to-pink-500 text-white px-12 py-4 rounded-2xl font-bold text-lg hover:from-red-600 hover:to-pink-600 transition-all duration-200 shadow-2xl hover:shadow-3xl transform hover:scale-105">
                Browse Restaurants
            </a>
        </div>
    </section>
</x-layouts.guest>