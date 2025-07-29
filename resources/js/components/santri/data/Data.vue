<template>
  <div class="min-h-screen py-8">
    <LoadingOverlay :show="showLoadingOverlay" :showProgress="true" :progress="loadingProgress" title="Menyimpan Data..." subtitle="Mohon tunggu, data sedang diproses" icon="fas fa-spinner fa-spin" />
    <div class="max-w-4xl mx-auto px-4 space-y-8">
      <!-- Data Calon Santri Card -->
      <div class="relative bg-white rounded-lg shadow-md p-6 border border-teal-200 group">
        <button @click="openEdit('santri')"
          class="absolute top-4 right-4 bg-teal-600 hover:bg-teal-700 text-white font-semibold rounded-lg shadow px-6 py-2 transition-all duration-150 focus:outline-none focus:ring-2 focus:ring-teal-400"
          title="Edit Data Pribadi"
          :disabled="updating">
          Edit
        </button>
        <h3 class="text-lg font-bold text-teal-700 flex items-center mb-6">
          <i class="fas fa-user text-teal-600 mr-3"></i>
          Data Calon Santri
        </h3>
        <ul class="divide-y divide-gray-200">
          <li class="py-3 flex justify-between items-center">
            <span class="text-sm font-medium text-gray-700">Nomor Pendaftaran</span>
            <span class="text-teal-700 font-mono">{{ santri?.nomor_pendaftaran || '-' }}</span>
          </li>
          <li class="py-3 flex justify-between items-center">
            <span class="text-sm font-medium text-gray-700">Unit</span>
            <span class="text-teal-700 font-semibold">{{ (santri?.unit || '-').toUpperCase() }}</span>
          </li>
          <li class="py-3 flex justify-between items-center">
            <span class="text-sm font-medium text-gray-700">Nama Lengkap</span>
            <span class="text-teal-700 font-semibold">{{ santri?.nama || '-' }}</span>
          </li>
          <li class="py-3 flex justify-between items-center">
            <span class="text-sm font-medium text-gray-700">NISN</span>
            <span class="text-teal-700">{{ santri?.nisn || '-' }}</span>
          </li>
          <li class="py-3 flex justify-between items-center">
            <span class="text-sm font-medium text-gray-700">Jenis Kelamin</span>
            <span class="text-teal-700">{{ santri?.jenis_kelamin || '-' }}</span>
          </li>
          <li class="py-3 flex justify-between items-center">
            <span class="text-sm font-medium text-gray-700">Tempat Lahir</span>
            <span class="text-teal-700">{{ santri?.tempat_lahir || '-' }}</span>
          </li>
          <li class="py-3 flex justify-between items-center">
            <span class="text-sm font-medium text-gray-700">Tanggal Lahir</span>
            <span class="text-teal-700">{{ formatDate(santri?.tanggal_lahir) || '-' }}</span>
          </li>
          <li class="py-3 flex justify-between items-center">
            <span class="text-sm font-medium text-gray-700">Asal Sekolah</span>
            <span class="text-teal-700">{{ santri?.asal_sekolah || '-' }}</span>
          </li>
          <li class="py-3 flex justify-between items-center">
            <span class="text-sm font-medium text-gray-700">Anak Ke</span>
            <span class="text-teal-700">{{ santri?.anak_ke || '-' }} dari {{ santri?.jumlah_saudara || '-' }} saudara</span>
          </li>
        </ul>
      </div>

      <!-- Data Orang Tua Card -->
      <div class="relative bg-white rounded-lg shadow-md p-6 border border-teal-200 group">
        <button @click="openEdit('orangtua')"
          class="absolute top-4 right-4 bg-teal-600 hover:bg-teal-700 text-white font-semibold rounded-lg shadow px-6 py-2 transition-all duration-150 focus:outline-none focus:ring-2 focus:ring-teal-400"
          title="Edit Data Orang Tua"
          :disabled="updating">
          Edit
        </button>
        <h3 class="text-lg font-bold text-teal-700 flex items-center mb-6">
          <i class="fas fa-users text-teal-600 mr-3"></i>
          Data Orang Tua
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
          <!-- Data Ayah -->
          <ul class="divide-y divide-gray-200">
            <li class="py-3 flex justify-between items-center font-semibold text-teal-700">Data Ayah</li>
            <li class="py-3 flex justify-between items-center">
              <span class="text-sm text-gray-600">Nama</span>
                <span class="text-sm font-medium text-gray-800">{{ santri?.orang_tua?.nama_ayah || '-' }}</span>
            </li>
            <li class="py-3 flex justify-between items-center">
              <span class="text-sm text-gray-600">Pekerjaan</span>
                <span class="text-sm font-medium text-gray-800">{{ santri?.orang_tua?.pekerjaan_ayah || '-' }}</span>
            </li>
            <li class="py-3 flex justify-between items-center">
              <span class="text-sm text-gray-600">Pendidikan</span>
                <span class="text-sm font-medium text-gray-800">{{ santri?.orang_tua?.pendidikan_ayah || '-' }}</span>
            </li>
            <li class="py-3 flex justify-between items-center">
              <span class="text-sm text-gray-600">WhatsApp</span>
                <span class="text-sm font-medium text-gray-800">{{ santri?.orang_tua?.wa_ayah || '-' }}</span>
            </li>
          </ul>
          <!-- Data Ibu -->
          <ul class="divide-y divide-gray-200">
            <li class="py-3 flex justify-between items-center font-semibold text-teal-700">Data Ibu</li>
            <li class="py-3 flex justify-between items-center">
              <span class="text-sm text-gray-600">Nama</span>
                <span class="text-sm font-medium text-gray-800">{{ santri?.orang_tua?.nama_ibu || '-' }}</span>
            </li>
            <li class="py-3 flex justify-between items-center">
              <span class="text-sm text-gray-600">Pekerjaan</span>
                <span class="text-sm font-medium text-gray-800">{{ santri?.orang_tua?.pekerjaan_ibu || '-' }}</span>
            </li>
            <li class="py-3 flex justify-between items-center">
              <span class="text-sm text-gray-600">Pendidikan</span>
                <span class="text-sm font-medium text-gray-800">{{ santri?.orang_tua?.pendidikan_ibu || '-' }}</span>
            </li>
            <li class="py-3 flex justify-between items-center">
              <span class="text-sm text-gray-600">WhatsApp</span>
                <span class="text-sm font-medium text-gray-800">{{ santri?.orang_tua?.wa_ibu || '-' }}</span>
            </li>
          </ul>
        </div>
      </div>

      <!-- Alamat Card -->
      <div class="relative bg-white rounded-lg shadow-md p-6 border border-teal-200 group">
        <button @click="openEdit('alamat')"
          class="absolute top-4 right-4 bg-teal-600 hover:bg-teal-700 text-white font-semibold rounded-lg shadow px-6 py-2 transition-all duration-150 focus:outline-none focus:ring-2 focus:ring-teal-400"
          title="Edit Alamat"
          :disabled="updating">
          Edit
        </button>
        <h3 class="text-lg font-bold text-teal-700 flex items-center mb-6">
          <i class="fas fa-map-marker-alt text-teal-600 mr-3"></i>
          Alamat
        </h3>
        <ul class="divide-y divide-gray-200">
          <li class="py-3 flex justify-between items-center">
            <span class="text-sm text-gray-600">Alamat Lengkap</span>
            <span class="text-sm font-medium text-gray-800">{{ santri?.orang_tua?.alamat || '-' }}</span>
          </li>
          <li class="py-3 flex justify-between items-center">
            <span class="text-sm text-gray-600">Provinsi</span>
            <span class="text-sm font-medium text-gray-800">{{ getNamaWilayah('provinsi', santri?.orang_tua?.provinsi_id) }}</span>
          </li>
          <li class="py-3 flex justify-between items-center">
            <span class="text-sm text-gray-600">Kabupaten/Kota</span>
            <span class="text-sm font-medium text-gray-800">{{ getNamaWilayah('kabupaten', santri?.orang_tua?.kabupaten_id) }}</span>
          </li>
          <li class="py-3 flex justify-between items-center">
            <span class="text-sm text-gray-600">Kecamatan</span>
            <span class="text-sm font-medium text-gray-800">{{ getNamaWilayah('kecamatan', santri?.orang_tua?.kecamatan_id) }}</span>
          </li>
          <li class="py-3 flex justify-between items-center">
            <span class="text-sm text-gray-600">Desa/Kelurahan</span>
            <span class="text-sm font-medium text-gray-800">{{ getNamaWilayah('desa', santri?.orang_tua?.desa_id) }}</span>
          </li>
          <li class="py-3 flex justify-between items-center">
            <span class="text-sm text-gray-600">Kode Pos</span>
            <span class="text-sm font-medium text-gray-800">{{ santri?.orang_tua?.kode_pos || '-' }}</span>
          </li>
        </ul>
      </div>

      <!-- Loading & Error State -->
      <div v-if="loading" class="flex justify-center items-center py-12">
        <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-teal-600"></div>
      </div>
      <div v-else-if="error" class="text-center text-red-600 py-8">
        <i class="fas fa-exclamation-triangle text-3xl mb-2"></i>
        <p>{{ error }}</p>
      </div>
      <!-- Edit Modals -->
      <EditSantriForm v-if="showEdit==='santri'" :show="true" :data="santri" @close="showEdit=null" @update="onUpdateSantri" />
      <EditOrangTuaForm v-if="showEdit==='orangtua'" :show="true" :data="santri?.orang_tua" @close="showEdit=null" @update="onUpdateOrangTua" />
      <EditAlamatForm v-if="showEdit==='alamat'" :show="true" :data="santri?.orang_tua" @close="showEdit=null" @update="onUpdateAlamat" />
    </div>
  </div>
