<div class="min-h-screen bg-gradient-to-br from-gray-50 via-blue-50 to-indigo-50 p-4 sm:p-6">
    <!-- Header -->
    <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-6 mb-6 relative overflow-hidden">
        <div class="absolute inset-0 opacity-5">
            <div class="absolute top-4 right-4 w-32 h-32 bg-gradient-to-br from-blue-400 to-indigo-400 rounded-full"></div>
            <div class="absolute bottom-4 left-4 w-24 h-24 bg-gradient-to-br from-purple-400 to-pink-400 rounded-full"></div>
        </div>
        <div class="relative flex flex-col sm:flex-row sm:justify-between sm:items-center space-y-4 sm:space-y-0">
            <div>
                <h1 class="text-3xl sm:text-4xl font-bold bg-gradient-to-r from-blue-600 via-indigo-600 to-purple-600 bg-clip-text text-transparent">Menu Items</h1>
                <p class="text-gray-600 mt-2 text-sm sm:text-base font-medium">Manage menu items and pricing</p>
            </div>
            <button wire:click="create" class="bg-gradient-to-r from-blue-600 to-indigo-600 text-white px-6 py-3 rounded-lg hover:from-blue-700 hover:to-indigo-700 transition-all duration-200 shadow-md hover:shadow-lg font-semibold w-full sm:w-auto transform hover:scale-105">
                + Add Menu Item
            </button>
        </div>
    </div>

    @if (session()->has('message'))
        <div class="bg-gradient-to-r from-green-50 to-emerald-50 border-l-4 border-green-500 text-green-800 px-4 py-3 rounded-lg mb-4 shadow-md flex items-center">
            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
            </svg>
            <span class="font-semibold">{{ session('message') }}</span>
        </div>
    @endif

    @if ($showForm)
        <div class="bg-white p-4 sm:p-6 rounded-lg shadow mb-6">
            <h2 class="text-lg font-semibold mb-4">
                {{ $editingId ? 'Edit Menu Item' : 'Add Menu Item' }}
            </h2>
            
            <form wire:submit.prevent="save" class="space-y-4">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Restaurant</label>
                        <select wire:model.live="restaurant_id" class="mt-1 block w-full rounded-lg border-2 border-gray-300 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 px-4 py-2 text-gray-900 bg-white font-medium text-sm">
                            <option value="">Select Restaurant</option>
                            @foreach($restaurants as $restaurant)
                                <option value="{{ $restaurant->id }}">{{ $restaurant->name }}</option>
                            @endforeach
                        </select>
                        @error('restaurant_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Category</label>
                        <select wire:model="menu_category_id" class="mt-1 block w-full rounded-lg border-2 border-gray-300 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 px-4 py-2.5 text-gray-900 bg-white font-semibold text-sm">
                            <option value="">Select Category</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                        @error('menu_category_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Name</label>
                    <input type="text" wire:model="name" class="mt-1 block w-full rounded-lg border-2 border-gray-300 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 px-4 py-2 text-gray-900 bg-white text-sm">
                    @error('name') <span class="text-red-600 text-sm font-medium mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Description</label>
                    <textarea wire:model="description" rows="3" class="mt-1 block w-full rounded-lg border-2 border-gray-300 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 px-4 py-2 text-gray-900 bg-white"></textarea>
                    @error('description') <span class="text-red-600 text-sm font-medium mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div class="grid grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Price (PKR)</label>
                        <input type="number" step="0.01" wire:model="price" class="mt-1 block w-full rounded-lg border-2 border-gray-300 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 px-4 py-2 text-gray-900 bg-white">
                        @error('price') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Prep Time (min)</label>
                        <input type="number" wire:model="preparation_time" class="mt-1 block w-full rounded-lg border-2 border-gray-300 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 px-4 py-2 text-gray-900 bg-white">
                        @error('preparation_time') <span class="text-red-600 text-sm font-medium mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Sort Order</label>
                        <input type="number" wire:model="sort_order" class="mt-1 block w-full rounded-lg border-2 border-gray-300 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 px-4 py-2 text-gray-900 bg-white">
                        @error('sort_order') <span class="text-red-600 text-sm font-medium mt-1 block">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="flex space-x-4">
                    <label class="flex items-center">
                        <input type="checkbox" wire:model="is_available" class="rounded border-gray-300">
                        <span class="ml-2 text-sm text-gray-700">Available</span>
                    </label>
                    
                    <label class="flex items-center">
                        <input type="checkbox" wire:model="is_featured" class="rounded border-gray-300">
                        <span class="ml-2 text-sm text-gray-700">Featured</span>
                    </label>
                </div>

                <div class="flex space-x-2">
                    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                        {{ $editingId ? 'Update' : 'Create' }}
                    </button>
                    <button type="button" wire:click="cancel" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">
                        Cancel
                    </button>
                </div>
            </form>
        </div>
    @endif

    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Item</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach ($menuItems as $item)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div>
                                    <div class="text-sm font-medium text-gray-900 flex items-center">
                                        {{ $item->name }}
                                        @if($item->is_featured)
                                            <span class="ml-2 bg-yellow-100 text-yellow-800 text-xs px-2 py-1 rounded">Featured</span>
                                        @endif
                                    </div>
                                    <div class="text-sm text-gray-500">{{ Str::limit($item->description, 50) }}</div>
                                    <div class="text-xs text-gray-400">{{ $item->restaurant->name }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $item->menuCategory->name }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            PKR {{ number_format($item->price, 2) }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $item->is_available ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $item->is_available ? 'Available' : 'Unavailable' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <button wire:click="edit({{ $item->id }})" class="text-indigo-600 hover:text-indigo-900 mr-2">Edit</button>
                            <button wire:click="delete({{ $item->id }})" onclick="return confirm('Are you sure?')" class="text-red-600 hover:text-red-900">Delete</button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        
        <div class="px-6 py-3">
            {{ $menuItems->links() }}
        </div>
    </div>
</div>