<template>
  <button
    :type="type"
    :disabled="loading || disabled"
    :class="buttonClasses"
    @click="$emit('click')"
    class="inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed"
  >
    <!-- Loading Spinner -->
    <div v-if="loading" class="mr-2">
      <div class="w-4 h-4 border-2 border-current border-t-transparent rounded-full animate-spin"></div>
    </div>

    <!-- Icon -->
    <i v-if="icon && !loading" :class="icon" class="mr-2"></i>

    <!-- Text -->
    <span>{{ loading ? loadingText : text }}</span>
  </button>
</template>

<script>
export default {
  name: 'LoadingButton',
  props: {
    text: {
      type: String,
      required: true
    },
    loadingText: {
      type: String,
      default: 'Memproses...'
    },
    loading: {
      type: Boolean,
      default: false
    },
    disabled: {
      type: Boolean,
      default: false
    },
    type: {
      type: String,
      default: 'button'
    },
    variant: {
      type: String,
      default: 'primary', // primary, secondary, success, danger, warning
      validator: value => ['primary', 'secondary', 'success', 'danger', 'warning'].includes(value)
    },
    size: {
      type: String,
      default: 'md', // sm, md, lg
      validator: value => ['sm', 'md', 'lg'].includes(value)
    },
    icon: {
      type: String,
      default: ''
    }
  },
  computed: {
    buttonClasses() {
      const baseClasses = 'inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed';

      const variantClasses = {
        primary: 'bg-teal-600 text-white hover:bg-teal-700 focus:ring-teal-500',
        secondary: 'bg-gray-600 text-white hover:bg-gray-700 focus:ring-gray-500',
        success: 'bg-green-600 text-white hover:bg-green-700 focus:ring-green-500',
        danger: 'bg-red-600 text-white hover:bg-red-700 focus:ring-red-500',
        warning: 'bg-yellow-600 text-white hover:bg-yellow-700 focus:ring-yellow-500'
      };

      const sizeClasses = {
        sm: 'px-3 py-1.5 text-xs',
        md: 'px-4 py-2 text-sm',
        lg: 'px-6 py-3 text-base'
      };

      return `${baseClasses} ${variantClasses[this.variant]} ${sizeClasses[this.size]}`;
    }
  }
}
</script>

<style scoped>
.animate-spin {
  animation: spin 1s linear infinite;
}

@keyframes spin {
  from {
    transform: rotate(0deg);
  }
  to {
    transform: rotate(360deg);
  }
}
</style>
