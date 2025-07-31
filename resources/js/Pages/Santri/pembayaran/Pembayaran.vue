<template>
  <div>
    <div class="max-w-4xl mx-auto px-4">
      <!-- Header -->
      <div class="text-center mb-8">
        <h1 class="text-3xl font-bold text-gray-800 mb-2">
          <i class="fas fa-credit-card text-teal-600 mr-3"></i>
          Informasi Pembayaran
        </h1>
        <p class="text-gray-600">Kelola pembayaran administrasi dan daftar ulang Anda</p>
      </div>

      <!-- Loading State -->
      <div v-if="loading" class="flex justify-center items-center py-12">
        <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-teal-600"></div>
      </div>

      <!-- Content -->
      <div v-else class="space-y-6">
        <!-- Status Utama Pembayaran -->
        <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-teal-600">
          <div class="flex items-center justify-between mb-4">
            <h2 class="text-xl font-semibold text-gray-800 flex items-center">
              <i class="fas fa-chart-line text-teal-600 mr-3"></i>
              Status Pembayaran
            </h2>
            <div class="flex space-x-2">
              <span class="px-3 py-1 text-xs font-medium rounded-full" :class="getOverallStatusClassTeal()">
                {{ getOverallStatusText() }}
              </span>
            </div>
          </div>

          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <!-- Progress Bar -->
            <div class="space-y-3">
              <div class="flex justify-between text-sm">
                <span class="text-gray-600">Progress Pembayaran</span>
                <span class="font-medium text-teal-600">{{ getProgressPercentage() }}%</span>
              </div>
              <div class="w-full bg-gray-200 rounded-full h-3">
                <div class="bg-gradient-to-r from-teal-500 to-teal-600 h-3 rounded-full transition-all duration-500"
                     :style="{ width: getProgressPercentage() + '%' }"></div>
              </div>
              <p class="text-xs text-gray-500">{{ getProgressDescription() }}</p>
            </div>

            <!-- Quick Stats -->
            <div class="grid grid-cols-2 gap-3">
              <div class="text-center p-3 bg-teal-50 rounded-lg">
                <div class="text-2xl font-bold text-teal-600">{{ formatCurrency(totalBiaya) }}</div>
                <div class="text-xs text-gray-600">Total Biaya</div>
              </div>
              <div class="text-center p-3 bg-teal-100 rounded-lg">
                <div class="text-2xl font-bold text-teal-700">{{ formatCurrency(totalDibayar) }}</div>
                <div class="text-xs text-gray-600">Sudah Dibayar</div>
              </div>
            </div>
          </div>
        </div>

        <!-- Biaya Administrasi Card -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
          <div class="bg-gradient-to-r from-teal-500 to-teal-600 p-4">
            <h3 class="text-lg font-semibold text-white flex items-center">
              <i class="fas fa-file-invoice-dollar mr-3"></i>
              Biaya Administrasi
            </h3>
          </div>

          <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
              <div class="text-center">
                <div class="text-2xl font-bold text-teal-600">{{ formatCurrency(santriData?.gelombang?.biaya_administrasi || 0) }}</div>
                <div class="text-sm text-gray-600">Nominal</div>
              </div>
              <div class="text-center">
                <span class="px-3 py-1 text-sm font-medium rounded-full" :class="getStatusClassTeal('administrasi')">
                  {{ getStatusText('administrasi') }}
                </span>
                <div class="text-sm text-gray-600 mt-1">Status</div>
              </div>
              <div class="text-center">
                <div class="text-lg font-semibold text-gray-800">
                  {{ getPaymentDate('administrasi') || '-' }}
                </div>
                <div class="text-sm text-gray-600">Tanggal Bayar</div>
              </div>
            </div>

            <!-- Action Button -->
            <div class="flex justify-center gap-3">
              <button
                v-if="canPayAdministrasi()"
                @click="openPaymentModal('administrasi')"
                class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-teal-600 to-teal-700 text-white font-medium rounded-lg hover:from-teal-700 hover:to-teal-800 focus:outline-none focus:ring-2 focus:ring-teal-500 focus:ring-offset-2 transition-all duration-200 transform hover:scale-105"
              >
                <i class="fas fa-credit-card mr-2"></i>
                Lakukan Pembayaran
              </button>
              <button
                v-else-if="canViewBukti('administrasi')"
                @click="viewBukti('administrasi')"
                class="inline-flex items-center px-6 py-3 bg-teal-600 text-white font-medium rounded-lg hover:bg-teal-700 focus:outline-none focus:ring-2 focus:ring-teal-500 focus:ring-offset-2 transition-all duration-200"
              >
                <i class="fas fa-eye mr-2"></i>
                Lihat Bukti
              </button>
              <button
                v-if="santriData?.pembayaran?.status_administrasi === 'diverifikasi'"
                @click="printInvoice('administrasi')"
                class="inline-flex items-center px-6 py-3 bg-blue-600 text-white font-bold rounded-lg shadow-lg hover:bg-blue-700 transition-all duration-200 ml-0"
              >
                <i class="fas fa-print mr-2"></i>
                Cetak Invoice
              </button>
            </div>
          </div>
        </div>

        <!-- Biaya Daftar Ulang Card -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
          <div class="bg-gradient-to-r from-teal-500 to-teal-700 p-4">
            <h3 class="text-lg font-semibold text-white flex items-center">
              <i class="fas fa-graduation-cap mr-3"></i>
              Biaya Daftar Ulang
            </h3>
          </div>

          <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
              <div class="text-center">
                <div class="text-2xl font-bold text-teal-600">{{ formatCurrency(santriData?.gelombang?.biaya_daftar_ulang || 0) }}</div>
                <div class="text-sm text-gray-600">Nominal</div>
              </div>
              <div class="text-center">
                <span class="px-3 py-1 text-sm font-medium rounded-full" :class="getStatusClassTeal('daftar_ulang')">
                  {{ getStatusText('daftar_ulang') }}
                </span>
                <div class="text-sm text-gray-600 mt-1">Status</div>
              </div>
              <div class="text-center">
                <div class="text-lg font-semibold text-gray-800">
                  {{ getPaymentDate('daftar_ulang') || '-' }}
                </div>
                <div class="text-sm text-gray-600">Tanggal Bayar</div>
              </div>
            </div>

            <!-- Action Button -->
            <div class="flex justify-center gap-3">
              <button
                v-if="canPayDaftarUlang()"
                @click="openPaymentModal('daftar_ulang')"
                class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-teal-600 to-teal-700 text-white font-medium rounded-lg hover:from-teal-700 hover:to-teal-800 focus:outline-none focus:ring-2 focus:ring-teal-500 focus:ring-offset-2 transition-all duration-200 transform hover:scale-105"
              >
                <i class="fas fa-credit-card mr-2"></i>
                Lakukan Pembayaran
              </button>
              <button
                v-else-if="canViewBukti('daftar_ulang')"
                @click="viewBukti('daftar_ulang')"
                class="inline-flex items-center px-6 py-3 bg-teal-600 text-white font-medium rounded-lg hover:bg-teal-700 focus:outline-none focus:ring-2 focus:ring-teal-500 focus:ring-offset-2 transition-all duration-200"
              >
                <i class="fas fa-eye mr-2"></i>
                Lihat Bukti
              </button>
              <button
                v-if="santriData?.pembayaran?.status_daftar_ulang === 'diverifikasi'"
                @click="printInvoice('daftar_ulang')"
                class="inline-flex items-center px-6 py-3 bg-blue-600 text-white font-bold rounded-lg shadow-lg hover:bg-blue-700 transition-all duration-200 ml-0"
              >
                <i class="fas fa-print mr-2"></i>
                Cetak Invoice
              </button>
              <div v-else class="text-center text-gray-500">
                <i class="fas fa-lock text-2xl mb-2"></i>
                <p class="text-sm">Harus bayar administrasi dulu</p>
              </div>
            </div>
          </div>
        </div>

        <!-- Panduan Pembayaran -->
        <div class="bg-white rounded-xl shadow-lg p-6">
          <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
            <i class="fas fa-info-circle text-teal-600 mr-3"></i>
            Panduan Pembayaran
          </h3>
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="space-y-3">
              <h4 class="font-medium text-gray-800">Cara Pembayaran:</h4>
              <ul class="space-y-2 text-sm text-gray-600">
                <li class="flex items-start">
                  <span class="bg-teal-100 text-teal-600 rounded-full w-5 h-5 flex items-center justify-center text-xs font-bold mr-3 mt-0.5">1</span>
                  Transfer ke rekening yang tertera
                </li>
                <li class="flex items-start">
                  <span class="bg-teal-100 text-teal-600 rounded-full w-5 h-5 flex items-center justify-center text-xs font-bold mr-3 mt-0.5">2</span>
                  Simpan bukti transfer
                </li>
                <li class="flex items-start">
                  <span class="bg-teal-100 text-teal-600 rounded-full w-5 h-5 flex items-center justify-center text-xs font-bold mr-3 mt-0.5">3</span>
                  Upload bukti melalui tombol "Lakukan Pembayaran"
                </li>
                <li class="flex items-start">
                  <span class="bg-teal-100 text-teal-600 rounded-full w-5 h-5 flex items-center justify-center text-xs font-bold mr-3 mt-0.5">4</span>
                  Tunggu verifikasi dari admin
                </li>
              </ul>
            </div>
            <div class="space-y-3">
              <h4 class="font-medium text-gray-800">Informasi Rekening:</h4>
              <div class="bg-gray-50 p-4 rounded-lg">
                <div class="space-y-2 text-sm">
                  <div class="flex justify-between">
                    <span class="text-gray-600">Bank:</span>
                    <span class="font-medium">BCA</span>
                  </div>
                  <div class="flex justify-between">
                    <span class="text-gray-600">No. Rekening:</span>
                    <span class="font-medium">1234567890</span>
                  </div>
                  <div class="flex justify-between">
                    <span class="text-gray-600">Atas Nama:</span>
                    <span class="font-medium">PPIN NGRUKI</span>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Payment Upload Modal -->
    <PaymentUploadModal
      :show="showPaymentModal"
      :payment-type="paymentType"
      :santri-id="santriData?.id"
      @close="closePaymentModal"
      @success="onPaymentSuccess"
    />

    <!-- Payment Preview Modal -->
    <PaymentPreviewModal
      :show="showBuktiModal"
      :bukti-type="buktiType"
      :bukti-url="buktiUrl"
      @close="closeBuktiModal"
      @change="handleChangeBukti"
    />
  </div>
