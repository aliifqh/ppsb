@props(['activities'])

<div x-data="{ isOpen: false }" class="relative">
    <!-- Debug info -->
    <span x-text="isOpen ? 'Dropdown Terbuka' : 'Dropdown Tertutup'" class="sr-only"></span>

    <!-- Notification Button -->
    <button @click="isOpen = !isOpen"
            type="button"
            aria-expanded="false"
            x-bind:aria-expanded="isOpen.toString()"
            class="relative p-2 text-gray-400 hover:text-gray-500 hover:bg-gray-100 rounded-full focus:outline-none focus:ring-2 focus:ring-emerald-500">
        <span class="sr-only">Lihat notifikasi</span>
        <i class="fas fa-bell"></i>
    </button>

    <!-- Dropdown menu -->
    <div x-show="isOpen"
         x-cloak
         @click.away="isOpen = false"
         @keydown.escape.window="isOpen = false"
         x-transition.opacity.duration.200ms
         class="absolute right-0 mt-2 w-80 bg-white rounded-lg shadow-lg py-1 z-50 border border-gray-100">

        <div class="px-4 py-2 border-b border-gray-100">
            <div class="flex justify-between items-center">
                <h3 class="text-sm font-semibold text-gray-900">Notifikasi</h3>
                <a href="{{ route('admin.notifications.index') }}" class="text-xs text-emerald-600 hover:text-emerald-800">
                    Lihat Semua
                </a>
            </div>
        </div>

        <div class="max-h-64 overflow-y-auto">
            @php
                $recentActivities = $activities->filter(function($activity) {
                    return $activity->created_at >= \Carbon\Carbon::now()->subWeek();
                })->take(5);
            @endphp

            @forelse($recentActivities as $activity)
                <div class="block px-4 py-3 hover:bg-gray-50">
                    <div class="flex items-start space-x-3">
                        <div class="flex-shrink-0">
                            @if(str_contains($activity->description, 'mengubah'))
                                <span class="inline-flex items-center justify-center h-8 w-8 rounded-full bg-yellow-100">
                                    <i class="fas fa-edit text-yellow-600"></i>
                                </span>
                            @elseif(str_contains($activity->description, 'keluar') || str_contains($activity->description, 'login') || str_contains($activity->description, 'masuk'))
                                <span class="inline-flex items-center justify-center h-8 w-8 rounded-full bg-blue-100">
                                    <i class="fas fa-sign-in-alt text-blue-600"></i>
                                </span>
                            @elseif(str_contains($activity->description, 'tambah') || str_contains($activity->description, 'buat'))
                                <span class="inline-flex items-center justify-center h-8 w-8 rounded-full bg-green-100">
                                    <i class="fas fa-plus text-green-600"></i>
                                </span>
                            @elseif(str_contains($activity->description, 'hapus'))
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
                                <span class="font-medium">{{ $activity->user->name }}</span>
                                {{ $activity->description }}
                            </p>
                            <p class="text-xs text-gray-500 mt-1">
                                {{ $activity->created_at->diffForHumans() }}
                            </p>
                        </div>
                    </div>
                </div>
            @empty
                <div class="px-4 py-6 text-center">
                    <i class="fas fa-bell-slash text-gray-400 text-2xl mb-2"></i>
                    <p class="text-sm text-gray-500">Tidak ada notifikasi</p>
                </div>
            @endforelse

            @if($activities->count() > $recentActivities->count())
                <div class="px-4 py-2 text-center border-t border-gray-100">
                    <a href="{{ route('admin.notifications.index', ['show_all' => 'true']) }}" class="text-xs text-emerald-600 hover:text-emerald-800">
                        Lihat {{ $activities->count() - $recentActivities->count() }} notifikasi lama lainnya
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
