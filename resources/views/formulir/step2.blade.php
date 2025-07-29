<!-- Step 2: Data Orang Tua -->
<div x-show="currentStep === 2" class="space-y-6">
    <h2 class="text-2xl font-semibold text-teal-700">Data Orang Tua</h2>

    <!-- Data Ayah -->
    <div class="mb-8">
        <h3 class="text-lg font-medium text-teal-600 mb-4">Data Ayah</h3>

        <!-- Nama Ayah -->
        <div class="mb-6">
            <label for="nama_ayah" class="block text-teal-700 font-medium mb-2">Nama Ayah:</label>
            <input
                type="text"
                id="nama_ayah"
                name="nama_ayah"
                x-model="formData.nama_ayah"
                @input="updateFormData('nama_ayah', $event.target.value)"
                class="w-full px-4 py-2 border-2 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-colors duration-300"
                required
            >
        </div>

        <!-- Pekerjaan Ayah -->
        <div class="mb-6" x-data="{ showOtherAyah: false }">
            <label for="pekerjaan_ayah" class="block text-teal-700 font-medium mb-2">Pekerjaan Ayah:</label>
            <select
                id="pekerjaan_ayah"
                name="pekerjaan_ayah"
                x-model="formData.pekerjaan_ayah"
                @change="updateFormData('pekerjaan_ayah', $event.target.value)"
                class="w-full px-4 py-2 border-2 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-colors duration-300"
                required
            >
                <option value="">Pilih Pekerjaan</option>
                <option value="PNS / ASN">PNS / ASN</option>
                <option value="TNI / Polri">TNI / Polri</option>
                <option value="Karyawan Swasta">Karyawan Swasta</option>
                <option value="Petani / Pekebun">Petani / Pekebun</option>
                <option value="Pedagang / Wirausaha">Pedagang / Wirausaha</option>
                <option value="Buruh / Karyawan Harian">Buruh / Karyawan Harian</option>
                <option value="Sopir / Ojek / Pengemudi">Sopir / Ojek / Pengemudi</option>
                <option value="Pensiunan">Pensiunan</option>
                <option value="Tidak Bekerja">Tidak Bekerja</option>
                <option value="Lainnya">Lainnya</option>
            </select>

            <!-- Pekerjaan Lainnya -->
            <div
                x-show="showOtherAyah === 'Lainnya'"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 -translate-y-2"
                x-transition:enter-end="opacity-100 translate-y-0"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-y-0"
                x-transition:leave-end="opacity-0 -translate-y-2"
                class="mt-3"
            >
                <input
                    type="text"
                    id="pekerjaan_ayah_lainnya"
                    name="pekerjaan_ayah_lainnya"
                    x-model="formData.pekerjaan_ayah_lainnya"
                    @input="updateFormData('pekerjaan_ayah_lainnya', $event.target.value)"
                    class="w-full px-4 py-2 border-2 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-colors duration-300"
                    placeholder="Sebutkan pekerjaan ayah"
                    x-bind:required="formData.pekerjaan_ayah === 'Lainnya'"
                >
            </div>
        </div>

        <!-- Pendidikan Ayah -->
        <div class="mb-6">
            <label for="pendidikan_ayah" class="block text-teal-700 font-medium mb-2">Pendidikan Ayah:</label>
            <select
                id="pendidikan_ayah"
                name="pendidikan_ayah"
                x-model="formData.pendidikan_ayah"
                @change="updateFormData('pendidikan_ayah', $event.target.value)"
                class="w-full px-4 py-2 border-2 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-colors duration-300"
                required
            >
                <option value="">Pilih Pendidikan</option>
                <option value="SD">SD</option>
                <option value="SLTP">SLTP</option>
                <option value="SLTA">SLTA</option>
                <option value="Diploma">Diploma</option>
                <option value="S1">S1</option>
                <option value="S2">S2</option>
                <option value="S3">S3</option>
            </select>
        </div>

        <!-- WhatsApp Ayah -->
        <div class="space-y-2">
            <label for="wa_ayah" class="block text-teal-700 font-medium">WhatsApp Ayah:</label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                    </svg>
                </div>
                <input type="text"
                    id="wa_ayah"
                    name="wa_ayah"
                    x-model="formData.wa_ayah"
                    @input="validateWhatsApp($event, 'ayah')"
                    class="w-full pl-10 pr-4 py-2 border-2 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-colors duration-300"
                    :class="{'border-red-500': errors.wa_ayah}"
                    placeholder="62812345678"
                    required>
                <div x-show="waAyahOperator"
                    class="absolute right-3 top-1/2 -translate-y-1/2">
                    <span class="px-2 py-1 text-xs font-medium rounded-full"
                        :class="{
                            'bg-red-100 text-red-800': waAyahOperator === 'Telkomsel',
                            'bg-blue-100 text-blue-800': waAyahOperator === 'XL',
                            'bg-yellow-100 text-yellow-800': waAyahOperator === 'Indosat',
                            'bg-green-100 text-green-800': waAyahOperator === 'Tri',
                            'bg-purple-100 text-purple-800': waAyahOperator === 'Axis',
                            'bg-orange-100 text-orange-800': waAyahOperator === 'Smartfren'
                        }"
                        x-text="waAyahOperator">
                    </span>
                </div>
            </div>
            <p x-show="errors.wa_ayah"
                x-text="errors.wa_ayah"
                class="mt-1 text-sm text-red-600"></p>
        </div>
    </div>

    <!-- Data Ibu -->
    <div class="mb-8">
        <h3 class="text-lg font-medium text-teal-600 mb-4">Data Ibu</h3>

        <!-- Nama Ibu -->
        <div class="mb-6">
            <label for="nama_ibu" class="block text-teal-700 font-medium mb-2">Nama Ibu:</label>
            <input
                type="text"
                id="nama_ibu"
                name="nama_ibu"
                x-model="formData.nama_ibu"
                @input="updateFormData('nama_ibu', $event.target.value)"
                class="w-full px-4 py-2 border-2 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-colors duration-300"
                required
            >
        </div>

        <!-- Pekerjaan Ibu -->
        <div class="mb-6" x-data="{ showOtherIbu: false }">
            <label for="pekerjaan_ibu" class="block text-teal-700 font-medium mb-2">Pekerjaan Ibu:</label>
            <select
                id="pekerjaan_ibu"
                name="pekerjaan_ibu"
                x-model="formData.pekerjaan_ibu"
                @change="updateFormData('pekerjaan_ibu', $event.target.value)"
                class="w-full px-4 py-2 border-2 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-colors duration-300"
                required
            >
                <option value="">Pilih Pekerjaan</option>
                <option value="PNS / ASN">PNS / ASN</option>
                <option value="TNI / Polri">TNI / Polri</option>
                <option value="Karyawan Swasta">Karyawan Swasta</option>
                <option value="Petani / Pekebun">Petani / Pekebun</option>
                <option value="Pedagang / Wirausaha">Pedagang / Wirausaha</option>
                <option value="Buruh / Karyawan Harian">Buruh / Karyawan Harian</option>
                <option value="Ibu Rumah Tangga">Ibu Rumah Tangga</option>
                <option value="Pensiunan">Pensiunan</option>
                <option value="Tidak Bekerja">Tidak Bekerja</option>
                <option value="Lainnya">Lainnya</option>
            </select>

            <!-- Pekerjaan Lainnya -->
            <div
                x-show="showOtherIbu === 'Lainnya'"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 -translate-y-2"
                x-transition:enter-end="opacity-100 translate-y-0"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-y-0"
                x-transition:leave-end="opacity-0 -translate-y-2"
                class="mt-3"
            >
                <input
                    type="text"
                    id="pekerjaan_ibu_lainnya"
                    name="pekerjaan_ibu_lainnya"
                    x-model="formData.pekerjaan_ibu_lainnya"
                    @input="updateFormData('pekerjaan_ibu_lainnya', $event.target.value)"
                    class="w-full px-4 py-2 border-2 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-colors duration-300"
                    placeholder="Sebutkan pekerjaan ibu"
                    x-bind:required="formData.pekerjaan_ibu === 'Lainnya'"
                >
            </div>
        </div>

        <!-- Pendidikan Ibu -->
        <div class="mb-6">
            <label for="pendidikan_ibu" class="block text-teal-700 font-medium mb-2">Pendidikan Ibu:</label>
            <select
                id="pendidikan_ibu"
                name="pendidikan_ibu"
                x-model="formData.pendidikan_ibu"
                @change="updateFormData('pendidikan_ibu', $event.target.value)"
                class="w-full px-4 py-2 border-2 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-colors duration-300"
                required
            >
                <option value="">Pilih Pendidikan</option>
                <option value="SD">SD</option>
                <option value="SLTP">SLTP</option>
                <option value="SLTA">SLTA</option>
                <option value="Diploma">Diploma</option>
                <option value="S1">S1</option>
                <option value="S2">S2</option>
                <option value="S3">S3</option>
            </select>
        </div>

        <!-- WhatsApp Ibu -->
        <div class="space-y-2">
            <label for="wa_ibu" class="block text-teal-700 font-medium">WhatsApp Ibu:</label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                    </svg>
                </div>
                <input type="text"
                    id="wa_ibu"
                    name="wa_ibu"
                    x-model="formData.wa_ibu"
                    @input="validateWhatsApp($event, 'ibu')"
                    class="w-full pl-10 pr-4 py-2 border-2 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-colors duration-300"
                    :class="{'border-red-500': errors.wa_ibu}"
                    placeholder="62812345678"
                    required>
                <div x-show="waIbuOperator"
                    class="absolute right-3 top-1/2 -translate-y-1/2">
                    <span class="px-2 py-1 text-xs font-medium rounded-full"
                        :class="{
                            'bg-red-100 text-red-800': waIbuOperator === 'Telkomsel',
                            'bg-blue-100 text-blue-800': waIbuOperator === 'XL',
                            'bg-yellow-100 text-yellow-800': waIbuOperator === 'Indosat',
                            'bg-green-100 text-green-800': waIbuOperator === 'Tri',
                            'bg-purple-100 text-purple-800': waIbuOperator === 'Axis',
                            'bg-orange-100 text-orange-800': waIbuOperator === 'Smartfren'
                        }"
                        x-text="waIbuOperator">
                    </span>
                </div>
            </div>
            <p x-show="errors.wa_ibu"
                x-text="errors.wa_ibu"
                class="mt-1 text-sm text-red-600"></p>
        </div>
    </div>

    <!-- Alamat -->
    <div class="space-y-2">
        <label for="alamat" class="block text-teal-700 font-medium">Alamat Lengkap:</label>
        <textarea id="alamat"
            name="alamat"
            x-model="formData.alamat"
            @input="validateAlamat"
            class="w-full px-4 py-2 border-2 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-colors duration-300"
            :class="{'border-red-500': errors.alamat}"
            placeholder="Masukkan alamat lengkap"
            rows="3"
            required></textarea>
        <p x-show="errors.alamat"
            x-text="errors.alamat"
            class="mt-1 text-sm text-red-600"></p>
    </div>

    <!-- Provinsi -->
    <div class="space-y-2">
        <label for="provinsi" class="block text-teal-700 font-medium">Provinsi:</label>
        <div class="relative">
            <select id="provinsi"
                name="provinsi"
                x-model="selectedProvinsi"
                @change="loadKabupaten()"
                class="w-full px-4 py-2 border-2 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-colors duration-300"
                :class="{'opacity-50': isLoadingProvinsi, 'border-red-500': errors.provinsi}"
                :disabled="isLoadingProvinsi"
                required>
                <option value="">Pilih Provinsi</option>
                <template x-for="prov in provinsiList" :key="prov.id">
                    <option :value="prov.id" x-text="prov.name"></option>
                </template>
            </select>
            <div x-show="isLoadingProvinsi"
                class="absolute right-3 top-1/2 -translate-y-1/2">
                <div class="animate-spin rounded-full h-5 w-5 border-t-2 border-b-2 border-teal-600"></div>
            </div>
        </div>
        <p x-show="errors.provinsi"
            x-text="errors.provinsi"
            class="mt-1 text-sm text-red-600"></p>
    </div>

    <!-- Kabupaten -->
    <div class="space-y-2">
        <label for="kabupaten" class="block text-teal-700 font-medium">Kabupaten/Kota:</label>
        <div class="relative">
            <select id="kabupaten"
                name="kabupaten"
                x-model="selectedKabupaten"
                @change="loadKecamatan()"
                class="w-full px-4 py-2 border-2 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-colors duration-300"
                :class="{'opacity-50': isLoadingKabupaten, 'border-red-500': errors.kabupaten}"
                :disabled="!selectedProvinsi || isLoadingKabupaten"
                required>
                <option value="">Pilih Kabupaten/Kota</option>
                <template x-for="kab in kabupatenList" :key="kab.id">
                    <option :value="kab.id" x-text="kab.name"></option>
                </template>
            </select>
            <div x-show="isLoadingKabupaten"
                class="absolute right-3 top-1/2 -translate-y-1/2">
                <div class="animate-spin rounded-full h-5 w-5 border-t-2 border-b-2 border-teal-600"></div>
            </div>
        </div>
        <p x-show="errors.kabupaten"
            x-text="errors.kabupaten"
            class="mt-1 text-sm text-red-600"></p>
    </div>

    <!-- Kecamatan -->
    <div class="space-y-2">
        <label for="kecamatan" class="block text-teal-700 font-medium">Kecamatan:</label>
        <div class="relative">
            <select id="kecamatan"
                name="kecamatan"
                x-model="selectedKecamatan"
                @change="loadDesa()"
                class="w-full px-4 py-2 border-2 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-colors duration-300"
                :class="{'opacity-50': isLoadingKecamatan, 'border-red-500': errors.kecamatan}"
                :disabled="!selectedKabupaten || isLoadingKecamatan"
                required>
                <option value="">Pilih Kecamatan</option>
                <template x-for="kec in kecamatanList" :key="kec.id">
                    <option :value="kec.id" x-text="kec.name"></option>
                </template>
            </select>
            <div x-show="isLoadingKecamatan"
                class="absolute right-3 top-1/2 -translate-y-1/2">
                <div class="animate-spin rounded-full h-5 w-5 border-t-2 border-b-2 border-teal-600"></div>
            </div>
        </div>
        <p x-show="errors.kecamatan"
            x-text="errors.kecamatan"
            class="mt-1 text-sm text-red-600"></p>
    </div>

    <!-- Desa -->
    <div class="space-y-2">
        <label for="desa" class="block text-teal-700 font-medium">Desa/Kelurahan:</label>
        <div class="relative">
            <select id="desa"
                name="desa"
                x-model="selectedDesa"
                class="w-full px-4 py-2 border-2 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-colors duration-300"
                :class="{'opacity-50': isLoadingDesa, 'border-red-500': errors.desa}"
                :disabled="!selectedKecamatan || isLoadingDesa"
                required>
                <option value="">Pilih Desa/Kelurahan</option>
                <template x-for="des in desaList" :key="des.id">
                    <option :value="des.id" x-text="des.name"></option>
                </template>
            </select>
            <div x-show="isLoadingDesa"
                class="absolute right-3 top-1/2 -translate-y-1/2">
                <div class="animate-spin rounded-full h-5 w-5 border-t-2 border-b-2 border-teal-600"></div>
            </div>
        </div>
        <p x-show="errors.desa"
            x-text="errors.desa"
            class="mt-1 text-sm text-red-600"></p>
    </div>

    <!-- Kode Pos -->
    <div class="space-y-2">
        <label for="kode_pos" class="block text-teal-700 font-medium">Kode Pos:</label>
        <input type="text"
            id="kode_pos"
            name="kode_pos"
            x-model="formData.kode_pos"
            @input="validateKodePos"
            class="w-full px-4 py-2 border-2 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-colors duration-300"
            :class="{'border-red-500': errors.kode_pos}"
            placeholder="Masukkan 5 digit kode pos"
            maxlength="5"
            required>
        <p x-show="errors.kode_pos"
            x-text="errors.kode_pos"
            class="mt-1 text-sm text-red-600"></p>
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