</template>

<script>
import EditSantriForm from './EditSantriForm.vue';
import EditOrangTuaForm from './EditOrangTuaForm.vue';
import EditAlamatForm from './EditAlamatForm.vue';
import LoadingOverlay from '@/components/common/LoadingOverlay.vue';

export default {
  name: 'SantriData',
  components: { EditSantriForm, EditOrangTuaForm, EditAlamatForm, LoadingOverlay },
  data() {
    return {
      loading: true,
      error: '',
      santri: null,
      showEdit: null,
      updating: false,
      showLoadingOverlay: false,
      loadingProgress: 0,
      provinceMap: {},
      regencyMap: {},
      districtMap: {},
      villageMap: {},
      wilayahLoading: false
    }
  },
  async mounted() {
    await this.fetchSantriData();
    this.fetchWilayahMaps();
  },
  methods: {
    async fetchSantriData() {
      try {
        this.loading = true;
        const nomorPendaftaran = this.$route.params.nomor_pendaftaran || sessionStorage.getItem('nomor_pendaftaran');
        if (!nomorPendaftaran) throw new Error('Nomor pendaftaran tidak ditemukan!');
        const response = await axios.get(`/api/santri/${nomorPendaftaran}/pembayaran`);
        this.santri = response.data.santri;
      } catch (err) {
        this.error = err.response?.data?.error || err.message || 'Gagal mengambil data santri.';
      } finally {
        this.loading = false;
      }
    },
    formatDate(date) {
      if (!date) return '-';
      return new Date(date).toLocaleDateString('id-ID', {
        day: 'numeric',
        month: 'long',
        year: 'numeric'
      });
    },
    editData(card) {
      // TODO: Implement edit per card
      let title = '';
      switch(card) {
        case 'santri': title = 'Edit Data Calon Santri'; break;
        case 'orangtua': title = 'Edit Data Orang Tua'; break;
        case 'alamat': title = 'Edit Alamat'; break;
        default: title = 'Edit Data';
      }
      Swal.fire({
        title,
        text: 'Fitur edit akan segera hadir!',
        icon: 'info',
        confirmButtonText: 'OK'
      });
    },
    openEdit(card) {
      this.showEdit = card;
    },
    async onUpdateSantri(data) {
      try {
      this.showEdit = null;
        this.showLoadingOverlay = true;
        this.loadingProgress = 0;
        let progressInterval = setInterval(() => {
          if (this.loadingProgress < 90) this.loadingProgress += 10;
        }, 100);
        const nomorPendaftaran = this.$route.params.nomor_pendaftaran || sessionStorage.getItem('nomor_pendaftaran');
        const response = await axios.put(`/api/santri/${nomorPendaftaran}`, data);
        clearInterval(progressInterval);
        this.loadingProgress = 100;
        await this.fetchSantriData();
        this.showLoadingOverlay = false;
        this.loadingProgress = 0;
        await Swal.fire({
          title: 'Berhasil!',
          text: 'Data santri berhasil diperbarui',
          icon: 'success',
          showConfirmButton: false,
          timer: 3000
        });
      } catch (err) {
        this.showLoadingOverlay = false;
        this.loadingProgress = 0;
        Swal.fire({
          title: 'Error!',
          text: err.response?.data?.message || 'Gagal memperbarui data santri',
          icon: 'error',
          confirmButtonText: 'OK'
        });
      }
    },
    async onUpdateOrangTua(data) {
      try {
      this.showEdit = null;
        this.showLoadingOverlay = true;
        this.loadingProgress = 0;
        let progressInterval = setInterval(() => {
          if (this.loadingProgress < 90) this.loadingProgress += 10;
        }, 100);
        const nomorPendaftaran = this.$route.params.nomor_pendaftaran || sessionStorage.getItem('nomor_pendaftaran');
        const response = await axios.put(`/api/santri/${nomorPendaftaran}/orang-tua`, data);
        clearInterval(progressInterval);
        this.loadingProgress = 100;
        await this.fetchSantriData();
        this.showLoadingOverlay = false;
        this.loadingProgress = 0;
        await Swal.fire({
          title: 'Berhasil!',
          text: 'Data orang tua berhasil diperbarui',
          icon: 'success',
          showConfirmButton: false,
          timer: 3000
        });
      } catch (err) {
        this.showLoadingOverlay = false;
        this.loadingProgress = 0;
        Swal.fire({
          title: 'Error!',
          text: err.response?.data?.message || 'Gagal memperbarui data orang tua',
          icon: 'error',
          confirmButtonText: 'OK'
        });
      }
    },
    async onUpdateAlamat(data) {
      try {
      this.showEdit = null;
        this.showLoadingOverlay = true;
        this.loadingProgress = 0;
        let progressInterval = setInterval(() => {
          if (this.loadingProgress < 90) this.loadingProgress += 10;
        }, 100);
        const nomorPendaftaran = this.$route.params.nomor_pendaftaran || sessionStorage.getItem('nomor_pendaftaran');
        const response = await axios.put(`/api/santri/${nomorPendaftaran}/alamat`, data);
        clearInterval(progressInterval);
        this.loadingProgress = 100;
        await this.fetchSantriData();
        this.showLoadingOverlay = false;
        this.loadingProgress = 0;
        await Swal.fire({
          title: 'Berhasil!',
          text: 'Data alamat berhasil diperbarui',
          icon: 'success',
          showConfirmButton: false,
          timer: 3000
        });
      } catch (err) {
        this.showLoadingOverlay = false;
        this.loadingProgress = 0;
        Swal.fire({
          title: 'Error!',
          text: err.response?.data?.message || 'Gagal memperbarui data alamat',
          icon: 'error',
          confirmButtonText: 'OK'
        });
      }
    },
    async fetchWilayahMaps() {
      this.wilayahLoading = true;
      const API_BASE_URL = 'https://www.emsifa.com/api-wilayah-indonesia/api';
      try {
        const provId = this.santri?.orang_tua?.provinsi_id;
        const kabId = this.santri?.orang_tua?.kabupaten_id;
        const kecId = this.santri?.orang_tua?.kecamatan_id;
        const desaId = this.santri?.orang_tua?.desa_id;
        // Provinsi
        if (provId) {
          const provRes = await fetch(`${API_BASE_URL}/provinces.json`);
          const provs = await provRes.json();
          const found = provs.find(p => p.id == provId);
          if (found) this.provinceMap[provId] = found.name;
        }
        // Kabupaten
        if (provId && kabId) {
          const regRes = await fetch(`${API_BASE_URL}/regencies/${provId}.json`);
          const regs = await regRes.json();
          const found = regs.find(r => r.id == kabId);
          if (found) this.regencyMap[kabId] = found.name;
        }
        // Kecamatan
        if (kabId && kecId) {
          const distRes = await fetch(`${API_BASE_URL}/districts/${kabId}.json`);
          const dists = await distRes.json();
          const found = dists.find(d => d.id == kecId);
          if (found) this.districtMap[kecId] = found.name;
        }
        // Desa
        if (kecId && desaId) {
          const desaRes = await fetch(`${API_BASE_URL}/villages/${kecId}.json`);
          const desas = await desaRes.json();
          const found = desas.find(v => v.id == desaId);
          if (found) this.villageMap[desaId] = found.name;
        }
      } catch (e) {
        console.error('Gagal fetch data wilayah:', e);
      } finally {
        this.wilayahLoading = false;
      }
    },
    getNamaWilayah(type, id) {
      if (!id) return '-';
      if (type === 'provinsi') return this.provinceMap[id] || '-';
      if (type === 'kabupaten') return this.regencyMap[id] || '-';
      if (type === 'kecamatan') return this.districtMap[id] || '-';
      if (type === 'desa') return this.villageMap[id] || '-';
      return '-';
    }
  }
}
</script>

<style scoped>
.bg-white {
  box-shadow: 0 4px 24px 0 rgba(0,0,0,0.04);
}

/* Animasi loading biar ga ngebosenin */
.animate-spin {
  animation: spin 1s linear infinite;
}
@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}
</style>
