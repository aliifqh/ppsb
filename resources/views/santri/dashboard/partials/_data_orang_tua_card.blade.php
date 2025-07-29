<div class="bg-white p-6 rounded-lg border-2 border-teal-100">
    <h3 class="text-lg font-medium text-teal-700 mb-4 flex items-center gap-2">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
        </svg>
        Data Orang Tua
    </h3>
    <div class="bg-gray-50 p-4 rounded-lg">
        <dl class="space-y-3">
            <div class="flex justify-between">
                <dt class="text-sm font-medium text-gray-700">Nama Ayah</dt>
                <dd class="text-sm text-teal-700">{{ $santri->orangTua->nama_ayah }}</dd>
            </div>
            <div class="flex justify-between">
                <dt class="text-sm font-medium text-gray-700">Nama Ibu</dt>
                <dd class="text-sm text-teal-700">{{ $santri->orangTua->nama_ibu }}</dd>
            </div>
            <div class="flex justify-between">
                <dt class="text-sm font-medium text-gray-700">No. WA Ayah</dt>
                <dd class="text-sm text-teal-700">{{ $santri->orangTua->wa_ayah }}</dd>
            </div>
            <div class="flex justify-between">
                <dt class="text-sm font-medium text-gray-700">No. WA Ibu</dt>
                <dd class="text-sm text-teal-700">{{ $santri->orangTua->wa_ibu }}</dd>
            </div>
            <div class="flex flex-col">
                <dt class="text-sm font-medium text-gray-700 mb-2">Alamat Lengkap</dt>
                <dd class="text-sm text-teal-700 bg-gray-50 p-3 rounded-lg">
                    <div class="space-y-1">
                        <p>{{ $santri->orangTua->alamat }}</p>
                        <p>Desa/Kelurahan: {{ $santri->orangTua->desa->nama ?? '-' }}</p>
                        <p>Kecamatan: {{ $santri->orangTua->kecamatan->nama ?? '-' }}</p>
                        <p>Kabupaten/Kota: {{ $santri->orangTua->kabupaten->nama ?? '-' }}</p>
                        <p>Provinsi: {{ $santri->orangTua->provinsi->nama ?? '-' }}</p>
                        <p>Kode Pos: {{ $santri->orangTua->kode_pos ?? '-' }}</p>
                    </div>
                </dd>
            </div>
        </dl>
    </div>
</div> 