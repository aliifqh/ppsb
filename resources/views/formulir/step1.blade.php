<!-- Step 1: Data Santri -->
<div x-show="currentStep === 1" class="space-y-6">
    <h2 class="text-2xl font-semibold text-teal-700">Data Calon Santri</h2>

    <!-- Unit Selection -->
    <div class="space-y-2">
        <label class="block text-teal-700 font-medium">Pilihan Unit:</label>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <label class="relative">
                <input type="radio"
                    name="unit"
                    value="ppim"
                    x-model="formData.unit"
                    class="peer sr-only"
                    required>
                <div class="p-4 border-2 rounded-lg cursor-pointer
                    peer-checked:border-teal-600 peer-checked:bg-teal-50
                    hover:border-teal-400 hover:bg-teal-50/50">
                    <span class="block text-center">PPIM (Lulusan SD/MI)</span>
                </div>
            </label>
            <label class="relative">
                <input type="radio"
                    name="unit"
                    value="tks"
                    x-model="formData.unit"
                    class="peer sr-only"
                    required>
                <div class="p-4 border-2 rounded-lg cursor-pointer
                    peer-checked:border-teal-600 peer-checked:bg-teal-50
                    hover:border-teal-400 hover:bg-teal-50/50">
                    <span class="block text-center">TKS (Lulusan SMP/MTs)</span>
                </div>
            </label>
        </div>
    </div>

    <!-- Nama Lengkap -->
    <div class="space-y-2">
        <label for="nama" class="block text-teal-700 font-medium">Nama Lengkap:</label>
        <input type="text"
            id="nama"
            name="nama"
            x-model="formData.nama"
            class="w-full px-4 py-2 border-2 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500"
            placeholder="Masukkan nama lengkap sesuai ijazah"
            required>
    </div>

    <!-- NISN -->
    <div class="space-y-2">
        <label for="NISN" class="block" text-teal-700 font-medium flex items-center justify-between">
            <span>NISN:</span>
            <a href="https://nisn.data.kemdikbud.go.id/index.php/Cindex/caribynama/"
               target="_blank"
               class="text-sm text-teal-600 hover:text-teal-700 underline">
                Cek NISN
            </a>
        </label>
        <input type="text"
            id="NISN"
            name="NISN"
            x-model="formData.nisn"
            @input="validateNISN"
            class="w-full px-4 py-2 border-2 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500"
            :class="{'border-red-500': errors.nisn}"
            placeholder="Masukkan 10 digit NISN"
            required>
        <p x-show="errors.nisn"
            x-text="errors.nisn"
            class="mt-1 text-sm text-red-600"></p>
    </div>

    <!-- Jenis Kelamin -->
    <div class="space-y-2">
        <label for="jenis_kelamin" class="block text-teal-700 font-medium">Jenis Kelamin:</label>
        <select id="jenis_kelamin"
            name="jenis_kelamin"
            x-model="formData.jenis_kelamin"
            class="w-full px-4 py-2 border-2 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500"
            required>
            <option value="">Pilih Jenis Kelamin</option>
            <option value="Laki-laki">Laki-laki</option>
            <option value="Perempuan">Perempuan</option>
        </select>
    </div>

    <!-- Tempat Lahir -->
    <div class="space-y-2">
        <label for="tempat_lahir" class="block text-teal-700 font-medium">Tempat Lahir:</label>
        <input type="text"
            id="tempat_lahir"
            name="tempat_lahir"
            x-model="formData.tempat_lahir"
            class="w-full px-4 py-2 border-2 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500"
            placeholder="Masukkan kota/kabupaten tempat lahir"
            required>
    </div>

    <!-- Tanggal Lahir -->
    <div class="space-y-2">
        <label for="tanggal_lahir" class="block text-teal-700 font-medium">Tanggal Lahir:</label>
        <input type="text"
            id="tanggal_lahir"
            name="tanggal_lahir"
            x-model="formData.tanggal_lahir"
            @input="validateTanggalLahir"
            class="w-full px-4 py-2 border-2 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500"
            :class="{'border-red-500': errors.tanggal_lahir}"
            placeholder="Contoh: 29 Juni 2005"
            required>
        <p x-show="errors.tanggal_lahir"
            x-text="errors.tanggal_lahir"
            class="mt-1 text-sm text-red-600"></p>
    </div>

    <!-- Asal Sekolah -->
    <div class="space-y-2">
        <label for="asal_sekolah" class="block text-teal-700 font-medium">Asal Sekolah:</label>
        <input type="text"
            id="asal_sekolah"
            name="asal_sekolah"
            x-model="formData.asal_sekolah"
            class="w-full px-4 py-2 border-2 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500"
            placeholder="Masukkan nama sekolah asal"
            required>
    </div>

    <!-- Data Saudara -->
    <div class="space-y-4">
        <label class="block text-teal-700 font-medium">Data Saudara:</label>
        <div class="bg-teal-50 rounded-lg p-6 border border-teal-100">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Anak Ke -->
                <div class="space-y-2">
                    <label class="block text-teal-700 font-medium">Anak ke:</label>
                    <div class="flex items-center gap-3">
                        <button type="button"
                            @click="decrementNumber('anak_ke')"
                            class="w-10 h-10 rounded-lg bg-teal-100 text-teal-600 hover:bg-teal-200 flex items-center justify-center transition-colors"
                            :class="{'opacity-50 cursor-not-allowed': formData.anak_ke <= 1}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>
                            </svg>
                        </button>
                        <input type="number"
                            name="anak_ke"
                            x-model="formData.anak_ke"
                            class="w-20 text-center px-3 py-2 border-2 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500 [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none"
                            min="1"
                            required
                            readonly>
                        <button type="button"
                            @click="incrementNumber('anak_ke')"
                            class="w-10 h-10 rounded-lg bg-teal-100 text-teal-600 hover:bg-teal-200 flex items-center justify-center transition-colors"
                            :class="{'opacity-50 cursor-not-allowed': formData.anak_ke >= formData.jumlah_saudara}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Jumlah Saudara -->
                <div class="space-y-2">
                    <label class="block text-teal-700 font-medium">Jumlah Saudara:</label>
                    <div class="flex items-center gap-3">
                        <button type="button"
                            @click="decrementNumber('jumlah_saudara')"
                            class="w-10 h-10 rounded-lg bg-teal-100 text-teal-600 hover:bg-teal-200 flex items-center justify-center transition-colors"
                            :class="{'opacity-50 cursor-not-allowed': formData.jumlah_saudara <= formData.anak_ke || formData.jumlah_saudara <= 1}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>
                            </svg>
                        </button>
                        <input type="number"
                            name="jumlah_saudara"
                            x-model="formData.jumlah_saudara"
                            class="w-20 text-center px-3 py-2 border-2 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500 [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none"
                            min="1"
                            required
                            readonly>
                        <button type="button"
                            @click="incrementNumber('jumlah_saudara')"
                            class="w-10 h-10 rounded-lg bg-teal-100 text-teal-600 hover:bg-teal-200 flex items-center justify-center transition-colors"
                            :class="{'opacity-50 cursor-not-allowed': formData.jumlah_saudara >= 20}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
            <p x-show="errors.sibling"
                x-text="errors.sibling"
                class="mt-4 text-sm text-red-600"></p>
        </div>
    </div>

    <!-- Navigation Buttons -->
    <div class="flex justify-between mt-8 pt-6 border-t border-gray-200">
        <!-- Back Button (hidden on first step) -->
        <div></div>

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