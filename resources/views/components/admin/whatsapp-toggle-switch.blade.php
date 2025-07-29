@props([
    'checked' => false,
    'name' => '',
    'id' => '',
    'label' => null,
])

<div class="flex items-center">
    @if($label)
    <span class="mr-2 text-sm text-gray-600">{{ $label }}:</span>
    @endif
    <label class="inline-flex items-center cursor-pointer">
        <input 
            type="checkbox" 
            class="sr-only peer" 
            name="{{ $name }}"
            id="{{ $id ?: $name }}"
            {{ $checked ? 'checked' : '' }}
            {{ $attributes }}
        >
        <div class="relative w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-emerald-300 rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-emerald-600"></div>
    </label>
</div> 