<template>
  <BaseModal
    :show="show"
    :title="`Bukti Pembayaran ${buktiType === 'administrasi' ? 'Administrasi' : 'Daftar Ulang'}`"
    icon="fas fa-eye"
    @close="$emit('close')"
  >
    <!-- Preview Content -->
    <div class="flex flex-col items-center justify-center">
      <img :src="buktiUrl" class="max-w-full max-h-96 mx-auto rounded-lg shadow-lg" />
    </div>

    <!-- Footer Buttons -->
    <template #footer>
      <button @click="$emit('change')" class="px-4 py-2 bg-teal-100 text-teal-700 rounded hover:bg-teal-200 mr-2">Ganti Bukti Pembayaran</button>
      <button @click="$emit('close')" class="px-4 py-2 bg-gray-200 rounded hover:bg-gray-300">Tutup</button>
    </template>
  </BaseModal>
</template>

<script>
import BaseModal from '@/components/common/BaseModal.vue';

export default {
  name: 'PaymentPreviewModal',
  components: {
    BaseModal
  },
  props: {
    show: Boolean,
    buktiType: String,
    buktiUrl: String
  },
  emits: ['close', 'change']
}
</script>
