<div class="bg-white p-6 rounded-lg border-2 border-teal-100">
    <h3 class="text-lg font-medium text-teal-700 mb-4 flex items-center gap-2">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
        </svg>
        Hasil Kuisioner
    </h3>
    <div class="bg-gray-50 p-4 rounded-lg">
        <dl class="space-y-3">
            <div class="flex justify-between">
                <dt class="text-sm font-medium text-gray-700">Status Kuisioner</dt>
                <dd class="text-sm">
                    <span class="px-2 py-1 rounded-full text-xs font-medium {{ $santri->status_kuisioner == 'selesai' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                        {{ $santri->status_kuisioner == 'selesai' ? 'Selesai' : 'Belum Selesai' }}
                    </span>
                </dd>
            </div>
        </dl>
    </div>
</div> 