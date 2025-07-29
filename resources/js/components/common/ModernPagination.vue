<template>
  <div v-if="totalPages > 1" class="flex flex-col sm:flex-row justify-center items-center mt-6 space-y-4 sm:space-y-0 sm:space-x-4">
    <nav class="inline-flex rounded-2xl shadow-xl bg-white/70 backdrop-blur-md border border-gray-200 px-3 py-2 space-x-2" aria-label="Pagination">
      <button
        :disabled="currentPage === 1"
        @click="$emit('page-changed', currentPage - 1)"
        class="relative inline-flex items-center px-3 py-2 bg-white/80 border border-gray-200 text-base font-medium text-gray-500 hover:bg-emerald-50 hover:text-emerald-600 rounded-xl shadow transition-all duration-200 focus:ring-2 focus:ring-emerald-300 focus:outline-none disabled:opacity-40 disabled:cursor-not-allowed"
      >
        <i class="fas fa-chevron-left"></i>
      </button>
      <transition-group name="fade" tag="span" class="flex space-x-2">
        <button
          v-for="page in pagesToShow"
          :key="page"
          @click="$emit('page-changed', page)"
          :class="[
            'relative inline-flex items-center px-4 py-2 border text-base font-semibold focus:z-10 transition-all duration-200',
            page === currentPage ? 'bg-emerald-500 text-white shadow-lg scale-105 border-emerald-500' : 'bg-white/80 text-gray-700 border-gray-200 hover:bg-emerald-100 hover:text-emerald-700',
            'rounded-xl',
          ]"
        >
          {{ page }}
        </button>
      </transition-group>
      <button
        :disabled="currentPage === totalPages"
        @click="$emit('page-changed', currentPage + 1)"
        class="relative inline-flex items-center px-3 py-2 bg-white/80 border border-gray-200 text-base font-medium text-gray-500 hover:bg-emerald-50 hover:text-emerald-600 rounded-xl shadow transition-all duration-200 focus:ring-2 focus:ring-emerald-300 focus:outline-none disabled:opacity-40 disabled:cursor-not-allowed"
      >
        <i class="fas fa-chevron-right"></i>
      </button>
    </nav>

    <!-- Per Page Selector -->
    <div class="flex items-center space-x-2 text-sm text-gray-600">
      <span class="font-medium">Show:</span>
      <select
        :value="perPage"
        @change="$emit('per-page-changed', parseInt($event.target.value))"
        class="block w-auto pl-3 pr-8 py-2 text-base border-gray-200 focus:outline-none focus:ring-emerald-300 focus:border-emerald-300 rounded-xl shadow-sm bg-white/80 transition"
      >
        <option v-for="option in perPageOptions" :key="option.value" :value="option.value">
          {{ option.text }}
        </option>
      </select>
    </div>
  </div>
</template>

<script>
export default {
  name: 'ModernPagination',
  props: {
    currentPage: {
      type: Number,
      required: true
    },
    totalPages: {
      type: Number,
      required: true
    },
    maxPages: {
      type: Number,
      default: 5
    },
    perPage: {
      type: Number,
      required: true
    },
    totalItems: {
      type: Number,
      required: true
    }
  },
  computed: {
    perPageOptions() {
      const options = [10, 20, 50, 100];
      const result = options.map(o => ({ value: o, text: o.toString() }));

      if (this.totalItems > 100) {
        result.push({ value: this.totalItems, text: 'All' });
      }
      return result;
    },
    pagesToShow() {
      const pages = [];
      let start = Math.max(1, this.currentPage - Math.floor(this.maxPages / 2));
      let end = start + this.maxPages - 1;
      if (end > this.totalPages) {
        end = this.totalPages;
        start = Math.max(1, end - this.maxPages + 1);
      }
      for (let i = start; i <= end; i++) {
        pages.push(i);
      }
      return pages;
    }
  }
}
</script>

<style scoped>
.fade-enter-active, .fade-leave-active {
  transition: all 0.2s;
}
.fade-enter-from, .fade-leave-to {
  opacity: 0;
  transform: scale(0.95);
}
nav {
  box-shadow: 0 6px 32px 0 rgba(16, 185, 129, 0.08), 0 1.5px 4px 0 rgba(0,0,0,0.04);
}
button, select {
  outline: none;
}
</style>
