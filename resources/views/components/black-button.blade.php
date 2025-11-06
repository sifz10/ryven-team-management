@props([
    'type' => 'button',
    'variant' => 'solid', // solid, outline
    'size' => 'md', // sm, md, lg
    'icon' => null,
    'iconPosition' => 'left', // left, right
    'href' => null, // If provided, renders as <a> tag instead of <button>
])

@php
    $baseClasses = 'inline-flex items-center justify-center gap-2 font-medium transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-900';

    $sizeClasses = [
        'sm' => 'px-3 py-1.5 text-sm rounded-full',
        'md' => 'px-5 py-2.5 text-base rounded-full',
        'lg' => 'px-6 py-3 text-lg rounded-full',
    ];

    $variantClasses = [
        'solid' => 'bg-black text-white hover:bg-gray-800 focus:ring-black dark:bg-black dark:text-white dark:hover:bg-gray-900 dark:focus:ring-black',
        'outline' => 'border-2 border-black text-black hover:bg-black hover:text-white focus:ring-black dark:border-white dark:text-white dark:hover:bg-white dark:hover:text-black dark:focus:ring-white',
    ];

    $classes = $baseClasses . ' ' . $sizeClasses[$size] . ' ' . $variantClasses[$variant];
@endphp

@if($href)
    <a
        href="{{ $href }}"
        {{ $attributes->merge(['class' => $classes]) }}>

        @if($icon && $iconPosition === 'left')
            {!! $icon !!}
        @endif

        {{ $slot }}

        @if($icon && $iconPosition === 'right')
            {!! $icon !!}
        @endif
    </a>
@else
    <button
        type="{{ $type }}"
        {{ $attributes->merge(['class' => $classes]) }}>

        @if($icon && $iconPosition === 'left')
            {!! $icon !!}
        @endif

        {{ $slot }}

        @if($icon && $iconPosition === 'right')
            {!! $icon !!}
        @endif
    </button>
@endif
