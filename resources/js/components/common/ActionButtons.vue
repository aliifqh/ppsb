<template>
  <div class="flex items-center space-x-1" v-if="actions.length <= 3">
    <div v-for="(action, idx) in actions" :key="idx" class="relative group"
        @mouseenter="handleMouseEnter($event, action, idx)"
        @mouseleave="handleMouseLeave">
      <button
        :class="[
          'btn-table-action',
          action.color || (variant === 'classic' ? '' : ''),
          { 'opacity-50 cursor-not-allowed': action.disabled },
          { 'px-2': !action.label || (isMobile && variant === 'icon-only') }
        ]"
        :disabled="action.disabled"
        @click="$emit('action', action.key, $event)"
        :title="action.label"
      >
        <i :class="[action.icon]" v-if="action.icon"></i>
        <span v-if="action.label && !isMobile && variant !== 'icon-only'" class="ml-1.5">{{ action.label }}</span>
      </button>
      <Teleport to="body">
        <transition name="fade-tooltip">
          <div v-if="showTooltip === idx && action.tooltip" class="tooltip-clean" :style="tooltipStyle">
            {{ action.tooltip }}
            <span class="tooltip-arrow"></span>
          </div>
        </transition>
      </Teleport>
    </div>
  </div>
  <div v-else class="relative">
    <button @click="showMenu = !showMenu" class="btn-table-action bg-gray-200 text-gray-700 hover:bg-gray-300" :disabled="loading">
      <i class="fas fa-spinner fa-spin" v-if="loading"></i>
      <i class="fas fa-ellipsis-h" v-else></i>
    </button>
    <div v-if="showMenu" class="absolute z-20 right-0 mt-2 w-40 bg-white border border-gray-200 rounded-lg shadow-lg py-1">
      <div v-for="(action, idx) in actions" :key="idx" class="relative group"
          @mouseenter="handleMouseEnter($event, action, idx)"
          @mouseleave="handleMouseLeave">
        <button
          :class="['w-full text-left px-4 py-2 text-xs flex items-center gap-2 hover:bg-gray-100 btn-table-action', action.color, { 'opacity-50 cursor-not-allowed': action.disabled } ]"
          :disabled="action.disabled"
          @click="$emit('action', action.key, $event); showMenu = false"
        >
          <i :class="action.icon" v-if="action.icon"></i>
          {{ action.label }}
        </button>
        <Teleport to="body">
            <transition name="fade-tooltip">
            <div v-if="showTooltip === idx && action.tooltip" class="tooltip-clean" :style="tooltipStyle">
                {{ action.tooltip }}
                <span class="tooltip-arrow"></span>
            </div>
            </transition>
        </Teleport>
      </div>
    </div>
    <div v-if="showMenu" class="fixed inset-0 z-10" @click="showMenu = false"></div>
  </div>
</template>

<script>
export default {
  name: 'ActionButtons',
  props: {
    actions: {
      type: Array,
      required: true
    },
    loading: {
      type: Boolean,
      default: false
    },
    variant: {
      type: String,
      default: '' // bisa diisi 'icon-only' atau 'classic' jika mau style lain
    }
  },
  data() {
    return {
      showMenu: false,
      showTooltip: null,
      isMobile: false,
      tooltipStyle: {},
    }
  },
  methods: {
    handleMouseEnter(event, action, idx) {
      if (action.tooltip) {
        const rect = event.currentTarget.getBoundingClientRect();
        this.tooltipStyle = {
          left: `${rect.left + rect.width / 2}px`,
          top: `${rect.top}px`,
        };
        this.showTooltip = idx;
      }
    },
    handleMouseLeave() {
      this.showTooltip = null;
    },
    checkDevice() {
      this.isMobile = /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);
    }
  },
  mounted() {
    this.checkDevice();
    window.addEventListener('resize', this.checkDevice);
  },
  beforeUnmount() {
    window.removeEventListener('resize', this.checkDevice);
  }
}
</script>

<style scoped>
.btn-table-action {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  padding: 0.375rem 0.5rem;
  border-radius: 0.375rem;
  font-size: 0.875rem;
  font-weight: 600;
  transition: all 0.15s cubic-bezier(0.4,0,0.2,1);
  position: relative;
  overflow: hidden;
  cursor: pointer;
  border: none;
  outline: none;
  box-shadow: none;
}

@media (max-width: 640px) {
  .btn-table-action {
    padding: 0.375rem;
  }
  .btn-table-action i {
    margin-right: 0 !important;
  }
}

.btn-table-action:hover:not(:disabled) {
  transform: translateY(-2px) scale(1.06);
  box-shadow: 0 4px 12px rgba(16,185,129,0.10);
  z-index: 2;
}
button:disabled {
  pointer-events: none;
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
  position: fixed;
  transform: translateX(-50%) translateY(-100%) translateY(-8px);
  background: #222;
  color: #fff;
  font-size: 11px;
  padding: 5px 14px;
  border-radius: 5px;
  opacity: 0.97;
  pointer-events: none;
  z-index: 9999;
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
