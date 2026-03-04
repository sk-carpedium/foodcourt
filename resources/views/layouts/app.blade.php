{{-- wrapper so Livewire and other code referencing layouts.app work by delegating to the component --}}
<x-layouts.app>
    {{ $slot }}
</x-layouts.app>
