<div class="bg-white p-6 rounded-lg border-2 border-teal-100">
    <h3 class="text-lg font-medium text-teal-700 mb-4 flex items-center gap-2">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
        </svg>
        Data Sekolah
    </h3>
    <div class="bg-gray-50 p-4 rounded-lg">
        <dl class="space-y-3">
            <div class="flex justify-between">
                <dt class="text-sm font-medium text-gray-700">Asal Sekolah</dt>
                <dd class="text-sm text-teal-700">{{ $santri->asal_sekolah }}</dd>
            </div>
            <div class="flex justify-between">
                <dt class="text-sm font-medium text-gray-700">Unit yang Dipilih</dt>
                <dd class="text-sm text-teal-700">{{ strtoupper($santri->unit) }}</dd>
            </div>
            <div class="flex justify-between">
                <dt class="text-sm font-medium text-gray-700">Tahun Ajaran</dt>
                <dd class="text-sm text-teal-700">{{ $santri->tahun_ajaran }}</dd>
            </div>
        </dl>
    </div>
</div> 