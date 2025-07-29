@props(['action'])

<div class="bg-white rounded-lg shadow-sm p-4 sm:p-6 mb-4 sm:mb-6">
    <form action="{{ $action }}" method="GET" class="space-y-4">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            {{ $slot }}
        </div>
        
        <div class="flex flex-col sm:flex-row justify-end space-y-2 sm:space-y-0 sm:space-x-2">
            <x-admin.button type="submit" variant="primary" class="w-full sm:w-auto">
                <i class="fas fa-filter mr-2"></i>
                Filter
            </x-admin.button>
            
            <x-admin.button type="button" variant="secondary" onclick="window.location.href='{{ $action }}'" class="w-full sm:w-auto">
                <i class="fas fa-undo mr-2"></i>
                Reset
            </x-admin.button>
        </div>
    </form>
</div> 