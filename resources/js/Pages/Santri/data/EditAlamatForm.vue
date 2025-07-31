<template>
  <BaseModal
    :show="show"
    title="Edit Alamat"
    icon="fas fa-map-marker-alt"
    @close="$emit('close')"
  >
    <!-- Form Content -->
    <form @submit.prevent="submit" id="form-edit-alamat" class="space-y-4">
      <div>
        <label class="block text-sm font-medium mb-1">Alamat Lengkap</label>
        <input v-model="form.alamat" type="text" class="input" required />
      </div>
      <div>
        <label class="block text-sm font-medium mb-1">Provinsi</label>
        <select v-model="selectedProvinsi" class="input" :disabled="isLoadingProvinsi" required>
          <option value="">{{ isLoadingProvinsi ? 'Memuat...' : 'Pilih Provinsi' }}</option>
          <option v-for="prov in provinsiList" :key="prov.id" :value="prov.id">{{ prov.name }}</option>
        </select>
      </div>
      <div>
        <label class="block text-sm font-medium mb-1">Kabupaten/Kota</label>
        <select v-model="selectedKabupaten" class="input" :disabled="!selectedProvinsi || isLoadingKabupaten" required>
          <option value="">{{ isLoadingKabupaten ? 'Memuat...' : 'Pilih Kabupaten/Kota' }}</option>
          <option v-for="kab in kabupatenList" :key="kab.id" :value="kab.id">{{ kab.name }}</option>
        </select>
      </div>
      <div>
        <label class="block text-sm font-medium mb-1">Kecamatan</label>
        <select v-model="selectedKecamatan" class="input" :disabled="!selectedKabupaten || isLoadingKecamatan" required>
          <option value="">{{ isLoadingKecamatan ? 'Memuat...' : 'Pilih Kecamatan' }}</option>
          <option v-for="kec in kecamatanList" :key="kec.id" :value="kec.id">{{ kec.name }}</option>
        </select>
      </div>
      <div>
        <label class="block text-sm font-medium mb-1">Desa/Kelurahan</label>
        <select v-model="selectedDesa" class="input" :disabled="!selectedKecamatan || isLoadingDesa" required>
          <option value="">{{ isLoadingDesa ? 'Memuat...' : 'Pilih Desa/Kelurahan' }}</option>
          <option v-for="desa in desaList" :key="desa.id" :value="desa.id">{{ desa.name }}</option>
        </select>
      </div>
      <div>
        <label class="block text-sm font-medium mb-1">Kode Pos</label>
        <input v-model="form.kode_pos" type="text" class="input" required />
      </div>
    </form>

    <!-- Footer Buttons -->
    <template #footer>
      <button type="button" @click="$emit('close')" class="px-4 py-2 bg-gray-200 rounded hover:bg-gray-300">Batal</button>
      <button type="submit" form="form-edit-alamat" class="px-4 py-2 bg-teal-600 text-white rounded hover:bg-teal-700">Simpan</button>
    </template>
  </BaseModal>
</template>
<script>
import { ref, watch, onMounted } from 'vue';
import Swal from 'sweetalert2';
import BaseModal from '@/components/common/BaseModal.vue';

