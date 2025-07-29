@props(['notifications'])

<div x-data="{
    open: false
}"
class="relative">
    <button @click="open = !open"
            class="relative p-2 text-gray-400 hover:text-gray-500 focus:outline-none">
        <span class="sr-only">Notifikasi</span>
        <i class="fas fa-bell text-xl"></i>
    </button>

    <div x-show="open"
         @click.away="open = false"
         x-transition:enter="transition ease-out duration-100"
         x-transition:enter-start="transform opacity-0 scale-95"
         x-transition:enter-end="transform opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-75"
         x-transition:leave-start="transform opacity-100 scale-100"
         x-transition:leave-end="transform opacity-0 scale-95"
         class="absolute right-0 mt-2 w-80 bg-white rounded-md shadow-lg overflow-hidden z-50">

        <div class="p-3 border-b bg-gray-50 flex justify-between items-center">
            <div class="flex items-center space-x-2">
                <h3 class="text-sm font-semibold text-gray-700">Notifikasi</h3>
                <a href="{{ route('admin.notifications.index') }}" class="text-xs text-blue-600 hover:text-blue-800">
                    Lihat Semua
                </a>
            </div>
        </div>

        <div class="divide-y divide-gray-100 max-h-96 overflow-y-auto notification-list">
            @php
                $recentNotifications = $notifications->filter(function($notification) {
                    return $notification->created_at >= \Carbon\Carbon::now()->subWeek();
                })->take(5);
            @endphp

            @forelse($recentNotifications as $notification)
                <div class="p-4 hover:bg-gray-50 flex items-start space-x-3">
                    <div class="flex-shrink-0">
                        @if(str_contains($notification->description, 'mengubah'))
                            <span class="inline-flex items-center justify-center h-8 w-8 rounded-full bg-yellow-100">
                                <i class="fas fa-edit text-yellow-600"></i>
                            </span>
                        @elseif(str_contains($notification->description, 'keluar') || str_contains($notification->description, 'login') || str_contains($notification->description, 'masuk'))
                            <span class="inline-flex items-center justify-center h-8 w-8 rounded-full bg-blue-100">
                                <i class="fas fa-sign-in-alt text-blue-600"></i>
                            </span>
                        @elseif(str_contains($notification->description, 'tambah') || str_contains($notification->description, 'buat'))
                            <span class="inline-flex items-center justify-center h-8 w-8 rounded-full bg-green-100">
                                <i class="fas fa-plus text-green-600"></i>
                            </span>
                        @elseif(str_contains($notification->description, 'hapus'))
                            <span class="inline-flex items-center justify-center h-8 w-8 rounded-full bg-red-100">
                                <i class="fas fa-trash text-red-600"></i>
                            </span>
                        @else
                            <span class="inline-flex items-center justify-center h-8 w-8 rounded-full bg-gray-100">
                                <i class="fas fa-info-circle text-gray-600"></i>
                            </span>
                        @endif
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm text-gray-900">
                            <span class="font-medium">{{ $notification->user->name }}</span>
                            {{ $notification->description }}
                        </p>
                        <p class="text-xs text-gray-500 mt-1">
                            {{ $notification->created_at->diffForHumans() }}
                        </p>
                    </div>
                </div>
            @empty
                <div class="p-4 text-sm text-gray-500 text-center">
                    Tidak ada notifikasi
                </div>
            @endforelse

            @if($notifications->count() > $recentNotifications->count())
                <div class="px-4 py-2 text-center border-t border-gray-100">
                    <a href="{{ route('admin.notifications.index', ['show_all' => 'true']) }}" class="text-xs text-blue-600 hover:text-blue-800">
                        Lihat {{ $notifications->count() - $recentNotifications->count() }} notifikasi lama lainnya
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
