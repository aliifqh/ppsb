<template>
    <div class="py-2 md:py-3">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-semibold text-gray-800">Data Pembayaran</h2>
            <div class="flex space-x-2">
                <button @click="exportExcel" class="inline-flex items-center px-3 py-1.5 bg-gray-200 text-gray-700 hover:bg-gray-300 rounded-lg transition-all duration-200 focus:ring-2 focus:ring-gray-400 focus:outline-none">
                    <i class="fas fa-file-export mr-2"></i>
                    <span class="hidden md:inline">Export</span>
                </button>
            </div>
        </div>
        <div class="mb-6">
            <SearchBar
                v-model="search"
                :placeholder="'Nama Santri / Gelombang / Status'"
                :loading="loading"
                @input="fetchPembayaran"
            />
        </div>
        <div class="bg-white rounded-lg shadow-[0_2px_15px_-3px_rgba(0,0,0,0.07),0_10px_20px_-2px_rgba(0,0,0,0.04)] hover:shadow-[0_8px_17px_2px_rgba(0,0,0,0.14),0_3px_14px_2px_rgba(0,0,0,0.12),0_5px_5px_-3px_rgba(0,0,0,0.2)] transition-shadow duration-300 p-6">
            <ModernTable
                :columns="columns"
                :items="paginatedPembayaran"
                :loading="loading"
                :sortable-columns="['santri','gelombang','biaya_administrasi','status_administrasi','biaya_daftar_ulang','status_daftar_ulang']"
                :visible-columns="visibleColumns"
                :row-clickable="true"
                :row-click-disabled="(item, idx) => false"
                @sort-changed="handleSort"
            >
                <template #no="{ index }">
                    {{ (currentPage-1)*perPage + index + 1 }}
                </template>
                <template #bukti="{ item }">
                    <div class="flex items-center space-x-3 justify-center text-lg">
                        <span @click="previewBukti(item, 'administrasi')"
                              class="cursor-pointer hover:scale-125 transition-transform"
                              :title="item.bukti_biaya_administrasi || item.bukti_biaya_administrasi_admin ? 'Klik untuk lihat bukti administrasi' : 'Klik untuk upload bukti administrasi'">
                            <i class="fas fa-file-invoice-dollar" :class="getBuktiIconClass(item.status_administrasi)"></i>
                        </span>
                        <span @click="previewBukti(item, 'daftar_ulang')"
                              class="cursor-pointer hover:scale-125 transition-transform"
                              :title="item.bukti_biaya_daftar_ulang || item.bukti_biaya_daftar_ulang_admin ? 'Klik untuk lihat bukti daftar ulang' : 'Klik untuk upload bukti daftar ulang'">
                            <i class="fas fa-graduation-cap" :class="getBuktiIconClass(item.status_daftar_ulang)"></i>
                        </span>
                    </div>
                </template>
                <template #santri="{ item }">
                    <div class="flex flex-col">
                        <div class="text-sm font-medium text-gray-900">{{ item.santri?.nama || '-' }}</div>
                        <div class="text-sm text-gray-500">{{ item.santri?.nomor_pendaftaran || '-' }}</div>
                    </div>
                </template>
                <template #gelombang="{ item }">
                    <div @click="openGantiGelombangModal(item)" class="flex items-center space-x-2 cursor-pointer group p-2 rounded-lg hover:bg-blue-50 transition-colors">
                        <span class="font-medium text-gray-700 group-hover:text-blue-600 transition-colors">{{ item.santri?.gelombang?.nama || '-' }}</span>
                        <i class="fas fa-pencil-alt text-gray-400 group-hover:text-blue-600 transition-colors text-xs opacity-0 group-hover:opacity-100"></i>
                    </div>
                </template>
                <template #biaya_administrasi="{ item }">
                    Rp {{ formatNumber(item.santri?.gelombang?.biaya_administrasi || 0) }}
                </template>
                <template #status_administrasi="{ item }">
                    <button
                        @click="toggleVerifikasi(item, 'administrasi')"
                        :disabled="!item.bukti_biaya_administrasi && !item.bukti_biaya_administrasi_admin"
                        :class="[
                            'px-3 py-1 inline-flex items-center text-xs leading-5 font-semibold rounded-full transition-transform transform hover:scale-110 focus:outline-none',
                            getStatusBadgeClass(item.status_administrasi),
                            (!item.bukti_biaya_administrasi && !item.bukti_biaya_administrasi_admin) ? 'cursor-not-allowed opacity-50' : 'cursor-pointer'
                        ]"
                        :title="getVerifikasiTooltip(item.status_administrasi, 'administrasi', item)"
                    >
                        <span class="mr-1.5 -ml-1" v-if="isProcessing && selectedPembayaran && selectedPembayaran.id === item.id && verifJenis === 'administrasi'">
                            <svg class="animate-spin h-4 w-4" :class="item.status_administrasi === 'diverifikasi' ? 'text-yellow-800' : 'text-green-800'" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                        </span>
                        <i v-else :class="getStatusIcon(item.status_administrasi)" class="mr-1.5 -ml-1"></i>
                        {{ item.status_administrasi ? item.status_administrasi.replace('_',' ').replace(/\b\w/g, l => l.toUpperCase()) : '-' }}
                    </button>
                </template>
                <template #biaya_daftar_ulang="{ item }">
                    Rp {{ formatNumber(item.santri?.gelombang?.biaya_daftar_ulang || 0) }}
                </template>
                <template #status_daftar_ulang="{ item }">
                    <button
                        @click="toggleVerifikasi(item, 'daftar_ulang')"
                        :disabled="!item.bukti_biaya_daftar_ulang && !item.bukti_biaya_daftar_ulang_admin"
                        :class="[
                            'px-3 py-1 inline-flex items-center text-xs leading-5 font-semibold rounded-full transition-transform transform hover:scale-110 focus:outline-none',
                            getStatusBadgeClass(item.status_daftar_ulang),
                            (!item.bukti_biaya_daftar_ulang && !item.bukti_biaya_daftar_ulang_admin) ? 'cursor-not-allowed opacity-50' : 'cursor-pointer'
                        ]"
                        :title="getVerifikasiTooltip(item.status_daftar_ulang, 'daftar_ulang', item)"
                    >
                        <span class="mr-1.5 -ml-1" v-if="isProcessing && selectedPembayaran && selectedPembayaran.id === item.id && verifJenis === 'daftar_ulang'">
                            <svg class="animate-spin h-4 w-4" :class="item.status_daftar_ulang === 'diverifikasi' ? 'text-yellow-800' : 'text-green-800'" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                        </span>
                        <i v-else :class="getStatusIcon(item.status_daftar_ulang)" class="mr-1.5 -ml-1"></i>
                        {{ item.status_daftar_ulang ? item.status_daftar_ulang.replace('_',' ').replace(/\b\w/g, l => l.toUpperCase()) : '-' }}
                    </button>
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
        <UploadBuktiAdminModal
            :show="showUploadModal"
            :context="uploadContext"
            @close="showUploadModal = false"
            @uploaded="handleUploaded"
        />

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

        <!-- Image Preview Modal -->
        <div v-if="showImagePreviewModal" @click="closeImagePreview" class="fixed inset-0 z-[99] flex items-center justify-center bg-black bg-opacity-75 backdrop-blur-sm p-4 animate-fadeIn">
            <div @click.stop class="relative animate-zoomIn max-w-[90vw] max-h-[90vh] flex flex-col items-center">
                <!-- Hidden file input for Ganti Bukti -->
                <input type="file" ref="gantiBuktiInput" @change="handleGantiBukti" class="hidden" accept="image/jpeg,image/png">
                <!-- Image and Label Container -->
                <div class="relative bg-gray-900 rounded-lg shadow-2xl">
                    <!-- Ganti Bukti Button -->
                    <button
                        v-if="imagePreviewUrls[currentPreviewIndex]?.label.includes('Admin')"
                        @click.stop="$refs.gantiBuktiInput.click()"
                        class="absolute top-4 left-4 z-20 px-3 py-1.5 bg-blue-500 text-white text-xs rounded-lg shadow-md hover:bg-blue-600 transition-all flex items-center font-semibold"
                        title="Ganti bukti yang diupload Admin"
                    >
                        <i class="fas fa-exchange-alt mr-2"></i>
                        Ganti Bukti Admin
                    </button>
                    <!-- Tambah Bukti Admin Button -->
                    <button
                        v-if="shouldShowAddAdminProofButton && imagePreviewUrls[currentPreviewIndex]?.label.includes('Santri')"
                        @click.stop="openUploadModalFromPreview"
                        class="absolute top-4 left-4 z-20 px-3 py-1.5 bg-green-500 text-white text-xs rounded-lg shadow-md hover:bg-green-600 transition-all flex items-center font-semibold"
                        title="Tambah bukti dari Admin"
                    >
                        <i class="fas fa-plus-circle mr-2"></i>
                        Tambah Bukti Admin
                    </button>
                    <img :src="imagePreviewUrls.length > 0 ? imagePreviewUrls[currentPreviewIndex].url : ''" class="max-w-[85vw] max-h-[80vh] object-contain rounded-lg">
                    <div v-if="imagePreviewUrls.length > 0" class="absolute bottom-0 w-full bg-black bg-opacity-60 text-white text-center py-2 rounded-b-lg text-sm font-semibold">
                        {{ imagePreviewUrls[currentPreviewIndex].label }} ({{ currentPreviewIndex + 1 }} / {{ imagePreviewUrls.length }})
                    </div>
                </div>

                <!-- Navigation Arrows -->
                <button v-if="imagePreviewUrls.length > 1" @click.stop="prevImage" class="absolute left-0 top-1/2 -translate-y-1/2 -translate-x-full bg-white/70 hover:bg-white text-gray-800 rounded-full w-12 h-12 flex items-center justify-center shadow-lg transition transform hover:scale-110 focus:outline-none">
                    <i class="fas fa-chevron-left text-2xl"></i>
                </button>
                <button v-if="imagePreviewUrls.length > 1" @click.stop="nextImage" class="absolute right-0 top-1/2 -translate-y-1/2 translate-x-full bg-white/70 hover:bg-white text-gray-800 rounded-full w-12 h-12 flex items-center justify-center shadow-lg transition transform hover:scale-110 focus:outline-none">
                    <i class="fas fa-chevron-right text-2xl"></i>
                </button>

                <!-- Floating Action Buttons -->
                <div v-if="canVerifyInPreview || canUnverifyInPreview || isVerifiedInPreview" class="absolute bottom-6 left-1/2 -translate-x-1/2 z-10 flex items-center space-x-4">
                    <!-- Tombol Verifikasi -->
                    <button v-if="canVerifyInPreview" @click="verifyFromPreview" class="px-6 py-3 bg-green-500 text-white font-bold rounded-full shadow-lg hover:bg-green-600 transition-all duration-200 transform hover:scale-105 flex items-center space-x-2">
                        <span v-if="isProcessing && verifJenis === previewContext.jenis">
                            <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                        </span>
                        <span v-else>
                            <i class="fas fa-check-circle mr-2"></i>
                            Verifikasi Pembayaran
                        </span>
                    </button>

                    <!-- Tombol Batal Verifikasi -->
                    <button v-if="canUnverifyInPreview" @click="unverifyFromPreview" class="px-6 py-3 bg-red-500 text-white font-bold rounded-full shadow-lg hover:bg-red-600 transition-all duration-200 transform hover:scale-105 flex items-center space-x-2">
                        <span v-if="isProcessing && verifJenis === previewContext.jenis">
                            <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                        </span>
                        <span v-else>
                            <i class="fas fa-times-circle mr-2"></i>
                            Batal Verifikasi
                        </span>
                    </button>

                    <!-- Tombol Cetak Invoice -->
                    <button v-if="isVerifiedInPreview" @click="printInvoice" class="px-6 py-3 bg-blue-500 text-white font-bold rounded-full shadow-lg hover:bg-blue-600 transition-all duration-200 transform hover:scale-105 flex items-center space-x-2">
                        <i class="fas fa-print mr-2"></i>
                        Cetak Invoice
                    </button>
                </div>

                <!-- Close Button -->
                <button @click="closeImagePreview" class="absolute -top-4 -right-4 bg-white text-gray-800 rounded-full w-10 h-10 flex items-center justify-center shadow-lg hover:bg-gray-200 transition transform hover:scale-110">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
        </div>
        <!-- Ganti Gelombang Modal -->
        <ModernModal
            :show="showGantiGelombangModal"
            @close="closeGantiGelombangModal"
            title="Ganti Gelombang"
            size="lg"
        >
            <p class="text-gray-500 text-sm mb-6 -mt-2">
                Untuk: <span class="font-semibold text-gray-700">{{ selectedPembayaranForGelombang?.santri?.nama }}</span>
            </p>

            <!-- Body -->
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Gelombang Saat Ini</label>
                    <p class="text-lg font-semibold text-teal-600 p-3 bg-gray-100 rounded-lg w-full">{{ selectedPembayaranForGelombang?.santri?.gelombang?.nama }}</p>
                </div>
                <div>
                    <label for="gelombang-select" class="block text-sm font-medium text-gray-700 mb-1">Pilih Gelombang Baru</label>
                    <select id="gelombang-select" v-model="newGelombangId" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                        <option :value="null" disabled>-- Pilih Gelombang --</option>
                        <option v-for="gelombang in availableGelombang" :key="gelombang.id" :value="gelombang.id">
                            {{ gelombang.nama }}
                        </option>
                    </select>
                </div>
            </div>

            <!-- Footer -->
            <template #footer>
                <div class="flex justify-end space-x-3">
                    <button type="button" @click="closeGantiGelombangModal" class="px-6 py-2.5 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 font-semibold transition-all">Batal</button>
                    <LoadingButton
                        :text="'Simpan Perubahan'"
                        :loading="isProcessing"
                        :disabled="!newGelombangId"
                        @click="submitGantiGelombang"
                    />
                </div>
            </template>
        </ModernModal>
    </div>
