<div class="min-h-screen bg-gradient-to-br from-gray-50 via-blue-50 to-indigo-50 p-4 sm:p-6">
    <!-- Header -->
    <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-6 mb-6 relative overflow-hidden">
        <div class="absolute inset-0 opacity-5">
            <div class="absolute top-4 right-4 w-32 h-32 bg-gradient-to-br from-blue-400 to-indigo-400 rounded-full"></div>
            <div class="absolute bottom-4 left-4 w-24 h-24 bg-gradient-to-br from-purple-400 to-pink-400 rounded-full"></div>
        </div>
        <div class="relative flex flex-col sm:flex-row sm:justify-between sm:items-center space-y-4 sm:space-y-0">
            <div>
                <h1 class="text-3xl sm:text-4xl font-bold bg-gradient-to-r from-blue-600 via-indigo-600 to-purple-600 bg-clip-text text-transparent">Restaurant Management</h1>
                <p class="text-gray-600 mt-2 text-sm sm:text-base font-medium">Manage restaurants and their settings</p>
            </div>
            <button wire:click="create" class="bg-gradient-to-r from-blue-600 to-indigo-600 text-white px-6 py-3 rounded-lg hover:from-blue-700 hover:to-indigo-700 transition-all duration-200 shadow-md hover:shadow-lg font-semibold w-full sm:w-auto transform hover:scale-105">
                + Add Restaurant
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

    @if ($createdKitchenUser)
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 sm:p-6 mb-6">
            <div class="flex items-center mb-4">
                <svg class="w-6 h-6 text-blue-600 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <h3 class="text-lg font-semibold text-blue-800">Kitchen User Created Successfully!</h3>
            </div>
            <div class="bg-white rounded-lg p-4 border border-blue-200">
                <h4 class="font-medium text-gray-900 mb-3">Kitchen Login Credentials for {{ $createdKitchenUser['restaurant_name'] }}:</h4>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 text-sm">
                    <div>
                        <span class="font-medium text-gray-700">Name:</span>
                        <p class="text-gray-900 font-mono bg-gray-50 px-2 py-1 rounded break-all">{{ $createdKitchenUser['name'] }}</p>
                    </div>
                    <div>
                        <span class="font-medium text-gray-700">Email:</span>
                        <p class="text-gray-900 font-mono bg-gray-50 px-2 py-1 rounded break-all">{{ $createdKitchenUser['email'] }}</p>
                    </div>
                    <div>
                        <span class="font-medium text-gray-700">Password:</span>
                        <p class="text-gray-900 font-mono bg-gray-50 px-2 py-1 rounded">{{ $createdKitchenUser['password'] }}</p>
                    </div>
                </div>
                <div class="mt-4 p-3 bg-yellow-50 border border-yellow-200 rounded">
                    <p class="text-sm text-yellow-800">
                        <strong>Important:</strong> Please save these credentials securely. The kitchen staff will need these to access the kitchen portal.
                    </p>
                </div>
            </div>
        </div>
    @endif

    @if ($showForm)
        <div class="bg-white p-4 sm:p-6 rounded-xl shadow-lg border border-gray-200 mb-6">
            <h2 class="text-xl font-bold text-gray-900 mb-4 pb-3 border-b border-gray-200">
                {{ $editingId ? 'Edit Restaurant' : 'Add Restaurant' }}
            </h2>
            
            <form wire:submit.prevent="save" class="space-y-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Name</label>
                    <input type="text" wire:model="name" class="mt-1 block w-full rounded-lg border-2 border-gray-300 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 px-4 py-2 text-gray-900 bg-white">
                    @error('name') <span class="text-red-600 text-sm font-medium mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Description</label>
                    <textarea wire:model="description" rows="3" class="mt-1 block w-full rounded-lg border-2 border-gray-300 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 px-4 py-2 text-gray-900 bg-white"></textarea>
                    @error('description') <span class="text-red-600 text-sm font-medium mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Phone</label>
                        <input type="text" wire:model="phone" class="mt-1 block w-full rounded-lg border-2 border-gray-300 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 px-4 py-2 text-gray-900 bg-white">
                        @error('phone') <span class="text-red-600 text-sm font-medium mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Email</label>
                        <input type="email" wire:model="email" class="mt-1 block w-full rounded-lg border-2 border-gray-300 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 px-4 py-2 text-gray-900 bg-white">
                        @error('email') <span class="text-red-600 text-sm font-medium mt-1 block">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Address</label>
                    <textarea wire:model="address" rows="2" class="mt-1 block w-full rounded-lg border-2 border-gray-300 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 px-4 py-2 text-gray-900 bg-white"></textarea>
                    @error('address') <span class="text-red-600 text-sm font-medium mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="flex items-center">
                        <input type="checkbox" wire:model="is_active" class="rounded border-gray-300">
                        <span class="ml-2 text-sm text-gray-700">Active</span>
                    </label>
                </div>

                <div class="flex space-x-3 pt-4">
                    <button type="submit" class="bg-gradient-to-r from-blue-600 to-indigo-600 text-white px-6 py-2.5 rounded-lg hover:from-blue-700 hover:to-indigo-700 transition-all duration-200 shadow-md hover:shadow-lg font-semibold">
                        {{ $editingId ? 'Update' : 'Create' }}
                    </button>
                    <button type="button" wire:click="cancel" class="bg-gray-500 text-white px-6 py-2.5 rounded-lg hover:bg-gray-600 transition-all duration-200 shadow-md hover:shadow-lg font-semibold">
                        Cancel
                    </button>
                </div>
            </form>
        </div>
    @endif

    <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gradient-to-r from-gray-100 to-gray-50">
                <tr>
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Name</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Contact</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Kitchen User</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach ($restaurants as $restaurant)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap hover:bg-gray-50 transition-colors">
                            <div>
                                <div class="text-sm font-bold text-gray-900">{{ $restaurant->name }}</div>
                                <div class="text-sm text-gray-600 font-medium">{{ Str::limit($restaurant->description, 50) }}</div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm hover:bg-gray-50 transition-colors">
                            <div class="font-semibold text-gray-900">{{ $restaurant->phone }}</div>
                            <div class="text-gray-700 font-medium">{{ $restaurant->email }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm hover:bg-gray-50 transition-colors">
                            @php
                                $kitchenUser = $restaurant->users->first();
                            @endphp
                            @if($kitchenUser)
                                <div class="text-sm">
                                    <div class="font-bold text-gray-900">{{ $kitchenUser->name }}</div>
                                    <div class="text-gray-700 font-medium">{{ $kitchenUser->email }}</div>
                                </div>
                            @else
                                <span class="text-red-600 text-sm font-semibold">No kitchen user</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap hover:bg-gray-50 transition-colors">
                            <span class="px-3 py-1.5 inline-flex text-xs leading-5 font-bold rounded-lg border {{ $restaurant->is_active ? 'bg-green-100 text-green-800 border-green-300' : 'bg-red-100 text-red-800 border-red-300' }}">
                                {{ $restaurant->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium hover:bg-gray-50 transition-colors">
                            <button wire:click="edit({{ $restaurant->id }})" class="text-indigo-700 hover:text-indigo-900 font-semibold mr-3 hover:underline">Edit</button>
                            @if(!$restaurant->users->first())
                                <button wire:click="createKitchenUserForRestaurant({{ $restaurant->id }})" class="text-green-700 hover:text-green-900 font-semibold mr-3 hover:underline">Add Kitchen User</button>
                            @endif
                            <button wire:click="delete({{ $restaurant->id }})" onclick="return confirm('Are you sure?')" class="text-red-700 hover:text-red-900 font-semibold hover:underline">Delete</button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        
        <div class="px-6 py-3">
            {{ $restaurants->links() }}
        </div>
    </div>
</div>