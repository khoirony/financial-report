@props([
    'icon' => null,
    'centered' => false,
])

<th
    {{
        $attributes->merge([
            'class' => 'px-9 py-3 text-center text-xs font-medium text-gray-500 uppercase',
            'scope' => 'col'
        ])
    }}
>
    <div class="flex gap-[10px] items-center {{ $centered ? 'justify-center' : '' }}">
        {{ $slot }}
        @if ($icon != '')
            <img src="{{ asset('img/icon/'.$icon) }}" class="w-4 h-4">
        @endif
    </div>
</th>
