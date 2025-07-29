<div class="mb-6 flex flex-col items-center">
    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $santri->status_pendaftaran == 'diterima' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
        <svg class="mr-1.5 h-2 w-2 {{ $santri->status_pendaftaran == 'diterima' ? 'text-green-400' : 'text-yellow-400' }}" fill="currentColor" viewBox="0 0 8 8">
            <circle cx="4" cy="4" r="3" />
        </svg>
        {{ ucfirst($santri->status_pendaftaran) }}
    </span>
    <p class="mt-2 text-sm text-gray-600 text-center">
        @if($santri->status_pendaftaran == 'diterima')
            Selamat! Anda telah diterima di Pondok Pesantren
        @else
            Status pendaftaran Anda sedang diproses
        @endif
    </p>
</div> 