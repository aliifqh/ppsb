<template>
  <ModernModal :show="show" :title="'Kirim Pesan WhatsApp'" @close="onClose" size="md">
    <div>
      <div class="mb-3">
        <label class="block text-sm font-medium mb-1">Pesan</label>
        <textarea v-model="message" rows="4" class="w-full border rounded p-2 focus:ring focus:ring-emerald-200" placeholder="Tulis pesan untuk orang tua..." />
      </div>
      <div class="mb-3 flex flex-wrap gap-2">
        <span class="text-xs text-gray-500 mr-2">Insert variabel:</span>
        <button v-for="v in variables" :key="v.key" @click.prevent="insertVariable(v.key)" class="px-2 py-1 bg-emerald-100 text-emerald-700 rounded text-xs hover:bg-emerald-200">{{ v.label }}</button>
      </div>
      <div class="mb-4 flex items-center">
        <input type="checkbox" id="saveTemplate" v-model="saveTemplate" class="mr-2" />
        <label for="saveTemplate" class="text-sm">Simpan pesan untuk santri lain</label>
      </div>
    </div>
    <template #footer>
      <div class="flex justify-end gap-2">
        <button @click="onClose" class="px-4 py-2 rounded bg-gray-200 text-gray-700 hover:bg-gray-300">Batal</button>
        <button @click="onSend" class="px-4 py-2 rounded bg-emerald-500 text-white hover:bg-emerald-600">Kirim</button>
      </div>
    </template>
  </ModernModal>
</template>

<script>
import ModernModal from './ModernModal.vue';
export default {
  name: 'SendWaModal',
  components: { ModernModal },
  props: {
    show: { type: Boolean, required: true },
    variablesData: { type: Object, default: () => ({}) },
    defaultMessage: { type: String, default: '' },
  },
  emits: ['close', 'send'],
  data() {
    return {
      message: this.defaultMessage,
      saveTemplate: true,
      variables: [
        { key: 'nama_anak', label: 'Nama Anak' },
        { key: 'nama_ayah', label: 'Nama Ayah' },
        { key: 'nama_ibu', label: 'Nama Ibu' },
        { key: 'nomor_pendaftaran', label: 'Nomor Pendaftaran' },
        { key: 'wa_ayah', label: 'WA Ayah' },
        { key: 'wa_ibu', label: 'WA Ibu' },
        { key: 'magic_login', label: 'Link Login Santri' },
      ],
    };
  },
  watch: {
    defaultMessage(val) {
      this.message = val;
    }
  },
  methods: {
    insertVariable(key) {
      const cursorPos = this.$el.querySelector('textarea').selectionStart;
      const before = this.message.slice(0, cursorPos);
      const after = this.message.slice(cursorPos);
      this.message = before + `{{${key}}}` + after;
      this.$nextTick(() => {
        const textarea = this.$el.querySelector('textarea');
        textarea.focus();
        textarea.selectionEnd = cursorPos + (`{{${key}}}`).length;
      });
    },
    onSend() {
      this.$emit('send', { message: this.message, saveTemplate: this.saveTemplate });
    },
    onClose() {
      this.$emit('close');
    },
  },
};
</script>
