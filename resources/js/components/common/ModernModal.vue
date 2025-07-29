<template>
  <div v-if="show" class="fixed inset-0 z-50 flex items-center justify-center p-4">
    <div class="absolute inset-0 bg-black bg-opacity-40 backdrop-blur-sm transition-all duration-300" @click="close"></div>
    <div class="relative bg-white rounded-xl shadow-2xl w-full animate-fadeIn" :class="sizeClass">
      <!-- Header -->
      <div v-if="title" class="flex justify-between items-center border-b px-6 py-4">
        <h2 class="text-xl font-semibold text-gray-800">{{ title }}</h2>
        <button class="text-gray-400 hover:text-red-500 text-2xl" @click="close">&times;</button>
      </div>
      <!-- Body -->
      <div class="p-6">
        <slot />
      </div>
      <!-- Footer -->
      <div v-if="$slots.footer" class="border-t bg-gray-50 px-6 py-4 rounded-b-xl">
        <slot name="footer" />
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'ModernModal',
  props: {
    show: {
      type: Boolean,
      required: true
    },
    title: {
      type: String,
      default: ''
    },
    size: {
      type: String,
      default: '2xl' // sm, md, lg, xl, 2xl
    }
  },
  emits: ['close'],
  computed: {
    sizeClass() {
      const sizes = {
        sm: 'max-w-sm',
        md: 'max-w-md',
        lg: 'max-w-lg',
        xl: 'max-w-xl',
        '2xl': 'max-w-2xl',
      };
      return sizes[this.size] || sizes['2xl'];
    }
  },
  methods: {
    close() {
      this.$emit('close');
    }
  }
}
</script>

<style scoped>
@keyframes fadeIn {
  from { opacity: 0; transform: scale(0.95) translateY(10px); }
  to { opacity: 1; transform: scale(1) translateY(0); }
}
.animate-fadeIn {
  animation: fadeIn 0.2s ease-out;
}
</style>