</template>

<script>
import Swal from 'sweetalert2';
import PaymentUploadModal from './PaymentUploadModal.vue';
import PaymentPreviewModal from './PaymentPreviewModal.vue';

export default {
  name: 'SantriPembayaran',
  components: {
    PaymentUploadModal,
    PaymentPreviewModal
  },
  data() {
    return {
      loading: true,
      santriData: null,
      showPaymentModal: false,
      showBuktiModal: false,
      paymentType: null,
      buktiType: null,
      buktiUrl: ''
    }
  },
  computed: {
    totalBiaya() {
      if (!this.santriData?.gelombang) return 0;
      return (this.santriData.gelombang.biaya_administrasi || 0) + (this.santriData.gelombang.biaya_daftar_ulang || 0);
    },
    totalDibayar() {
      if (!this.santriData?.pembayaran) return 0;
      let total = 0;
      if (this.santriData.pembayaran.status_administrasi === 'diverifikasi') {
        total += this.santriData.gelombang?.biaya_administrasi || 0;
      }
      if (this.santriData.pembayaran.status_daftar_ulang === 'diverifikasi') {
        total += this.santriData.gelombang?.biaya_daftar_ulang || 0;
      }
      return total;
    }
  },
  async mounted() {
    await this.fetchSantriData();
  },
  methods: {
    async fetchSantriData() {
      try {
        this.loading = true;
        // Ambil nomor pendaftaran dari route atau session
        const nomorPendaftaran = this.$route.params.nomor_pendaftaran || sessionStorage.getItem('nomor_pendaftaran');

        if (!nomorPendaftaran) {
          throw new Error('Nomor pendaftaran tidak ditemukan');
        }

        // Gunakan API endpoint yang baru
        const response = await axios.get(`/api/santri/${nomorPendaftaran}/pembayaran`);
        this.santriData = response.data.santri;
      } catch (error) {
        console.error('Error fetching santri data:', error);
        Swal.fire({
          icon: 'error',
          title: 'Gagal mengambil data pembayaran',
          text: error.response?.data?.message || 'Terjadi kesalahan saat memuat data'
        });
      } finally {
        this.loading = false;
      }
    },

    formatCurrency(amount) {
      return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0
      }).format(amount);
    },

    getOverallStatusClassTeal() {
      if (!this.santriData?.pembayaran) return 'bg-red-100 text-red-800';

      const adminStatus = this.santriData.pembayaran.status_administrasi;
      const daftarUlangStatus = this.santriData.pembayaran.status_daftar_ulang;

      if (adminStatus === 'diverifikasi' && daftarUlangStatus === 'diverifikasi') {
        return 'bg-teal-100 text-teal-800';
      } else if (adminStatus === 'diverifikasi' || daftarUlangStatus === 'diverifikasi') {
        return 'bg-teal-50 text-teal-700';
      } else if (adminStatus === 'sudah_bayar' || daftarUlangStatus === 'sudah_bayar') {
        return 'bg-yellow-100 text-yellow-800';
      } else {
        return 'bg-red-100 text-red-800';
      }
    },

    getOverallStatusText() {
      if (!this.santriData?.pembayaran) return 'Belum Bayar';

      const adminStatus = this.santriData.pembayaran.status_administrasi;
      const daftarUlangStatus = this.santriData.pembayaran.status_daftar_ulang;

      if (adminStatus === 'diverifikasi' && daftarUlangStatus === 'diverifikasi') {
        return 'Lunas';
      } else if (adminStatus === 'diverifikasi') {
        return 'Menunggu Daftar Ulang';
      } else if (daftarUlangStatus === 'diverifikasi') {
        return 'Menunggu Administrasi';
      } else if (adminStatus === 'sudah_bayar' || daftarUlangStatus === 'sudah_bayar') {
        return 'Menunggu Verifikasi';
      } else {
        return 'Belum Bayar';
      }
    },

    getProgressPercentage() {
      if (!this.santriData?.pembayaran) return 0;

      let progress = 0;
      const adminStatus = this.santriData.pembayaran.status_administrasi;
      const daftarUlangStatus = this.santriData.pembayaran.status_daftar_ulang;

      if (adminStatus === 'diverifikasi') progress += 50;
      if (daftarUlangStatus === 'diverifikasi') progress += 50;

      return progress;
    },

    getProgressDescription() {
      const percentage = this.getProgressPercentage();
      if (percentage === 0) return 'Belum ada pembayaran yang diverifikasi';
      if (percentage === 50) return 'Pembayaran administrasi sudah diverifikasi';
      if (percentage === 100) return 'Semua pembayaran sudah diverifikasi';
      return 'Pembayaran sedang diproses';
    },

    getStatusClassTeal(type) {
      if (!this.santriData?.pembayaran) return 'bg-red-100 text-red-800';

      const status = type === 'administrasi'
        ? this.santriData.pembayaran.status_administrasi
        : this.santriData.pembayaran.status_daftar_ulang;

      switch (status) {
        case 'diverifikasi': return 'bg-teal-100 text-teal-800';
        case 'sudah_bayar': return 'bg-yellow-100 text-yellow-800';
        case 'belum_bayar': return 'bg-red-100 text-red-800';
        default: return 'bg-gray-100 text-gray-800';
      }
    },

    getStatusText(type) {
      if (!this.santriData?.pembayaran) return 'Belum Bayar';

      const status = type === 'administrasi'
        ? this.santriData.pembayaran.status_administrasi
        : this.santriData.pembayaran.status_daftar_ulang;

      switch (status) {
        case 'diverifikasi': return 'Terverifikasi';
        case 'sudah_bayar': return 'Menunggu Verifikasi';
        case 'belum_bayar': return 'Belum Bayar';
        default: return 'Belum Bayar';
      }
    },

    getPaymentDate(type) {
      if (!this.santriData?.pembayaran) return null;

      const date = type === 'administrasi'
        ? this.santriData.pembayaran.tanggal_administrasi
        : this.santriData.pembayaran.tanggal_daftar_ulang;

      if (!date) return null;

      return new Date(date).toLocaleDateString('id-ID', {
        day: 'numeric',
        month: 'long',
        year: 'numeric'
      });
    },

    canPayAdministrasi() {
      return !this.santriData?.pembayaran ||
             this.santriData.pembayaran.status_administrasi === 'belum_bayar';
    },

    canPayDaftarUlang() {
      return this.santriData?.pembayaran?.status_administrasi === 'diverifikasi' &&
             (!this.santriData.pembayaran.status_daftar_ulang ||
              this.santriData.pembayaran.status_daftar_ulang === 'belum_bayar');
    },

    canViewBukti(type) {
      if (!this.santriData?.pembayaran) return false;

      const status = type === 'administrasi'
        ? this.santriData.pembayaran.status_administrasi
        : this.santriData.pembayaran.status_daftar_ulang;

      return status === 'sudah_bayar' || status === 'diverifikasi';
    },

    openPaymentModal(type) {
      this.paymentType = type;
      this.showPaymentModal = true;
    },

    closePaymentModal() {
      this.showPaymentModal = false;
      this.paymentType = null;
    },

    onPaymentSuccess() {
      this.fetchSantriData(); // Refresh data
    },

    viewBukti(type) {
      this.buktiType = type;

      const buktiPath = type === 'administrasi'
        ? this.santriData.pembayaran.bukti_biaya_administrasi
        : this.santriData.pembayaran.bukti_biaya_daftar_ulang;

      if (buktiPath) {
        this.buktiUrl = `/storage/${buktiPath}`;
        this.showBuktiModal = true;
      }
    },

    closeBuktiModal() {
      this.showBuktiModal = false;
      this.buktiType = null;
      this.buktiUrl = '';
    },

    handleChangeBukti() {
      // Tutup modal preview, buka modal upload dengan paymentType yang sama
      this.showBuktiModal = false;
      this.showPaymentModal = true;
      this.paymentType = this.buktiType;
    },

    printInvoice(jenis) {
      const nomor_pendaftaran = this.santriData?.nomor_pendaftaran;
      if (!nomor_pendaftaran) {
        Swal.fire({ icon: 'error', title: 'Nomor pendaftaran tidak ditemukan!' });
        return;
      }
      const url = `/invoice/${nomor_pendaftaran}/${jenis}`;
      window.open(url, '_blank');
    }
  }
}
</script>

<style scoped>
/* Custom animations */
@keyframes fadeIn {
  from { opacity: 0; transform: translateY(20px); }
  to { opacity: 1; transform: translateY(0); }
}

.animate-fade-in {
  animation: fadeIn 0.5s ease-out;
}

/* Custom scrollbar */
::-webkit-scrollbar {
  width: 6px;
}

::-webkit-scrollbar-track {
  background: #f1f1f1;
  border-radius: 3px;
}

::-webkit-scrollbar-thumb {
  background: #c1c1c1;
  border-radius: 3px;
}

::-webkit-scrollbar-thumb:hover {
  background: #a8a8a8;
}
</style>
