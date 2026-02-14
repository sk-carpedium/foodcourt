<x-layouts.guest :title="$restaurant->name . ' - Order Online'">
    <livewire:customer.restaurant-menu :restaurant-slug="$restaurant->slug" />
</x-layouts.guest>