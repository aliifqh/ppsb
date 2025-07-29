<template>
  <div class="py-2 md:py-3">
    <div class="w-full px-2">
      <!-- Header -->
      <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-semibold text-gray-800">Trashed Santri</h2>
        <div class="flex space-x-2">
          <button @click="handleBack" class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300 focus:outline-none focus:border-gray-400 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
            <i class="fas fa-arrow-left mr-2"></i>
            Kembali
          </button>
        </div>
      </div>
      <!-- Search -->
      <div class="mb-6">
        <SearchBar
          v-model="search"
          :placeholder="'Nama / NISN / Nomor Pendaftaran'"
          :loading="loading"
          @input="fetchSantri(1)"
        />
      </div>

      <!-- Bulk Action Bar -->
      <transition
          enter-active-class="transition ease-out duration-200"
          enter-from-class="transform opacity-0 -translate-y-4"
          enter-to-class="transform opacity-100 translate-y-0"
          leave-active-class="transition ease-in duration-150"
          leave-from-class="transform opacity-100 translate-y-0"
          leave-to-class="transform opacity-0 -translate-y-4"
      >
          <div v-if="selectedSantri.length > 0" class="mb-4 bg-yellow-50 border border-yellow-200 shadow-sm rounded-lg p-3">
              <div class="flex items-center justify-between">
                  <span class="text-sm font-medium text-yellow-800">{{ selectedSantri.length }} santri dipilih</span>
                  <div class="flex items-center space-x-2">
                      <button @click="bulkRestore" class="px-3 py-1.5 text-xs font-medium text-white bg-green-600 rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 flex items-center">
                          <i class="fas fa-undo-alt mr-2"></i> Pulihkan
                      </button>
                      <button @click="bulkForceDelete" class="px-3 py-1.5 text-xs font-medium text-white bg-red-600 rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 flex items-center">
                          <i class="fas fa-trash-alt mr-2"></i> Hapus Permanen
                      </button>
                  </div>
              </div>
          </div>
      </transition>

      <!-- Table Card -->
      <div class="bg-white rounded-lg shadow-[0_2px_15px_-3px_rgba(0,0,0,0.07),0_10px_20px_-2px_rgba(0,0,0,0.04)] hover:shadow-[0_8px_17px_2px_rgba(0,0,0,0.14),0_3px_14px_2px_rgba(0,0,0,0.12),0_5px_5px_-3px_rgba(0,0,0,0.2)] transition-shadow duration-300 p-6">
        <ModernTable
          v-model="selectedSantri"
          :columns="columns"
          :items="paginatedSantri"
          :loading="loading"
          :row-clickable="true"
          :selectable="true"
          @sort-changed="handleSort"
          @row-dblclick="handleRowDblClick"
        >
          <template #no="{ index }">
            {{ (currentPage - 1) * perPage + index + 1 }}
          </template>
          <template #nama_lengkap="{ item }">
            <div class="font-medium text-gray-800">{{ item.nama }}</div>
            <div class="text-xs text-gray-500">{{ item.nomor_pendaftaran }}</div>
          </template>
          <template #dihapus_pada="{ item }">
            {{ formatTanggal(item.deleted_at) }}
          </template>
          <template #aksi="{ item }">
            <div class="flex space-x-2">
              <button @click="confirmRestore(item.id)" class="text-green-500 hover:text-green-700" title="Pulihkan">
                <i class="fas fa-undo-alt"></i>
              </button>
              <button @click="confirmDelete(item.id)" class="text-red-500 hover:text-red-700" title="Hapus Permanen">
                <i class="fas fa-trash"></i>
              </button>
            </div>
          </template>
        </ModernTable>
      </div>
      <ModernPagination
        :current-page="currentPage"
        :total-pages="totalPages"
        :per-page="perPage"
        :total-items="totalItems"
        @page-changed="changePage"
        @per-page-changed="handlePerPageChange"
        class="mt-6"
      />
    </div>

    <!-- Loading Overlay -->
    <LoadingOverlay
      :show="loadingOverlay.show"
      :title="loadingOverlay.title"
      :subtitle="loadingOverlay.subtitle"
      :icon="loadingOverlay.icon"
      :show-progress="loadingOverlay.showProgress"
      :progress="loadingOverlay.progress"
      :progress-text="loadingOverlay.progressText"
      :show-cancel="loadingOverlay.showCancel"
      @cancel="cancelLoading"
    />
  </div>
</template>

<script>
import ModernTable from '../../common/ModernTable.vue'
import SearchBar from '../../common/SearchBar.vue'
import ActionButtons from '../../common/ActionButtons.vue'
import LoadingOverlay from '../../common/LoadingOverlay.vue'
import ModernPagination from '../../common/ModernPagination.vue'
import { swalConfirm } from '../../common/Swal2Dialog.js'

