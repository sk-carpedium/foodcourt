<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Restaurant Ecommerce' }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="font-sans antialiased bg-gray-50">
    <!-- Navigation Header -->
    <nav class="bg-white shadow-sm border-b border-gray-200 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <!-- Logo -->
                <div class="flex items-center">
                    <a href="{{ route('home') }}" class="flex items-center space-x-2">
                        <div class="w-8 h-8 bg-blue-600 rounded-lg flex items-center justify-center">
                            <span class="text-white font-bold text-sm">R</span>
                        </div>
                        <span class="text-xl font-bold text-gray-900">RestaurantHub</span>
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden md:flex items-center space-x-8">
                    <a href="{{ route('home') }}" class="text-gray-600 hover:text-gray-900 px-3 py-2 rounded-md text-sm font-medium">
                        Home
                    </a>
                    <a href="{{ route('home') }}#restaurants" class="text-gray-600 hover:text-gray-900 px-3 py-2 rounded-md text-sm font-medium">
                        Restaurants
                    </a>
                    <a href="{{ route('order.track') }}" class="text-gray-600 hover:text-gray-900 px-3 py-2 rounded-md text-sm font-medium">
                        Track Order
                    </a>
                    <a href="#" class="text-gray-600 hover:text-gray-900 px-3 py-2 rounded-md text-sm font-medium">
                        About
                    </a>
                    <a href="#" class="text-gray-600 hover:text-gray-900 px-3 py-2 rounded-md text-sm font-medium">
                        Contact
                    </a>
                </div>

                <!-- Cart and Actions -->
                <div class="flex items-center space-x-4">
                    <!-- Cart Button with Count Text -->
                    <button onclick="toggleCart()" class="flex items-center space-x-2 px-3 py-2 text-gray-600 hover:text-gray-900 hover:bg-gray-100 rounded-lg transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-1.5 6M7 13l-1.5 6m0 0h9M17 13v6a2 2 0 01-2 2H9a2 2 0 01-2-2v-6"></path>
                        </svg>
                        <span class="hidden sm:inline font-medium">Cart</span>
                        <span id="cart-count-text" class="bg-blue-600 text-white text-xs px-2 py-1 rounded-full font-medium hidden">0</span>
                    </button>
                </div>

                <!-- Mobile menu button -->
                <div class="md:hidden">
                    <button type="button" class="text-gray-600 hover:text-gray-900 focus:outline-none focus:text-gray-900" onclick="toggleMobileMenu()">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile menu -->
        <div id="mobile-menu" class="md:hidden hidden">
            <div class="px-2 pt-2 pb-3 space-y-1 sm:px-3 bg-white border-t border-gray-200">
                <a href="{{ route('home') }}" class="text-gray-600 hover:text-gray-900 block px-3 py-2 rounded-md text-base font-medium">Home</a>
                <a href="{{ route('home') }}#restaurants" class="text-gray-600 hover:text-gray-900 block px-3 py-2 rounded-md text-base font-medium">Restaurants</a>
                <a href="{{ route('order.track') }}" class="text-gray-600 hover:text-gray-900 block px-3 py-2 rounded-md text-base font-medium">Track Order</a>
                <a href="#" class="text-gray-600 hover:text-gray-900 block px-3 py-2 rounded-md text-base font-medium">About</a>
                <a href="#" class="text-gray-600 hover:text-gray-900 block px-3 py-2 rounded-md text-base font-medium">Contact</a>
                
                <!-- Mobile Cart Button -->
                <button onclick="toggleCart()" class="w-full text-left text-gray-600 hover:text-gray-900 hover:bg-gray-100 block px-3 py-2 rounded-md text-base font-medium flex items-center justify-between transition-colors">
                    <span class="flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-1.5 6M7 13l-1.5 6m0 0h9M17 13v6a2 2 0 01-2 2H9a2 2 0 01-2-2v-6"></path>
                        </svg>
                        Cart
                    </span>
                    <span id="mobile-cart-count-text" class="bg-blue-600 text-white text-xs px-2 py-1 rounded-full font-medium hidden">0</span>
                </button>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main>
        {{ $slot }}
    </main>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white mt-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="grid grid-cols-1 md:grid-cols-5 gap-8">
                <div>
                    <div class="flex items-center space-x-2 mb-4">
                        <div class="w-8 h-8 bg-blue-600 rounded-lg flex items-center justify-center">
                            <span class="text-white font-bold text-sm">R</span>
                        </div>
                        <span class="text-xl font-bold">RestaurantHub</span>
                    </div>
                    <p class="text-gray-400">Your favorite restaurants, delivered to your door.</p>
                </div>
                
                <div>
                    <h3 class="text-lg font-semibold mb-4">Quick Links</h3>
                    <ul class="space-y-2">
                        <li><a href="{{ route('home') }}" class="text-gray-400 hover:text-white transition-colors">Home</a></li>
                        <li><a href="{{ route('home') }}#restaurants" class="text-gray-400 hover:text-white transition-colors">Restaurants</a></li>
                        <li><a href="{{ route('order.track') }}" class="text-gray-400 hover:text-white transition-colors">Track Order</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition-colors">About Us</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition-colors">Contact</a></li>
                    </ul>
                </div>
                
                <div>
                    <h3 class="text-lg font-semibold mb-4">Support</h3>
                    <ul class="space-y-2">
                        <li><a href="#" class="text-gray-400 hover:text-white transition-colors">Help Center</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition-colors">Terms of Service</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition-colors">Privacy Policy</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition-colors">FAQ</a></li>
                    </ul>
                </div>
                
                <div>
                    <h3 class="text-lg font-semibold mb-4">For Restaurants</h3>
                    <ul class="space-y-2">
                        @guest
                            <li><a href="{{ route('login') }}" class="text-gray-400 hover:text-white transition-colors">Restaurant Login</a></li>
                            <li><a href="{{ route('register') }}" class="text-gray-400 hover:text-white transition-colors">Partner with Us</a></li>
                        @else
                            <li><a href="{{ route('dashboard') }}" class="text-gray-400 hover:text-white transition-colors">Dashboard</a></li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}" class="inline">
                                    @csrf
                                    <button type="submit" class="text-gray-400 hover:text-white transition-colors text-left">
                                        Sign Out
                                    </button>
                                </form>
                            </li>
                        @endguest
                        <li><a href="#" class="text-gray-400 hover:text-white transition-colors">Business Solutions</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition-colors">Restaurant Tools</a></li>
                    </ul>
                </div>
                
                <div>
                    <h3 class="text-lg font-semibold mb-4">Contact Info</h3>
                    <ul class="space-y-2 text-gray-400">
                        <li class="flex items-center">
                            <span class="mr-2">📞</span>
                            <a href="tel:+15551234567" class="hover:text-white transition-colors">+1 (555) 123-4567</a>
                        </li>
                        <li class="flex items-center">
                            <span class="mr-2">✉️</span>
                            <a href="mailto:info@restauranthub.com" class="hover:text-white transition-colors">info@restauranthub.com</a>
                        </li>
                        <li class="flex items-center">
                            <span class="mr-2">📍</span>
                            <span>123 Food Street, City</span>
                        </li>
                    </ul>
                </div>
            </div>
            
            <div class="border-t border-gray-700 mt-8 pt-8">
                <div class="flex flex-col md:flex-row justify-between items-center">
                    <p class="text-gray-400 text-center md:text-left">&copy; {{ date('Y') }} RestaurantHub. All rights reserved.</p>
                    
                    @guest
                        <div class="flex items-center mt-4 md:mt-0">
                            <a href="{{ route('login') }}" class="bg-gray-700 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition-colors text-sm font-medium">
                                Sign In
                            </a>
                        </div>
                    @else
                        <div class="flex items-center space-x-4 mt-4 md:mt-0">
                            <span class="text-gray-400 text-sm">Welcome back!</span>
                            <a href="{{ route('dashboard') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors text-sm font-medium">
                                Go to Dashboard
                            </a>
                        </div>
                    @endguest
                </div>
            </div>
        </div>
    </footer>

    @livewireScripts
    
    <script>
        function toggleMobileMenu() {
            const menu = document.getElementById('mobile-menu');
            menu.classList.toggle('hidden');
        }

        function toggleCart() {
            // This will be handled by Livewire components
            window.dispatchEvent(new CustomEvent('toggle-cart'));
        }

        // Update cart count display
        function updateCartDisplay(count) {
            const cartCountText = document.getElementById('cart-count-text');
            const mobileCartCountText = document.getElementById('mobile-cart-count-text');
            
            // Update desktop cart count
            if (cartCountText) {
                if (count > 0) {
                    cartCountText.textContent = count;
                    cartCountText.classList.remove('hidden');
                } else {
                    cartCountText.classList.add('hidden');
                }
            }
            
            // Update mobile cart count
            if (mobileCartCountText) {
                if (count > 0) {
                    mobileCartCountText.textContent = count;
                    mobileCartCountText.classList.remove('hidden');
                } else {
                    mobileCartCountText.classList.add('hidden');
                }
            }
        }

        // Update cart count
        window.addEventListener('cart-updated', function(e) {
            const count = e.detail?.count || 0;
            updateCartDisplay(count);
        });

        // Initialize cart count from session storage if available
        document.addEventListener('DOMContentLoaded', function() {
            // Try to get cart count from session storage
            const savedCart = sessionStorage.getItem('cart');
            if (savedCart) {
                try {
                    const cart = JSON.parse(savedCart);
                    const count = Object.values(cart).reduce((total, item) => total + (item.quantity || 0), 0);
                    updateCartDisplay(count);
                } catch (e) {
                    // Ignore parsing errors
                    updateCartDisplay(0);
                }
            } else {
                updateCartDisplay(0);
            }
        });
    </script>
</body>
</html>