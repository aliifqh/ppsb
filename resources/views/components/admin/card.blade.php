@props(['title' => null])

<div {{ $attributes->merge(['class' => 'bg-white rounded-lg shadow-[0_2px_15px_-3px_rgba(0,0,0,0.07),0_10px_20px_-2px_rgba(0,0,0,0.04)] hover:shadow-[0_8px_17px_2px_rgba(0,0,0,0.14),0_3px_14px_2px_rgba(0,0,0,0.12),0_5px_5px_-3px_rgba(0,0,0,0.2)] transition-shadow duration-300 ease-in-out p-6']) }}>
    @if($title)
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-xl font-semibold text-gray-800">{{ $title }}</h2>
    </div>
    @endif
    
    {{ $slot }}
</div> 