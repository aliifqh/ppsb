<template>
    <div class="py-2 md:py-3">
        <SantriModal
            :showModal="showEditModal"
            :isEditing="true"
            :form="editForm"
            :activeTab="activeTab"
            @close="showEditModal = false"
            @save="updateSantri"
            @update:form="handleFormUpdate"
            @update:activeTab="activeTab = $event"
        />
        <DokumenPreviewModal
            :show="showPreviewModal"
            :items="previewDokumenItems"
            :startIndex="previewStartIndex"
            @close="showPreviewModal = false"
        />
        <div v-if="showTrashed">
            <SantriTrashed @back="showTrashed = false; fetchSantri()" />
        </div>
        <div v-else class="w-full px-2">
            <!-- Header -->
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-semibold text-gray-800">Data Santri</h2>
                <div class="flex space-x-2">
                    <button @click="exportExcel" class="inline-flex items-center justify-center w-10 h-10 md:w-auto md:px-4 md:py-2 bg-gray-200 text-gray-700 hover:bg-gray-300 rounded-lg transition-all duration-200 focus:ring-2 focus:ring-gray-400 focus:outline-none">
                        <i class="fas fa-file-excel"></i>
                        <span class="hidden md:inline ml-2">Export</span>
                    </button>
                    <button @click="showTrashed = true" class="inline-flex items-center justify-center w-10 h-10 md:w-auto md:px-4 md:py-2 bg-red-200 text-red-700 hover:bg-red-300 rounded-lg transition-all duration-200 focus:ring-2 focus:ring-red-400 focus:outline-none">
                        <i class="fas fa-trash-arrow-up"></i>
                        <span class="hidden md:inline ml-2">Trashed</span>
                    </button>
                    <a href="/formulir" target="_blank" class="inline-flex items-center justify-center w-10 h-10 md:w-auto md:px-4 md:py-2 bg-emerald-500 text-white hover:bg-emerald-600 rounded-lg transition-all duration-200 focus:ring-2 focus:ring-emerald-400 focus:outline-none">
                        <i class="fas fa-plus"></i>
                        <span class="hidden md:inline ml-2">Tambah</span>
                    </a>
                </div>
            </div>

            <!-- Search -->
            <div class="mb-6">
                <SearchBar
                    v-model="search"
                    :placeholder="'Nama / NISN / Nomor Pendaftaran'"
                    :loading="loading"
                    :modalActive="showEditModal"
                    @input="fetchSantri"
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
                <div v-if="selectedSantri.length > 0" class="mb-4 bg-emerald-50 border border-emerald-200 shadow-sm rounded-lg p-3">
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-medium text-emerald-800">{{ selectedSantri.length }} santri dipilih</span>
                        <div class="flex items-center space-x-2">
                            <button @click="bulkUpdateStatus" class="px-3 py-1.5 text-xs font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 flex items-center">
                                <i class="fas fa-check-double mr-2"></i> Ubah Status Tes
                            </button>
                            <button @click="bulkDelete" class="px-3 py-1.5 text-xs font-medium text-white bg-red-600 rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 flex items-center">
                                <i class="fas fa-trash-alt mr-2"></i> Hapus
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
                    :sortable-columns="['nama', 'nisn', 'unit', 'jenis_kelamin', 'status_tes']"
                    :visible-columns="visibleColumns"
                    :row-clickable="true"
                    :row-click-disabled="(item, idx) => !item.nama"
                    :row-class="getRowClass"
                    :selectable="true"
                    @sort-changed="handleSort"
                    @row-dblclick="handleRowDblClick"
                >
                    <!-- Custom cell content -->
                    <template #no="{ index }">
                        {{ (currentPage-1)*perPage + index + 1 }}
                    </template>

                    <template #aksi="{ item }">
                        <ActionButtons
                            :actions="[
                                {
                                    key: 'edit',
                                    icon: 'fas fa-edit',
                                    color: 'bg-blue-500 text-white',
                                    tooltip: 'Edit data santri'
                                },
                                {
                                    key: 'delete',
                                    icon: 'fas fa-trash',
                                    color: 'bg-red-500 text-white',
                                    tooltip: 'Tekan Ctrl + klik pada tombol hapus! untuk menghapus secara paksa.'
                                },
                                {
                                    key: 'login',
                                    icon: loadingLoginSantri === item.id ? 'fas fa-spinner fa-spin' : 'fas fa-user-secret',
                                    color: 'bg-emerald-500 text-white',
                                    tooltip: 'Login sebagai santri ini',
                                    disabled: loadingLoginSantri !== null && loadingLoginSantri !== item.id
                                }
                            ]"
                            @action="(key, event) => {
                                if (key === 'edit') editSantri(item)
                                else if (key === 'delete') deleteSantri(item.id, event)
                                else if (key === 'login') loginAsSantri(item)
                            }"
                        />
                    </template>

                    <template #nama="{ item }">
                        <div class="flex flex-col">
                            <div class="text-sm font-medium text-gray-900">{{ item.nama }}</div>
                            <div class="text-sm text-gray-500">{{ item.nomor_pendaftaran }}</div>
                        </div>
                    </template>

                    <template #unit="{ item }">
                        <span :class="'px-2 inline-flex text-xs leading-5 font-semibold rounded-full ' + (item.unit === 'ppim' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800')">
                            {{ item.unit ? item.unit.toUpperCase() : '-' }}
                        </span>
                    </template>

                    <template #jenis_kelamin="{ item }">
                        <span :class="'px-2 inline-flex text-xs leading-5 font-semibold rounded-full ' + (item.jenis_kelamin === 'Laki-laki' ? 'bg-blue-100 text-blue-800' : 'bg-pink-100 text-pink-800')">
                            {{ item.jenis_kelamin || '-' }}
                        </span>
                    </template>

                    <template #tempat_tanggal_lahir="{ item }">
                        {{ item.tempat_lahir ? item.tempat_lahir : '-' }}{{ item.tempat_lahir && item.tanggal_lahir ? ', ' : '' }}{{ formatDate(item.tanggal_lahir) }}
                    </template>

                    <template #nisn="{ item }">
                        {{ item.nisn || '-' }}
                    </template>

                    <template #anak_ke="{ item }">
                        <span class="text-sm text-gray-700">
                            <template v-if="item.anak_ke && item.jumlah_saudara">
                                Anak ke {{ item.anak_ke }} dari {{ item.jumlah_saudara }} saudara
                            </template>
                            <template v-else-if="item.anak_ke">
                                Anak ke {{ item.anak_ke }}
                            </template>
                            <template v-else>
                                -
                            </template>
                        </span>
                    </template>

                    <template #asal_sekolah="{ item }">
                        <div class="flex flex-col">
                            <span class="text-sm font-medium text-gray-900">{{ item.asal_sekolah || '-' }}</span>
                            <span v-if="item.kelas" class="text-xs text-gray-500">Kelas: {{ item.kelas }}</span>
                        </div>
                    </template>

                    <template #status_tes="{ item, index }">
                        <div class="inline-block text-left">
                            <button @click.stop="toggleTesDropdown(index, $event)" type="button" :class="getTesStatusClass(item.status_tes)"
                                class="inline-flex justify-center w-full rounded-md shadow-sm px-3 py-1 text-xs font-semibold focus:outline-none transition-all duration-200 hover:opacity-80 items-center">
                                {{ item.status_tes || 'Menunggu Tes' }}
                                <i class="fas fa-chevron-down -mr-1 ml-2 h-4 w-4"></i>
                            </button>

                            <Teleport to="body">
                                <transition
                                    enter-active-class="transition ease-out duration-100"
                                    enter-from-class="transform opacity-0 scale-95"
                                    enter-to-class="transform opacity-100 scale-100"
                                    leave-active-class="transition ease-in duration-75"
                                    leave-from-class="transform opacity-100 scale-100"
                                    leave-to-class="transform opacity-0 scale-95"
                                >
                                    <div v-show="activeTesDropdown === index"
                                         :style="dropdownStyle"
                                         @click.stop
                                         class="fixed w-52 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 focus:outline-none z-50">
                                        <div class="py-1" role="menu" aria-orientation="vertical" aria-labelledby="options-menu">
                                            <a href="#" @click.prevent="promptUpdateStatus(item, 'Lulus', $event)" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900" role="menuitem">
                                                <i class="fas fa-check-circle w-5 mr-3 text-green-500"></i>
                                                <span>Lulus</span>
                                            </a>
                                            <a href="#" @click.prevent="promptUpdateStatus(item, 'Tidak Lulus', $event)" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900" role="menuitem">
                                                <i class="fas fa-times-circle w-5 mr-3 text-red-500"></i>
                                                <span>Tidak Lulus</span>
                                            </a>
                                            <div class="border-t border-gray-100 my-1"></div>
                                            <a href="#" @click.prevent="promptUpdateStatus(item, 'Menunggu Tes', $event)" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900" role="menuitem">
                                                <i class="fas fa-clock w-5 mr-3 text-yellow-500"></i>
                                                <span>Batal (Menunggu Tes)</span>
                                            </a>
                                        </div>
                                    </div>
                                </transition>
                            </Teleport>
                        </div>
                    </template>

                    <template #status_pembayaran="{ item }">
                        <span v-if="item.pembayaran">
                            <span v-if="item.pembayaran.status_administrasi === 'diverifikasi' && item.pembayaran.status_daftar_ulang === 'diverifikasi'" class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Lunas</span>
                            <span v-else-if="item.pembayaran.status_administrasi === 'diverifikasi'" class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">Menunggu Daftar Ulang</span>
                            <span v-else-if="item.pembayaran.status_administrasi === 'sudah_bayar'" class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">Menunggu Verifikasi</span>
                            <span v-else class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Belum Bayar</span>
                        </span>
                        <span v-else class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Belum Bayar</span>
                    </template>

                    <template #wa_ayah="{ item }">
                        <button v-if="item.orang_tua?.wa_ayah" @click="openSendWaModal(item, 'ayah')" class="inline-flex items-center text-emerald-600 hover:text-emerald-700">
                            <i class="fab fa-whatsapp text-xl mr-1"></i>
                            <span class="text-sm">{{ item.orang_tua.wa_ayah }}</span>
                        </button>
                        <span v-else class="text-gray-400">-</span>
                    </template>

                    <template #wa_ibu="{ item }">
                        <button v-if="item.orang_tua?.wa_ibu" @click="openSendWaModal(item, 'ibu')" class="inline-flex items-center text-emerald-600 hover:text-emerald-700">
                            <i class="fab fa-whatsapp text-xl mr-1"></i>
                            <span class="text-sm">{{ item.orang_tua.wa_ibu }}</span>
                        </button>
                        <span v-else class="text-gray-400">-</span>
                    </template>

                    <template #alamat="{ item }">
                        <div class="flex flex-col">
                            <span class="text-sm text-gray-500">{{ item.orang_tua?.alamat || '-' }}</span>
                            <span class="text-xs text-gray-400">
                                {{ getNamaWilayah('desa', item.orang_tua?.desa_id) }},
                                {{ getNamaWilayah('kecamatan', item.orang_tua?.kecamatan_id) }},
                                {{ getNamaWilayah('kabupaten', item.orang_tua?.kabupaten_id) }},
                                {{ getNamaWilayah('provinsi', item.orang_tua?.provinsi_id) }}
                            </span>
                        </div>
                    </template>

                    <template #dokumen="{ item }">
                        <span
                            v-if="isDokumenLengkap(item)"
                            class="px-4 py-2 inline-flex text-sm leading-5 font-semibold rounded-full bg-green-100 text-green-800 shadow-md items-center transition-all duration-200 hover:scale-105 hover:bg-green-200 cursor-pointer"
                            :title="'Semua dokumen wajib sudah lengkap: Pas Foto, Akta Kelahiran, Kartu Keluarga, Ijazah'"
                            style="overflow: visible; white-space: nowrap;"
                            @click="previewDokumen(item)"
                        >
                            <i class="fas fa-check-circle mr-2 text-lg"></i> Lengkap
                        </span>
                        <span
                            v-else
                            class="px-4 py-2 inline-flex text-sm leading-5 font-semibold rounded-full bg-red-100 text-red-800 shadow-md items-center transition-all duration-200 hover:scale-105 hover:bg-red-200 cursor-pointer"
                            :title="'Dokumen kurang: ' + dokumenKurang(item).join(', ')"
                            style="overflow: visible; white-space: nowrap;"
                        >
                            <i class="fas fa-times-circle mr-2 text-lg"></i> Kurang {{ dokumenKurang(item).length }}
                        </span>
                    </template>

                    <template #nama_ayah="{ item }">
                        {{ item.orang_tua?.nama_ayah || '-' }}
                    </template>

                    <template #nama_ibu="{ item }">
                        {{ item.orang_tua?.nama_ibu || '-' }}
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

        <SendWaModal
            :show="showSendWaModal"
            :variablesData="waSantri ? {
                nama_anak: waSantri.nama,
                nama_ayah: waSantri.orang_tua?.nama_ayah,
                nama_ibu: waSantri.orang_tua?.nama_ibu,
                nomor_pendaftaran: waSantri.nomor_pendaftaran,
                wa_ayah: waSantri.orang_tua?.wa_ayah,
                wa_ibu: waSantri.orang_tua?.wa_ibu,
            } : {}"
            :defaultMessage="waDefaultMessage"
            @close="showSendWaModal = false"
            @send="handleSendWa"
        />
    </div>
