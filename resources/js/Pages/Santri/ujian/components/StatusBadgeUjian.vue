<template>
  <div class="mb-6 flex justify-center">
    <transition name="bounce" appear>
      <span
        :class="badgeClasses"
        class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium shadow-md hover:shadow-lg transition-all duration-300 cursor-pointer"
        @click="handleBadgeClick"
      >
        <svg
          :class="iconClasses"
          class="mr-2 h-3 w-3 animate-pulse"
          fill="currentColor"
          viewBox="0 0 8 8"
        >
          <circle cx="4" cy="4" r="3" />
        </svg>
        {{ statusText }}
        <span class="ml-2 text-xs opacity-75">(klik untuk detail)</span>
      </span>
    </transition>
  </div>
</template>

<script>
export default {
  name: 'StatusBadgeUjian',
  props: {
    santri: {
      type: Object,
      required: true
    }
  },
  computed: {
    statusText() {
      const status = this.santri.status_tes || 'belum_dinilai'
      return status.split('_').map(word =>
        word.charAt(0).toUpperCase() + word.slice(1)
      ).join(' ')
    },

    badgeClasses() {
      const status = this.santri.status_tes || 'belum_dinilai'
      const baseClasses = 'inline-flex items-center px-4 py-2 rounded-full text-sm font-medium'

      switch(status) {
        case 'lulus':
          return `${baseClasses} bg-green-100 text-green-800 hover:bg-green-200`
        case 'perlu_ulang':
          return `${baseClasses} bg-red-100 text-red-800 hover:bg-red-200`
        case 'belum_dinilai':
          return `${baseClasses} bg-yellow-100 text-yellow-800 hover:bg-yellow-200`
        default:
          return `${baseClasses} bg-gray-100 text-gray-800 hover:bg-gray-200`
      }
    },

    iconClasses() {
      const status = this.santri.status_tes || 'belum_dinilai'

      switch(status) {
        case 'lulus':
          return 'text-green-400'
        case 'perlu_ulang':
          return 'text-red-400'
        case 'belum_dinilai':
          return 'text-yellow-400'
        default:
          return 'text-gray-400'
      }
    }
  },
  methods: {
    handleBadgeClick() {
      console.log('🎯 Status badge diklik! Status:', this.santri.status_tes)
      this.$emit('status-click', this.santri.status_tes)

      // Tampilkan toast notification
      this.showToast()
    },

    showToast() {
      const messages = {
        'lulus': '🎉 Selamat! Kamu sudah lulus semua tes!',
        'perlu_ulang': '🔄 Kamu perlu mengulang beberapa tes. Jangan menyerah!',
        'belum_dinilai': '⏳ Tes masih dalam proses penilaian. Sabar ya!',
        'default': '📋 Status tes kamu sedang diproses'
      }

      const message = messages[this.santri.status_tes] || messages.default

      // Emit event untuk toast (bisa dihandle di parent)
      this.$emit('show-toast', {
        message,
        type: this.santri.status_tes === 'lulus' ? 'success' : 'info'
      })
    }
  }
}
</script>

<style scoped>
.bounce-enter-active {
  animation: bounce-in 0.6s;
}

.bounce-leave-active {
  animation: bounce-in 0.6s reverse;
}

@keyframes bounce-in {
  0% {
    transform: scale(0);
  }
  50% {
    transform: scale(1.05);
  }
  100% {
    transform: scale(1);
  }
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
