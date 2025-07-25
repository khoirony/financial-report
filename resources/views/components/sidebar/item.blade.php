@props([
    'route' => 'home',
    'active' => '',
    'title' => 'Home',
    'menuName' => '',
])

<a wire:navigate href="{{ route($route) }}"
    x-data="{ hovered: false, keepShowLabel: @js($attributes->has('keep-show-label'))}" wire:navigate href="{{ route($route) }} }"
    class="{{($menuName == $active) ? 'bg-gray-200 font-medium' : 'font-normal'}} flex items-center py-2 px-2.5 text-sm hover:bg-gray-200 rounded-lg"
    aria-selected="false">
    <div
        x-on:mouseenter="hovered = true"
        x-on:mouseleave="hovered = false"
        class="relative flex"
    >
        <span class="text-claret">
            {{ $slot }}
        </span>

        {{-- Tooltip --}}
        <div
            x-show="!show && hovered"
            x-cloak
            x-ref="tooltip"
            class="fixed z-50 left-[52px] p-1 bg-black text-white text-sm rounded shadow-lg"
        >
            {{ $title }}
        </div>
    </div>
    <span class="ml-3" x-show="show || keepShowLabel">{{ $title }}</span>
</a>
