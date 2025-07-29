<template>
  <Teleport to="body">
    <div v-if="show" class="fixed inset-0 z-[9999] flex items-center justify-center bg-black bg-opacity-50 backdrop-blur-sm">
      <div class="bg-white rounded-2xl shadow-2xl p-8 max-w-md w-full mx-4 transform transition-all duration-300 animate-fadeIn">
        <!-- Loading Dots (always show as main animation) -->
        <div class="flex justify-center mb-6">
          <div class="flex justify-center space-x-2">
            <div class="w-4 h-4 bg-teal-500 rounded-full animate-bounce" style="animation-delay: 0ms;"></div>
            <div class="w-4 h-4 bg-teal-500 rounded-full animate-bounce" style="animation-delay: 150ms;"></div>
            <div class="w-4 h-4 bg-teal-500 rounded-full animate-bounce" style="animation-delay: 300ms;"></div>
          </div>
        </div>
        <!-- Title -->
        <h3 class="text-xl font-semibold text-gray-800 text-center mb-2">{{ title }}</h3>
        <!-- Subtitle -->
        <p class="text-gray-600 text-center mb-6">{{ subtitle }}</p>
        <!-- Progress Bar -->
        <div v-if="showProgress" class="mb-4">
          <div class="flex justify-between text-sm text-gray-600 mb-2">
            <span>{{ progressText }}</span>
            <span>{{ progress }}%</span>
          </div>
          <div class="w-full bg-gray-200 rounded-full h-2 overflow-hidden">
            <div
              class="bg-gradient-to-r from-teal-400 to-teal-600 h-2 rounded-full transition-all duration-300 ease-out"
              :style="{ width: progress + '%' }"
            ></div>
          </div>
        </div>
        <!-- Cancel Button -->
        <div v-if="showCancel" class="flex justify-center">
          <button
            @click="$emit('cancel')"
            class="px-4 py-2 text-gray-600 hover:text-gray-800 transition-colors duration-200"
          >
            Batal
          </button>
        </div>
      </div>
    </div>
  </Teleport>
</template>

<script>
export default {
  name: 'LoadingOverlay',
  props: {
    show: {
      type: Boolean,
      default: false
    },
    title: {
      type: String,
      default: 'Memproses...'
    },
    subtitle: {
      type: String,
      default: 'Mohon tunggu sebentar'
    },
    icon: {
      type: String,
      default: 'fas fa-cog'
    },
    showProgress: {
      type: Boolean,
      default: false
    },
    progress: {
      type: Number,
      default: 0
    },
    progressText: {
      type: String,
      default: 'Memproses data...'
    },
    showCancel: {
      type: Boolean,
      default: false
    }
  }
}
</script>

<style scoped>
.animate-fadeIn {
  animation: fadeIn 0.3s ease-out;
}

@keyframes fadeIn {
  from {
    opacity: 0;
    transform: scale(0.9) translateY(20px);
  }
  to {
    opacity: 1;
    transform: scale(1) translateY(0);
  }
}

.animate-bounce {
  animation: bounce 1.4s infinite ease-in-out both;
}

@keyframes bounce {
  0%, 80%, 100% {
    transform: scale(0);
  }
  40% {
    transform: scale(1);
  }
}
</style>