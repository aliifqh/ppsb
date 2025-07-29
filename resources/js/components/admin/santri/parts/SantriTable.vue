<template>
  <div class="bg-white rounded-lg shadow-[0_2px_15px_-3px_rgba(0,0,0,0.07),0_10px_20px_-2px_rgba(0,0,0,0.04)] hover:shadow-[0_8px_17px_2px_rgba(0,0,0,0.14),0_3px_14px_2px_rgba(0,0,0,0.12),0_5px_5px_-3px_rgba(0,0,0,0.2)] transition-shadow duration-300 p-6">
    <ModernTable
      :columns="columns"
      :items="santriList"
      :loading="loading"
      :show-pagination="true"
      :current-page="currentPage"
      :total-pages="totalPages"
      :sortable-columns="['nama', 'nisn', 'unit', 'jenis_kelamin', 'status_tes']"
      :visible-columns="visibleColumns"
      :row-clickable="true"
      :row-click-disabled="(item, idx) => !item.nama"
      @page-changed="$emit('page-changed', $event)"
      @sort-changed="$emit('sort-changed', $event)"
      @row-dblclick="$emit('row-dblclick', $event)"
    >
      <template #no="{ index }">
        {{ (currentPage-1)*perPage + index + 1 }}
      </template>
      <template #aksi="{ item }">
        <ActionButtons
          :actions="[
            { key: 'edit', label: 'Edit', icon: 'fas fa-edit', color: 'bg-blue-500 text-white', tooltip: 'Edit data santri' },
            { key: 'delete', label: 'Hapus', icon: 'fas fa-trash', color: 'bg-red-500 text-white', tooltip: 'Tekan Ctrl + klik pada tombol hapus! untuk menghapus secara paksa.' }
          ]"
          @action="(key) => key === 'edit' ? $emit('edit-santri', item) : $emit('delete-santri', item.id)"
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
        {{ item.tempat_lahir ? item.tempat_lahir : '-' }}{{ item.tempat_lahir && item.tanggal_lahir ? ', ' : '' }}{{ item.tanggal_lahir }}
      </template>
      <template #status_tes="{ item }">
        <span :class="'px-2 inline-flex text-xs leading-5 font-semibold rounded-full ' + (item.status_tes === 'Lulus' ? 'bg-green-100 text-green-800' : item.status_tes === 'Tidak Lulus' ? 'bg-red-100 text-red-800' : item.status_tes === 'Belum Tes' ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-800')">
          {{ item.status_tes || '-' }}
        </span>
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
        <a v-if="item.orang_tua?.wa_ayah" :href="'https://wa.me/' + item.orang_tua.wa_ayah.replace(/[^0-9]/g, '')" target="_blank" class="inline-flex items-center text-emerald-600 hover:text-emerald-700">
          <i class="fab fa-whatsapp text-xl mr-1"></i>
          <span class="text-sm">{{ item.orang_tua.wa_ayah }}</span>
        </a>
        <span v-else class="text-gray-400">-</span>
      </template>
      <template #wa_ibu="{ item }">
        <a v-if="item.orang_tua?.wa_ibu" :href="'https://wa.me/' + item.orang_tua.wa_ibu.replace(/[^0-9]/g, '')" target="_blank" class="inline-flex items-center text-emerald-600 hover:text-emerald-700">
          <i class="fab fa-whatsapp text-xl mr-1"></i>
          <span class="text-sm">{{ item.orang_tua.wa_ibu }}</span>
        </a>
        <span v-else class="text-gray-400">-</span>
      </template>
      <template #alamat="{ item }">
        <div class="flex flex-col">
          <span class="text-sm text-gray-500">{{ item.orang_tua?.alamat || '-' }}</span>
          <span class="text-xs text-gray-400">
            {{ item.orang_tua?.desa_id || '-' }}, {{ item.orang_tua?.kecamatan_id || '-' }}, {{ item.orang_tua?.kabupaten_id || '-' }}, {{ item.orang_tua?.provinsi_id || '-' }}
          </span>
        </div>
      </template>
      <template #dokumen="{ item }">
        <span v-if="item.dokumen && ['pasfoto','akta_lahir','kartu_keluarga','ijazah'].every(key => !!item.dokumen[key])" class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
          <i class="fas fa-check-circle mr-1"></i> Lengkap
        </span>
        <span v-else class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
          <i class="fas fa-times-circle mr-1"></i> Kurang
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
</template>
<script>
import ModernTable from '../../common/ModernTable.vue'
import ActionButtons from '../../common/ActionButtons.vue'
export default {
  name: 'SantriTable',
  components: { ModernTable, ActionButtons },
  props: {
    santriList: Array,
    columns: Array,
    loading: Boolean,
    currentPage: Number,
    totalPages: Number,
    perPage: Number,
    visibleColumns: Array,
    sortKey: String,
    sortAsc: Boolean
  },
  emits: ['page-changed', 'sort-changed', 'row-dblclick', 'edit-santri', 'delete-santri'],
}
</script>
