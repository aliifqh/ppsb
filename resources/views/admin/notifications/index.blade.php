@extends('layouts.admin')

@section('title', 'Notifikasi')

@section('content')
<div class="container px-6 mx-auto grid">
    <h2 class="my-6 text-2xl font-semibold text-gray-700">
        Notifikasi
    </h2>

    <div class="w-full overflow-hidden rounded-lg shadow-xs mb-8">
        <div class="w-full overflow-x-auto">
            <div class="bg-white shadow-md rounded-lg overflow-hidden">
                <div class="px-4 py-5 border-b border-gray-200 sm:px-6 bg-gray-50">
                    <div class="flex justify-between items-center flex-wrap">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">
                            Daftar Notifikasi
                        </h3>
                        <div class="flex items-center">
                            <a href="{{ route('admin.notifications.index', ['show_all' => !$showAll]) }}" class="text-sm px-3 py-1 rounded-md bg-emerald-100 text-emerald-700 hover:bg-emerald-200 transition-colors">
                                {{ $showAll ? 'Tampilkan 1 minggu terakhir' : 'Tampilkan semua' }}
                            </a>
                        </div>
                    </div>
                </div>

                <div class="bg-white">
                    @if($notifications->isEmpty())
                        <div class="text-center py-10">
                            <i class="fas fa-bell-slash text-gray-400 text-4xl mb-3"></i>
                            <p class="text-gray-500">Tidak ada notifikasi</p>
                        </div>
                    @else
                        <div class="divide-y divide-gray-200">
                            @foreach($notifications as $notification)
                                <div class="p-4 sm:px-6 hover:bg-gray-50">
                                    <div class="flex items-start space-x-4">
                                        <div class="flex-shrink-0">
                                            @if(str_contains($notification->description, 'mengubah'))
                                                <span class="inline-flex items-center justify-center h-10 w-10 rounded-full bg-yellow-100">
                                                    <i class="fas fa-edit text-yellow-600"></i>
                                                </span>
                                            @elseif(str_contains($notification->description, 'keluar') || str_contains($notification->description, 'login') || str_contains($notification->description, 'masuk'))
                                                <span class="inline-flex items-center justify-center h-10 w-10 rounded-full bg-blue-100">
                                                    <i class="fas fa-sign-in-alt text-blue-600"></i>
                                                </span>
                                            @elseif(str_contains($notification->description, 'tambah') || str_contains($notification->description, 'buat'))
                                                <span class="inline-flex items-center justify-center h-10 w-10 rounded-full bg-green-100">
                                                    <i class="fas fa-plus text-green-600"></i>
                                                </span>
                                            @elseif(str_contains($notification->description, 'hapus'))
                                                <span class="inline-flex items-center justify-center h-10 w-10 rounded-full bg-red-100">
                                                    <i class="fas fa-trash text-red-600"></i>
                                                </span>
                                            @else
                                                <span class="inline-flex items-center justify-center h-10 w-10 rounded-full bg-gray-100">
                                                    <i class="fas fa-info-circle text-gray-600"></i>
                                                </span>
                                            @endif
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-sm font-medium text-gray-900">
                                                <span class="font-medium">{{ $notification->user->name }}</span>
                                                {{ $notification->description }}
                                            </p>
                                            <p class="text-xs text-gray-500 mt-1">
                                                {{ $notification->created_at->diffForHumans() }}
                                                <span class="mx-1">•</span>
                                                {{ $notification->created_at->format('d M Y, H:i') }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="px-4 py-3 bg-gray-50 border-t border-gray-200 sm:px-6">
                            {{ $notifications->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
