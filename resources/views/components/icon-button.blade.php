@props([
    'type' => 'button',
    'size' => 'md', // sm, md, lg
    'variant' => 'default', // default, black
])

@php
    $baseClasses = 'inline-flex items-center justify-center rounded-lg transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2';
    
    $sizeClasses = [
        'sm' => 'p-1.5 w-8 h-8',
        'md' => 'p-2 w-10 h-10',
        'lg' => 'p-3 w-12 h-12',
    ];
    
    $variantClasses = [
        'default' => 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 focus:ring-gray-500',
        'black' => 'bg-black text-white hover:bg-gray-800 focus:ring-black dark:bg-white dark:text-black dark:hover:bg-gray-200 dark:focus:ring-white',
    ];
    
    $classes = $baseClasses . ' ' . $sizeClasses[$size] . ' ' . $variantClasses[$variant];
@endphp

<button 
    type="{{ $type }}"
    {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</button>
