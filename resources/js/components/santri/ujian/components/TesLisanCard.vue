<template>
  <div class="bg-white p-4 md:p-6 rounded-lg border-2 border-teal-100 hover-lift">
    <h3 class="text-lg font-medium text-teal-700 mb-4 flex items-center gap-2">
      <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
      </svg>
      Tes Lisan
      <span class="text-sm bg-teal-100 text-teal-700 px-2 py-1 rounded-full">Live</span>
    </h3>

    <div class="space-y-3">
      <!-- Tes Bacaan Al-Qur'an -->
      <div
        class="bg-gray-50 p-4 rounded-lg hover:bg-gray-100 transition-colors cursor-pointer"
        @click="handleTesClick('bacaan_alquran')"
      >
        <div class="flex justify-between items-center">
          <div class="flex-1">
            <h4 class="font-medium text-gray-900 flex items-center gap-2">
              📖 {{ tesLisan.bacaan_alquran.nama }}
              <span v-if="tesLisan.bacaan_alquran.status === 'selesai'" class="text-green-500">✅</span>
              <span v-else-if="tesLisan.bacaan_alquran.status === 'sedang_berlangsung'" class="text-blue-500 animate-pulse">🔄</span>
            </h4>
            <p class="text-sm text-gray-600">{{ tesLisan.bacaan_alquran.deskripsi }}</p>
            <div class="mt-2 flex items-center gap-2 text-xs text-gray-500">
              <span>⏰ Durasi: ~15 menit</span>
              <span>👨‍🏫 Penguji: Ust. Ahmad</span>
            </div>
          </div>
          <div class="text-right">
            <div class="text-xs text-gray-500 mb-1">Status</div>
            <span :class="getStatusClass(tesLisan.bacaan_alquran.status)">
              {{ getStatusText(tesLisan.bacaan_alquran.status) }}
            </span>
          </div>
        </div>
      </div>

      <!-- Tes Hafalan -->
      <div
        class="bg-gray-50 p-4 rounded-lg hover:bg-gray-100 transition-colors cursor-pointer"
        @click="handleTesClick('hafalan')"
      >
        <div class="flex justify-between items-center">
          <div class="flex-1">
            <h4 class="font-medium text-gray-900 flex items-center gap-2">
              🧠 {{ tesLisan.hafalan.nama }}
              <span v-if="tesLisan.hafalan.status === 'selesai'" class="text-green-500">✅</span>
              <span v-else-if="tesLisan.hafalan.status === 'sedang_berlangsung'" class="text-blue-500 animate-pulse">🔄</span>
            </h4>
            <p class="text-sm text-gray-600">{{ tesLisan.hafalan.deskripsi }}</p>
            <div class="mt-2 flex items-center gap-2 text-xs text-gray-500">
              <span>⏰ Durasi: ~20 menit</span>
              <span>👨‍🏫 Penguji: Ust. Muhammad</span>
            </div>
          </div>
          <div class="text-right">
            <div class="text-xs text-gray-500 mb-1">Status</div>
            <span :class="getStatusClass(tesLisan.hafalan.status)">
              {{ getStatusText(tesLisan.hafalan.status) }}
            </span>
          </div>
        </div>
      </div>

      <!-- Tes Doa -->
      <div
        class="bg-gray-50 p-4 rounded-lg hover:bg-gray-100 transition-colors cursor-pointer"
        @click="handleTesClick('doa')"
      >
        <div class="flex justify-between items-center">
          <div class="flex-1">
            <h4 class="font-medium text-gray-900 flex items-center gap-2">
              🙏 {{ tesLisan.doa.nama }}
              <span v-if="tesLisan.doa.status === 'selesai'" class="text-green-500">✅</span>
              <span v-else-if="tesLisan.doa.status === 'sedang_berlangsung'" class="text-blue-500 animate-pulse">🔄</span>
            </h4>
            <p class="text-sm text-gray-600">{{ tesLisan.doa.deskripsi }}</p>
            <div class="mt-2 flex items-center gap-2 text-xs text-gray-500">
              <span>⏰ Durasi: ~10 menit</span>
              <span>👨‍🏫 Penguji: Ust. Ali</span>
            </div>
          </div>
          <div class="text-right">
            <div class="text-xs text-gray-500 mb-1">Status</div>
            <span :class="getStatusClass(tesLisan.doa.status)">
              {{ getStatusText(tesLisan.doa.status) }}
            </span>
          </div>
        </div>
      </div>
    </div>

    <!-- Tombol Join Jitsi -->
    <div class="flex justify-center mt-6">
      <button
        @click="handleJoinJitsi"
        class="bg-teal-600 hover:bg-teal-700 text-white px-8 py-3 rounded-lg text-base font-medium transition-all duration-300 shadow-md hover:shadow-lg transform hover:scale-105 flex items-center gap-2"
        :disabled="isJoining"
      >
        <svg v-if="!isJoining" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
        </svg>
        <svg v-else class="w-5 h-5 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
        </svg>
        {{ isJoining ? 'Connecting...' : 'Join Jitsi Meet' }}
      </button>
    </div>

    <!-- Info Tambahan -->
    <div class="mt-4 p-3 bg-blue-50 rounded-lg border border-blue-200">
      <div class="flex items-start gap-2">
        <svg class="w-5 h-5 text-blue-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
        </svg>
        <div class="text-sm text-blue-700">
          <p class="font-medium mb-1">💡 Tips Tes Lisan:</p>
          <ul class="text-xs space-y-1">
            <li>• Pastikan koneksi internet stabil</li>
            <li>• Siapkan Al-Qur'an dan buku doa</li>
            <li>• Berpakaian rapi dan sopan</li>
            <li>• Jangan lupa berdoa sebelum tes! 🙏</li>
          </ul>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'TesLisanCard',
  props: {
    tesLisan: {
      type: Object,
      required: true,
      default: () => ({
        bacaan_alquran: {
          nama: 'Tes Bacaan Al-Qur\'an',
          deskripsi: 'Live talaqqi via Jitsi Meet',
          status: 'pending'
        },
        hafalan: {
          nama: 'Tes Hafalan Al-Qur\'an (Juz 30)',
          deskripsi: 'Live talaqqi via Jitsi Meet',
          status: 'pending'
        },
        doa: {
          nama: 'Tes Hafalan Doa Harian',
          deskripsi: 'Live talaqqi dengan checklist',
          status: 'pending'
        }
      })
    }
  },
  data() {
    return {
      isJoining: false
    }
  },
  methods: {
    handleTesClick(jenis) {
      console.log(`🎤 Tes ${jenis} diklik!`)
      this.$emit('tes-click', {
        jenis,
        data: this.tesLisan[jenis]
      })
    },

    async handleJoinJitsi() {
      this.isJoining = true
      console.log('🎤 Joining Jitsi Meet...')

      // Simulasi loading
      await new Promise(resolve => setTimeout(resolve, 1500))

      this.isJoining = false
      this.$emit('join-jitsi')
    },

    getStatusClass(status) {
      const classes = {
        'selesai': 'text-green-600 bg-green-100 px-2 py-1 rounded-full text-xs font-medium',
        'sedang_berlangsung': 'text-blue-600 bg-blue-100 px-2 py-1 rounded-full text-xs font-medium',
        'pending': 'text-yellow-600 bg-yellow-100 px-2 py-1 rounded-full text-xs font-medium',
        'belum_mulai': 'text-gray-600 bg-gray-100 px-2 py-1 rounded-full text-xs font-medium'
      }
      return classes[status] || classes['belum_mulai']
    },

    getStatusText(status) {
      const texts = {
        'selesai': 'Selesai',
        'sedang_berlangsung': 'Sedang Berlangsung',
        'pending': 'Menunggu',
        'belum_mulai': 'Belum Mulai'
      }
      return texts[status] || 'Belum Mulai'
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

.animate-pulse {
  animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
}

@keyframes pulse {
  0%, 100% {
    opacity: 1;
  }
  50% {
    opacity: .5;
  }
}
</style>
