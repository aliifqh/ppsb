<template>
  <div class="flex flex-col">
    <!-- Tab Content -->
    <div class="pt-6">
      <form @submit.prevent>
        <!-- Data Santri -->
        <h3 class="text-lg font-semibold text-gray-800 border-b pb-2 mb-6">Data Santri</h3>
        <div class="space-y-6">
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <FormInput name="nama" label="Nama Lengkap" v-model="localForm.nama" :error="errors.nama" />
            <FormInput name="nisn" label="NISN" v-model="localForm.nisn" :error="errors.nisn" />
            <FormInput name="nomor_pendaftaran" label="Nomor Pendaftaran" v-model="localForm.nomor_pendaftaran" :disabled="isEditing" />

            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Unit</label>
              <select v-model="localForm.unit" class="input-field" :class="{'border-red-500': errors.unit}">
                <option value="ppim">PPIM</option>
                <option value="tks">TKS</option>
              </select>
            </div>

            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Jenis Kelamin</label>
              <select v-model="localForm.jenis_kelamin" class="input-field" :class="{'border-red-500': errors.jenis_kelamin}">
                <option value="Laki-laki">Laki-laki</option>
                <option value="Perempuan">Perempuan</option>
              </select>
            </div>

            <FormInput name="tempat_lahir" label="Tempat Lahir" v-model="localForm.tempat_lahir" :error="errors.tempat_lahir" />
            <FormInput name="tanggal_lahir" type="date" label="Tanggal Lahir" v-model="localForm.tanggal_lahir" :error="errors.tanggal_lahir" />
            <FormInput name="asal_sekolah" label="Asal Sekolah" v-model="localForm.asal_sekolah" :error="errors.asal_sekolah" />

            <FormInput name="anak_ke" type="number" label="Anak ke-" v-model="localForm.anak_ke" :error="errors.anak_ke" />
            <FormInput name="jumlah_saudara" type="number" label="Jumlah Saudara" v-model="localForm.jumlah_saudara" :error="errors.jumlah_saudara" />
          </div>
        </div>

        <!-- Data Orang Tua -->
        <h3 class="text-lg font-semibold text-gray-800 border-b pb-2 mt-10 mb-6">Data Orang Tua</h3>
        <div class="space-y-6">
           <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <FormInput name="nama_ayah" label="Nama Ayah" v-model="localForm.orangTua.nama_ayah" :error="errors.nama_ayah" />
              <FormInput name="wa_ayah" label="Nomor WhatsApp Ayah" v-model="localForm.orangTua.wa_ayah" :error="errors.wa_ayah" />
              <FormInput name="pekerjaan_ayah" label="Pekerjaan Ayah" v-model="localForm.orangTua.pekerjaan_ayah" :error="errors.pekerjaan_ayah" />

              <div>
                  <label class="block text-sm font-medium text-gray-700 mb-1">Pendidikan Ayah</label>
                  <select v-model="localForm.orangTua.pendidikan_ayah" class="input-field">
                      <option v-for="opt in pendidikanOptions" :key="opt" :value="opt">{{ opt }}</option>
                  </select>
              </div>

              <FormInput name="nama_ibu" label="Nama Ibu" v-model="localForm.orangTua.nama_ibu" :error="errors.nama_ibu" />
              <FormInput name="wa_ibu" label="Nomor WhatsApp Ibu" v-model="localForm.orangTua.wa_ibu" />
              <FormInput name="pekerjaan_ibu" label="Pekerjaan Ibu" v-model="localForm.orangTua.pekerjaan_ibu" />

              <div>
                  <label class="block text-sm font-medium text-gray-700 mb-1">Pendidikan Ibu</label>
                  <select v-model="localForm.orangTua.pendidikan_ibu" class="input-field">
                      <option v-for="opt in pendidikanOptions" :key="opt" :value="opt">{{ opt }}</option>
                  </select>
              </div>

              <div class="md:col-span-2">
                  <label class="block text-sm font-medium text-gray-700 mb-1">Alamat Lengkap</label>
                  <textarea v-model="localForm.orangTua.alamat" rows="3" class="input-field"></textarea>
              </div>

              <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Provinsi</label>
                <select v-model="selectedProvinsi" class="input-field" :disabled="isLoadingProvinsi">
                  <option value="">{{ isLoadingProvinsi ? 'Memuat...' : 'Pilih Provinsi' }}</option>
                  <option v-for="provinsi in provinsiList" :key="provinsi.id" :value="provinsi.id">{{ provinsi.name }}</option>
                </select>
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Kabupaten/Kota</label>
                <select v-model="selectedKabupaten" class="input-field" :disabled="!selectedProvinsi || isLoadingKabupaten">
                  <option value="">{{ isLoadingKabupaten ? 'Memuat...' : 'Pilih Kabupaten/Kota' }}</option>
                  <option v-for="kab in kabupatenList" :key="kab.id" :value="kab.id">{{ kab.name }}</option>
                </select>
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Kecamatan</label>
                <select v-model="selectedKecamatan" class="input-field" :disabled="!selectedKabupaten || isLoadingKecamatan">
                  <option value="">{{ isLoadingKecamatan ? 'Memuat...' : 'Pilih Kecamatan' }}</option>
                  <option v-for="kec in kecamatanList" :key="kec.id" :value="kec.id">{{ kec.name }}</option>
                </select>
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Desa/Kelurahan</label>
                <select v-model="selectedDesa" class="input-field" :disabled="!selectedKecamatan || isLoadingDesa">
                  <option value="">{{ isLoadingDesa ? 'Memuat...' : 'Pilih Desa/Kelurahan' }}</option>
                  <option v-for="desa in desaList" :key="desa.id" :value="desa.id">{{ desa.name }}</option>
                </select>
              </div>
              <FormInput name="kode_pos" label="Kode Pos" v-model="localForm.orangTua.kode_pos" />
           </div>
        </div>

        <!-- Dokumen -->
        <div class="mt-10">
            <DokumenSection :dokumen="localForm.dokumen" @update:dokumen="handleDokumenUpdate" />
        </div>
      </form>
    </div>
  </div>
