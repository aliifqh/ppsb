<div class="bg-white p-4 rounded-lg border-2 border-teal-100 hover:border-teal-200 transition-colors">
    <div class="flex justify-between items-center mb-3">
        <h3 class="text-sm font-medium text-teal-700">Progress Ujian</h3>
        <a href="{{ route('santri.ujian.index', ['nomor_pendaftaran' => $santri->nomor_pendaftaran]) }}"
           class="text-xs text-teal-600 hover:text-teal-800 font-medium">
            Lihat Detail →
        </a>
    </div>
    <div class="space-y-2">
        <div>
            <div class="flex justify-between text-xs mb-1">
                <span>Tes Lisan</span>
                <span>{{ $santri->status_tes_lisan == 'selesai' ? '100%' : '0%' }}</span>
            </div>
            <div class="w-full bg-gray-200 rounded-full h-2">
                <div class="bg-teal-600 h-2 rounded-full transition-all duration-300" style="width: {{ $santri->status_tes_lisan == 'selesai' ? '100%' : '0%' }}"></div>
            </div>
        </div>
        <div>
            <div class="flex justify-between text-xs mb-1">
                <span>Tes Tulis</span>
                <span>{{ $santri->status_tes_tulis == 'selesai' ? '100%' : '0%' }}</span>
            </div>
            <div class="w-full bg-gray-200 rounded-full h-2">
                <div class="bg-teal-600 h-2 rounded-full transition-all duration-300" style="width: {{ $santri->status_tes_tulis == 'selesai' ? '100%' : '0%' }}"></div>
            </div>
        </div>
        <div>
            <div class="flex justify-between text-xs mb-1">
                <span>Tes Psikotes</span>
                <span>{{ $santri->status_tes_psikotes == 'selesai' ? '100%' : '0%' }}</span>
            </div>
            <div class="w-full bg-gray-200 rounded-full h-2">
                <div class="bg-teal-600 h-2 rounded-full transition-all duration-300" style="width: {{ $santri->status_tes_psikotes == 'selesai' ? '100%' : '0%' }}"></div>
            </div>
        </div>
        <div>
            <div class="flex justify-between text-xs mb-1">
                <span>Tes Kesehatan</span>
                <span>{{ $santri->status_tes_kesehatan == 'selesai' ? '100%' : '0%' }}</span>
            </div>
            <div class="w-full bg-gray-200 rounded-full h-2">
                <div class="bg-teal-600 h-2 rounded-full transition-all duration-300" style="width: {{ $santri->status_tes_kesehatan == 'selesai' ? '100%' : '0%' }}"></div>
            </div>
        </div>
    </div>
</div>