export default {
  components: {
    ModernTable,
    SearchBar,
    ActionButtons,
    LoadingOverlay,
    ModernPagination
  },
  data() {
    return {
      santriList: [],
      selectedSantri: [],
      totalPages: 1,
      totalItems: 0,
      search: '',
      loading: false,
      sortKey: 'deleted_at',
      sortAsc: false,
      currentPage: 1,
      perPage: 10,
      searchDebounce: null,
      columns: [
        { key: 'no', label: 'No' },
        { key: 'aksi', label: 'Aksi' },
        { key: 'nama_lengkap', label: 'Nama Lengkap' },
        { key: 'nisn', label: 'NISN' },
        { key: 'unit', label: 'Unit' },
        { key: 'status_tes', label: 'Status Tes' },
        { key: 'dihapus_pada', label: 'Dihapus Pada' }
      ],
      visibleColumns: ['no','aksi','nama_lengkap','nisn','unit','status_tes','dihapus_pada'],
      loadingForceDelete: null,
      loadingOverlay: {
        show: false,
        title: 'Memproses...',
        subtitle: 'Mohon tunggu sebentar',
        icon: 'fas fa-cog',
        showProgress: false,
        progress: 0,
        progressText: 'Memproses data...',
        showCancel: false
      }
    }
  },
  watch: {
    search() {
      clearTimeout(this.searchDebounce);
      this.searchDebounce = setTimeout(() => {
        this.currentPage = 1;
        this.fetchSantri();
      }, 500);
    }
  },
  computed: {
    paginatedSantri() {
      return this.santriList;
    },
  },
  mounted() {
    this.fetchSantri();
  },
  methods: {
    // Loading Overlay Methods
    showLoading(title = 'Memproses...', subtitle = 'Mohon tunggu sebentar', icon = 'fas fa-cog', showProgress = false, showCancel = false) {
      this.loadingOverlay = {
        show: true,
        title,
        subtitle,
        icon,
        showProgress,
        progress: 0,
        progressText: 'Memproses data...',
        showCancel
      };
    },
    hideLoading() {
      this.loadingOverlay.show = false;
    },
    updateProgress(progress, text = 'Memproses data...') {
      this.loadingOverlay.progress = progress;
      this.loadingOverlay.progressText = text;
    },
    cancelLoading() {
      this.hideLoading();
      // Bisa ditambahkan logic untuk cancel operasi yang sedang berjalan
    },

    async fetchSantri() {
      this.loading = true;
      try {
        const params = {
          page: this.currentPage,
          perPage: this.perPage,
          search: this.search,
          sortKey: this.sortKey,
          sortAsc: this.sortAsc,
        };
        // Use the new trashed data endpoint
        const response = await axios.get('/admin/santri/trashed/data', { params });
        this.santriList = response.data.data;
        this.totalPages = response.data.last_page;
        this.totalItems = response.data.total;
        this.currentPage = response.data.current_page;
      } catch (error) {
        console.error("Error fetching trashed santri:", error);
        Toast.fire({ icon: 'error', title: 'Gagal mengambil data terhapus' });
      } finally {
        this.loading = false;
      }
    },
    handleSort({ key, asc }) {
      this.sortKey = key;
      this.sortAsc = asc;
      this.currentPage = 1;
      this.fetchSantri();
    },
    changePage(page) {
      if (page >= 1 && page <= this.totalPages && page !== this.currentPage) {
        this.currentPage = page;
        this.fetchSantri();
      }
    },
    resetFilter() {
      this.search = ''
      this.fetchSantri(1)
    },
    handleBack() {
      if (this.$listeners && this.$listeners.back) {
        this.$emit('back')
      } else {
        window.location.href = '/admin/santri'
      }
    },
    formatTanggal(tanggal) {
      if (!tanggal) return '-';
      return new Date(tanggal).toLocaleString('id-ID', {
        year: 'numeric',
        month: 'long',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
      });
    },
    getTesStatusClass(status) {
      switch (status) {
        case 'Lulus': return 'bg-green-100 text-green-800'
        case 'Tidak Lulus': return 'bg-red-100 text-red-800'
        default: return 'bg-yellow-100 text-yellow-800'
      }
    },
    async confirmRestore(id) {
      const result = await swalConfirm({
        title: 'Kembalikan data?',
        text: 'Data santri akan dikembalikan!',
        icon: 'question',
        confirmButtonText: 'Ya, kembalikan',
        cancelButtonText: 'Batal'
      })
      if (result.isConfirmed) {
        this.restoreSantri(id)
      }
    },
    async restoreSantri(id) {
      this.showLoading(
        'Mengembalikan Data...',
        'Sedang mengembalikan data santri',
        'fas fa-trash-restore',
        true,
        true
      );

      try {
        this.updateProgress(20, 'Menyiapkan data...');
        await new Promise(resolve => setTimeout(resolve, 300));

        this.updateProgress(50, 'Mengembalikan data...');
        await axios.post(`/admin/santri/${id}/restore`);

        this.updateProgress(80, 'Memperbarui tampilan...');
        await new Promise(resolve => setTimeout(resolve, 200));

        this.updateProgress(100, 'Selesai!');
        await new Promise(resolve => setTimeout(resolve, 300));

        Toast.fire({ icon: 'success', title: 'Santri berhasil dipulihkan.' });
        this.fetchSantri();
      } catch (e) {
        Toast.fire({ icon: 'error', title: 'Gagal memulihkan santri.' });
      } finally {
        this.hideLoading();
      }
    },
    async confirmDelete(id) {
      const result = await swalConfirm({
        title: 'Hapus permanen?',
        text: 'PERHATIAN! Data yang dihapus permanen tidak dapat dikembalikan.',
        icon: 'warning',
        confirmButtonText: 'Ya, hapus permanen',
        cancelButtonText: 'Batal'
      })
      if (result.isConfirmed) {
        this.forceDeleteSantri(id)
      }
    },
    handleRowDblClick({ item }) {
      this.confirmRestore(item.id);
    },
    async forceDeleteSantri(id) {
      this.loadingForceDelete = id;
      this.showLoading(
        'Menghapus Permanen...',
        'Sedang menghapus data secara permanen',
        'fas fa-trash-alt',
        true,
        true
      );

      try {
        this.updateProgress(20, 'Menyiapkan data...');
        await new Promise(resolve => setTimeout(resolve, 300));

        this.updateProgress(50, 'Menghapus data...');
        await axios.delete(`/admin/santri/${id}/force-delete`);

        this.updateProgress(80, 'Memperbarui tampilan...');
        await new Promise(resolve => setTimeout(resolve, 200));

        this.updateProgress(100, 'Selesai!');
        await new Promise(resolve => setTimeout(resolve, 300));

        Toast.fire({ icon: 'success', title: 'Santri berhasil dihapus permanen.' });
        this.fetchSantri();
      } catch (e) {
        let msg = 'Gagal menghapus data';
        if (e.response && e.response.data && e.response.data.message) {
          msg = e.response.data.message;
        }
        Toast.fire({ icon: 'error', title: msg });
        console.error('Force delete error:', e);
      } finally {
        this.loadingForceDelete = null;
        this.hideLoading();
      }
    },
    handlePerPageChange(newPerPage) {
        this.perPage = newPerPage;
        this.currentPage = 1;
        this.fetchSantri();
    },
    async bulkRestore() {
        const result = await swalConfirm({
            title: `Yakin pulihkan ${this.selectedSantri.length} santri?`,
            text: 'Data yang dipilih akan dikembalikan.',
            icon: 'question',
            confirmButtonText: 'Ya, pulihkan!',
            cancelButtonText: 'Batal'
        });

        if (result.isConfirmed) {
            this.showLoading('Memulihkan Data...', `Sedang memulihkan ${this.selectedSantri.length} data santri...`);
            try {
                const ids = this.selectedSantri.map(s => s.id);
                await axios.post('/admin/santri/bulk-restore', { ids });

                await this.fetchSantri();
                this.selectedSantri = []; // Clear selection

                Toast.fire({
                    icon: 'success',
                    title: 'Santri yang dipilih berhasil dipulihkan!'
                });
            } catch (error) {
                console.error("Error during bulk restore:", error);
                Toast.fire({
                    icon: 'error',
                    title: 'Gagal memulihkan data!',
                    text: error.response?.data?.message || 'Terjadi kesalahan di server.'
                });
            } finally {
                this.hideLoading();
            }
        }
    },
    async bulkForceDelete() {
        const result = await swalConfirm({
            title: `Hapus permanen ${this.selectedSantri.length} santri?`,
            text: 'PERHATIAN! Data yang dihapus permanen tidak dapat dikembalikan.',
            icon: 'warning',
            confirmButtonText: 'Ya, hapus permanen',
            cancelButtonText: 'Batal'
        });

        if (result.isConfirmed) {
            this.showLoading('Menghapus Permanen...', `Sedang menghapus ${this.selectedSantri.length} data santri...`);
            try {
                const ids = this.selectedSantri.map(s => s.id);
                await axios.post('/admin/santri/bulk-force-delete', { ids });

                await this.fetchSantri();
                this.selectedSantri = []; // Clear selection

                Toast.fire({
                    icon: 'success',
                    title: 'Santri yang dipilih berhasil dihapus permanen!'
                });
            } catch (error) {
                console.error("Error during bulk force delete:", error);
                Toast.fire({
                    icon: 'error',
                    title: 'Gagal menghapus data!',
                    text: error.response?.data?.message || 'Terjadi kesalahan di server.'
                });
            } finally {
                this.hideLoading();
            }
        }
    },
  }
}
</script>
