<!-- Step 4: Konfirmasi -->
<div x-show="currentStep === 4" class="space-y-6">
    <h2 class="text-2xl font-semibold text-teal-700">Konfirmasi Data</h2>

    <!-- Data Santri -->
    <div class="bg-white p-6 rounded-lg border-2 border-teal-100 mb-6">
        <h3 class="text-lg font-medium text-teal-700 mb-4 flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
            </svg>
            Data Santri
        </h3>
        <div class="space-y-3">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="p-4 bg-gray-50 rounded-lg border border-gray-200">
                    <span class="text-sm font-medium text-gray-700">Unit Pendaftaran:</span>
                    <p class="mt-1 text-teal-700" x-text="formData.unit === 'ppim' ? 'PPIM (Lulusan SD/MI)' : 'TKS (Lulusan SMP/MTs)'"></p>
                </div>
                <div class="p-4 bg-gray-50 rounded-lg border border-gray-200">
                    <span class="text-sm font-medium text-gray-700">Nama Lengkap:</span>
                <p class="mt-1 text-teal-700" x-text="formData.nama || '-'"></p>
            </div>
            <div class="p-4 bg-gray-50 rounded-lg border border-gray-200">
                <span class="text-sm font-medium text-gray-700">NISN:</span>
                <p class="mt-1 text-teal-700" x-text="formData.nisn || '-'"></p>
            </div>
                <div class="p-4 bg-gray-50 rounded-lg border border-gray-200">
                    <span class="text-sm font-medium text-gray-700">Jenis Kelamin:</span>
                    <p class="mt-1 text-teal-700" x-text="formData.jenis_kelamin || '-'"></p>
                </div>
            <div class="p-4 bg-gray-50 rounded-lg border border-gray-200">
                <span class="text-sm font-medium text-gray-700">Tempat Lahir:</span>
                <p class="mt-1 text-teal-700" x-text="formData.tempat_lahir || '-'"></p>
            </div>
            <div class="p-4 bg-gray-50 rounded-lg border border-gray-200">
                <span class="text-sm font-medium text-gray-700">Tanggal Lahir:</span>
                    <p class="mt-1 text-teal-700" x-text="formData.tanggal_lahir || '-'"></p>
            </div>
            <div class="p-4 bg-gray-50 rounded-lg border border-gray-200">
                    <span class="text-sm font-medium text-gray-700">Asal Sekolah:</span>
                    <p class="mt-1 text-teal-700" x-text="formData.asal_sekolah || '-'"></p>
            </div>
            <div class="p-4 bg-gray-50 rounded-lg border border-gray-200">
                    <span class="text-sm font-medium text-gray-700">Data Saudara:</span>
                    <p class="mt-1 text-teal-700">
                        Anak ke <span x-text="formData.anak_ke || '-'"></span> dari <span x-text="formData.jumlah_saudara || '-'"></span> bersaudara
                    </p>
            </div>
            </div>
        </div>
    </div>

    <!-- Data Orang Tua -->
    <div class="bg-white p-6 rounded-lg border-2 border-teal-100 mb-6">
        <h3 class="text-lg font-medium text-teal-700 mb-4 flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
            </svg>
            Data Orang Tua
        </h3>

        <!-- Data Ayah -->
        <div class="mb-6">
            <h4 class="font-medium text-gray-700 mb-4">Data Ayah:</h4>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="p-4 bg-gray-50 rounded-lg border border-gray-200">
                    <span class="text-sm font-medium text-gray-700">Nama:</span>
                    <p class="mt-1 text-teal-700" x-text="formData.nama_ayah || '-'"></p>
            </div>
            <div class="p-4 bg-gray-50 rounded-lg border border-gray-200">
                    <span class="text-sm font-medium text-gray-700">WhatsApp:</span>
                    <p class="mt-1 text-teal-700" x-text="formatWhatsApp(formData.wa_ayah) || '-'"></p>
                    <span x-show="waAyahOperator" class="text-sm text-teal-600" x-text="waAyahOperator"></span>
            </div>
            <div class="p-4 bg-gray-50 rounded-lg border border-gray-200">
                    <span class="text-sm font-medium text-gray-700">Pekerjaan:</span>
                    <p class="mt-1 text-teal-700" x-text="getPekerjaan('ayah')"></p>
            </div>
            <div class="p-4 bg-gray-50 rounded-lg border border-gray-200">
                    <span class="text-sm font-medium text-gray-700">Pendidikan:</span>
                    <p class="mt-1 text-teal-700" x-text="formData.pendidikan_ayah || '-'"></p>
            </div>
        </div>
    </div>

        <!-- Data Ibu -->
        <div>
            <h4 class="font-medium text-gray-700 mb-4">Data Ibu:</h4>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="p-4 bg-gray-50 rounded-lg border border-gray-200">
                <span class="text-sm font-medium text-gray-700">Nama:</span>
                    <p class="mt-1 text-teal-700" x-text="formData.nama_ibu || '-'"></p>
            </div>
            <div class="p-4 bg-gray-50 rounded-lg border border-gray-200">
                <span class="text-sm font-medium text-gray-700">WhatsApp:</span>
                    <p class="mt-1 text-teal-700" x-text="formatWhatsApp(formData.wa_ibu) || '-'"></p>
                    <span x-show="waIbuOperator" class="text-sm text-teal-600" x-text="waIbuOperator"></span>
                </div>
                <div class="p-4 bg-gray-50 rounded-lg border border-gray-200">
                    <span class="text-sm font-medium text-gray-700">Pekerjaan:</span>
                    <p class="mt-1 text-teal-700" x-text="getPekerjaan('ibu')"></p>
                </div>
                <div class="p-4 bg-gray-50 rounded-lg border border-gray-200">
                    <span class="text-sm font-medium text-gray-700">Pendidikan:</span>
                    <p class="mt-1 text-teal-700" x-text="formData.pendidikan_ibu || '-'"></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Data Alamat -->
    <div class="bg-white p-6 rounded-lg border-2 border-teal-100 mb-6">
        <h3 class="text-lg font-medium text-teal-700 mb-4 flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
            </svg>
            Data Alamat
        </h3>
        <div class="space-y-4">
            <div class="p-4 bg-gray-50 rounded-lg border border-gray-200">
                <span class="text-sm font-medium text-gray-700">Alamat Lengkap:</span>
                <p class="mt-1 text-teal-700" x-text="formData.alamat || '-'"></p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="p-4 bg-gray-50 rounded-lg border border-gray-200">
                    <span class="text-sm font-medium text-gray-700">Provinsi:</span>
                    <p class="mt-1 text-teal-700" x-text="(() => {
                        const prov = provinsiList.find(p => p.id === selectedProvinsi);
                        return prov ? prov.name : '-';
                    })()"></p>
                </div>
                <div class="p-4 bg-gray-50 rounded-lg border border-gray-200">
                    <span class="text-sm font-medium text-gray-700">Kabupaten/Kota:</span>
                    <p class="mt-1 text-teal-700" x-text="(() => {
                        const kab = kabupatenList.find(k => k.id === selectedKabupaten);
                        return kab ? kab.name : '-';
                    })()"></p>
                </div>
                <div class="p-4 bg-gray-50 rounded-lg border border-gray-200">
                    <span class="text-sm font-medium text-gray-700">Kecamatan:</span>
                    <p class="mt-1 text-teal-700" x-text="(() => {
                        const kec = kecamatanList.find(k => k.id === selectedKecamatan);
                        return kec ? kec.name : '-';
                    })()"></p>
                </div>
                <div class="p-4 bg-gray-50 rounded-lg border border-gray-200">
                    <span class="text-sm font-medium text-gray-700">Desa/Kelurahan:</span>
                    <p class="mt-1 text-teal-700" x-text="(() => {
                        const desa = desaList.find(d => d.id === selectedDesa);
                        return desa ? desa.name : '-';
                    })()"></p>
                </div>
            </div>
            <div class="p-4 bg-gray-50 rounded-lg border border-gray-200">
                <span class="text-sm font-medium text-gray-700">Kode Pos:</span>
                <p class="mt-1 text-teal-700" x-text="formData.kode_pos || '-'"></p>
            </div>
        </div>
    </div>

    <!-- Lampiran -->
    <div class="bg-white p-6 rounded-lg border-2 border-teal-100 mb-6">
        <h3 class="text-lg font-medium text-teal-700 mb-4 flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path>
            </svg>
            Lampiran
        </h3>
        <div class="space-y-6">
            <!-- Required Files -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Pas Foto -->
                <div class="bg-gray-50 rounded-lg p-4">
                    <div class="mb-2">
                        <span class="text-sm text-gray-600">Pas Foto:</span>
                        <p class="font-medium" x-text="filePreview.pasfoto?.name || 'Belum diunggah'"></p>
                    </div>
                    <template x-if="filePreview.pasfoto">
                        <div class="relative mt-2">
                            <img
                                x-show="filePreview.pasfoto.type === 'image'"
                                :src="filePreview.pasfoto.url"
                                class="w-full h-48 object-contain rounded-lg border border-gray-200"
                            >
                            <div
                                x-show="filePreview.pasfoto.type === 'pdf'"
                                class="w-full h-48 bg-gray-100 rounded-lg flex items-center justify-center border border-gray-200"
                            >
                                <div class="text-center p-4">
                                    <svg class="w-12 h-12 mx-auto mb-2 text-teal-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                    </svg>
                                    <p class="text-sm text-gray-600">Dokumen PDF</p>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>

                <!-- Akta Kelahiran -->
                <div class="bg-gray-50 rounded-lg p-4">
                    <div class="mb-2">
                        <span class="text-sm text-gray-600">Akta Kelahiran:</span>
                        <p class="font-medium" x-text="filePreview.akta_lahir?.name || 'Belum diunggah'"></p>
                    </div>
                    <template x-if="filePreview.akta_lahir">
                        <div class="relative mt-2">
                            <img
                                x-show="filePreview.akta_lahir.type === 'image'"
                                :src="filePreview.akta_lahir.url"
                                class="w-full h-48 object-contain rounded-lg border border-gray-200"
                            >
                            <div
                                x-show="filePreview.akta_lahir.type === 'pdf'"
                                class="w-full h-48 bg-gray-100 rounded-lg flex items-center justify-center border border-gray-200"
                            >
                                <div class="text-center p-4">
                                    <svg class="w-12 h-12 mx-auto mb-2 text-teal-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                    </svg>
                                    <p class="text-sm text-gray-600">Dokumen PDF</p>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>

                <!-- Kartu Keluarga -->
                <div class="bg-gray-50 rounded-lg p-4">
                    <div class="mb-2">
                        <span class="text-sm text-gray-600">Kartu Keluarga:</span>
                        <p class="font-medium" x-text="filePreview.kartu_keluarga?.name || 'Belum diunggah'"></p>
                    </div>
                    <template x-if="filePreview.kartu_keluarga">
                        <div class="relative mt-2">
                            <img
                                x-show="filePreview.kartu_keluarga.type === 'image'"
                                :src="filePreview.kartu_keluarga.url"
                                class="w-full h-48 object-contain rounded-lg border border-gray-200"
                            >
                            <div
                                x-show="filePreview.kartu_keluarga.type === 'pdf'"
                                class="w-full h-48 bg-gray-100 rounded-lg flex items-center justify-center border border-gray-200"
                            >
                                <div class="text-center p-4">
                                    <svg class="w-12 h-12 mx-auto mb-2 text-teal-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                    </svg>
                                    <p class="text-sm text-gray-600">Dokumen PDF</p>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>

                <!-- Ijazah/Raport -->
                <div class="bg-gray-50 rounded-lg p-4"
                >
                    <div class="mb-2">
                        <span class="text-sm text-gray-600">Ijazah/Raport SMP/MTs:</span>
                        <p class="font-medium" x-text="filePreview.ijazah?.name || 'Belum diunggah'"></p>
                    </div>
                    <template x-if="filePreview.ijazah">
                        <div class="relative mt-2">
                            <img
                                x-show="filePreview.ijazah.type === 'image'"
                                :src="filePreview.ijazah.url"
                                class="w-full h-48 object-contain rounded-lg border border-gray-200"
                            >
                            <div
                                x-show="filePreview.ijazah.type === 'pdf'"
                                class="w-full h-48 bg-gray-100 rounded-lg flex items-center justify-center border border-gray-200"
                            >
                                <div class="text-center p-4">
                                    <svg class="w-12 h-12 mx-auto mb-2 text-teal-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                    </svg>
                                    <p class="text-sm text-gray-600">Dokumen PDF</p>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>
            </div>

            <!-- Dokumen Pendukung -->
            <template x-if="filePreview.dokumen_pendukung.length > 0">
                <div>
                    <h4 class="text-teal-600 font-medium mb-4">Dokumen Pendukung</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        <template x-for="(doc, index) in filePreview.dokumen_pendukung" :key="index">
                            <div class="bg-gray-50 rounded-lg p-4">
                                <div class="mb-2">
                                    <span class="text-sm text-gray-600" x-text="`Dokumen ${index + 1}:`"></span>
                                    <p class="font-medium" x-text="doc.name"></p>
                                </div>
                                <div class="relative mt-2">
                                    <img
                                        x-show="doc.type === 'image'"
                                        :src="doc.url"
                                        class="w-full h-48 object-contain rounded-lg border border-gray-200"
                                    >
                                    <div
                                        x-show="doc.type === 'pdf'"
                                        class="w-full h-48 bg-gray-100 rounded-lg flex items-center justify-center border border-gray-200"
                                    >
                                        <div class="text-center p-4">
                                            <svg class="w-12 h-12 mx-auto mb-2 text-teal-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                            </svg>
                                            <p class="text-sm text-gray-600">Dokumen PDF</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>
            </template>
        </div>
    </div>

    <!-- Navigation Buttons -->
    <div class="flex justify-between mt-8 pt-6 border-t border-gray-200">
        <!-- Back Button -->
        <button type="button"
            @click="prevStep"
            class="px-6 py-2.5 bg-white border-2 border-teal-500 text-teal-600 rounded-lg hover:bg-teal-50 focus:ring-2 focus:ring-teal-500 focus:ring-offset-2 transition-all duration-300 flex items-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed"
            :disabled="isLoading">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
            <span>Kembali</span>
        </button>

        <!-- Submit Button -->
        <button type="button"
            @click="handleSubmit"
            class="px-6 py-2.5 bg-gradient-to-r from-teal-600 to-teal-500 text-white rounded-lg hover:from-teal-700 hover:to-teal-600 focus:ring-2 focus:ring-teal-500 focus:ring-offset-2 transition-all duration-300 flex items-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed"
            :disabled="isLoading">
            <span>Kirim Pendaftaran</span>
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
        </button>
    </div>
</div>