<div class="bg-white p-4 rounded-lg border-2 border-teal-100">
    <h3 class="text-sm font-medium text-teal-700 mb-2">Kelengkapan Formulir</h3>
    <div class="space-y-2">
        <div>
            <div class="flex justify-between text-xs mb-1">
                <span>Data Pribadi</span>
                <span>{{ $santri->nama && $santri->nisn ? '100%' : '0%' }}</span>
            </div>
            <div class="w-full bg-gray-200 rounded-full h-2">
                <div class="bg-teal-600 h-2 rounded-full" style="width: {{ $santri->nama && $santri->nisn ? '100%' : '0%' }}"></div>
            </div>
        </div>
        <div>
            <div class="flex justify-between text-xs mb-1">
                <span>Data Orang Tua</span>
                <span>{{ $santri->orangTua ? '100%' : '0%' }}</span>
            </div>
            <div class="w-full bg-gray-200 rounded-full h-2">
                <div class="bg-teal-600 h-2 rounded-full" style="width: {{ $santri->orangTua ? '100%' : '0%' }}"></div>
            </div>
        </div>
    </div>
</div> 