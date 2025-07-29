<template>
  <div class="mb-6">
    <input
      :value="modelValue"
      @input="$emit('update:modelValue', $event.target.value); $emit('input', $event.target.value)"
      :placeholder="placeholder || 'Cari... (Nama / NISN / Nomor Pendaftaran)'"
      :disabled="loading"
      class="block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm"
    />
  </div>
</template>
<script>
export default {
  name: 'SantriSearchBar',
  props: {
    modelValue: String,
    loading: Boolean,
    placeholder: String
  },
  emits: ['update:modelValue', 'input'],
}
</script>
