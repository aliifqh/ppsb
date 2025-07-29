<template>
  <div class="bg-white p-4 md:p-6 rounded-lg border-2 border-teal-100 hover-lift">
    <h3 class="text-lg font-medium text-teal-700 mb-4 flex items-center gap-2">
      <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
      </svg>
      Tes Kesehatan
      <span class="text-sm bg-pink-100 text-pink-700 px-2 py-1 rounded-full">Offline</span>
    </h3>

    <div class="mb-6 p-4 bg-gray-50 rounded-lg flex flex-col md:flex-row md:items-center md:justify-between gap-4">
      <div>
        <div class="text-sm text-gray-600 mb-1">Status Tes</div>
        <div :class="statusClass">
          {{ statusText }}
        </div>
      </div>
      <div>
        <div class="text-sm text-gray-600 mb-1">Jadwal</div>
        <div class="font-semibold text-teal-700">
          <span v-if="tesKesehatan.tanggal">{{ formatTanggal(tesKesehatan.tanggal) }}</span>
          <span v-else class="text-gray-400">Belum dijadwalkan</span>
        </div>
      </div>
      <div>
        <div class="text-sm text-gray-600 mb-1">Lokasi</div>
        <div class="font-semibold text-teal-700">
          {{ tesKesehatan.lokasi || 'Belum ditentukan' }}
        </div>
      </div>
    </div>

    <div class="flex gap-3">
      <button
        v-if="tesKesehatan.status === 'belum_dijadwalkan'"
        @click="$emit('view-schedule')"
        class="flex-1 bg-teal-600 hover:bg-teal-700 text-white px-6 py-3 rounded-lg font-medium transition-all duration-300 shadow-md hover:shadow-lg transform hover:scale-105 flex items-center justify-center gap-2"
      >
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10m-9 4h6m2 5H7a2 2 0 01-2-2V7a2 2 0 012-2h10a2 2 0 012 2v10a2 2 0 01-2 2z"></path>
        </svg>
        Lihat Jadwal
      </button>
      <button
        v-else-if="tesKesehatan.status === 'selesai'"
        disabled
        class="flex-1 bg-green-600 text-white px-6 py-3 rounded-lg font-medium shadow-md flex items-center justify-center gap-2 opacity-70 cursor-not-allowed"
      >
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
        </svg>
        Sudah Selesai
      </button>
      <button
        v-else
        @click="$emit('view-schedule')"
        class="flex-1 bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-medium transition-all duration-300 shadow-md hover:shadow-lg transform hover:scale-105 flex items-center justify-center gap-2"
      >
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10m-9 4h6m2 5H7a2 2 0 01-2-2V7a2 2 0 012-2h10a2 2 0 012 2v10a2 2 0 01-2 2z"></path>
        </svg>
        Lihat Detail
      </button>
    </div>

    <div class="mt-4 p-3 bg-pink-50 rounded-lg border border-pink-200">
      <div class="flex items-start gap-2">
        <svg class="w-5 h-5 text-pink-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
        </svg>
        <div class="text-sm text-pink-700">
          <p class="font-medium mb-1">🩺 Tips Tes Kesehatan:</p>
          <ul class="text-xs space-y-1">
            <li>• Datang tepat waktu sesuai jadwal</li>
            <li>• Bawa kartu identitas & alat tulis</li>
            <li>• Jaga kesehatan sebelum tes (tidur cukup, makan sehat)</li>
            <li>• Jangan lupa sarapan biar ga pingsan! 😁</li>
          </ul>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'TesKesehatanCard',
  props: {
    tesKesehatan: {
      type: Object,
      required: true,
      default: () => ({
        status: 'belum_dijadwalkan',
        tanggal: null,
        lokasi: null
      })
    }
  },
  computed: {
    statusText() {
      const texts = {
        'belum_dijadwalkan': 'Belum Dijadwalkan',
        'dijadwalkan': 'Sudah Dijadwalkan',
        'selesai': 'Selesai',
        'proses': 'Sedang Proses'
      }
      return texts[this.tesKesehatan.status] || 'Belum Dijadwalkan'
    },
    statusClass() {
      const classes = {
        'belum_dijadwalkan': 'text-gray-600 bg-gray-100 px-2 py-1 rounded-full text-xs font-medium',
        'dijadwalkan': 'text-blue-600 bg-blue-100 px-2 py-1 rounded-full text-xs font-medium',
        'selesai': 'text-green-600 bg-green-100 px-2 py-1 rounded-full text-xs font-medium',
        'proses': 'text-yellow-600 bg-yellow-100 px-2 py-1 rounded-full text-xs font-medium'
      }
      return classes[this.tesKesehatan.status] || classes['belum_dijadwalkan']
    }
  },
  methods: {
    formatTanggal(tgl) {
      if (!tgl) return '-'
      const d = new Date(tgl)
      return d.toLocaleDateString('id-ID', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' })
    }
  }
}
</script>

<style scoped>
.hover-lift {
  transition: transform 0.2s ease-in-out;
}

.hover-lift:hover {
  transform: translateY(-2px);
}
</style>