</template>

<script>
import { ref, watch, toRefs, reactive, onMounted } from 'vue'
import DokumenSection from '../dokumen/DokumenSection.vue'
import FormInput from '@/components/common/forms/FormInput.vue'

export default {
  name: 'SantriForm',
  components: { DokumenSection, FormInput },
  props: {
    form: Object,
    isEditing: Boolean
  },
  emits: ['update:form', 'validity-change'],
  setup(props, { emit }) {
    const { form } = toRefs(props)
    const localForm = ref(JSON.parse(JSON.stringify(form.value)))
    const errors = reactive({})

    const pendidikanOptions = ['SD', 'SLTP', 'SLTA', 'Diploma', 'S1', 'S2', 'S3', 'Tidak Sekolah'];

    // State untuk data wilayah
    const provinsiList = ref([]);
    const kabupatenList = ref([]);
    const kecamatanList = ref([]);
    const desaList = ref([]);
    const selectedProvinsi = ref(localForm.value.orangTua.provinsi_id || '');
    const selectedKabupaten = ref(localForm.value.orangTua.kabupaten_id || '');
    const selectedKecamatan = ref(localForm.value.orangTua.kecamatan_id || '');
    const selectedDesa = ref(localForm.value.orangTua.desa_id || '');

    // State untuk status loading
    const isLoadingProvinsi = ref(false);
    const isLoadingKabupaten = ref(false);
    const isLoadingKecamatan = ref(false);
    const isLoadingDesa = ref(false);

    const API_BASE_URL = 'https://www.emsifa.com/api-wilayah-indonesia/api';

    // Fetch data dari API
    const fetchAPI = async (endpoint) => {
      try {
        const response = await fetch(`${API_BASE_URL}/${endpoint}`);
        if (!response.ok) throw new Error('Network response was not ok');
        return await response.json();
      } catch (error) {
        console.error(`Error fetching from ${endpoint}:`, error);
        // Di sini bisa ditambahkan notifikasi error untuk user
        return [];
      }
    };

    // Load data awal untuk dropdown, terutama saat mode edit
    onMounted(async () => {
      isLoadingProvinsi.value = true;
      provinsiList.value = await fetchAPI('provinces.json');
      isLoadingProvinsi.value = false;

      if (selectedProvinsi.value) {
          isLoadingKabupaten.value = true;
          kabupatenList.value = await fetchAPI(`regencies/${selectedProvinsi.value}.json`);
          isLoadingKabupaten.value = false;
      }
      if (selectedKabupaten.value) {
          isLoadingKecamatan.value = true;
          kecamatanList.value = await fetchAPI(`districts/${selectedKabupaten.value}.json`);
          isLoadingKecamatan.value = false;
      }
      if (selectedKecamatan.value) {
          isLoadingDesa.value = true;
          desaList.value = await fetchAPI(`villages/${selectedKecamatan.value}.json`);
          isLoadingDesa.value = false;
      }
    });

    // Watchers untuk dropdown wilayah
    watch(selectedProvinsi, async (newVal, oldVal) => {
      localForm.value.orangTua.provinsi_id = newVal;
      if (newVal) {
        if (newVal !== oldVal) {
            selectedKabupaten.value = '';
            selectedKecamatan.value = '';
            selectedDesa.value = '';
            kabupatenList.value = [];
            kecamatanList.value = [];
            desaList.value = [];
        }
        isLoadingKabupaten.value = true;
        kabupatenList.value = await fetchAPI(`regencies/${newVal}.json`);
        isLoadingKabupaten.value = false;
      } else {
        kabupatenList.value = [];
      }
    });

    watch(selectedKabupaten, async (newVal, oldVal) => {
      localForm.value.orangTua.kabupaten_id = newVal;
      if (newVal) {
        if (newVal !== oldVal) {
            selectedKecamatan.value = '';
            selectedDesa.value = '';
            kecamatanList.value = [];
            desaList.value = [];
        }
        isLoadingKecamatan.value = true;
        kecamatanList.value = await fetchAPI(`districts/${newVal}.json`);
        isLoadingKecamatan.value = false;
      } else {
        kecamatanList.value = [];
      }
    });

    watch(selectedKecamatan, async (newVal, oldVal) => {
      localForm.value.orangTua.kecamatan_id = newVal;
      if (newVal) {
        if (newVal !== oldVal) {
            selectedDesa.value = '';
            desaList.value = [];
        }
        isLoadingDesa.value = true;
        desaList.value = await fetchAPI(`villages/${newVal}.json`);
        isLoadingDesa.value = false;
      } else {
        desaList.value = [];
      }
    });

    watch(selectedDesa, (newVal) => {
        localForm.value.orangTua.desa_id = newVal;
        // Kode pos tidak diisi otomatis karena API tidak menyediakan
    });

    // Auto-format dan validasi nomor WhatsApp ayah & ibu
    watch(() => localForm.value.orangTua.wa_ayah, (val, oldVal) => {
      if (typeof val === 'string') {
        let num = val.replace(/[^0-9]/g, '');
        if (num.startsWith('0')) num = '62' + num.slice(1);
        if (!num.startsWith('62')) num = '62' + num;
        if (num !== val) localForm.value.orangTua.wa_ayah = num;
        // Validasi
        if (num.length < 10 || num.length > 15) {
          errors.wa_ayah = 'Nomor WhatsApp ayah harus 10-15 digit dan diawali 62.';
        } else {
          delete errors.wa_ayah;
        }
      }
    });
    watch(() => localForm.value.orangTua.wa_ibu, (val, oldVal) => {
      if (typeof val === 'string') {
        let num = val.replace(/[^0-9]/g, '');
        if (num.startsWith('0')) num = '62' + num.slice(1);
        if (!num.startsWith('62')) num = '62' + num;
        if (num !== val) localForm.value.orangTua.wa_ibu = num;
        // Validasi
        if (num.length < 10 || num.length > 15) {
          errors.wa_ibu = 'Nomor WhatsApp ibu harus 10-15 digit dan diawali 62.';
        } else {
          delete errors.wa_ibu;
        }
      }
    });

    const validate = () => {
      Object.keys(errors).forEach(key => delete errors[key]);

      if (!localForm.value.nama) errors.nama = 'Nama lengkap wajib diisi.';
      if (!localForm.value.nisn) errors.nisn = 'NISN wajib diisi.';
      else if (!/^\d{10}$/.test(localForm.value.nisn)) errors.nisn = 'NISN harus 10 digit angka.';

      if (!localForm.value.tempat_lahir) errors.tempat_lahir = 'Tempat lahir wajib diisi.';
      if (!localForm.value.tanggal_lahir) errors.tanggal_lahir = 'Tanggal lahir wajib diisi.';
      if (!localForm.value.asal_sekolah) errors.asal_sekolah = 'Asal sekolah wajib diisi.';

      if (localForm.value.orangTua) {
        if (!localForm.value.orangTua.nama_ayah) errors.nama_ayah = 'Nama ayah wajib diisi.';
        if (localForm.value.orangTua.wa_ayah && !/^(\+62|62|0)8[1-9][0-9]{6,11}$/.test(localForm.value.orangTua.wa_ayah)) {
          errors.wa_ayah = 'Format nomor WhatsApp ayah tidak valid.';
        }
      }

      const isValid = Object.keys(errors).length === 0;
      emit('validity-change', isValid);
      return isValid;
    };

    watch(form, (newForm) => {
      if (JSON.stringify(newForm) !== JSON.stringify(localForm.value)) {
        localForm.value = JSON.parse(JSON.stringify(newForm));
        selectedProvinsi.value = localForm.value.orangTua.provinsi_id || '';
        selectedKabupaten.value = localForm.value.orangTua.kabupaten_id || '';
        selectedKecamatan.value = localForm.value.orangTua.kecamatan_id || '';
        selectedDesa.value = localForm.value.orangTua.desa_id || '';
      }
    }, { deep: true });

    watch(localForm, (newLocalForm) => {
      emit('update:form', newLocalForm);
      validate();
    }, { deep: true, immediate: true });

    const handleDokumenUpdate = (newDokumen) => {
      localForm.value.dokumen = { ...localForm.value.dokumen, ...newDokumen };
    };

    return {
      localForm, errors, pendidikanOptions, handleDokumenUpdate,
      provinsiList, kabupatenList, kecamatanList, desaList,
      selectedProvinsi, selectedKabupaten, selectedKecamatan, selectedDesa,
      isLoadingProvinsi, isLoadingKabupaten, isLoadingKecamatan, isLoadingDesa
    }
  }
}
</script>

<style lang="postcss">
.input-field {
  @apply block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm;
}
</style>
