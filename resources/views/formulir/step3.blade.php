<!-- Step 3: Lampiran -->
<div x-show="currentStep === 3" class="space-y-6">
    <h2 class="text-2xl font-semibold text-teal-700">Lampiran</h2>

    <!-- Required Documents -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
        <!-- Pas Foto -->
        <div class="bg-white p-6 rounded-lg border-2 border-teal-100">
            <div class="flex items-start justify-between mb-4">
                <div>
                    <h3 class="text-lg font-medium text-teal-700">Pas Foto</h3>
                    <p class="text-sm text-gray-600">Format: JPG/PNG, Max: 3MB</p>
                </div>
                <div class="text-red-500 text-sm">*Wajib</div>
            </div>

            <div class="relative">
                <template x-if="!filePreview.pasfoto">
                    <label class="block w-full p-4 border-2 border-dashed border-teal-300 rounded-lg hover:border-teal-500 transition-colors cursor-pointer bg-teal-50">
                        <input
                            type="file"
                            name="pasfoto"
                            accept="image/*"
                            class="hidden"
                            @change="handleFileUpload($event, 'pasfoto')"
                        >
                        <div class="text-center">
                            <svg class="w-8 h-8 mx-auto mb-2 text-teal-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                            <span class="text-teal-600">Unggah Pas Foto</span>
                </div>
                    </label>
                </template>

                <template x-if="filePreview.pasfoto">
                    <div class="relative">
                        <img
                            x-show="filePreview.pasfoto.type === 'image'"
                            :src="filePreview.pasfoto.url"
                            class="w-full h-48 object-cover rounded-lg"
                        >
                        <button
                            @click.prevent="removeFile('pasfoto')"
                            class="absolute top-2 right-2 bg-red-500 text-white p-1 rounded-full hover:bg-red-600 transition-colors"
                        >
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                </div>
                </template>
            </div>
        </div>

        <!-- Akta Kelahiran -->
        <div class="bg-white p-6 rounded-lg border-2 border-teal-100">
            <div class="flex items-start justify-between mb-4">
                <div>
                    <h3 class="text-lg font-medium text-teal-700">Akta Kelahiran</h3>
                    <p class="text-sm text-gray-600">Format: JPG/PNG/PDF, Max: 3MB</p>
                </div>
                <div class="text-red-500 text-sm">*Wajib</div>
            </div>

            <div class="relative">
                <template x-if="!filePreview.akta_lahir">
                    <label class="block w-full p-4 border-2 border-dashed border-teal-300 rounded-lg hover:border-teal-500 transition-colors cursor-pointer bg-teal-50">
                        <input
                            type="file"
                            name="akta_lahir"
                            accept="image/*,.pdf"
                            class="hidden"
                            @change="handleFileUpload($event, 'akta_lahir')"
                >
                        <div class="text-center">
                            <svg class="w-8 h-8 mx-auto mb-2 text-teal-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            <span class="text-teal-600">Unggah Akta Kelahiran</span>
                </div>
                    </label>
                    </template>

                <template x-if="filePreview.akta_lahir">
                    <div class="relative">
                        <img
                            x-show="filePreview.akta_lahir.type === 'image'"
                            :src="filePreview.akta_lahir.url"
                            class="w-full h-48 object-cover rounded-lg"
                        >
                        <div
                            x-show="filePreview.akta_lahir.type === 'pdf'"
                            class="w-full h-48 bg-gray-100 rounded-lg flex items-center justify-center"
                        >
                            <div class="text-center">
                                <svg class="w-12 h-12 mx-auto mb-2 text-teal-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                    </svg>
                                <span x-text="filePreview.akta_lahir.name" class="text-sm text-gray-600"></span>
                </div>
                        </div>
                        <button
                            @click.prevent="removeFile('akta_lahir')"
                            class="absolute top-2 right-2 bg-red-500 text-white p-1 rounded-full hover:bg-red-600 transition-colors"
                        >
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                </template>
            </div>
        </div>

        <!-- Kartu Keluarga -->
        <div class="bg-white p-6 rounded-lg border-2 border-teal-100">
            <div class="flex items-start justify-between mb-4">
                <div>
                    <h3 class="text-lg font-medium text-teal-700">Kartu Keluarga</h3>
                    <p class="text-sm text-gray-600">Format: JPG/PNG/PDF, Max: 3MB</p>
                </div>
                <div class="text-red-500 text-sm">*Wajib</div>
            </div>

            <div class="relative">
                <template x-if="!filePreview.kartu_keluarga">
                    <label class="block w-full p-4 border-2 border-dashed border-teal-300 rounded-lg hover:border-teal-500 transition-colors cursor-pointer bg-teal-50">
                        <input
                            type="file"
                            name="kartu_keluarga"
                            accept="image/*,.pdf"
                            class="hidden"
                            @change="handleFileUpload($event, 'kartu_keluarga')"
                >
                        <div class="text-center">
                            <svg class="w-8 h-8 mx-auto mb-2 text-teal-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            <span class="text-teal-600">Unggah Kartu Keluarga</span>
                </div>
                    </label>
                    </template>

                <template x-if="filePreview.kartu_keluarga">
                    <div class="relative">
                        <img
                            x-show="filePreview.kartu_keluarga.type === 'image'"
                            :src="filePreview.kartu_keluarga.url"
                            class="w-full h-48 object-cover rounded-lg"
                        >
                        <div
                            x-show="filePreview.kartu_keluarga.type === 'pdf'"
                            class="w-full h-48 bg-gray-100 rounded-lg flex items-center justify-center"
                        >
                            <div class="text-center">
                                <svg class="w-12 h-12 mx-auto mb-2 text-teal-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                    </svg>
                                <span x-text="filePreview.kartu_keluarga.name" class="text-sm text-gray-600"></span>
                </div>
                        </div>
                        <button
                            @click.prevent="removeFile('kartu_keluarga')"
                            class="absolute top-2 right-2 bg-red-500 text-white p-1 rounded-full hover:bg-red-600 transition-colors"
                        >
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                </template>
            </div>
        </div>

        <!-- Ijazah/Raport -->
        <div
            class="bg-white p-6 rounded-lg border-2 border-teal-100"
        >
            <div class="flex items-start justify-between mb-4">
                <div>
                    <h3 class="text-lg font-medium text-teal-700">Ijazah/Raport SMP/MTs</h3>
                    <p class="text-sm text-gray-600">Format: JPG/PNG/PDF, Max: 3MB</p>
                </div>
                <div class="text-red-500 text-sm">*Wajib</div>
            </div>

            <div class="relative">
                <template x-if="!filePreview.ijazah">
                    <label class="block w-full p-4 border-2 border-dashed border-teal-300 rounded-lg hover:border-teal-500 transition-colors cursor-pointer bg-teal-50">
                        <input
                            type="file"
                        name="ijazah"
                            accept="image/*,.pdf"
                            class="hidden"
                            @change="handleFileUpload($event, 'ijazah')"
                        >
                        <div class="text-center">
                            <svg class="w-8 h-8 mx-auto mb-2 text-teal-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                            <span class="text-teal-600">Unggah Ijazah/Raport</span>
                </div>
                    </label>
                </template>

                <template x-if="filePreview.ijazah">
            <div class="relative">
                        <img
                            x-show="filePreview.ijazah.type === 'image'"
                            :src="filePreview.ijazah.url"
                            class="w-full h-48 object-cover rounded-lg"
                        >
                <div
                            x-show="filePreview.ijazah.type === 'pdf'"
                            class="w-full h-48 bg-gray-100 rounded-lg flex items-center justify-center"
                >
                            <div class="text-center">
                                <svg class="w-12 h-12 mx-auto mb-2 text-teal-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                        </svg>
                                <span x-text="filePreview.ijazah.name" class="text-sm text-gray-600"></span>
                </div>
            </div>
                        <button
                            @click.prevent="removeFile('ijazah')"
                            class="absolute top-2 right-2 bg-red-500 text-white p-1 rounded-full hover:bg-red-600 transition-colors"
                        >
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                </div>
                </template>
                </div>
            </div>
        </div>

    <!-- Dokumen Pendukung (Optional) -->
    <div class="bg-white p-6 rounded-lg border-2 border-teal-100 mb-8">
            <div class="flex items-start justify-between mb-4">
                <div>
                <h3 class="text-lg font-medium text-teal-700">Dokumen Pendukung</h3>
                <p class="text-sm text-gray-600">Format: JPG/PNG/PDF, Max: 3MB per file (Maksimal 5 file)</p>
                </div>
            <div class="text-gray-500 text-sm">Opsional</div>
            </div>

            <div class="relative">
            <label class="block w-full p-4 border-2 border-dashed border-teal-300 rounded-lg hover:border-teal-500 transition-colors cursor-pointer bg-teal-50">
                <input
                    type="file"
                    name="dokumen_pendukung[]"
                    accept="image/*,.pdf"
                    multiple
                    class="hidden"
                    @change="handleDokumenPendukung($event)"
                >
                <div class="text-center">
                    <svg class="w-8 h-8 mx-auto mb-2 text-teal-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    <span class="text-teal-600">Unggah Dokumen Pendukung</span>
                    <p class="text-sm text-gray-500 mt-1">Sertifikat prestasi, piagam penghargaan, KIP (Kartu Indonesia Pintar), PKH (Program Keluarga Harapan), atau dokumen pendukung lainnya.</p>
                </div>
            </label>
                </div>

        <!-- Preview Dokumen Pendukung -->
                <div
            x-show="filePreview.dokumen_pendukung.length > 0"
            class="mt-4 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4"
        >
            <template x-for="(doc, index) in filePreview.dokumen_pendukung" :key="index">
                <div class="relative bg-gray-50 p-3 rounded-lg">
                    <template x-if="doc.type === 'image'">
                        <img :src="doc.url" class="w-full h-32 object-cover rounded">
                    </template>
                    <template x-if="doc.type === 'pdf'">
                        <div class="w-full h-32 bg-gray-100 rounded flex items-center justify-center">
                            <svg class="w-12 h-12 text-teal-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                        </svg>
            </div>
                    </template>
                    <div class="mt-2 text-sm text-gray-600 truncate" x-text="doc.name"></div>
                    <button
                        @click.prevent="removeDokumenPendukung(index)"
                        class="absolute top-2 right-2 bg-red-500 text-white p-1 rounded-full hover:bg-red-600 transition-colors"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
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

        <!-- Next Button -->
        <button type="button"
            @click="nextStep"
            class="px-6 py-2.5 bg-gradient-to-r from-teal-600 to-teal-500 text-white rounded-lg hover:from-teal-700 hover:to-teal-600 focus:ring-2 focus:ring-teal-500 focus:ring-offset-2 transition-all duration-300 flex items-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed"
            :disabled="isLoading">
            <span>Lanjutkan</span>
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
            </svg>
        </button>
    </div>
</div>