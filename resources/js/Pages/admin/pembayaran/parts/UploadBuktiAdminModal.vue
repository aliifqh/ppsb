<template>
  <ModernModal
    :show="show"
    @close="$emit('close')"
    title="Upload Bukti Admin"
    size="xl"
  >
    <p class="text-gray-500 text-sm mb-6 -mt-2">
      Untuk: <span class="font-semibold text-gray-700">{{ context.item?.santri?.nama }}</span> | Jenis: <span class="font-semibold text-gray-700">{{ context.jenis === 'administrasi' ? 'Administrasi' : 'Daftar Ulang' }}</span>
    </p>

    <form @submit.prevent="submitUpload" class="space-y-6">
      <label
          class="w-full p-8 border-2 border-dashed border-gray-300 rounded-xl hover:border-teal-500 transition-all duration-300 cursor-pointer bg-gray-50 flex flex-col items-center justify-center text-center"
          @dragover.prevent="onDragOver"
          @dragleave.prevent="onDragLeave"
          @drop.prevent="onDrop"
          :class="{'border-teal-500 bg-teal-50 shadow-inner': isDragging}"
      >
          <input type="file" ref="fileInput" accept="image/jpeg,image/png" class="hidden" @change="handleFileChange">

          <div v-if="!filePreview" class="text-center flex flex-col items-center transition-all duration-300">
              <i class="fas fa-upload text-4xl text-teal-500 mb-4"></i>
              <p class="text-gray-700 font-semibold mb-1">Pilih File Bukti</p>
              <p class="text-sm text-gray-500">Drag & drop atau klik untuk memilih</p>
              <p class="text-xs text-gray-400 mt-2">Format: JPG, PNG(Max: 2MB)</p>
          </div>

          <div v-else class="relative w-full flex flex-col items-center transition-all duration-300">
              <img v-if="filePreview.type === 'image'" :src="filePreview.url" class="w-full max-h-40 object-cover rounded-lg border-2 border-teal-200 shadow-md" />
              <div v-if="filePreview.type === 'pdf'" class="w-full h-32 bg-gray-100 rounded-lg flex flex-col items-center justify-center border-2 border-teal-200 shadow-md">
                  <i class="fas fa-file-pdf text-5xl text-red-500"></i>
                  <span class="mt-2 text-sm text-gray-700 font-semibold">{{ filePreview.name }}</span>
              </div>
              <button @click.prevent="removeFile" class="absolute -top-3 -right-3 bg-red-500 text-white w-8 h-8 flex items-center justify-center rounded-full shadow-lg hover:bg-red-600 transition-all transform hover:scale-110">
                  <i class="fas fa-times"></i>
              </button>
          </div>
      </label>

      <div class="flex items-center justify-start">
          <input type="checkbox" id="verify-checkbox" v-model="langsungVerifikasi" class="h-4 w-4 text-teal-600 border-gray-300 rounded focus:ring-teal-500">
          <label for="verify-checkbox" class="ml-2 block text-sm text-gray-900 cursor-pointer">
              Langsung Verifikasi Pembayaran
          </label>
      </div>
    </form>
    <template #footer>
        <div class="flex justify-end space-x-3">
            <button type="button" @click="$emit('close')" class="px-6 py-2.5 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 font-semibold transition-all">Batal</button>
            <LoadingButton
              :text="'Upload'"
              :loading-text="'Uploading...'"
              :loading="uploading"
              :disabled="!fileData"
              :icon="'fas fa-check-circle'"
              variant="success"
              @click="submitUpload"
            />
        </div>
    </template>
  </ModernModal>
</template>

<script>
import ModernModal from '@/components/common/ModernModal.vue';
import LoadingButton from '@/components/common/LoadingButton.vue';

export default {
  name: 'UploadBuktiAdminModal',
  components: { ModernModal, LoadingButton },
  props: {
    show: Boolean,
    context: Object, // { item, jenis }
  },
  data() {
    return {
      filePreview: null,
      fileData: null,
      uploading: false,
      isDragging: false,
      langsungVerifikasi: true,
    }
  },
  methods: {
    onDragOver() { this.isDragging = true; },
    onDragLeave() { this.isDragging = false; },
    onDrop(e) {
      this.isDragging = false;
      const files = e.dataTransfer.files;
      if (files.length > 0) { this.handleFileChange({ target: { files } }); }
    },
    handleFileChange(e) {
      const file = e.target.files[0];
      if (!file) return;

      const allowedTypes = ['image/jpeg', 'image/png'];
      if (!allowedTypes.includes(file.type)) {
        Toast.fire({ icon: 'error', title: 'Format file tidak didukung! Hanya JPG dan PNG.' });
        return;
      }

      if (file.size > 2 * 1024 * 1024) { // 2MB
        Toast.fire({ icon: 'error', title: 'File terlalu besar! Maksimal 2MB' });
        return;
      }
      this.fileData = file;
      if (file.type.startsWith('image/')) {
        const reader = new FileReader();
        reader.onload = (ev) => { this.filePreview = { name: file.name, type: 'image', url: ev.target.result }; };
        reader.readAsDataURL(file);
      } else {
        this.fileData = null;
        this.$refs.fileInput.value = '';
      }
    },
    removeFile() {
      this.filePreview = null;
      this.fileData = null;
      if (this.$refs.fileInput) { this.$refs.fileInput.value = ''; }
    },
    async submitUpload() {
      if (!this.fileData) {
        Toast.fire({ icon: 'error', title: 'Pilih file terlebih dahulu!' });
        return;
      }
      this.uploading = true;
      try {
        const formData = new FormData();
        formData.append('bukti_pembayaran', this.fileData);
        formData.append('jenis', this.context.jenis);
        if (this.langsungVerifikasi) {
            formData.append('langsung_verifikasi', 'true');
        }

        await axios.post(`/admin/pembayaran/${this.context.item.id}/upload-bukti`, formData, {
            headers: { 'Content-Type': 'multipart/form-data' }
        });

        Toast.fire({ icon: 'success', title: 'Bukti berhasil diupload!' });
        this.$emit('uploaded');
        this.$emit('close');
      } catch (error) {
        const errorMessage = error.response?.data?.message || 'Gagal upload bukti!';
        Toast.fire({ icon: 'error', title: errorMessage });
      } finally {
        this.uploading = false;
      }
    },
  },
  watch: {
    show(newVal) {
      if(newVal) {
        this.removeFile();
        this.langsungVerifikasi = true;
      }
    }
  }
}
</script>

<style scoped>
.animate-fadeIn {
  animation: fadeIn 0.3s ease-out;
}
@keyframes fadeIn {
  from {
    opacity: 0;
    transform: scale(0.95);
  }
  to {
    opacity: 1;
    transform: scale(1);
  }
}
</style>
