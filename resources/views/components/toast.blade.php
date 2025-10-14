@props(['type' => 'info', 'message' => ''])

@php
    $colors = [
        'success' => 'bg-green-600',
        'error' => 'bg-red-600',
        'info' => 'bg-black',
    ];
    $bg = $colors[$type] ?? $colors['info'];
@endphp

<div x-data="{ show: true }" x-init="setTimeout(() => show = false, 4000)" x-show="show"
     x-transition:enter="transition ease-out duration-200"
     x-transition:enter-start="opacity-0 translate-y-2"
     x-transition:enter-end="opacity-100 translate-y-0"
     x-transition:leave="transition ease-in duration-150"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0"
     class="{{ $bg }} text-white rounded-full shadow px-4 py-2 flex items-center gap-3">
    <span class="inline-block w-2 h-2 rounded-full bg-white/70"></span>
    <span class="text-sm">{{ $message }}</span>
    <button type="button" class="ms-2 text-white/80 hover:text-white" @click="show = false">×</button>
    <span class="sr-only">Toast</span>
</div>


