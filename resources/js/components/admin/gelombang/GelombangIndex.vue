<template>
  <div class="py-2 md:py-3">
    <!-- Modal Form Create/Edit Gelombang -->
    <ModernModal :show="showFormModal" @close="closeFormModal" :title="formMode === 'create' ? 'Tambah Gelombang' : 'Edit Gelombang'">
      <form @submit.prevent="submitForm">
        <div class="space-y-4">
          <div>
            <label class="block text-sm font-medium text-gray-700">Nama Gelombang</label>
            <input v-model="form.nama" @input="form.nama = toTitleCase(form.nama)" type="text" class="mt-1 block w-full rounded border-gray-300" required />
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700">Tanggal Mulai</label>
            <input v-model="form.tanggal_mulai" type="date" class="mt-1 block w-full rounded border-gray-300" required placeholder="yyyy-mm-dd" />
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700">Tanggal Selesai</label>
            <input v-model="form.tanggal_selesai" type="date" class="mt-1 block w-full rounded border-gray-300" required placeholder="yyyy-mm-dd" />
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700">Biaya Administrasi</label>
            <div class="flex items-center">
              <span class="text-gray-500 mr-2">Rp</span>
              <input v-model="form.biaya_administrasi" type="text" class="mt-1 block w-full rounded border-gray-300" @input="formatRupiahField('biaya_administrasi')" required />
            </div>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700">Biaya Daftar Ulang</label>
            <div class="flex items-center">
              <span class="text-gray-500 mr-2">Rp</span>
              <input v-model="form.biaya_daftar_ulang" type="text" class="mt-1 block w-full rounded border-gray-300" @input="formatRupiahField('biaya_daftar_ulang')" required />
            </div>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700">Status</label>
            <label class="inline-flex items-center mt-1">
              <input type="checkbox" v-model="form.status" class="rounded border-gray-300 text-emerald-600 shadow-sm focus:border-emerald-300 focus:ring focus:ring-emerald-200 focus:ring-opacity-50" />
              <span class="ml-2 text-sm text-gray-600">Aktif</span>
            </label>
          </div>
        </div>
        <div class="flex justify-end space-x-4 mt-6">
          <LoadingButton
            text="Batal"
            variant="secondary"
            @click="closeFormModal"
            :loading="false"
            type="button"
          />
          <LoadingButton
            :text="formMode === 'create' ? 'Simpan' : 'Simpan Perubahan'"
            :loading="formLoading"
            :icon="formMode === 'create' ? 'fas fa-plus' : 'fas fa-save'"
            type="submit"
          />
        </div>
      </form>
      <LoadingOverlay :show="formLoading" title="Menyimpan..." subtitle="Mohon tunggu sebentar" />
    </ModernModal>

    <!-- Header & Tombol Tambah -->
    <div class="flex justify-between items-center mb-6">
      <h2 class="text-2xl font-semibold text-gray-800">Data Gelombang</h2>
      <div class="flex space-x-2">
        <LoadingButton
          text="Tambah Gelombang"
          icon="fas fa-plus"
          @click="openCreateModal"
          variant="primary"
          size="md"
        />
      </div>
    </div>

    <!-- Alert Sukses/Error -->
    <div v-if="alert.message" :class="alert.type === 'success' ? 'bg-emerald-100 text-emerald-700' : 'bg-red-100 text-red-700'" class="rounded p-3 mb-4">
      {{ alert.message }}
    </div>

    <!-- SearchBar -->
    <div class="mb-6">
      <SearchBar v-model="search" placeholder="Cari nama gelombang..." />
    </div>

    <!-- Table Card -->
    <div class="bg-white rounded-lg shadow-[0_2px_15px_-3px_rgba(0,0,0,0.07),0_10px_20px_-2px_rgba(0,0,0,0.04)] hover:shadow-[0_8px_17px_2px_rgba(0,0,0,0.14),0_3px_14px_2px_rgba(0,0,0,0.12),0_5px_5px_-3px_rgba(0,0,0,0.2)] transition-shadow duration-300 p-6">
      <ModernTable
        :columns="columns"
        :items="paginatedGelombang"
        :loading="loading"
        :sortable-columns="['nama', 'tanggal_mulai', 'tanggal_selesai']"
        :rowClickable="true"
        @row-dblclick="openEditModal($event.item)"
      >
        <template #status="{ item }">
          <div class="flex items-center space-x-2">
            <div
              class="custom-switch"
              :class="item.status ? 'custom-switch--on' : 'custom-switch--off'"
              @click="toggleStatus(item)"
              :aria-checked="item.status"
              role="switch"
              tabindex="0"
            >
              <div class="custom-switch__slider"></div>
            </div>
            <span :class="item.status ? 'text-emerald-600 font-semibold' : 'text-gray-400'" class="text-sm font-medium">{{ item.status ? 'Aktif' : 'Tidak Aktif' }}</span>
          </div>
        </template>
        <template #tanggal_mulai="{ value }">
          {{ formatTanggalIndo(value) }}
        </template>
        <template #tanggal_selesai="{ value }">
          {{ formatTanggalIndo(value) }}
        </template>
        <template #biaya_administrasi="{ value }">
          Rp {{ formatRupiah(value) }}
        </template>
        <template #biaya_daftar_ulang="{ value }">
          Rp {{ formatRupiah(value) }}
        </template>
        <template #aksi="{ item }">
          <ActionButtons
            :actions="getActions(item)"
            @action="handleAction(item, $event)"
          />
        </template>
      </ModernTable>
      <ModernPagination
        :current-page="currentPage"
        :total-pages="totalPages"
        :perPage="perPage"
        :totalItems="filteredGelombang.length"
        @page-changed="currentPage = $event"
        class="mt-6"
      />
    </div>

    <!-- STATUS FORMULIR PENDAFTARAN (versi simpel) -->
    <transition name="fade-down">
        <div v-if="alertStatusShow" class="mt-6 flex items-center justify-between rounded-lg bg-emerald-50 border border-emerald-200 p-4 shadow-sm">
            <div class="flex items-center">
                <i class="fas fa-check-circle text-emerald-500 mr-3"></i>
                <p class="text-sm text-emerald-800">
                Formulir pendaftaran saat ini <b class="font-semibold">{{ activeGelombang ? 'terbuka' : 'tertutup' }}</b>.
                <a v-if="activeGelombang" href="/formulir" target="_blank" class="ml-2 font-semibold underline hover:text-emerald-600">
                    Buka Formulir <i class="fas fa-external-link-alt text-xs"></i>
                </a>
                </p>
            </div>
            <button class="text-emerald-500 hover:text-emerald-700" @click="alertStatusShow = false">
                <i class="fas fa-times"></i>
            </button>
        </div>
    </transition>
  </div>
