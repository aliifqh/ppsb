<template>
  <BaseModal
    :show="show"
    :title="`Upload Bukti Pembayaran ${paymentType === 'administrasi' ? 'Administrasi' : 'Daftar Ulang'}`"
    icon="fas fa-cloud-upload-alt"
    @close="$emit('close')"
  >
    <!-- Form Content -->
    <form @submit.prevent="submitPayment" id="form-upload-bukti" class="space-y-4">
      <!-- File Upload -->
      <div>
        <label class="block text-sm font-medium mb-1">Bukti Pembayaran</label>
        <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-teal-400 transition-colors">
          <input
            type="file"
            ref="fileInput"
            @change="handleFileChange"
            accept="image/*"
            class="hidden"
          >
          <div v-if="!selectedFile" @click="$refs.fileInput.click()" class="cursor-pointer">
            <i class="fas fa-cloud-upload-alt text-4xl text-gray-400 mb-2"></i>
            <p class="text-sm text-gray-600">Klik untuk memilih file</p>
            <p class="text-xs text-gray-500">JPG, PNG (Max 2MB)</p>
          </div>
          <div v-else class="relative">
            <img :src="filePreview" class="max-h-32 mx-auto rounded" />
            <button
              @click="removeFile"
              type="button"
              class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs hover:bg-red-600"
            >
              <i class="fas fa-times"></i>
            </button>
          </div>
        </div>
      </div>
      <!-- Nominal -->
      <div>
        <label class="block text-sm font-medium mb-1">Nominal Transfer</label>
        <input
          v-model="form.nominal"
          type="text"
          class="w-full px-3 py-2 border border-gray-300 rounded focus:ring-2 focus:ring-teal-500 focus:border-teal-500"
          placeholder="Rp 0"
          required
        >
      </div>
      <!-- Tanggal Transfer -->
      <div>
        <label class="block text-sm font-medium mb-1">Tanggal Transfer</label>
        <input
          v-model="form.tanggal_transfer"
          type="date"
          class="w-full px-3 py-2 border border-gray-300 rounded focus:ring-2 focus:ring-teal-500 focus:border-teal-500"
          required
        >
      </div>
      <!-- Keterangan -->
      <div>
        <label class="block text-sm font-medium mb-1">Keterangan (Opsional)</label>
        <textarea
          v-model="form.keterangan"
          rows="3"
          class="w-full px-3 py-2 border border-gray-300 rounded focus:ring-2 focus:ring-teal-500 focus:border-teal-500"
          placeholder="Tambahkan keterangan jika diperlukan..."
        ></textarea>
      </div>
    </form>

    <!-- Footer Buttons -->
    <template #footer>
      <button type="button" @click="$emit('close')" class="px-4 py-2 bg-gray-200 rounded hover:bg-gray-300">Batal</button>
      <button type="submit" form="form-upload-bukti" :disabled="submitting" class="px-4 py-2 bg-teal-600 text-white rounded hover:bg-teal-700 disabled:opacity-50 disabled:cursor-not-allowed">
        <span v-if="submitting">
          <i class="fas fa-spinner fa-spin mr-2"></i>
          Mengupload...
        </span>
        <span v-else>
          <i class="fas fa-upload mr-2"></i>
          Upload Bukti
        </span>
      </button>
    </template>
  </BaseModal>
</template>

<script>
import Swal from 'sweetalert2';
import BaseModal from '@/components/common/BaseModal.vue';

export default {
  name: 'PaymentUploadModal',
  components: {
    BaseModal
  },
  props: {
    show: Boolean,
    paymentType: String,
    santriId: Number
  },
  emits: ['close', 'success'],
  data() {
    return {
      submitting: false,
      selectedFile: null,
      filePreview: null,
      form: {
        nominal: '',
        tanggal_transfer: '',
        keterangan: ''
      }
    }
  },
  watch: {
    show(newVal) {
      if (newVal) {
        this.resetForm();
      }
    }
  },
  methods: {
    resetForm() {
      this.form = {
        nominal: '',
        tanggal_transfer: '',
        keterangan: ''
      };
      this.selectedFile = null;
      this.filePreview = null;
      if (this.$refs.fileInput) {
        this.$refs.fileInput.value = '';
      }
    },

    handleFileChange(event) {
      const file = event.target.files[0];
      if (file) {
        if (file.size > 2 * 1024 * 1024) {
          Swal.fire({
            icon: 'warning',
            title: 'File Terlalu Besar',
            text: 'Ukuran file maksimal 2MB'
          });
          return;
        }

        this.selectedFile = file;
        const reader = new FileReader();
        reader.onload = (e) => {
          this.filePreview = e.target.result;
        };
        reader.readAsDataURL(file);
      }
    },

    removeFile() {
      this.selectedFile = null;
      this.filePreview = null;
      this.$refs.fileInput.value = '';
    },

    async submitPayment() {
      if (!this.selectedFile) {
        Swal.fire({
          icon: 'warning',
          title: 'File Bukti Diperlukan',
          text: 'Pilih file bukti pembayaran terlebih dahulu'
        });
        return;
      }

      try {
        this.submitting = true;

        const formData = new FormData();
        formData.append('santri_id', this.santriId);
        formData.append('bukti_pembayaran', this.selectedFile);
        formData.append('nominal', this.form.nominal);
        formData.append('tanggal_transfer', this.form.tanggal_transfer);
        formData.append('keterangan', this.form.keterangan);

        const endpoint = this.paymentType === 'administrasi'
          ? '/santri/upload-bukti-administrasi'
          : '/santri/upload-bukti-daftar-ulang';

        const response = await axios.post(endpoint, formData, {
          headers: {
            'Content-Type': 'multipart/form-data'
          }
        });

        if (response.data.success) {
          Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: response.data.message
          });
          this.$emit('success');
          this.$emit('close');
        }
      } catch (error) {
        console.error('Error submitting payment:', error);
        Swal.fire({
          icon: 'error',
          title: 'Gagal Upload Bukti',
          text: error.response?.data?.message || 'Terjadi kesalahan saat mengupload bukti pembayaran'
        });
      } finally {
        this.submitting = false;
      }
    }
  }
}
</script>
