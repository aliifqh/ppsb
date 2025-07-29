<template>
  <ModernModal
    :show="showModal"
    @close="$emit('close')"
    :title="isEditing ? 'Edit Data Santri' : 'Tambah Santri Baru'"
    size="2xl"
  >
    <div class="max-h-[70vh] overflow-y-auto -mx-6 px-6">
        <IdentitasForm
          :form="form"
          :isEditing="isEditing"
          @update:form="$emit('update:form', $event)"
          @validity-change="handleValidityChange"
        />
    </div>
    <template #footer>
        <div class="flex justify-end space-x-3">
          <button type="button" @click="$emit('close')"
            class="px-5 py-2.5 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 font-semibold transition-all">
            Batal
          </button>
          <button type="button" @click="$emit('save')"
            :disabled="!isFormValid"
            class="px-5 py-2.5 inline-flex justify-center rounded-lg border border-transparent bg-emerald-600 text-base font-medium text-white hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 disabled:opacity-50 disabled:cursor-not-allowed">
            Simpan
          </button>
        </div>
    </template>
  </ModernModal>
</template>
<script>
import ModernModal from '@/components/common/ModernModal.vue';
import IdentitasForm from './identitas/IdentitasForm.vue'
export default {
  name: 'SantriModal',
  components: { ModernModal, IdentitasForm },
  props: {
    showModal: Boolean,
    isEditing: Boolean,
    form: Object,
    activeTab: String
  },
  data() {
    return {
      isFormValid: true,
    }
  },
  emits: ['close', 'save', 'update:form', 'update:activeTab'],
  methods: {
    handleValidityChange(isValid) {
      this.isFormValid = isValid;
    }
  }
}
</script>