</template>

<script>
import ModernTable from '../../common/ModernTable.vue'
import ActionButtons from '../../common/ActionButtons.vue'
import SearchBar from '../../common/SearchBar.vue'
import UploadBuktiAdminModal from './parts/UploadBuktiAdminModal.vue'
import LoadingOverlay from '../../common/LoadingOverlay.vue'
import ModernPagination from '../../common/ModernPagination.vue'
import { swalConfirm } from '../../common/Swal2Dialog.js'
import ModernModal from '../../common/ModernModal.vue'
import LoadingButton from '../../common/LoadingButton.vue'
import ExcelJS from 'exceljs';
import { saveAs } from 'file-saver';

export default {
    components: {
        ModernTable,
        ActionButtons,
        SearchBar,
        UploadBuktiAdminModal,
        LoadingOverlay,
        ModernPagination,
        ModernModal,
        LoadingButton,
    },
    data() {
        return {
            pembayaranList: [],
            showModal: false,
            isEditing: false,
            form: {
                id: null,
                santri_id: '',
                jenis: '',
                jumlah: 0,
                status: 'pending'
            },
            search: '',
            loading: false,
            columns: [
                { key: 'no', label: 'No' },
                { key: 'bukti', label: 'Bukti' },
                { key: 'santri', label: 'Santri' },
                { key: 'gelombang', label: 'Gelombang' },
                { key: 'biaya_administrasi', label: 'Biaya Administrasi' },
                { key: 'status_administrasi', label: 'Status Administrasi' },
                { key: 'biaya_daftar_ulang', label: 'Biaya Daftar Ulang' },
                { key: 'status_daftar_ulang', label: 'Status Daftar Ulang' }
            ],
            visibleColumns: ['no','bukti','santri','gelombang','biaya_administrasi','status_administrasi','biaya_daftar_ulang','status_daftar_ulang'],
            sortKey: '',
            sortAsc: true,
            currentPage: 1,
            perPage: 10,
            selectedPembayaran: null,
            isProcessing: false,
            showImagePreviewModal: false,
            imagePreviewUrls: [],
            currentPreviewIndex: 0,
            previewContext: null,
            verifJenis: null,
            showUploadModal: false,
            uploadContext: null,
            loadingOverlay: {
                show: false,
                title: 'Memproses...',
                subtitle: 'Mohon tunggu sebentar',
                icon: 'fas fa-cog',
                showProgress: false,
                progress: 0,
                progressText: 'Memproses data...',
                showCancel: false
            },
            totalPages: 1,
            totalItems: 0,
            searchDebounce: null,
            gelombangList: [],
            showGantiGelombangModal: false,
            selectedPembayaranForGelombang: null,
            newGelombangId: null,
        }
    },
    computed: {
        paginatedPembayaran() {
            return this.pembayaranList;
        },
        availableGelombang() {
            if (!this.selectedPembayaranForGelombang) return this.gelombangList;
            const currentGelombangId = this.selectedPembayaranForGelombang.santri.gelombang_id;
            return this.gelombangList.filter(g => g.id !== currentGelombangId);
        },
        shouldShowAddAdminProofButton() {
            if (!this.previewContext) return false;
            const { item, jenis } = this.previewContext;
            if (jenis === 'administrasi') {
                return !!item.bukti_biaya_administrasi && !item.bukti_biaya_administrasi_admin;
            }
            if (jenis === 'daftar_ulang') {
                return !!item.bukti_biaya_daftar_ulang && !item.bukti_biaya_daftar_ulang_admin;
            }
            return false;
        },
        isVerifiedInPreview() {
            if (!this.previewContext) return false;
            const { item, jenis } = this.previewContext;
            const status = jenis === 'administrasi' ? item.status_administrasi : item.status_daftar_ulang;
            return status === 'diverifikasi';
        },
        canVerifyInPreview() {
            if (!this.previewContext) return false;
            const { item, jenis } = this.previewContext;
            const status = jenis === 'administrasi' ? item.status_administrasi : item.status_daftar_ulang;
            return status === 'sudah_bayar';
        },
        canUnverifyInPreview() {
            if (!this.previewContext) return false;
            const { item, jenis } = this.previewContext;
            const status = jenis === 'administrasi' ? item.status_administrasi : item.status_daftar_ulang;
            return status === 'diverifikasi';
        }
    },
    watch: {
        search() {
            clearTimeout(this.searchDebounce);
            this.searchDebounce = setTimeout(() => {
                this.currentPage = 1;
                this.fetchPembayaran();
            }, 500);
        }
    },
    mounted() {
        this.fetchPembayaran();
        this.fetchGelombangList();
    },
    methods: {
        async fetchGelombangList() {
            try {
                const response = await axios.get('/admin/gelombang/api');
                this.gelombangList = response.data;
            } catch (error) {
                Toast.fire({ icon: 'error', title: 'Gagal mengambil daftar gelombang' });
            }
        },
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
        },
        async fetchPembayaran() {
            this.loading = true;
            try {
                const params = {
                    page: this.currentPage,
                    perPage: this.perPage,
                    search: this.search,
                    sortKey: this.sortKey,
                    sortAsc: this.sortAsc,
                };
                const response = await axios.get('/admin/pembayaran/data', { params });

                this.pembayaranList = response.data.data;
                this.totalPages = response.data.last_page;
                this.totalItems = response.data.total;
                this.currentPage = response.data.current_page;

            } catch (error) {
                Toast.fire({ icon: 'error', title: 'Gagal mengambil data pembayaran' });
            } finally {
                this.loading = false;
            }
        },
        async savePembayaran() {
            try {
                if (this.isEditing) {
                    await axios.put(`/api/admin/pembayaran/${this.form.id}`, this.form)
                } else {
                    await axios.post('/api/admin/pembayaran', this.form)
                }
                this.closeModal()
                this.fetchPembayaran()
                Toast.fire({ icon: 'success', title: 'Data berhasil disimpan' })
            } catch (error) {
                Toast.fire({ icon: 'error', title: 'Gagal menyimpan data' })
            }
        },
        formatNumber(number) {
            return new Intl.NumberFormat('id-ID').format(number)
        },
        formatDate(date) {
            return new Date(date).toLocaleDateString('id-ID')
        },
        formatJenisForDisplay(jenis) {
            if (jenis === 'daftar_ulang') {
                return 'Daftar Ulang';
            }
            // Capitalize first letter for other cases like 'administrasi'
            return jenis.charAt(0).toUpperCase() + jenis.slice(1);
        },
        getStatusBadgeClass(status) {
            switch (status) {
                case 'diverifikasi': return 'bg-green-100 text-green-800 hover:bg-green-200';
                case 'sudah_bayar': return 'bg-yellow-100 text-yellow-800 hover:bg-yellow-200';
                case 'belum_bayar': return 'bg-red-100 text-red-800';
                default: return 'bg-gray-100 text-gray-800';
            }
        },
        getStatusIcon(status) {
            switch (status) {
                case 'diverifikasi': return 'fas fa-check-circle';
                case 'sudah_bayar': return 'fas fa-hourglass-half';
                case 'belum_bayar': return 'fas fa-times-circle';
                default: return 'fas fa-question-circle';
            }
        },
        getVerifikasiTooltip(status, jenis, item) {
            const hasBukti = jenis === 'administrasi'
                ? (item.bukti_biaya_administrasi || item.bukti_biaya_administrasi_admin)
                : (item.bukti_biaya_daftar_ulang || item.bukti_biaya_daftar_ulang_admin);

            if (!hasBukti) {
                return 'Bukti pembayaran belum diupload';
            }

            const jenisFormatted = this.formatJenisForDisplay(jenis);

            switch (status) {
                case 'diverifikasi': return `Klik untuk membatalkan verifikasi ${jenisFormatted}`;
                case 'sudah_bayar': return `Klik untuk verifikasi ${jenisFormatted}`;
                case 'belum_bayar': return 'Status belum bayar, tidak ada aksi';
                default: return 'Status tidak diketahui';
            }
        },
        toggleVerifikasi(item, jenis) {
            this.selectedPembayaran = item;
            this.verifJenis = jenis;

            const status = jenis === 'administrasi' ? item.status_administrasi : item.status_daftar_ulang;

            if (status === 'diverifikasi') {
                this.handleUnverify({ item, jenis });
            } else if (status === 'sudah_bayar') {
                this.handleVerify({ item, jenis });
            }
        },
        async handleVerify({ item, jenis }) {
            const itemToProcess = item || this.previewContext.item;
            const jenisToProcess = jenis || this.previewContext.jenis;

            const jenisFormatted = this.formatJenisForDisplay(jenisToProcess);
            const result = await swalConfirm({
                title: `Verifikasi Pembayaran ${jenisFormatted}?`,
                text: "Pastikan Anda sudah memeriksa bukti dengan benar. Aksi ini akan mengubah status pembayaran menjadi 'Diverifikasi'.",
                icon: 'question',
                confirmButtonText: 'Ya, Verifikasi',
                cancelButtonText: 'Batal',
                confirmButtonColor: '#10b981',
                reverseButtons: true
            });

            if (result.isConfirmed) {
                this.isProcessing = true;
                this.verifJenis = jenisToProcess;
                this.selectedPembayaran = itemToProcess;
                try {
                    await axios.post(`/admin/pembayaran/${itemToProcess.id}/verifikasi`, { jenis: jenisToProcess });
                    await this.refreshPembayaranData(itemToProcess.id);
                    Toast.fire({ icon: 'success', title: 'Pembayaran berhasil diverifikasi!' });
                    this.closeImagePreview();
                } catch (e) {
                    console.error('Verification error:', e);
                    Toast.fire({ icon: 'error', title: 'Gagal verifikasi!' });
                } finally {
                    this.isProcessing = false;
                    this.verifJenis = null;
                    this.selectedPembayaran = null;
                }
            }
        },
        async handleUnverify({ item, jenis }) {
            const itemToProcess = item || this.previewContext.item;
            const jenisToProcess = jenis || this.previewContext.jenis;

            const jenisFormatted = this.formatJenisForDisplay(jenisToProcess);
            const result = await swalConfirm({
                title: `Batalkan Verifikasi ${jenisFormatted}?`,
                text: "Aksi ini akan mengembalikan status pembayaran menjadi 'Sudah Bayar'.",
                icon: 'warning',
                confirmButtonText: 'Ya, Batalkan',
                cancelButtonText: 'Tidak',
                confirmButtonColor: '#f59e0b',
                reverseButtons: true
            });

            if (result.isConfirmed) {
                this.isProcessing = true;
                this.verifJenis = jenisToProcess;
                this.selectedPembayaran = itemToProcess;
                try {
                    await axios.post(`/admin/pembayaran/${itemToProcess.id}/batalkan-verifikasi`, { jenis: jenisToProcess });
                    await this.refreshPembayaranData(itemToProcess.id);
                    Toast.fire({ icon: 'success', title: 'Verifikasi dibatalkan!' });
                    this.closeImagePreview();
                } catch (e) {
                    console.error('Unverification error:', e);
                    Toast.fire({ icon: 'error', title: 'Gagal membatalkan verifikasi!' });
                } finally {
                    this.isProcessing = false;
                    this.verifJenis = null;
                    this.selectedPembayaran = null;
                }
            }
        },
        async handleUploadBuktiAdmin(data) {
            // This logic is now inside UploadBuktiAdminModal.vue
            // This method can be removed or kept for other purposes if needed.
        },
        async refreshPembayaranData(pembayaranId) {
            try {
                await this.fetchPembayaran();

                const updatedItem = this.pembayaranList.find(p => p.id === pembayaranId);

                if (this.previewContext && updatedItem) {
                    this.previewContext.item = updatedItem;
                }

            } catch (error) {
                console.error("Gagal refresh data pembayaran:", error);
                Toast.fire({ icon: 'error', title: 'Gagal memuat ulang data!' });
            }
        },
        handleUploaded() {
            this.fetchPembayaran();
        },
        handlePerPageChange(newPerPage) {
            this.perPage = newPerPage;
            this.currentPage = 1;
            this.fetchPembayaran();
        },
        changePage(page) {
            if (page >= 1 && page <= this.totalPages && page !== this.currentPage) {
                this.currentPage = page;
                this.fetchPembayaran();
            }
        },
        handleSort({ key, asc }) {
            this.sortKey = key;
            this.sortAsc = asc;
            this.currentPage = 1;
            this.fetchPembayaran();
        },
        async exportExcel() {
            this.showLoading('Mengekspor Data...', 'Mohon tunggu sebentar', 'fas fa-file-excel');

            try {
                const workbook = new ExcelJS.Workbook();
                const sheet = workbook.addWorksheet('Data Pembayaran');

                // Judul dan Header
                sheet.mergeCells('A1:K1');
                sheet.getCell('A1').value = 'Data Pembayaran Santri';
                sheet.getCell('A1').font = { bold: true, size: 16 };
                sheet.getCell('A1').alignment = { horizontal: 'center' };
                sheet.getRow(1).height = 30;

                const headers = [
                    'No', 'Nomor Pendaftaran', 'Nama Santri', 'Unit', 'Gelombang',
                    'Biaya Administrasi', 'Status Administrasi', 'Tanggal Administrasi',
                    'Biaya Daftar Ulang', 'Status Daftar Ulang', 'Tanggal Daftar Ulang'
                ];
                const headerRow = sheet.addRow(headers);
                headerRow.eachCell(cell => {
                    cell.font = { bold: true, color: { argb: 'FFFFFFFF' } };
                    cell.fill = { type: 'pattern', pattern: 'solid', fgColor: { argb: '1E293B' } };
                    cell.alignment = { horizontal: 'center', vertical: 'middle', wrapText: true };
                    cell.border = {
                        top: { style: 'thin' }, bottom: { style: 'thin' },
                        left: { style: 'thin' }, right: { style: 'thin' }
                    };
                });
                headerRow.height = 25;

                // Fungsi helper untuk format
                const formatStatus = (status) => status ? status.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase()) : '-';
                const formatDateExport = (date) => date ? new Date(date).toLocaleString('id-ID') : '-';

                // Mengambil semua data tanpa paginasi untuk di-export
                const allDataResponse = await axios.get('/admin/pembayaran/data', { params: { perPage: -1, search: this.search, sortKey: this.sortKey, sortAsc: this.sortAsc } });
                const allData = allDataResponse.data.data;

                // Tambah data ke sheet
                allData.forEach((p, index) => {
                    sheet.addRow([
                        index + 1,
                        p.santri?.nomor_pendaftaran || '-',
                        p.santri?.nama || '-',
                        p.santri?.unit?.toUpperCase() || '-',
                        p.santri?.gelombang?.nama || '-',
                        this.formatNumber(p.santri?.gelombang?.biaya_administrasi || 0),
                        formatStatus(p.status_administrasi),
                        formatDateExport(p.tanggal_administrasi),
                        this.formatNumber(p.santri?.gelombang?.biaya_daftar_ulang || 0),
                        formatStatus(p.status_daftar_ulang),
                        formatDateExport(p.tanggal_daftar_ulang),
                    ]);
                });

                // Atur lebar kolom
                sheet.columns = [
                    { key: 'no', width: 5 }, { key: 'no_pendaftaran', width: 20 },
                    { key: 'nama', width: 30 }, { key: 'unit', width: 10 },
                    { key: 'gelombang', width: 20 }, { key: 'biaya_adm', width: 20 },
                    { key: 'status_adm', width: 20 }, { key: 'tgl_adm', width: 20 },
                    { key: 'biaya_du', width: 20 }, { key: 'status_du', width: 20 },
                    { key: 'tgl_du', width: 20 },
                ];

                const buffer = await workbook.xlsx.writeBuffer();
                const blob = new Blob([buffer], { type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' });
                const tgl = new Date().toISOString().slice(0, 10);
                saveAs(blob, `pembayaran_${tgl}.xlsx`);

                this.hideLoading();
                Toast.fire({ icon: 'success', title: 'Data berhasil diekspor!' });

            } catch (error) {
                console.error("Export error:", error);
                this.hideLoading();
                Toast.fire({ icon: 'error', title: 'Gagal mengekspor data!', text: 'Terjadi kesalahan di server.' });
            }
        },
        isImage(file) {
            if (!file) return false;
            const ext = file.split('.').pop().toLowerCase();
            return ['jpg','jpeg','png','gif','bmp','webp'].includes(ext);
        },
        getFileUrl(file) {
            if (!file) return '';
            if (file.startsWith('http')) return file;
            return `/storage/${file.replace('public/','')}`;
        },
        previewBukti(item, jenis) {
            const baseProof = jenis === 'administrasi' ? item.bukti_biaya_administrasi : item.bukti_biaya_daftar_ulang;
            const adminProof = jenis === 'administrasi' ? item.bukti_biaya_administrasi_admin : item.bukti_biaya_daftar_ulang_admin;
            const hasProof = baseProof || adminProof;

            if (!hasProof) {
                this.uploadContext = { item, jenis };
                this.showUploadModal = true;
                return;
            }

            const proofs = [];
            if (baseProof) {
                proofs.push({
                    url: this.getFileUrl(baseProof),
                    label: 'Bukti dari Santri',
                    path: baseProof,
                });
            }
            if (adminProof) {
                proofs.push({
                    url: this.getFileUrl(adminProof),
                    label: 'Bukti dari Admin',
                    path: adminProof,
                });
            }

            if (proofs.length === 0) {
                Toast.fire({ icon: 'info', title: 'Tidak ada bukti untuk ditampilkan.' });
                return;
            }

            const imageProofs = proofs.filter(p => this.isImage(p.path));
            const otherProofs = proofs.filter(p => !this.isImage(p.path));

            if (imageProofs.length > 0) {
                this.imagePreviewUrls = imageProofs;
                this.currentPreviewIndex = 0;
                this.previewContext = { item, jenis };
                this.showImagePreviewModal = true;
            }

            otherProofs.forEach(p => {
                window.open(p.url, '_blank');
            });
        },
        async verifyFromPreview() {
            if (!this.canVerifyInPreview) return;
            await this.handleVerify({ item: this.previewContext.item, jenis: this.previewContext.jenis });
        },
        async unverifyFromPreview() {
            if (!this.canUnverifyInPreview) return;
            await this.handleUnverify({ item: this.previewContext.item, jenis: this.previewContext.jenis });
        },
        closeImagePreview() {
            this.showImagePreviewModal = false;
            this.imagePreviewUrls = [];
            this.currentPreviewIndex = 0;
            this.previewContext = null;
        },
        nextImage() {
            if (this.imagePreviewUrls.length > 1) {
                this.currentPreviewIndex = (this.currentPreviewIndex + 1) % this.imagePreviewUrls.length;
            }
        },
        prevImage() {
            if (this.imagePreviewUrls.length > 1) {
                this.currentPreviewIndex = (this.currentPreviewIndex - 1 + this.imagePreviewUrls.length) % this.imagePreviewUrls.length;
            }
        },
        async handleGantiBukti(event) {
            const file = event.target.files[0];
            if (!file) return;

            const allowedTypes = ['image/jpeg', 'image/png'];
            if (!allowedTypes.includes(file.type)) {
                Toast.fire({ icon: 'error', title: 'Format file tidak didukung! Hanya JPG dan PNG.' });
                return;
            }

            if (file.size > 2 * 1024 * 1024) { // 2MB validation
                Toast.fire({ icon: 'error', title: 'File terlalu besar! Maksimal 2MB.' });
                return;
            }

            const { item, jenis } = this.previewContext;

            const formData = new FormData();
            formData.append('bukti_pembayaran', file);
            formData.append('jenis', jenis);

            this.showLoading('Mengganti Bukti...', 'Mohon tunggu sebentar');

            try {
                await axios.post(`/admin/pembayaran/${item.id}/upload-bukti`, formData, {
                    headers: { 'Content-Type': 'multipart/form-data' }
                });

                Toast.fire({ icon: 'success', title: 'Bukti berhasil diganti!' });

                await this.refreshPembayaranData(item.id);

                this.rebuildPreviewUrls();

                const adminProofIndex = this.imagePreviewUrls.findIndex(p => p.label.includes('Admin'));
                if (adminProofIndex !== -1) {
                    this.currentPreviewIndex = adminProofIndex;
                }

            } catch (error) {
                const errorMessage = error.response?.data?.message || 'Gagal ganti bukti!';
                Toast.fire({ icon: 'error', title: errorMessage });
            } finally {
                this.hideLoading();
                if (this.$refs.gantiBuktiInput) {
                    this.$refs.gantiBuktiInput.value = '';
                }
            }
        },
        rebuildPreviewUrls() {
            if (!this.previewContext) return;

            const { item, jenis } = this.previewContext;

            const proofs = [];
            const baseProof = jenis === 'administrasi' ? item.bukti_biaya_administrasi : item.bukti_biaya_daftar_ulang;
            const adminProof = jenis === 'administrasi' ? item.bukti_biaya_administrasi_admin : item.bukti_biaya_daftar_ulang_admin;

            if (baseProof) {
                proofs.push({
                    url: this.getFileUrl(baseProof),
                    label: 'Bukti dari Santri',
                    path: baseProof,
                });
            }
            if (adminProof) {
                proofs.push({
                    url: this.getFileUrl(adminProof),
                    label: 'Bukti dari Admin',
                    path: adminProof,
                });
            }

            const imageProofs = proofs.filter(p => this.isImage(p.path));

            if (imageProofs.length > 0) {
                this.imagePreviewUrls = imageProofs;
                // Ensure current index is still valid
                if (this.currentPreviewIndex >= this.imagePreviewUrls.length) {
                    this.currentPreviewIndex = this.imagePreviewUrls.length - 1;
                }
            } else {
                this.closeImagePreview();
            }
        },
        openUploadModalFromPreview() {
            if (!this.previewContext) return;

            // Use the context from the preview
            this.uploadContext = this.previewContext;

            // Close the preview modal
            this.closeImagePreview();

            // Open the upload modal
            this.showUploadModal = true;
        },
        openGantiGelombangModal(item) {
            this.selectedPembayaranForGelombang = item;
            this.newGelombangId = null; // Reset selection
            this.showGantiGelombangModal = true;
        },
        closeGantiGelombangModal() {
            this.showGantiGelombangModal = false;
            this.selectedPembayaranForGelombang = null;
        },
        async submitGantiGelombang() {
            if (!this.newGelombangId) return;

            this.isProcessing = true;
            try {
                const santriId = this.selectedPembayaranForGelombang.santri.id;
                await axios.post(`/admin/santri/${santriId}/change-gelombang`, {
                    gelombang_id: this.newGelombangId,
                });

                Toast.fire({ icon: 'success', title: 'Gelombang berhasil diubah!' });
                this.closeGantiGelombangModal();
                await this.fetchPembayaran(); // Refresh data
            } catch (error) {
                const errorMessage = error.response?.data?.message || 'Gagal mengubah gelombang!';
                Toast.fire({ icon: 'error', title: errorMessage });
            } finally {
                this.isProcessing = false;
            }
        },
        printInvoice() {
            if (!this.isVerifiedInPreview) return;
            const { item, jenis } = this.previewContext;
            const nomor_pendaftaran = item.santri?.nomor_pendaftaran;
            if (!nomor_pendaftaran) {
                Toast.fire({ icon: 'error', title: 'Nomor pendaftaran tidak ditemukan!' });
                return;
            }
            const url = `/invoice/${nomor_pendaftaran}/${jenis}`;
            window.open(url, '_blank');
        },
        getBuktiIconClass(status) {
            switch (status) {
                case 'diverifikasi':
                    return 'text-green-500';
                case 'sudah_bayar':
                    return 'text-orange-500';
                case 'belum_bayar':
                default:
                    return 'text-gray-300';
            }
        },
    }
}
</script>

<style scoped>
.modal {
    display: block;
    background-color: rgba(0,0,0,0.5);
}
.payment-methods {
    background-color: #f8f9fa;
    padding: 20px;
    border-radius: 5px;
}
.animate-fadeIn {
  animation: fadeIn 0.2s ease-out forwards;
}
.animate-zoomIn {
  animation: zoomIn 0.2s ease-out forwards;
}

@keyframes fadeIn {
  from { opacity: 0; }
  to { opacity: 1; }
}
@keyframes zoomIn {
  from { transform: scale(0.9); opacity: 0; }
  to { transform: scale(1); opacity: 1; }
}
</style>
