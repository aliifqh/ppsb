@if(session()->has('success'))
    <div class="mb-4">
        <x-admin.alert type="success" :dismissible="true">
            {{ session('success') }}
        </x-admin.alert>
    </div>
@endif

@if(session()->has('error'))
    <div class="mb-4">
        <x-admin.alert type="error" :dismissible="true">
            {{ session('error') }}
        </x-admin.alert>
    </div>
@endif

@if(session()->has('warning'))
    <div class="mb-4">
        <x-admin.alert type="warning" :dismissible="true">
            {{ session('warning') }}
        </x-admin.alert>
    </div>
@endif

@if(session()->has('info'))
    <div class="mb-4">
        <x-admin.alert type="info" :dismissible="true">
            {{ session('info') }}
        </x-admin.alert>
    </div>
@endif 