<template>
  <div class="bg-white p-4 md:p-6 rounded-lg border-2 border-teal-100 hover-lift">
    <h3 class="text-lg font-medium text-teal-700 mb-4 flex items-center gap-2">
      <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
      </svg>
      Tes Psikotes
      <span class="text-sm bg-purple-100 text-purple-700 px-2 py-1 rounded-full">Personality</span>
    </h3>

    <!-- Status Tes -->
    <div class="mb-6 p-4 bg-gray-50 rounded-lg">
      <div class="flex justify-between items-center">
        <div>
          <h4 class="font-medium text-gray-900">Tes Kepribadian & Bakat</h4>
          <p class="text-sm text-gray-600">{{ tesPsikotes.jenis }} - Mengenal diri lebih dalam</p>
        </div>
        <div class="text-right">
          <div class="text-2xl font-bold text-purple-600">{{ tesPsikotes.status === 'sedang_berlangsung' ? formatTime(remainingTime) : '--:--' }}</div>
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
            class="bg-purple-600 h-2 rounded-full transition-all duration-500"
            :style="{ width: progressPercentage + '%' }"
          ></div>
        </div>
      </div>
    </div>

    <!-- Jenis Tes -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
      <div class="bg-purple-50 p-4 rounded-lg border border-purple-200">
        <div class="flex items-center gap-3">
          <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center">
            <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
            </svg>
          </div>
          <div>
            <div class="text-sm font-medium text-purple-700">MBTI</div>
            <div class="text-xs text-purple-600">Myers-Briggs Type Indicator</div>
            <div class="text-lg font-bold text-purple-800 mt-1">{{ tesPsikotes.mbti_result || 'Belum tes' }}</div>
          </div>
        </div>
      </div>

      <div class="bg-indigo-50 p-4 rounded-lg border border-indigo-200">
        <div class="flex items-center gap-3">
          <div class="w-12 h-12 bg-indigo-100 rounded-full flex items-center justify-center">
            <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
            </svg>
          </div>
          <div>
            <div class="text-sm font-medium text-indigo-700">DISC</div>
            <div class="text-xs text-indigo-600">Dominance, Influence, Steadiness, Conscientiousness</div>
            <div class="text-lg font-bold text-indigo-800 mt-1">{{ tesPsikotes.disc_result || 'Belum tes' }}</div>
          </div>
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
            <div class="text-lg font-bold text-blue-800">{{ tesPsikotes.durasi }}</div>
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
            <div class="text-lg font-bold text-green-800">{{ tesPsikotes.jumlah_soal }}</div>
          </div>
        </div>
      </div>

      <div class="bg-yellow-50 p-3 rounded-lg border border-yellow-200">
        <div class="flex items-center gap-2">
          <svg class="w-5 h-5 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
          </svg>
          <div>
            <div class="text-sm font-medium text-yellow-700">Bakat</div>
            <div class="text-lg font-bold text-yellow-800">{{ tesPsikotes.bakat || 'Belum diketahui' }}</div>
          </div>
        </div>
      </div>
    </div>

    <!-- Tombol Aksi -->
    <div class="flex gap-3">
      <button
        v-if="tesPsikotes.status === 'belum_mulai'"
        @click="handleStartPsikotes"
        class="flex-1 bg-purple-600 hover:bg-purple-700 text-white px-6 py-3 rounded-lg font-medium transition-all duration-300 shadow-md hover:shadow-lg transform hover:scale-105 flex items-center justify-center gap-2"
      >
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
        </svg>
        Mulai Tes Psikotes
      </button>

      <button
        v-else-if="tesPsikotes.status === 'sedang_berlangsung'"
        @click="handleContinuePsikotes"
        class="flex-1 bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-3 rounded-lg font-medium transition-all duration-300 shadow-md hover:shadow-lg transform hover:scale-105 flex items-center justify-center gap-2"
      >
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
        </svg>
        Lanjutkan Tes
      </button>

      <button
        v-else-if="tesPsikotes.status === 'selesai'"
        @click="handleViewResult"
        class="flex-1 bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg font-medium transition-all duration-300 shadow-md hover:shadow-lg transform hover:scale-105 flex items-center justify-center gap-2"
      >
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
        </svg>
        Lihat Hasil
      </button>
    </div>

    <!-- Info Tambahan -->
    <div class="mt-4 p-3 bg-purple-50 rounded-lg border border-purple-200">
      <div class="flex items-start gap-2">
        <svg class="w-5 h-5 text-purple-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
        </svg>
        <div class="text-sm text-purple-700">
          <p class="font-medium mb-1">🧠 Tentang Tes Psikotes:</p>
          <ul class="text-xs space-y-1">
            <li>• Tidak ada jawaban benar atau salah</li>
            <li>• Jawab sesuai dengan kepribadian kamu</li>
            <li>• Jujur dalam menjawab untuk hasil yang akurat</li>
            <li>• Hasil akan membantu mengenali potensi diri</li>
          </ul>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'TesPsikotesCard',
  props: {
    tesPsikotes: {
      type: Object,
      required: true,
      default: () => ({
        status: 'belum_mulai',
        durasi: '90 menit',
        jumlah_soal: 60,
        jenis: 'MBTI & DISC',
        mbti_result: null,
        disc_result: null,
        bakat: null,
        waktu_mulai: null,
        waktu_selesai: null
      })
    }
  },
  data() {
    return {
      remainingTime: 5400, // 90 menit dalam detik
      timer: null,
      answeredQuestions: 0
    }
  },
  computed: {
    progressPercentage() {
      if (this.tesPsikotes.status === 'selesai') return 100
      if (this.tesPsikotes.status === 'belum_mulai') return 0
      return Math.round((this.answeredQuestions / this.tesPsikotes.jumlah_soal) * 100)
    }
  },
  mounted() {
    if (this.tesPsikotes.status === 'sedang_berlangsung') {
      this.startTimer()
    }
  },
  beforeDestroy() {
    if (this.timer) {
      clearInterval(this.timer)
    }
  },
  methods: {
    handleStartPsikotes() {
      console.log('🧠 Mulai tes psikotes...')
      this.$emit('start-psikotes')
    },

    handleContinuePsikotes() {
      console.log('🧠 Lanjutkan tes psikotes...')
      this.$emit('continue-psikotes')
    },

    handleViewResult() {
      console.log('📊 Lihat hasil tes psikotes...')
      this.$emit('view-result', 'psikotes')
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
      console.log('⏰ Waktu tes psikotes habis!')
      this.$emit('time-up', 'psikotes')
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