</template>

<script>
import SantriTrashed from './SantriTrashed.vue'
import ModernTable from '../../../components/common/ModernTable.vue'
import SearchBar from '../../../components/common/SearchBar.vue'
import ActionButtons from '../../../components/common/ActionButtons.vue'
import SantriModal from './parts/SantriModal.vue'
import LoadingOverlay from '../../../components/common/LoadingOverlay.vue'
import * as XLSX from 'xlsx'
import ExcelJS from 'exceljs'
import { saveAs } from 'file-saver'
import ModernPagination from '../../../components/common/ModernPagination.vue'
import { swalConfirm, swalPromptSelect } from '../../../components/common/Swal2Dialog.js'
import DokumenPreviewModal from '../../../components/common/DokumenPreviewModal.vue'
import SendWaModal from '../../../components/common/SendWaModal.vue'

export default {
    components: {
        SantriTrashed,
        ModernTable,
        SearchBar,
        ActionButtons,
        SantriModal,
        LoadingOverlay,
        ModernPagination,
        DokumenPreviewModal,
        SendWaModal,
    },
    data() {
        return {
            santriList: [],
            showTrashed: false,
            loading: false,
            showEditModal: false,
            editForm: {
                orangTua: {},
                dokumen: {}
            },
            activeTab: 'santri',
            columns: [
                { key: 'no', label: 'No' },
                { key: 'aksi', label: 'Aksi' },
                { key: 'unit', label: 'Unit' },
                { key: 'nama', label: 'Nama Lengkap' },
                { key: 'nisn', label: 'NISN' },
                { key: 'jenis_kelamin', label: 'Jenis Kelamin' },
                { key: 'tempat_tanggal_lahir', label: 'Tempat, Tanggal Lahir' },
                { key: 'anak_ke', label: 'Anak Ke (dari Bersaudara)' },
                { key: 'asal_sekolah', label: 'Asal Sekolah' },
                { key: 'status_tes', label: 'Status Tes' },
                { key: 'status_pembayaran', label: 'Status Pembayaran' },
                { key: 'nama_ayah', label: 'Ayah' },
                { key: 'wa_ayah', label: 'WA Ayah' },
                { key: 'nama_ibu', label: 'Ibu' },
                { key: 'wa_ibu', label: 'WA Ibu' },
                { key: 'alamat', label: 'Alamat' },
                { key: 'dokumen', label: 'Dokumen' }
            ],
            search: '',
            sortKey: 'created_at',
            sortAsc: false,
            currentPage: 1,
            perPage: 10,
            totalItems: 0,
            searchDebounce: null,
            visibleColumns: [
                'no','aksi','unit','nama','nisn','jenis_kelamin','tempat_tanggal_lahir','anak_ke','asal_sekolah','status_tes','status_pembayaran','nama_ayah','wa_ayah','nama_ibu','wa_ibu','alamat','dokumen'
            ],
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
            activeTesDropdown: null,
            selectedSantri: [],
            dropdownStyle: {},
            provinceMap: {},
            regencyMap: {},
            districtMap: {},
            villageMap: {},
            wilayahLoading: false,
            showPreviewModal: false,
            previewDokumenItems: [],
            previewStartIndex: 0,
            showSendWaModal: false,
            waTarget: null,
            waType: '', // 'ayah' atau 'ibu'
            waSantri: null,
            waDefaultMessage: '',
            loadingLoginSantri: null,
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
        document.addEventListener('click', this.closeTesDropdown);
    },
    beforeUnmount() {
        document.removeEventListener('click', this.closeTesDropdown);
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
                const response = await axios.get('/admin/santri/data', { params });
                this.santriList = response.data.data;
                this.totalPages = response.data.last_page;
                this.currentPage = response.data.current_page;
                this.totalItems = response.data.total;
                // Setelah data santri didapat, fetch mapping wilayah
                await this.fetchWilayahMapsOptimized(this.santriList);
            } catch (error) {
                console.error("Error fetching santri:", error);
                Toast.fire({ icon: 'error', title: 'Gagal mengambil data santri' });
            } finally {
                this.loading = false;
            }
        },
        async fetchWilayahMapsOptimized(santriList) {
            this.wilayahLoading = true;
            const API_BASE_URL = 'https://www.emsifa.com/api-wilayah-indonesia/api';
            // Ambil unique ID
            const provIds = [...new Set(santriList.map(s => s.orang_tua?.provinsi_id).filter(Boolean))];
            const kabIds = [...new Set(santriList.map(s => s.orang_tua?.kabupaten_id).filter(Boolean))];
            const kecIds = [...new Set(santriList.map(s => s.orang_tua?.kecamatan_id).filter(Boolean))];
            const desaIds = [...new Set(santriList.map(s => s.orang_tua?.desa_id).filter(Boolean))];
            try {
                // Provinsi
                if (provIds.length) {
                    const provRes = await fetch(`${API_BASE_URL}/provinces.json`);
                    const provs = await provRes.json();
                    provIds.forEach(id => {
                        const found = provs.find(p => p.id == id);
                        if (found) this.provinceMap[id] = found.name;
                    });
                }
                // Kabupaten
                await Promise.all(provIds.map(async provId => {
                    const regRes = await fetch(`${API_BASE_URL}/regencies/${provId}.json`);
                    const regs = await regRes.json();
                    kabIds.forEach(id => {
                        const found = regs.find(r => r.id == id);
                        if (found) this.regencyMap[id] = found.name;
                    });
                }));
                // Kecamatan
                await Promise.all(kabIds.map(async kabId => {
                    const distRes = await fetch(`${API_BASE_URL}/districts/${kabId}.json`);
                    const dists = await distRes.json();
                    kecIds.forEach(id => {
                        const found = dists.find(d => d.id == id);
                        if (found) this.districtMap[id] = found.name;
                    });
                }));
                // Desa
                await Promise.all(kecIds.map(async kecId => {
                    const desaRes = await fetch(`${API_BASE_URL}/villages/${kecId}.json`);
                    const desas = await desaRes.json();
                    desaIds.forEach(id => {
                        const found = desas.find(v => v.id == id);
                        if (found) this.villageMap[id] = found.name;
                    });
                }));
            } catch (e) {
                console.error('Gagal fetch data wilayah:', e);
            } finally {
                this.wilayahLoading = false;
            }
        },
        getNamaWilayah(type, id) {
            if (!id) return '-';
            if (type === 'provinsi') return this.provinceMap[id] || id;
            if (type === 'kabupaten') return this.regencyMap[id] || id;
            if (type === 'kecamatan') return this.districtMap[id] || id;
            if (type === 'desa') return this.villageMap[id] || id;
            return id;
        },
        async editSantri(santri) {
            this.editForm = JSON.parse(JSON.stringify(santri))
            // Normalisasi struktur orang_tua ke orangTua (biar cocok sama form)
            if (this.editForm.orang_tua) {
                this.editForm.orangTua = { ...this.editForm.orang_tua }
            } else if (!this.editForm.orangTua) {
                this.editForm.orangTua = {}
            }
            // Convert tanggal_lahir ke format yyyy-mm-dd
            this.editForm.tanggal_lahir = this.toDateInputValue(this.editForm.tanggal_lahir)
            this.activeTab = 'santri'
            this.showEditModal = true
        },
        toDateInputValue(dateStr) {
            if (!dateStr) return '';
            const d = new Date(dateStr);
            if (isNaN(d.getTime())) return '';
            return d.toISOString().slice(0, 10);
        },
        async deleteSantri(id, event) {
            const isForce = event && event.ctrlKey;

            const result = await swalConfirm({
                title: isForce ? 'Pindahkan Paksa ke Sampah?' : 'Yakin hapus?',
                html: isForce
                    ? 'Santri ini memiliki bukti pembayaran. Anda akan <strong>memaksa</strong> memindahkannya ke data terhapus.'
                    : `Data santri akan dipindahkan ke data terhapus.<br><div style="margin-top:12px;color:#f59e0b;font-size:14px;"><b>Ingin hapus secara paksa?</b> Tekan <kbd>Ctrl</kbd> + klik pada tombol <b>hapus!</b></div>`,
                icon: 'warning',
                confirmButtonColor: isForce ? '#f59e0b' : '#3085d6',
                cancelButtonColor: '#718096',
                confirmButtonText: isForce ? 'Ya, Pindahkan Paksa!' : 'Ya, hapus!',
                cancelButtonText: 'Batal'
            });

            if (result.isConfirmed) {
                this.showLoading(
                    isForce ? 'Memindahkan Paksa...' : 'Menghapus Data...',
                    'Mohon tunggu sebentar',
                    'fas fa-trash-alt',
                    true,
                    true
                );

                try {
                    this.updateProgress(50, 'Menghubungi server...');
                    const url = isForce ? `/admin/santri/${id}?force=true` : `/admin/santri/${id}`;
                    await axios.delete(url);
                    await new Promise(resolve => setTimeout(resolve, 300));

                    this.updateProgress(100, 'Selesai!');
                    await this.fetchSantri();
                    Toast.fire({
                        icon: 'success',
                        title: `Data berhasil ${isForce ? 'dipindahkan ke sampah' : 'dihapus'}`
                    });
                } catch (error) {
                    console.error("Error deleting santri:", error);
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
        changePage(page) {
            if (page >= 1 && page <= this.totalPages && page !== this.currentPage) {
                this.currentPage = page;
                this.fetchSantri();
            }
        },
        handlePerPageChange(newPerPage) {
            this.perPage = newPerPage;
            this.currentPage = 1;
            this.fetchSantri();
        },
        sortBy(key) {
            if (this.sortKey === key) {
                this.sortAsc = !this.sortAsc
            } else {
                this.sortKey = key
                this.sortAsc = true
            }
        },
        sortIcon(key) {
            if (this.sortKey !== key) return 'fas fa-sort text-muted';
            return this.sortAsc ? 'fas fa-sort-up text-primary' : 'fas fa-sort-down text-primary';
        },
        async exportExcel() {
            this.showLoading(
                'Export Excel...',
                'Mengunduh semua data santri...',
                'fas fa-file-excel',
                true,
                true
            );

            let allSantri = [];
            try {
                this.updateProgress(5, 'Mengambil data dari server...');
                const response = await axios.get('/admin/santri/data', {
                    params: {
                        perPage: -1, // Memberi tahu backend untuk mengirim semua data
                        search: this.search,
                        sortKey: this.sortKey,
                        sortAsc: this.sortAsc,
                    }
                });
                allSantri = response.data.data;
            } catch (error) {
                this.hideLoading();
                Toast.fire({ icon: 'error', title: 'Gagal mengunduh data santri!' });
                console.error("Error fetching all santri for export:", error);
                return;
            }

            // Helper untuk resolve nama wilayah
            const provinceMap = new Map();
            const regencyMap = new Map();
            const districtMap = new Map();
            const villageMap = new Map();
            const API_BASE_URL = 'https://www.emsifa.com/api-wilayah-indonesia/api';

            try {
                this.updateProgress(10, 'Mengunduh data provinsi...');
                const provinces = await (await fetch(`${API_BASE_URL}/provinces.json`)).json();
                provinces.forEach(p => provinceMap.set(p.id, p.name));

                const uniqueProvinsiIds = [...new Set(allSantri.map(s => s.orang_tua?.provinsi_id).filter(id => id))];
                const uniqueKabupatenIds = [...new Set(allSantri.map(s => s.orang_tua?.kabupaten_id).filter(id => id))];
                const uniqueKecamatanIds = [...new Set(allSantri.map(s => s.orang_tua?.kecamatan_id).filter(id => id))];

                this.updateProgress(25, 'Mengunduh data kabupaten...');
                await Promise.all(uniqueProvinsiIds.map(async id => {
                    const response = await fetch(`${API_BASE_URL}/regencies/${id}.json`);
                    if(response.ok) {
                        const regencies = await response.json();
                        regencies.forEach(r => regencyMap.set(r.id, r.name));
                    } else {
                        console.warn(`Gagal mengambil data kabupaten untuk provinsi ID: ${id}`);
                    }
                }));

                this.updateProgress(50, 'Mengunduh data kecamatan...');
                await Promise.all(uniqueKabupatenIds.map(async id => {
                    const response = await fetch(`${API_BASE_URL}/districts/${id}.json`);
                    if(response.ok) {
                        const districts = await response.json();
                        districts.forEach(d => districtMap.set(d.id, d.name));
                    } else {
                        console.warn(`Gagal mengambil data kecamatan untuk kabupaten ID: ${id}`);
                    }
                }));

                this.updateProgress(75, 'Mengunduh data desa...');
                await Promise.all(uniqueKecamatanIds.map(async id => {
                    const response = await fetch(`${API_BASE_URL}/villages/${id}.json`);
                    if(response.ok) {
                        const villages = await response.json();
                        villages.forEach(v => villageMap.set(v.id, v.name));
                    } else {
                        console.warn(`Gagal mengambil data desa untuk kecamatan ID: ${id}`);
                    }
                }));

                this.updateProgress(90, 'Membuat file Excel...');

                const workbook = new ExcelJS.Workbook()
                const sheet = workbook.addWorksheet('Data Santri')

                // Warna modern
                const primary = '2563EB' // biru modern
                const headerBg = '1E293B' // abu gelap
                const zebra = 'F1F5F9' // abu muda
                const border = 'E5E7EB' // abu border
                const judul = '0F172A' // biru navy

                // Tanggal
                const date = new Date()
                const months = ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember']
                const metaTanggal = `${date.getDate()} ${months[date.getMonth()]} ${date.getFullYear()}`

                // Judul
                sheet.mergeCells('A1:AI1')
                sheet.getCell('A1').value = 'DATA SANTRI BARU PONDOK PESANTREN'
                sheet.getCell('A1').font = { bold: true, size: 18, color: { argb: judul } }
                sheet.getCell('A1').alignment = { horizontal: 'center', vertical: 'middle' }
                sheet.getRow(1).height = 32

                // Metadata
                sheet.mergeCells('A2:AI2')
                sheet.getCell('A2').value = `Diekspor pada: ${metaTanggal}`
                sheet.getCell('A2').font = { size: 11, color: { argb: primary } }
                sheet.getCell('A2').alignment = { horizontal: 'center', vertical: 'middle' }
                sheet.getRow(2).height = 18

                sheet.getRow(3).height = 6

                // Header
                const headers = [
                    'No','Unit','Nama','NISN','Jenis Kelamin','Tempat Lahir','Tanggal Lahir','Anak Ke (dari Bersaudara)','Asal Sekolah','Kelas','Status Pembayaran','Status Tes','Nama Ayah','Pekerjaan Ayah','Pekerjaan Ayah Lainnya','Pendidikan Ayah','WA Ayah','Nama Ibu','Pekerjaan Ibu','Pekerjaan Ibu Lainnya','Pendidikan Ibu','WA Ibu','Provinsi','Kabupaten','Kecamatan','Desa','Alamat','Kode Pos','Pasfoto','Akta Lahir','Kartu Keluarga','Ijazah'
                ]
                sheet.addRow(headers)
                const headerRow = sheet.getRow(4)
                headerRow.eachCell(cell => {
                    cell.font = { bold: true, color: { argb: 'FFFFFFFF' }, size: 12 }
                    cell.fill = { type: 'pattern', pattern: 'solid', fgColor: { argb: headerBg } }
                    cell.alignment = { horizontal: 'center', vertical: 'middle', wrapText: true }
                    cell.border = {
                        top: { style: 'thin', color: { argb: border } },
                        bottom: { style: 'thin', color: { argb: border } },
                        left: { style: 'thin', color: { argb: border } },
                        right: { style: 'thin', color: { argb: border } }
                    }
                })
                sheet.getRow(4).height = 28

                // Data
                allSantri.forEach((santri, idx) => {
                    const anakKeStr = santri.anak_ke && santri.jumlah_saudara ? `Anak ke ${santri.anak_ke} dari ${santri.jumlah_saudara} saudara` : (santri.anak_ke ? `Anak ke ${santri.anak_ke}` : '-');
                    const row = [
                        idx+1,
                        santri.unit ? santri.unit.toUpperCase() : '-',
                        santri.nama || '-',
                        santri.nisn || '-',
                        santri.jenis_kelamin || '-',
                        santri.tempat_lahir || '-',
                        this.formatDate(santri.tanggal_lahir) || '-',
                        anakKeStr,
                        santri.asal_sekolah || '-',
                        santri.kelas || '-',
                        this.getStatusPembayaranText(santri.pembayaran),
                        santri.status_tes || '-',
                        santri.orang_tua?.nama_ayah || '-',
                        santri.orang_tua?.pekerjaan_ayah || '-',
                        santri.orang_tua?.pekerjaan_ayah_lainnya || '-',
                        santri.orang_tua?.pendidikan_ayah || '-',
                        santri.orang_tua?.wa_ayah || '-',
                        santri.orang_tua?.nama_ibu || '-',
                        santri.orang_tua?.pekerjaan_ibu || '-',
                        santri.orang_tua?.pekerjaan_ibu_lainnya || '-',
                        santri.orang_tua?.pendidikan_ibu || '-',
                        santri.orang_tua?.wa_ibu || '-',
                        provinceMap.get(santri.orang_tua?.provinsi_id) || santri.orang_tua?.provinsi_id || '-',
                        regencyMap.get(santri.orang_tua?.kabupaten_id) || santri.orang_tua?.kabupaten_id || '-',
                        districtMap.get(santri.orang_tua?.kecamatan_id) || santri.orang_tua?.kecamatan_id || '-',
                        villageMap.get(santri.orang_tua?.desa_id) || santri.orang_tua?.desa_id || '-',
                        santri.orang_tua?.alamat || '-',
                        santri.orang_tua?.kode_pos || '-',
                        santri.dokumen?.pasfoto ? 'Lengkap' : 'Belum Lengkap',
                        santri.dokumen?.akta_lahir ? 'Lengkap' : 'Belum Lengkap',
                        santri.dokumen?.kartu_keluarga ? 'Lengkap' : 'Belum Lengkap',
                        santri.dokumen?.ijazah ? 'Lengkap' : 'Belum Lengkap'
                    ]
                    sheet.addRow(row)
                })

                // Styling data
                for (let i = 5; i <= sheet.rowCount; i++) {
                    const row = sheet.getRow(i)
                    row.height = 22
                    row.eachCell((cell, col) => {
                        // Zebra striping
                        if (i % 2 === 0) cell.fill = { type: 'pattern', pattern: 'solid', fgColor: { argb: zebra } }
                        // Border tipis
                        cell.border = {
                            top: { style: 'thin', color: { argb: border } },
                            bottom: { style: 'thin', color: { argb: border } },
                            left: { style: 'thin', color: { argb: border } },
                            right: { style: 'thin', color: { argb: border } }
                        }
                        // Alignment
                        if ([1,2,10,11,12,13,14,15].includes(col)) {
                            cell.alignment = { horizontal: 'center', vertical: 'middle', wrapText: true }
                        } else {
                            cell.alignment = { horizontal: 'left', vertical: 'middle', wrapText: false }
                        }
                    })
                }

                // Freeze header & autofilter
                sheet.views = [{ state: 'frozen', ySplit: 4 }]
                sheet.autoFilter = { from: 'A4', to: 'AI4' }

                // Lebar kolom auto (kecuali kolom panjang)
                const autoCols = [3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31,32,33,34,35]
                autoCols.forEach(i => {
                    sheet.getColumn(i).width = 18
                })
                sheet.getColumn(1).width = 5 // No
                sheet.getColumn(2).width = 8 // ID
                sheet.getColumn(3).width = 35 // Nomor Pendaftaran (dibuat lebih lebar)
                sheet.getColumn(30).width = 40 // Alamat

                // Khusus kolom Nomor Pendaftaran: matikan wrapText & alignment kiri
                for (let i = 5; i <= sheet.rowCount; i++) {
                    const cell = sheet.getRow(i).getCell(3)
                    cell.alignment = { horizontal: 'left', vertical: 'middle', wrapText: false }
                }

                this.updateProgress(100, 'Download file...');
                const buffer = await workbook.xlsx.writeBuffer()
                saveAs(new Blob([buffer], { type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' }), `Data_Santri_${date.getDate()}-${date.getMonth() + 1}-${date.getFullYear()}.xlsx`)

                setTimeout(() => {
                    this.hideLoading();
                    Toast.fire({ icon: 'success', title: 'Export berhasil!' });
                }, 500);

            } catch (error) {
                console.error("Error exporting excel: ", error)
                this.hideLoading();
                Toast.fire({ icon: 'error', title: 'Gagal export data!', text: 'Gagal mengambil data wilayah.' });
            }
        },
        getStatusPembayaranText(pembayaran) {
            if (!pembayaran) return 'Belum Bayar'
            if (pembayaran.status_administrasi === 'diverifikasi' && pembayaran.status_daftar_ulang === 'diverifikasi') return 'Lunas'
            if (pembayaran.status_administrasi === 'diverifikasi') return 'Menunggu Daftar Ulang'
            if (pembayaran.status_administrasi === 'sudah_bayar') return 'Menunggu Verifikasi'
            return 'Belum Bayar'
        },
        formatDate(date) {
            if (!date) return '-';
            try {
                // Cek dulu apakah tanggal valid
                if (date.includes('Invalid Date') || date.includes('Format Tanggal Invalid')) return 'Format Tanggal Invalid';

                // Parse tanggal
                const d = new Date(date);
                if (isNaN(d.getTime())) return 'Format Tanggal Invalid';

                // Nama bulan dalam bahasa Indonesia
                const namaBulan = [
                    'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
                    'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
                ];

                // Format tanggal ke format Indonesia: DD Bulan YYYY
                const tanggal = d.getDate();
                const bulan = namaBulan[d.getMonth()];
                const tahun = d.getFullYear();

                return `${tanggal} ${bulan} ${tahun}`;
            } catch (error) {
                return 'Format Tanggal Invalid';
            }
        },
        onlyNumber(str) {
            return (str || '').replace(/[^0-9]/g, '');
        },
        getTesStatusClass(status) {
            switch (status) {
                case 'Lulus': return 'bg-green-100 text-green-800';
                case 'Tidak Lulus': return 'bg-red-100 text-red-800';
                case 'Menunggu Tes': return 'bg-yellow-100 text-yellow-800';
                default: return 'bg-gray-100 text-gray-800';
            }
        },
        isDokumenLengkap(santri) {
            const wajib = ['pasfoto','akta_lahir','kartu_keluarga','ijazah'];
            if (!santri.dokumen) return false;
            return wajib.every(key => !!santri.dokumen[key]);
        },
        dokumenKurang(santri) {
            const wajib = { pasfoto: 'Pas Foto', akta_lahir: 'Akta Kelahiran', kartu_keluarga: 'Kartu Keluarga', ijazah: 'Ijazah' };
            if (!santri.dokumen) return Object.values(wajib);
            return Object.entries(wajib).filter(([key]) => !santri.dokumen[key]).map(([,label]) => label);
        },
        handleSort({ key, asc }) {
            this.sortKey = key;
            this.sortAsc = asc;
            this.currentPage = 1;
            this.fetchSantri();
        },
        handleRowDblClick({ item, index, event }) {
            this.editSantri(item);
        },
        handleFormUpdate(newForm) {
            this.editForm = newForm;
        },
        async updateSantri() {
            this.showLoading(
                'Update Data Santri...',
                'Sedang mengupdate data santri',
                'fas fa-save',
                true,
                true
            );

            try {
                this.updateProgress(20, 'Menyiapkan data...');
                await new Promise(resolve => setTimeout(resolve, 300));

                this.updateProgress(40, 'Mengupload file...');
                const formData = new FormData()

                // Append all fields from editForm
                Object.keys(this.editForm).forEach(key => {
                    const value = this.editForm[key];
                    if (key === 'orangTua' && typeof value === 'object' && value !== null) {
                        Object.keys(value).forEach(subKey => {
                            formData.append(`orang_tua[${subKey}]`, value[subKey] ?? '');
                        });
                    } else if (key === 'dokumen' && typeof value === 'object' && value !== null) {
                        Object.keys(value).forEach(subKey => {
                            if (value[subKey] instanceof File) {
                                formData.append(`dokumen[${subKey}]`, value[subKey]);
                            }
                        });
                    } else if (value !== null && value !== undefined) {
                        formData.append(key, value);
                    }
                });

                this.updateProgress(60, 'Menyimpan data...');
                await axios.post(`/admin/santri/${this.editForm.id}?_method=PUT`, formData, {
                    headers: { 'Content-Type': 'multipart/form-data' }
                });

                this.updateProgress(80, 'Memperbarui tampilan...');
                await new Promise(resolve => setTimeout(resolve, 200));

                this.updateProgress(100, 'Selesai!');
                await new Promise(resolve => setTimeout(resolve, 300));

                this.showEditModal = false;
                this.fetchSantri();
                Toast.fire({ icon: 'success', title: 'Data santri berhasil diupdate!' });
            } catch (error) {
                console.error('Error updating santri:', error);
                if (error.response && error.response.data && error.response.data.errors) {
                    // Tampilkan error validasi
                    const errors = error.response.data.errors;
                    const errorMessages = Object.values(errors).flat().join(', ');
                    Toast.fire({ icon: 'error', title: 'Validasi gagal', text: errorMessages });
                } else {
                    Toast.fire({ icon: 'error', title: 'Gagal update data santri!' });
                }
            } finally {
                this.hideLoading();
            }
        },
        toggleTesDropdown(index, event) {
            if (this.activeTesDropdown === index) {
                this.activeTesDropdown = null;
            } else {
                const button = event.currentTarget;
                const rect = button.getBoundingClientRect();
                const dropdownWidth = 208; // w-52 = 13rem * 16px = 208px
                const leftPosition = rect.right - dropdownWidth;

                this.dropdownStyle = {
                    top: `${rect.bottom + 2}px`,
                    left: `${leftPosition}px`,
                };
                this.activeTesDropdown = index;
            }
        },
        closeTesDropdown(event) {
            this.activeTesDropdown = null;
        },
        async promptUpdateStatus(santri, status, event) {
            this.closeTesDropdown(event);

            const result = await swalConfirm({
                title: `Ubah status menjadi "${status}"?`,
                text: `Anda akan mengubah status tes untuk santri ${santri.nama}.`,
                icon: 'question',
                confirmButtonColor: '#34D399',
                cancelButtonColor: '#F87171',
                confirmButtonText: 'Ya, ubah!',
                cancelButtonText: 'Batal'
            });

            if (result.isConfirmed) {
                this.showLoading('Mengubah Status...', `Status tes untuk ${santri.nama} sedang diubah...`);
                try {
                    await axios.post(`/admin/santri/${santri.id}/update-status`, {
                        status_tes: status
                    });
                    await this.fetchSantri();
                    Toast.fire({
                        icon: 'success',
                        title: 'Status tes berhasil diubah!'
                    });
                } catch (error) {
                    console.error("Error updating test status:", error);
                    Toast.fire({
                        icon: 'error',
                        title: 'Gagal mengubah status!',
                        text: error.response?.data?.message || 'Terjadi kesalahan di server.'
                    });
                } finally {
                    this.hideLoading();
                }
            }
        },
        getRowClass(item, index) {
            return this.activeTesDropdown === index ? 'dropdown-active' : '';
        },
        async bulkDelete() {
            const result = await swalConfirm({
                title: `Yakin hapus ${this.selectedSantri.length} santri?`,
                text: 'Data yang dihapus akan masuk ke keranjang sampah.',
                icon: 'warning',
                confirmButtonColor: '#E53E3E',
                cancelButtonColor: '#718096',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            });

            if (result.isConfirmed) {
                this.showLoading('Menghapus Data...', `Sedang menghapus ${this.selectedSantri.length} data santri...`);
                try {
                    const ids = this.selectedSantri.map(s => s.id);
                    await axios.post('/admin/santri/bulk-delete', { ids });

                    await this.fetchSantri();
                    this.selectedSantri = []; // Kosongkan seleksi setelah berhasil

                    Toast.fire({
                        icon: 'success',
                        title: 'Santri yang dipilih berhasil dihapus!'
                    });
                } catch (error) {
                    console.error("Error during bulk delete:", error);
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
        async bulkUpdateStatus() {
            const { value: status } = await swalPromptSelect({
                title: 'Pilih status tes baru',
                inputOptions: {
                    'Lulus': 'Lulus',
                    'Tidak Lulus': 'Tidak Lulus',
                    'Menunggu Tes': 'Menunggu Tes'
                },
                inputPlaceholder: 'Pilih status',
                confirmButtonText: 'Ubah Status',
                cancelButtonText: 'Batal',
                inputValidator: (value) => {
                    return new Promise((resolve) => {
                        if (value) {
                            resolve();
                        } else {
                            resolve('Anda harus memilih status tes');
                        }
                    });
                }
            });

            if (status) {
                this.showLoading('Mengubah Status...', `Sedang mengubah status untuk ${this.selectedSantri.length} santri...`);
                try {
                    const ids = this.selectedSantri.map(s => s.id);
                    await axios.post('/admin/santri/bulk-update-status', {
                        ids,
                        status_tes: status
                    });

                    await this.fetchSantri();
                    this.selectedSantri = [];

                    Toast.fire({
                        icon: 'success',
                        title: 'Status tes berhasil diubah!'
                    });
                } catch (error) {
                    console.error("Error during bulk status update:", error);
                    Toast.fire({
                        icon: 'error',
                        title: 'Gagal mengubah status!',
                        text: error.response?.data?.message || 'Terjadi kesalahan di server.'
                    });
                } finally {
                    this.hideLoading();
                }
            }
        },
        previewDokumen(item) {
            // Urutkan dokumen wajib
            const dokumenWajib = [
                { key: 'pasfoto', label: 'Pas Foto 3x4' },
                { key: 'akta_lahir', label: 'Akta Kelahiran' },
                { key: 'kartu_keluarga', label: 'Kartu Keluarga' },
                { key: 'ijazah', label: 'Ijazah Terakhir' },
            ];
            this.previewDokumenItems = dokumenWajib
                .filter(doc => item.dokumen && item.dokumen[doc.key])
                .map(doc => ({
                    label: doc.label,
                    url: item.dokumen[doc.key].startsWith('http') ? item.dokumen[doc.key] : `/storage/${item.dokumen[doc.key]}`
                }));
            this.previewStartIndex = 0;
            this.showPreviewModal = true;
        },
        openSendWaModal(item, type) {
            this.waSantri = item;
            this.waType = type;
            this.waTarget = type === 'ayah' ? item.orang_tua?.wa_ayah : item.orang_tua?.wa_ibu;
            // Cek localStorage buat default message
            const saved = localStorage.getItem('wa_template_msg');
            this.waDefaultMessage = saved || `Assalamu'alaikum Wr. Wb.\n\nYth. Bapak/Ibu {{nama_ayah}}/{{nama_ibu}}, orang tua dari {{nama_anak}} (No. Pendaftaran: {{nomor_pendaftaran}}).\n\nKami ingin menginformasikan terkait proses pendaftaran santri. Mohon untuk mengecek status pendaftaran dan melengkapi dokumen yang diperlukan.\n\nUntuk akses langsung ke dashboard santri, silakan klik link berikut:\n{{magic_login}}\n\nTerima kasih atas perhatian dan kerjasamanya.\n\nWassalamu'alaikum Wr. Wb.`;
            this.showSendWaModal = true;
        },
        async handleSendWa({ message, saveTemplate }) {
            // Replace variable
            let msg = message;
            const vars = this.waSantri ? {
                nama_anak: this.waSantri.nama,
                nama_ayah: this.waSantri.orang_tua?.nama_ayah,
                nama_ibu: this.waSantri.orang_tua?.nama_ibu,
                nomor_pendaftaran: this.waSantri.nomor_pendaftaran,
                wa_ayah: this.waSantri.orang_tua?.wa_ayah,
                wa_ibu: this.waSantri.orang_tua?.wa_ibu,
            } : {};
            // Generate magic link (dummy, ganti dengan API call kalau sudah ada endpoint)
            let magicLink = '';
            try {
                const res = await axios.post(`/admin/santri/${this.waSantri.id}/generate-magic-link`);
                magicLink = res.data.link;
            } catch (e) {
                magicLink = `${window.location.origin}/santri/magic-login/dummytoken`;
            }
            vars.magic_login = magicLink;
            Object.keys(vars).forEach(k => {
                msg = msg.replaceAll(`{{${k}}}`, vars[k] || '');
            });
            // Simpan template kalau dicentang
            if (saveTemplate) {
                localStorage.setItem('wa_template_msg', message);
            }
            // Buka WhatsApp web
            const phone = (this.waTarget || '').replace(/[^0-9]/g, '');
            const url = `https://wa.me/${phone}?text=${encodeURIComponent(msg)}`;
            window.open(url, '_blank');
            this.showSendWaModal = false;
        },
        async loginAsSantri(item) {
            this.loadingLoginSantri = item.id;
            try {
                const res = await axios.post(`/admin/santri/${item.id}/generate-magic-link`);
                const link = res.data.link;
                // Buka/reuse tab dengan nama khusus
                const win = window.open('', 'santriMagicLogin');
                if (win) {
                    win.location.href = link;
                    win.focus();
                } else {
                    window.open(link, '_blank');
                }
            } catch (e) {
                Toast.fire({ icon: 'error', title: 'Gagal generate magic link santri!' });
            } finally {
                this.loadingLoginSantri = null;
            }
        },
    }
}
</script>

<style>
.modern-table {
    border-collapse: separate;
    border-spacing: 0;
    width: 100%;
    border-radius: 12px;
    overflow: hidden;
    min-width: 1200px; /* Memastikan tabel memiliki lebar minimum */
}

.modern-table thead {
    background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
}

.modern-table th {
    padding: 16px 24px;
    font-weight: 600;
    color: #475569;
    text-transform: uppercase;
    font-size: 0.75rem;
    letter-spacing: 0.05em;
    border-bottom: 1px solid #e2e8f0;
    position: relative;
    transition: all 0.2s;
}

.modern-table tbody tr {
    transition: all 0.2s ease;
}

.modern-table tbody tr:hover {
    background-color: #f8fafc;
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.04);
}

.modern-table td {
    padding: 16px 24px;
    color: #334155;
    border-bottom: 1px solid #e2e8f0;
    transition: all 0.2s;
}

.modern-table tbody tr:last-child td {
    border-bottom: none;
}

.btn-table-action {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s;
    position: relative;
    overflow: hidden;
    cursor: pointer;
}

.btn-table-action:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.table-container {
    position: relative;
    overflow-x: auto;
    scroll-behavior: smooth;
    -webkit-overflow-scrolling: touch;
    max-width: 100%;
    cursor: default;
}

.table-container:active {
    cursor: default;
}

.table-container::-webkit-scrollbar {
    height: 8px;
    width: 8px;
}

.table-container::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 4px;
}

.table-container::-webkit-scrollbar-thumb {
    background: #888;
    border-radius: 4px;
}

.table-container::-webkit-scrollbar-thumb:hover {
    background: #555;
}

.modern-table tbody tr.dropdown-active {
    position: relative;
    z-index: 10;
}
</style>