</template>

<script>
import ModernTable from '@/components/common/ModernTable.vue';
import ActionButtons from '@/components/common/ActionButtons.vue';
import SearchBar from '@/components/common/SearchBar.vue';
import ModernPagination from '@/components/common/ModernPagination.vue';
import LoadingButton from '@/components/common/LoadingButton.vue';
import LoadingOverlay from '@/components/common/LoadingOverlay.vue';
import ModernModal from '@/components/common/ModernModal.vue';
import axios from 'axios';
import { swalConfirm, swalError } from '@/components/common/Swal2Dialog.js';

let alertTimeout = null;

export default {
  name: 'GelombangIndex',
  components: { ModernTable, ActionButtons, SearchBar, ModernPagination, LoadingButton, LoadingOverlay, ModernModal },
  data() {
    return {
      activeGelombang: false,
      alert: { message: '', type: 'success' },
      gelombang: [],
      loading: true,
      columns: [
        { key: 'status', label: 'Status' },
        { key: 'nama', label: 'Nama' },
        { key: 'tanggal_mulai', label: 'Tanggal Mulai' },
        { key: 'tanggal_selesai', label: 'Tanggal Selesai' },
        { key: 'biaya_administrasi', label: 'Biaya Administrasi' },
        { key: 'biaya_daftar_ulang', label: 'Biaya Daftar Ulang' },
        { key: 'aksi', label: 'Aksi' }
      ],
      search: '',
      currentPage: 1,
      perPage: 10,
      showFormModal: false,
      formMode: 'create',
      form: {
        nama: '',
        tanggal_mulai: '',
        tanggal_selesai: '',
        biaya_administrasi: '',
        biaya_daftar_ulang: '',
        status: false
      },
      formLoading: false,
      editId: null,
      deleteLoading: false,
      alertStatusShow: true,
      toggleLoadingId: null
    };
  },
  computed: {
    filteredGelombang() {
      if (!this.search) return this.gelombang;
      return this.gelombang.filter(g => g.nama.toLowerCase().includes(this.search.toLowerCase()));
    },
    totalPages() {
      return Math.max(1, Math.ceil(this.filteredGelombang.length / this.perPage));
    },
    paginatedGelombang() {
      const start = (this.currentPage - 1) * this.perPage;
      return this.filteredGelombang.slice(start, start + this.perPage);
    }
  },
  watch: {
    search() {
      this.currentPage = 1;
    }
  },
  mounted() {
    this.fetchGelombang();
    setTimeout(() => { this.alertStatusShow = false }, 10000);
  },
  methods: {
    setAlert(msg, type = 'success') {
      this.alert = { message: msg, type };
      if (alertTimeout) clearTimeout(alertTimeout);
      alertTimeout = setTimeout(() => { this.alert.message = ''; }, 3500);
    },
    async fetchGelombang() {
      this.loading = true;
      try {
        const res = await axios.get('/api/gelombang');
        this.gelombang = res.data;
        this.activeGelombang = this.gelombang.some(g => g.status && new Date(g.tanggal_mulai) <= new Date() && new Date(g.tanggal_selesai) >= new Date());
        this.alertStatusShow = true;
        setTimeout(() => { this.alertStatusShow = false }, 10000);
      } catch (e) {
        this.setAlert('Gagal mengambil data gelombang', 'error');
      } finally {
        this.loading = false;
      }
    },
    openCreateModal() {
      this.formMode = 'create';
      this.editId = null;
      this.form = {
        nama: '',
        tanggal_mulai: '',
        tanggal_selesai: '',
        biaya_administrasi: '',
        biaya_daftar_ulang: '',
        status: false
      };
      this.showFormModal = true;
    },
    openEditModal(item) {
      this.formMode = 'edit';
      this.editId = item.id;
      // Format tanggal ke yyyy-MM-dd agar input date langsung terisi
      const toDateInput = (date) => {
        if (!date) return '';
        const d = new Date(date);
        if (isNaN(d.getTime())) return '';
        return d.toISOString().slice(0, 10);
      };
      // Format biaya ke ribuan (300.000)
      const toRupiah = (val) => {
        if (!val) return '';
        let angka = val.toString().replace(/[^\d]/g, '');
        let rupiah = '', i = angka.length;
        while (i > 3) {
          rupiah = '.' + angka.slice(i - 3, i) + rupiah;
          i -= 3;
        }
        rupiah = angka.slice(0, i) + rupiah;
        return rupiah;
      };
      this.form = {
        nama: this.toTitleCase(item.nama),
        tanggal_mulai: toDateInput(item.tanggal_mulai),
        tanggal_selesai: toDateInput(item.tanggal_selesai),
        biaya_administrasi: toRupiah(item.biaya_administrasi),
        biaya_daftar_ulang: toRupiah(item.biaya_daftar_ulang),
        status: !!item.status
      };
      this.showFormModal = true;
    },
    closeFormModal() {
      this.showFormModal = false;
    },
    validateForm() {
      if (!this.form.nama || !this.form.tanggal_mulai || !this.form.tanggal_selesai || !this.form.biaya_administrasi || !this.form.biaya_daftar_ulang) {
        this.setAlert('Semua field wajib diisi!', 'error');
        return false;
      }
      // Validasi tanggal
      if (this.form.tanggal_selesai < this.form.tanggal_mulai) {
        this.setAlert('Tanggal selesai tidak boleh sebelum tanggal mulai!', 'error');
        return false;
      }
      // Validasi angka
      if (isNaN(this.form.biaya_administrasi.replace(/\./g, '')) || isNaN(this.form.biaya_daftar_ulang.replace(/\./g, ''))) {
        this.setAlert('Biaya hanya boleh angka!', 'error');
        return false;
      }
      return true;
    },
    async submitForm() {
      if (this.formLoading) return;
      if (!this.validateForm()) return;
      this.formLoading = true;
      const payload = {
        ...this.form,
        biaya_administrasi: this.form.biaya_administrasi.replace(/\./g, ''),
        biaya_daftar_ulang: this.form.biaya_daftar_ulang.replace(/\./g, '')
      };
      try {
        if (this.formMode === 'create') {
          await axios.post('/api/gelombang', payload);
          this.setAlert('Gelombang berhasil ditambah!');
        } else {
          await axios.put(`/api/gelombang/${this.editId}`, payload);
          this.setAlert('Gelombang berhasil diupdate!');
        }
        await this.fetchGelombang();
        this.closeFormModal();
      } catch (err) {
        let msg = 'Gagal menyimpan gelombang';
        if (err.response && err.response.data && err.response.data.message) {
          msg = err.response.data.message;
        }
        this.setAlert(msg, 'error');
      } finally {
        this.formLoading = false;
      }
    },
    formatRupiahField(field) {
      let angka = this.form[field].replace(/[^\d]/g, ''),
        rupiah = '',
        i = angka.length;
      while (i > 3) {
        rupiah = '.' + angka.slice(i - 3, i) + rupiah;
        i -= 3;
      }
      rupiah = angka.slice(0, i) + rupiah;
      this.form[field] = rupiah;
    },
    getActions(item) {
      const canBeDeleted = item.canBeDeleted !== false;
      return [
        {
          key: 'edit',
          label: 'Edit',
          icon: 'fas fa-edit',
          color: 'bg-gray-200 text-gray-700 hover:bg-gray-300',
          tooltip: 'Edit gelombang'
        },
        {
          key: 'delete',
          label: 'Hapus',
          icon: 'fas fa-trash-alt',
          color: 'bg-red-600 text-white hover:bg-red-700',
          disabled: !canBeDeleted,
          tooltip: canBeDeleted ? 'Hapus gelombang' : 'Tidak dapat dihapus karena masih ada santri terdaftar'
        }
      ];
    },
    handleAction(item, action) {
      if (action === 'edit') {
        this.openEditModal(item);
      } else if (action === 'delete') {
        this.confirmDelete(item);
      }
    },
    async confirmDelete(item) {
      if (this.deleteLoading) return;
      const result = await swalConfirm({
        title: 'Konfirmasi Hapus',
        text: 'Apakah Anda yakin ingin menghapus gelombang ini? Tindakan ini tidak dapat dibatalkan.',
        icon: 'warning',
        confirmButtonText: 'Ya, Hapus',
        cancelButtonText: 'Batal',
      });
      if (result.isConfirmed) {
        this.deleteGelombang(item);
      }
    },
    async deleteGelombang(item) {
      this.deleteLoading = true;
      try {
        await axios.delete(`/api/gelombang/${item.id}`);
        this.setAlert('Gelombang berhasil dihapus!');
        await this.fetchGelombang();
      } catch (err) {
        let msg = 'Gagal menghapus gelombang';
        if (err.response && err.response.data && err.response.data.error) {
          msg = err.response.data.error;
        }
        this.setAlert(msg, 'error');
      } finally {
        this.deleteLoading = false;
      }
    },
    formatRupiah(angka) {
      let number_string = angka ? angka.toString().replace(/[^\d]/g, '') : '',
        rupiah = '',
        i = number_string.length;
      while (i > 3) {
        rupiah = '.' + number_string.slice(i - 3, i) + rupiah;
        i -= 3;
      }
      rupiah = number_string.slice(0, i) + rupiah;
      return rupiah;
    },
    async toggleStatus(item) {
      if (this.toggleLoadingId === item.id) return;
      // Konfirmasi jika menonaktifkan gelombang aktif
      if (item.status) {
        const result = await swalConfirm({
          title: 'Nonaktifkan Gelombang?',
          text: 'Yakin ingin menonaktifkan gelombang ini? Calon santri tidak bisa daftar jika tidak ada gelombang aktif.',
          icon: 'warning',
          confirmButtonText: 'Ya, Nonaktifkan',
          cancelButtonText: 'Batal',
        });
        if (!result.isConfirmed) return;
      }
      this.toggleLoadingId = item.id;
      try {
        await axios.patch(`/api/gelombang/${item.id}`, {
          nama: item.nama,
          tanggal_mulai: item.tanggal_mulai,
          tanggal_selesai: item.tanggal_selesai,
          biaya_administrasi: String(item.biaya_administrasi),
          biaya_daftar_ulang: String(item.biaya_daftar_ulang),
          status: !item.status
        });
        this.setAlert('Status gelombang diubah!');
        await this.fetchGelombang();
      } catch (err) {
        let msg = 'Gagal mengubah status gelombang';
        if (err.response && err.response.data && err.response.data.message) {
          msg = err.response.data.message;
        }
        this.setAlert(msg, 'error');
      } finally {
        this.toggleLoadingId = null;
      }
    },
    formatTanggalIndo(date) {
      if (!date) return '-';
      const d = new Date(date);
      if (isNaN(d.getTime())) return '-';
      const bulan = [
        'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
        'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
      ];
      return `${d.getDate().toString().padStart(2, '0')} ${bulan[d.getMonth()]} ${d.getFullYear()}`;
    },
    toTitleCase(str) {
      return (str || '').replace(/\w\S*/g, (txt) => txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase());
    }
  }
};
</script>

