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
                        <a href="#restaurants-quick" class="inline-block bg-gradient-to-r from-yellow-400 to-orange-500 text-gray-900 px-8 py-4 rounded-2xl font-bold text-lg hover:from-yellow-500 hover:to-orange-600 transition-all duration-200 shadow-2xl hover:shadow-3xl transform hover:scale-105">
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

    <!-- Restaurants Quick Access Section -->
    <section id="restaurants-quick" class="py-12 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-8 text-center">What's on your mind?</h2>
            
            <div class="grid grid-cols-3 md:grid-cols-6 lg:grid-cols-8 gap-4">
                @foreach(\App\Models\Restaurant::where('is_active', true)->get() as $resto)
                <a href="{{ route('menu', $resto->slug) }}" class="group cursor-pointer">
                    <div class="bg-gradient-to-br from-red-400 to-red-600 rounded-2xl p-4 text-center shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-200">
                        @if($resto->image)
                            <img src="{{ $resto->image }}" alt="{{ $resto->name }}" class="w-10 h-10 sm:w-12 sm:h-12 mx-auto mb-2 rounded-lg object-cover">
                        @else
                            <div class="w-10 h-10 sm:w-12 sm:h-12 mx-auto mb-2 bg-white/20 rounded-lg flex items-center justify-center">
                                <span class="text-white text-xl font-bold">{{ substr($resto->name, 0, 1) }}</span>
                            </div>
                        @endif
                        <div class="text-white font-semibold text-xs sm:text-sm leading-tight">{{ $resto->name }}</div>
                    </div>
                </a>
                @endforeach
            </div>
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
            <a href="#restaurants-quick" class="inline-block bg-gradient-to-r from-red-500 to-pink-500 text-white px-12 py-4 rounded-2xl font-bold text-lg hover:from-red-600 hover:to-pink-600 transition-all duration-200 shadow-2xl hover:shadow-3xl transform hover:scale-105">
                Browse Restaurants
            </a>
        </div>
    </section>
</x-layouts.guest>