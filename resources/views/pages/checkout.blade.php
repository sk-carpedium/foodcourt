<x-layouts.guest :title="'Checkout - ' . $restaurant->name">
    <livewire:customer.checkout :restaurant-slug="$restaurant->slug" />
</x-layouts.guest>