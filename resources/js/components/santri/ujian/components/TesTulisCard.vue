<template>
  <div class="bg-white p-4 md:p-6 rounded-lg border-2 border-teal-100 hover-lift">
    <h3 class="text-lg font-medium text-teal-700 mb-4 flex items-center gap-2">
      <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
      </svg>
      Tes Tulis
      <span class="text-sm bg-orange-100 text-orange-700 px-2 py-1 rounded-full">Online</span>
    </h3>

    <!-- Status Tes -->
    <div class="mb-6 p-4 bg-gray-50 rounded-lg">
      <div class="flex justify-between items-center">
        <div>
          <h4 class="font-medium text-gray-900">Status Tes Tulis</h4>
          <p class="text-sm text-gray-600">Tes pengetahuan umum dan agama</p>
        </div>
        <div class="text-right">
          <div class="text-2xl font-bold text-teal-600">{{ tesTulis.status === 'sedang_berlangsung' ? formatTime(remainingTime) : '--:--' }}</div>
          <div class="text-xs text-gray-500">Sisa Waktu</div>
        </div>
      </div>

      <!-- Progress Bar -->
      <div class="mt-4">
        <div class="flex justify-between text-sm text-gray-600 mb-1">
          <span>Progress</span>
          <span>{{ progressPercentage }}%</span>
        </div>
        <div class="w-full bg-gray-200 rounded-full h-2">
          <div
            class="bg-teal-600 h-2 rounded-full transition-all duration-500"
            :style="{ width: progressPercentage + '%' }"
          ></div>
        </div>
      </div>
    </div>

    <!-- Informasi Tes -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
      <div class="bg-blue-50 p-3 rounded-lg border border-blue-200">
        <div class="flex items-center gap-2">
          <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
          </svg>
          <div>
            <div class="text-sm font-medium text-blue-700">Durasi</div>
            <div class="text-lg font-bold text-blue-800">{{ tesTulis.durasi }}</div>
          </div>
        </div>
      </div>

      <div class="bg-green-50 p-3 rounded-lg border border-green-200">
        <div class="flex items-center gap-2">
          <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
          </svg>
          <div>
            <div class="text-sm font-medium text-green-700">Jumlah Soal</div>
            <div class="text-lg font-bold text-green-800">{{ tesTulis.jumlah_soal }}</div>
          </div>
        </div>
      </div>

      <div class="bg-purple-50 p-3 rounded-lg border border-purple-200">
        <div class="flex items-center gap-2">
          <svg class="w-5 h-5 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
          </svg>
          <div>
            <div class="text-sm font-medium text-purple-700">Nilai</div>
            <div class="text-lg font-bold text-purple-800">{{ tesTulis.nilai || '--' }}</div>
          </div>
        </div>
      </div>
    </div>

    <!-- Tombol Aksi -->
    <div class="flex gap-3">
      <button
        v-if="tesTulis.status === 'belum_mulai'"
        @click="handleStartTes"
        class="flex-1 bg-teal-600 hover:bg-teal-700 text-white px-6 py-3 rounded-lg font-medium transition-all duration-300 shadow-md hover:shadow-lg transform hover:scale-105 flex items-center justify-center gap-2"
      >
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h1m4 0h1m-6 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
        </svg>
        Mulai Tes
      </button>

      <button
        v-else-if="tesTulis.status === 'sedang_berlangsung'"
        @click="handleContinueTes"
        class="flex-1 bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-medium transition-all duration-300 shadow-md hover:shadow-lg transform hover:scale-105 flex items-center justify-center gap-2"
      >
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
        </svg>
        Lanjutkan Tes
      </button>

      <button
        v-else-if="tesTulis.status === 'selesai'"
        @click="handleViewResult"
        class="flex-1 bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg font-medium transition-all duration-300 shadow-md hover:shadow-lg transform hover:scale-105 flex items-center justify-center gap-2"
      >
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
        </svg>
        Lihat Hasil
      </button>

      <button
        v-if="tesTulis.status === 'sedang_berlangsung'"
        @click="handlePauseTes"
        class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-3 rounded-lg font-medium transition-all duration-300 shadow-md hover:shadow-lg"
      >
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 9v6m4-6v6m7-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
        </svg>
      </button>
    </div>

    <!-- Info Tambahan -->
    <div class="mt-4 p-3 bg-yellow-50 rounded-lg border border-yellow-200">
      <div class="flex items-start gap-2">
        <svg class="w-5 h-5 text-yellow-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
        </svg>
        <div class="text-sm text-yellow-700">
          <p class="font-medium mb-1">⚠️ Perhatian:</p>
          <ul class="text-xs space-y-1">
            <li>• Jangan refresh halaman saat tes berlangsung</li>
            <li>• Pastikan koneksi internet stabil</li>
            <li>• Jawab semua pertanyaan sebelum waktu habis</li>
            <li>• Tes akan otomatis submit jika waktu habis</li>
          </ul>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'TesTulisCard',
  props: {
    tesTulis: {
      type: Object,
      required: true,
      default: () => ({
        status: 'belum_mulai',
        durasi: '120 menit',
        jumlah_soal: 50,
        nilai: null,
        waktu_mulai: null,
        waktu_selesai: null
      })
    }
  },
  data() {
    return {
      remainingTime: 7200, // 120 menit dalam detik
      timer: null,
      answeredQuestions: 0
    }
  },
  computed: {
    progressPercentage() {
      if (this.tesTulis.status === 'selesai') return 100
      if (this.tesTulis.status === 'belum_mulai') return 0
      return Math.round((this.answeredQuestions / this.tesTulis.jumlah_soal) * 100)
    }
  },
  mounted() {
    if (this.tesTulis.status === 'sedang_berlangsung') {
      this.startTimer()
    }
  },
  beforeDestroy() {
    if (this.timer) {
      clearInterval(this.timer)
    }
  },
  methods: {
    handleStartTes() {
      console.log('📝 Mulai tes tulis...')
      this.$emit('start-tes', 'tulis')
    },

    handleContinueTes() {
      console.log('📝 Lanjutkan tes tulis...')
      this.$emit('continue-tes', 'tulis')
    },

    handlePauseTes() {
      console.log('⏸️ Pause tes tulis...')
      this.$emit('pause-tes', 'tulis')
    },

    handleViewResult() {
      console.log('📊 Lihat hasil tes tulis...')
      this.$emit('view-result', 'tulis')
    },

    startTimer() {
      this.timer = setInterval(() => {
        if (this.remainingTime > 0) {
          this.remainingTime--
        } else {
          this.handleTimeUp()
        }
      }, 1000)
    },

    handleTimeUp() {
      clearInterval(this.timer)
      console.log('⏰ Waktu tes habis!')
      this.$emit('time-up', 'tulis')
    },

    formatTime(seconds) {
      const hours = Math.floor(seconds / 3600)
      const minutes = Math.floor((seconds % 3600) / 60)
      const secs = seconds % 60

      if (hours > 0) {
        return `${hours.toString().padStart(2, '0')}:${minutes.toString().padStart(2, '0')}:${secs.toString().padStart(2, '0')}`
      }
      return `${minutes.toString().padStart(2, '0')}:${secs.toString().padStart(2, '0')}`
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
