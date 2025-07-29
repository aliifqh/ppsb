<template>
  <Teleport to="body">
    <div v-if="show" :style="{ zIndex }" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-40">
      <div :class="['bg-white rounded-xl shadow-lg w-full', maxWidth, 'relative flex flex-col max-h-[90vh]']">
        <!-- Header Sticky -->
        <div class="sticky top-0 z-10 bg-white rounded-t-xl shadow-sm flex items-center justify-between px-6 pt-6 pb-2">
          <slot name="header">
            <h3 class="text-lg font-bold text-teal-700 flex items-center">
              <i v-if="icon" :class="[icon, 'text-teal-600 mr-2']"></i>
              <span>{{ title }}</span>
            </h3>
          </slot>
          <button @click="$emit('close')" class="text-gray-400 hover:text-red-500 ml-2">
            <i class="fas fa-times"></i>
          </button>
        </div>
        <!-- Body Scrollable -->
        <div class="overflow-y-auto px-6 py-2 flex-1 min-h-0 flex flex-col">
          <slot />
        </div>
        <!-- Footer Sticky -->
        <div class="sticky bottom-0 z-10 bg-white rounded-b-xl shadow-sm px-6 pb-4 pt-3 flex justify-end gap-2 border-t">
          <slot name="footer">
            <button @click="$emit('close')" class="px-4 py-2 bg-gray-200 rounded hover:bg-gray-300">Tutup</button>
          </slot>
        </div>
      </div>
    </div>
  </Teleport>
</template>
<script>
export default {
  name: 'BaseModal',
  props: {
    show: Boolean,
    title: String,
    icon: String,
    maxWidth: {
      type: String,
      default: 'max-w-lg'
    },
    zIndex: {
      type: [String, Number],
      default: 9999
    }
  },
  emits: ['close']
}
</script>
<style scoped>
/* Tambahan biar modal sticky dan responsif */
</style>
