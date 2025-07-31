<template>
  <div class="bg-white p-4 md:p-6 rounded-lg border-2 border-teal-100 mb-6 hover-lift">
    <h3 class="text-lg font-medium text-teal-700 mb-4 flex items-center gap-2">
      <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
      </svg>
      Progress Ujian
      <span class="text-sm text-gray-500">({{ totalProgress }}%)</span>
    </h3>

    <div class="space-y-4">
      <!-- Tes Lisan -->
      <div class="progress-item" @click="handleProgressClick('tes_lisan')">
        <div class="flex justify-between mb-1">
          <span class="text-sm font-medium text-gray-700 flex items-center gap-2">
            🎤 Tes Lisan
            <span v-if="progress.tes_lisan === 100" class="text-green-500">✅</span>
          </span>
          <span class="text-sm text-teal-700 font-semibold">{{ progress.tes_lisan }}%</span>
        </div>
        <div class="w-full bg-gray-200 rounded-full h-2.5 relative overflow-hidden">
          <div
            class="bg-teal-600 h-2.5 rounded-full transition-all duration-1000 ease-out"
            :style="{ width: progress.tes_lisan + '%' }"
          ></div>
          <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white to-transparent opacity-20 animate-shimmer"></div>
        </div>
      </div>

      <!-- Tes Tulis -->
      <div class="progress-item" @click="handleProgressClick('tes_tulis')">
        <div class="flex justify-between mb-1">
          <span class="text-sm font-medium text-gray-700 flex items-center gap-2">
            📝 Tes Tulis
            <span v-if="progress.tes_tulis === 100" class="text-green-500">✅</span>
          </span>
          <span class="text-sm text-teal-700 font-semibold">{{ progress.tes_tulis }}%</span>
        </div>
        <div class="w-full bg-gray-200 rounded-full h-2.5 relative overflow-hidden">
          <div
            class="bg-teal-600 h-2.5 rounded-full transition-all duration-1000 ease-out"
            :style="{ width: progress.tes_tulis + '%' }"
          ></div>
          <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white to-transparent opacity-20 animate-shimmer"></div>
        </div>
      </div>

      <!-- Tes Psikotes -->
      <div class="progress-item" @click="handleProgressClick('tes_psikotes')">
        <div class="flex justify-between mb-1">
          <span class="text-sm font-medium text-gray-700 flex items-center gap-2">
            🧠 Tes Psikotes
            <span v-if="progress.tes_psikotes === 100" class="text-green-500">✅</span>
          </span>
          <span class="text-sm text-teal-700 font-semibold">{{ progress.tes_psikotes }}%</span>
        </div>
        <div class="w-full bg-gray-200 rounded-full h-2.5 relative overflow-hidden">
          <div
            class="bg-teal-600 h-2.5 rounded-full transition-all duration-1000 ease-out"
            :style="{ width: progress.tes_psikotes + '%' }"
          ></div>
          <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white to-transparent opacity-20 animate-shimmer"></div>
        </div>
      </div>

      <!-- Tes Kesehatan -->
      <div class="progress-item" @click="handleProgressClick('tes_kesehatan')">
        <div class="flex justify-between mb-1">
          <span class="text-sm font-medium text-gray-700 flex items-center gap-2">
            🏥 Tes Kesehatan
            <span v-if="progress.tes_kesehatan === 100" class="text-green-500">✅</span>
          </span>
          <span class="text-sm text-teal-700 font-semibold">{{ progress.tes_kesehatan }}%</span>
        </div>
        <div class="w-full bg-gray-200 rounded-full h-2.5 relative overflow-hidden">
          <div
            class="bg-teal-600 h-2.5 rounded-full transition-all duration-1000 ease-out"
            :style="{ width: progress.tes_kesehatan + '%' }"
          ></div>
          <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white to-transparent opacity-20 animate-shimmer"></div>
        </div>
      </div>
    </div>

    <!-- Overall Progress -->
    <div class="mt-6 pt-4 border-t border-gray-200">
      <div class="flex justify-between items-center">
        <span class="text-sm font-medium text-gray-700">Total Progress</span>
        <span class="text-lg font-bold text-teal-700">{{ totalProgress }}%</span>
      </div>
      <div class="w-full bg-gray-200 rounded-full h-3 mt-2 relative overflow-hidden">
        <div
          class="bg-gradient-to-r from-teal-500 to-teal-600 h-3 rounded-full transition-all duration-1500 ease-out"
          :style="{ width: totalProgress + '%' }"
        ></div>
        <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white to-transparent opacity-30 animate-shimmer"></div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'ProgressUjianOverview',
  props: {
    progress: {
      type: Object,
      required: true,
      default: () => ({
        tes_lisan: 0,
        tes_tulis: 0,
        tes_psikotes: 0,
        tes_kesehatan: 0
      })
    }
  },
  computed: {
    totalProgress() {
      const total = Object.values(this.progress).reduce((sum, value) => sum + value, 0)
      return Math.round(total / 4) // 4 jenis tes
    }
  },
  methods: {
    handleProgressClick(jenis) {
      console.log(`📊 Progress ${jenis} diklik! Progress: ${this.progress[jenis]}%`)

      const messages = {
        tes_lisan: `🎤 Tes Lisan: ${this.progress.tes_lisan}% selesai`,
        tes_tulis: `📝 Tes Tulis: ${this.progress.tes_tulis}% selesai`,
        tes_psikotes: `🧠 Tes Psikotes: ${this.progress.tes_psikotes}% selesai`,
        tes_kesehatan: `🏥 Tes Kesehatan: ${this.progress.tes_kesehatan}% selesai`
      }

      this.$emit('progress-click', {
        jenis,
        progress: this.progress[jenis],
        message: messages[jenis]
      })
    }
  }
}
</script>

<style scoped>
.progress-item {
  cursor: pointer;
  transition: all 0.2s ease-in-out;
  padding: 8px;
  border-radius: 8px;
}

.progress-item:hover {
  background-color: rgba(20, 184, 166, 0.05);
  transform: translateX(4px);
}

.hover-lift {
  transition: transform 0.2s ease-in-out;
}

.hover-lift:hover {
  transform: translateY(-2px);
}

.animate-shimmer {
  animation: shimmer 2s infinite;
}

@keyframes shimmer {
  0% {
    transform: translateX(-100%);
  }
  100% {
    transform: translateX(100%);
  }
}
</style>
