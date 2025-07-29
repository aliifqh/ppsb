<template>
  <div class="relative group">
    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
      <i class="fas fa-search text-gray-400" v-if="!loading"></i>
      <i class="fas fa-spinner fa-spin text-gray-400" v-else></i>
    </div>
    <input
      ref="searchInput"
      :type="type"
      :placeholder="placeholder"
      :value="modelValue"
      @input="onInput"
      class="block w-full pl-10 pr-16 py-2.5 border border-gray-300 rounded-lg focus:ring-emerald-500 focus:border-emerald-500 text-sm search-input"
    >
    <span class="shortcut-ctrlk" v-if="!isMobile && !modalActive">{{ shortcutText }}</span>
  </div>
</template>

<script>
export default {
  name: 'SearchBar',
  props: {
    modelValue: {
      type: String,
      default: ''
    },
    placeholder: {
      type: String,
      default: 'Cari...'
    },
    loading: {
      type: Boolean,
      default: false
    },
    type: {
      type: String,
      default: 'text'
    },
    debounce: {
      type: Number,
      default: 300
    },
    modalActive: {
      type: Boolean,
      default: false
    }
  },
  data() {
    return {
      debounceTimeout: null,
      showTooltip: false,
      isMobile: false,
      isMac: false
    }
  },
  computed: {
    shortcutText() {
      return this.isMac ? '⌘K' : 'Ctrl K'
    }
  },
  methods: {
    onInput(e) {
      const value = e.target.value;
      this.$emit('update:modelValue', value);
      if (this.debounceTimeout) clearTimeout(this.debounceTimeout);
      this.debounceTimeout = setTimeout(() => {
        this.$emit('input', value);
      }, this.debounce);
    },
    focusInput(e) {
      if (this.isMobile || this.modalActive) return;
      const isMacKey = this.isMac && e.metaKey && e.key === 'k';
      const isWindowsKey = !this.isMac && e.ctrlKey && e.key === 'k';
      if (isMacKey || isWindowsKey) {
        e.preventDefault();
        this.$refs.searchInput.focus();
      }
    },
    checkDevice() {
      this.isMobile = /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);
      this.isMac = /Mac|iPod|iPhone|iPad/.test(navigator.platform);
    }
  },
  mounted() {
    this.checkDevice();
    window.addEventListener('keydown', this.focusInput);
  },
  beforeUnmount() {
    window.removeEventListener('keydown', this.focusInput);
    if (this.debounceTimeout) clearTimeout(this.debounceTimeout);
  }
}
</script>

<style scoped>
input:disabled {
  background: #f3f4f6;
  color: #a1a1aa;
}

.search-input:focus {
  animation: borderBlink 0.5s ease-in-out;
}

@keyframes borderBlink {
  0% { border-color: #10b981; }
  50% { border-color: #059669; }
  100% { border-color: #10b981; }
}

.shortcut-ctrlk {
  position: absolute;
  right: 8px;
  top: 50%;
  transform: translateY(-50%);
  background: #f8fafc;
  border: 1px solid #d1d5db;
  color: #222;
  font-weight: bold;
  font-size: 13px;
  padding: 2px 10px;
  border-radius: 6px;
  box-shadow: none;
  z-index: 10;
  pointer-events: none;
  letter-spacing: 0.5px;
}

.fade-tooltip-enter-active, .fade-tooltip-leave-active {
  transition: opacity 0.18s cubic-bezier(0.4,0,0.2,1);
}
.fade-tooltip-enter-from, .fade-tooltip-leave-to {
  opacity: 0;
}
.fade-tooltip-enter-to, .fade-tooltip-leave-from {
  opacity: 1;
}

.tooltip-clean {
  position: absolute;
  left: 50%;
  bottom: 100%;
  transform: translateX(-50%) translateY(-8px);
  background: #222;
  color: #fff;
  font-size: 11px;
  padding: 5px 14px;
  border-radius: 5px;
  opacity: 0.97;
  pointer-events: none;
  z-index: 20;
  box-shadow: 0 4px 16px rgba(0,0,0,0.10);
  font-weight: 400;
  white-space: nowrap;
  display: flex;
  align-items: center;
}

.tooltip-arrow {
  position: absolute;
  left: 50%;
  top: 100%;
  transform: translateX(-50%);
  width: 14px;
  height: 7px;
  overflow: hidden;
  pointer-events: none;
}
.tooltip-arrow::after {
  content: '';
  display: block;
  margin: auto;
  width: 10px;
  height: 10px;
  background: #222;
  transform: translateY(-7px) rotate(45deg);
  box-shadow: 0 2px 8px rgba(0,0,0,0.08);
}
</style>
