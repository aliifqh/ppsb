@props([
    'title',
    'description',
    'icon' => 'fas fa-inbox'
])

<div class="text-center py-12">
    <i class="{{ $icon }} text-4xl text-gray-400 mb-4"></i>
    <h3 class="mt-2 text-sm font-medium text-gray-900">{{ $title }}</h3>
    <p class="mt-1 text-sm text-gray-500">{{ $description }}</p>
    @if(isset($action))
    <div class="mt-6">
        {{ $action }}
    </div>
    @endif
</div> 