<div class="bg-white p-6 rounded-lg border-2 border-teal-100">
    <h3 class="text-lg font-medium text-teal-700 mb-4 flex items-center justify-between">
        <span class="flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
            </svg>
            Data Pribadi
        </span>
        <a href="{{ route('santri.data', ['nomor_pendaftaran' => session('nomor_pendaftaran')]) }}" class="inline-flex items-center px-3 py-1 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-teal-600 hover:bg-teal-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-500">
            Edit
        </a>
    </h3>
    <div class="bg-gray-50 p-4 rounded-lg">
        <dl class="space-y-3">
            <div class="flex justify-between">
                <dt class="text-sm font-medium text-gray-700">Nama Lengkap</dt>
                <dd class="text-sm text-teal-700">{{ $santri->nama }}</dd>
            </div>
            <div class="flex justify-between">
                <dt class="text-sm font-medium text-gray-700">NISN</dt>
                <dd class="text-sm text-teal-700">{{ $santri->nisn }}</dd>
            </div>
            <div class="flex justify-between">
                <dt class="text-sm font-medium text-gray-700">Tempat Lahir</dt>
                <dd class="text-sm text-teal-700">{{ $santri->tempat_lahir }}</dd>
            </div>
            <div class="flex justify-between">
                <dt class="text-sm font-medium text-gray-700">Tanggal Lahir</dt>
                <dd class="text-sm text-teal-700">{{ $santri->tanggal_lahir }}</dd>
            </div>
            <div class="flex justify-between">
                <dt class="text-sm font-medium text-gray-700">Jenis Kelamin</dt>
                <dd class="text-sm text-teal-700">{{ $santri->jenis_kelamin }}</dd>
            </div>
        </dl>
    </div>
</div> 