<flux:navlist variant="outline">
    <flux:navlist.group :heading="__('Platform')" class="grid">
        <flux:navlist.item icon="home" :href="route('dashboard')" :current="request()->routeIs('dashboard')" wire:navigate>{{ __('Dashboard') }}</flux:navlist.item>
    </flux:navlist.group>

    @can('view restaurants')
    <flux:navlist.group :heading="__('Restaurant Management')" class="grid">
        <flux:navlist.item icon="chart-bar" :href="route('admin.dashboard')" :current="request()->routeIs('admin.dashboard')" wire:navigate>{{ __('Admin Dashboard') }}</flux:navlist.item>
        <flux:navlist.item icon="building-office" :href="route('admin.restaurant-menu-manager')" :current="request()->routeIs('admin.restaurant-menu-manager')" wire:navigate>{{ __('Restaurant Management') }}</flux:navlist.item>
        <flux:navlist.item icon="clipboard-document-list" :href="route('admin.orders')" :current="request()->routeIs('admin.orders')" wire:navigate>
            <div class="flex items-center justify-between w-full">
                <span>{{ __('Order Management') }}</span>
                @if($totalOrders > 0)
                    <span class="bg-blue-500 text-white text-xs rounded-full px-2 py-0.5 ml-2">
                        {{ $totalOrders }}
                    </span>
                @endif
            </div>
        </flux:navlist.item>
        <flux:navlist.item icon="document-text" :href="route('admin.invoices')" :current="request()->routeIs('admin.invoices')" wire:navigate>{{ __('Invoices') }}</flux:navlist.item>
    </flux:navlist.group>
    @endcan

    @can('take orders')
    <flux:navlist.group :heading="__('Waiter Portal')" class="grid">
        <flux:navlist.item icon="clipboard-document-check" :href="route('waiter.orders')" :current="request()->routeIs('waiter.orders')" wire:navigate>
            <div class="flex items-center justify-between w-full">
                <span>{{ __('Order Management') }}</span>
                @if($pendingOrders > 0)
                    <span class="bg-red-500 text-white text-xs rounded-full px-2 py-0.5 ml-2">
                        {{ $pendingOrders }}
                    </span>
                @endif
            </div>
        </flux:navlist.item>
    </flux:navlist.group>
    @endcan

    @can('view kitchen orders')
    <flux:navlist.group :heading="__('Kitchen Portal')" class="grid">
        <flux:navlist.item icon="fire" :href="route('kitchen.orders')" :current="request()->routeIs('kitchen.orders')" wire:navigate>
            <div class="flex items-center justify-between w-full">
                <span>{{ __('Order Queue') }}</span>
                @if($kitchenOrders > 0)
                    <span class="bg-orange-500 text-white text-xs rounded-full px-2 py-0.5 ml-2">
                        {{ $kitchenOrders }}
                    </span>
                @endif
            </div>
        </flux:navlist.item>
    </flux:navlist.group>
    @endcan

    <flux:navlist.group :heading="__('Customer Portal')" class="grid">
        <flux:navlist.item icon="shopping-bag" :href="route('menu', 'the-gourmet-kitchen')" wire:navigate>{{ __('Browse Menu') }}</flux:navlist.item>
        <flux:navlist.item icon="globe-alt" :href="route('home')" wire:navigate>{{ __('Public Site') }}</flux:navlist.item>
    </flux:navlist.group>

    @hasrole('super-admin|admin')
    <flux:navlist.group :heading="__('User Management')" class="grid">
        <flux:navlist.item icon="users" :href="route('users.index')" :current="request()->routeIs('users.index')" wire:navigate>{{ __('Users') }}</flux:navlist.item>
    </flux:navlist.group>
    @endhasrole

    <!-- Quick Stats Section -->
    @auth
    <flux:navlist.group :heading="__('Quick Stats')" class="grid">
        <livewire:components.sidebar-stats />
    </flux:navlist.group>
    @endauth
</flux:navlist>