export default {
  name: 'EditAlamatForm',
  components: {
    BaseModal
  },
  props: {
    show: Boolean,
    data: Object
  },
  emits: ['close', 'update'],
  setup(props, { emit }) {
    const form = ref({ ...props.data });
    const provinsiList = ref([]);
    const kabupatenList = ref([]);
    const kecamatanList = ref([]);
    const desaList = ref([]);
    const selectedProvinsi = ref(form.value.provinsi_id || '');
    const selectedKabupaten = ref(form.value.kabupaten_id || '');
    const selectedKecamatan = ref(form.value.kecamatan_id || '');
    const selectedDesa = ref(form.value.desa_id || '');
    const isLoadingProvinsi = ref(false);
    const isLoadingKabupaten = ref(false);
    const isLoadingKecamatan = ref(false);
    const isLoadingDesa = ref(false);
    const API_BASE_URL = 'https://www.emsifa.com/api-wilayah-indonesia/api';

    const fetchAPI = async (endpoint) => {
      try {
        const response = await fetch(`${API_BASE_URL}/${endpoint}`);
        if (!response.ok) throw new Error('Network response was not ok');
        return await response.json();
      } catch (error) {
        console.error(`Error fetching from ${endpoint}:`, error);
        return [];
      }
    };

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

    watch(selectedProvinsi, async (newVal, oldVal) => {
      form.value.provinsi_id = newVal;
      form.value.provinsi_nama = provinsiList.value.find(p => p.id === newVal)?.name || '';
      if (newVal !== oldVal) {
        selectedKabupaten.value = '';
        selectedKecamatan.value = '';
        selectedDesa.value = '';
        kabupatenList.value = [];
        kecamatanList.value = [];
        desaList.value = [];
      }
      if (newVal) {
        isLoadingKabupaten.value = true;
        kabupatenList.value = await fetchAPI(`regencies/${newVal}.json`);
        isLoadingKabupaten.value = false;
      } else {
        kabupatenList.value = [];
      }
    });

    watch(selectedKabupaten, async (newVal, oldVal) => {
      form.value.kabupaten_id = newVal;
      form.value.kabupaten_nama = kabupatenList.value.find(k => k.id === newVal)?.name || '';
      if (newVal !== oldVal) {
        selectedKecamatan.value = '';
        selectedDesa.value = '';
        kecamatanList.value = [];
        desaList.value = [];
      }
      if (newVal) {
        isLoadingKecamatan.value = true;
        kecamatanList.value = await fetchAPI(`districts/${newVal}.json`);
        isLoadingKecamatan.value = false;
      } else {
        kecamatanList.value = [];
      }
    });

    watch(selectedKecamatan, async (newVal, oldVal) => {
      form.value.kecamatan_id = newVal;
      form.value.kecamatan_nama = kecamatanList.value.find(k => k.id === newVal)?.name || '';
      if (newVal !== oldVal) {
        selectedDesa.value = '';
        desaList.value = [];
      }
      if (newVal) {
        isLoadingDesa.value = true;
        desaList.value = await fetchAPI(`villages/${newVal}.json`);
        isLoadingDesa.value = false;
      } else {
        desaList.value = [];
      }
    });

    watch(selectedDesa, (newVal) => {
      form.value.desa_id = newVal;
      form.value.desa_nama = desaList.value.find(d => d.id === newVal)?.name || '';
    });

    watch(() => props.data, (val) => {
      form.value = { ...val };
      selectedProvinsi.value = form.value.provinsi_id || '';
      selectedKabupaten.value = form.value.kabupaten_id || '';
      selectedKecamatan.value = form.value.kecamatan_id || '';
      selectedDesa.value = form.value.desa_id || '';
    });

    const submit = () => {
      if (!form.value.alamat || !selectedProvinsi.value || !selectedKabupaten.value || !selectedKecamatan.value || !selectedDesa.value) {
        Swal.fire({
          title: 'Error!',
          text: 'Semua field alamat harus diisi',
          icon: 'error',
          confirmButtonText: 'OK'
        });
        return;
      }
      form.value.provinsi_nama = provinsiList.value.find(p => p.id === selectedProvinsi.value)?.name || '';
      form.value.kabupaten_nama = kabupatenList.value.find(k => k.id === selectedKabupaten.value)?.name || '';
      form.value.kecamatan_nama = kecamatanList.value.find(k => k.id === selectedKecamatan.value)?.name || '';
      form.value.desa_nama = desaList.value.find(d => d.id === selectedDesa.value)?.name || '';
      emit('update', { ...form.value });
      emit('close');
    };

    return {
      form,
      provinsiList, kabupatenList, kecamatanList, desaList,
      selectedProvinsi, selectedKabupaten, selectedKecamatan, selectedDesa,
      isLoadingProvinsi, isLoadingKabupaten, isLoadingKecamatan, isLoadingDesa,
      submit
    };
  }
}
</script>
<style scoped>
.input { @apply w-full px-3 py-2 border border-gray-300 rounded focus:ring-2 focus:ring-teal-500; }
</style>
