@props([
    'type' => 'button',
    'variant' => 'primary',
    'size' => 'md',
    'iconOnly' => false
])

@php
    $baseClasses = 'inline-flex items-center justify-center font-medium rounded-lg focus:outline-none transition-all duration-200';
    
    $variants = [
        'primary' => 'bg-emerald-500 text-white hover:bg-emerald-600 focus:ring-2 focus:ring-emerald-400',
        'secondary' => 'bg-gray-200 text-gray-700 hover:bg-gray-300 focus:ring-2 focus:ring-gray-400',
        'danger' => 'bg-red-500 text-white hover:bg-red-600 focus:ring-2 focus:ring-red-400',
    ];
    
    $sizes = [
        'sm' => 'px-3 py-1.5 text-sm',
        'md' => 'px-4 py-2 text-base',
        'lg' => 'px-6 py-2.5 text-lg'
    ];
    
    $classes = $baseClasses . ' ' . $variants[$variant] . ' ' . $sizes[$size];
    
    // Parse slot content to check if it contains an icon
    $hasIcon = false;
@endphp

<button 
    type="{{ $type }}"
    {{ $attributes->merge(['class' => $classes]) }}
>
    {{-- Render slot content with responsive text handling --}}
    <span class="button-content">
        {{ $slot }}
    </span>
</button>

<style>
    /* Automatically hide text in buttons on mobile, keep icons visible */
    @media (max-width: 767px) {
        .button-content > i {
            margin-right: 0 !important;
        }
        
        .button-content > *:not(i) {
            display: none;
        }
        
        /* Exception for buttons that only contain text (no icons) */
        .button-content:not(:has(i)) > * {
            display: inline;
        }
    }
</style> 