@props([
    'type' => 'default',
    'size' => 'sm'
])

@php
    $types = [
        'default' => 'bg-gray-100 text-gray-800',
        'primary' => 'bg-emerald-100 text-emerald-800',
        'success' => 'bg-green-100 text-green-800',
        'warning' => 'bg-yellow-100 text-yellow-800',
        'danger' => 'bg-red-100 text-red-800',
        'info' => 'bg-blue-100 text-blue-800',
        'pendaftaran' => 'bg-blue-100 text-blue-800',
        'pembayaran' => 'bg-green-100 text-green-800',
        'ujian' => 'bg-purple-100 text-purple-800',
        'hasil' => 'bg-yellow-100 text-yellow-800',
        'terkirim' => 'bg-green-100 text-green-800',
        'gagal' => 'bg-red-100 text-red-800',
        'pending' => 'bg-yellow-100 text-yellow-800',
    ];
    
    $sizes = [
        'xs' => 'px-2 py-0.5 text-xs',
        'sm' => 'px-2 py-1 text-xs',
        'md' => 'px-2.5 py-1.5 text-sm',
    ];
    
    $baseClass = 'inline-flex rounded font-semibold';
    $classes = $baseClass . ' ' . $types[$type] . ' ' . $sizes[$size];
@endphp

<span {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</span> 