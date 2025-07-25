@props([
    'title' => null,
    'or' => null,
    'button' => null,
    'description' => null,
    'maxFile' => null,
    'classTitle' => '',
    'classOr' => '',
    'classButton' => '',
    'classDescription' => '',
])

<div
    {{ $attributes->merge([
        'class' => 'w-full flex flex-col items-center rounded-lg border border-bright-gray text-quick-silver py-12 space-y-4'
    ])
}}>
    {{ $slot }}
    <p class="text-center text-xl text-gray-900 font-bold {{ $classTitle }}">{{ $title }}</p>
    <p class="text-center text-sm font-medium text-prediction {{ $classOr }}">{{ $or }}</p>
    <p class="text-center text-lg text-white bg-gold px-8 py-2 rounded-md {{ $classButton }}">{{ $button }}</p>
    <p class="text-center text-sm font-medium text-prediction {{ $classDescription }}">
        {{ $description }} <br>
        {{ $maxFile }}
    </p>
</div>