<style scoped>
/* Style tambahan biar mirip Blade */
th, td {
  padding: 0.75rem 1rem;
  text-align: left;
}
th {
  background: #f3f4f6;
  font-weight: 600;
}
.animate-fadeIn {
  animation: fadeIn 0.2s ease-out;
}
@keyframes fadeIn {
  from { opacity: 0; transform: scale(0.95); }
  to { opacity: 1; transform: scale(1); }
}
.custom-switch {
  width: 48px;
  height: 28px;
  background: #e5e7eb;
  border-radius: 9999px;
  position: relative;
  cursor: pointer;
  transition: background 0.2s;
  box-shadow: 0 2px 8px rgba(0,0,0,0.06);
  display: flex;
  align-items: center;
}
.custom-switch__slider {
  width: 24px;
  height: 24px;
  background: #fff;
  border-radius: 50%;
  position: absolute;
  left: 2px;
  top: 2px;
  transition: left 0.2s cubic-bezier(.4,2,.6,1), background 0.2s;
  box-shadow: 0 1px 4px rgba(0,0,0,0.10);
}
.custom-switch--on {
  background: #059669;
}
.custom-switch--on .custom-switch__slider {
  left: 22px;
  background: #fff;
}
.custom-switch--off {
  background: #e5e7eb;
}
.custom-switch:active .custom-switch__slider {
  box-shadow: 0 2px 8px rgba(0,0,0,0.18);
}
.fade-down-enter-active,
.fade-down-leave-active {
  transition: all 0.3s ease-in-out;
}
.fade-down-enter-from,
.fade-down-leave-to {
  opacity: 0;
  transform: translateY(-10px);
}
</style>
