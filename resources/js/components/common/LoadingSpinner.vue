<template>
  <div v-if="show" class="fixed inset-0 z-[9999] flex items-center justify-center bg-black bg-opacity-30 backdrop-blur-sm">
    <div class="bg-white rounded-xl shadow-xl p-6 max-w-sm w-full mx-4 transform transition-all duration-300 animate-fadeIn">
      <!-- Loading Icon -->
      <div class="flex justify-center mb-4">
        <div class="relative">
          <div class="w-12 h-12 border-4 border-teal-200 rounded-full animate-spin"></div>
          <div class="absolute top-0 left-0 w-12 h-12 border-4 border-transparent border-t-teal-500 rounded-full animate-spin" style="animation-duration: 1.2s;"></div>
          <div class="absolute inset-0 flex items-center justify-center">
            <i :class="icon" class="text-teal-500 text-lg"></i>
          </div>
        </div>
      </div>

      <!-- Message -->
      <div class="text-center">
        <h3 class="text-lg font-semibold text-gray-800 mb-1">{{ title }}</h3>
        <p class="text-sm text-gray-600">{{ message }}</p>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'LoadingSpinner',
  props: {
    show: {
      type: Boolean,
      default: false
    },
    title: {
      type: String,
      default: 'Loading...'
    },
    message: {
      type: String,
      default: 'Mohon tunggu sebentar'
    },
    icon: {
      type: String,
      default: 'fas fa-spinner'
    }
  }
}
</script>

<style scoped>
.animate-fadeIn {
  animation: fadeIn 0.2s ease-out;
}

@keyframes fadeIn {
  from {
    opacity: 0;
    transform: scale(0.9);
  }
  to {
    opacity: 1;
    transform: scale(1);
  }
}
</style>
