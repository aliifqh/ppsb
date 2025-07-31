<template>
  <div class="space-y-6">
    <h3 class="text-lg font-semibold text-gray-800 border-b pb-2">Dokumen Wajib</h3>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">
      <div v-for="(doc, index) in requiredDocuments" :key="doc.key">
        <DokumenInput
          :label="doc.label"
          :file-url="getFileUrl(doc.key)"
          @file-change="handleFileChange(doc.key, $event)"
          @preview="openPreview(index)"
        />
      </div>
    </div>

    <DokumenPreviewModal
        :show="showPreviewModal"
        :items="previewItems"
        :start-index="previewStartIndex"
        @close="showPreviewModal = false"
    />
  </div>
</template>

<script>
import { ref, watch, computed } from 'vue';
import DokumenInput from './DokumenInput.vue';
import DokumenPreviewModal from '../../../../../components/common/DokumenPreviewModal.vue';

export default {
  name: 'DokumenSection',
  components: { DokumenInput, DokumenPreviewModal },
  props: {
    dokumen: Object
  },
  emits: ['update:dokumen'],
  setup(props, { emit }) {
    const requiredDocuments = ref([
      { key: 'pasfoto', label: 'Pas Foto 3x4' },
      { key: 'akta_lahir', label: 'Akta Kelahiran' },
      { key: 'kartu_keluarga', label: 'Kartu Keluarga' },
      { key: 'ijazah', label: 'Ijazah Terakhir' },
    ]);

    const newFiles = ref({});
    const filePreviews = ref({});

    const showPreviewModal = ref(false);
    const previewStartIndex = ref(0);

    const previewItems = computed(() => {
        return requiredDocuments.value
            .map(doc => ({
                label: doc.label,
                url: getFileUrl(doc.key)
            }))
            .filter(item => item.url);
    });

    const getFileUrl = (key) => {
      // Prioritaskan preview untuk file yang baru dipilih
      if (filePreviews.value[key]) {
        return filePreviews.value[key];
      }

      // Jika tidak ada preview, gunakan path file yang sudah ada dari server
      const existingFile = props.dokumen?.[key];
      if (typeof existingFile === 'string' && existingFile) {
        return existingFile.startsWith('http') ? existingFile : `/storage/${existingFile}`;
      }

      return null;
    };

    const handleFileChange = (key, file) => {
      const currentNewFiles = { ...newFiles.value };
      const currentPreviews = { ...filePreviews.value };

      // Hapus preview lama jika ada untuk mencegah memory leak
      if (currentPreviews[key] && currentPreviews[key].startsWith('blob:')) {
        URL.revokeObjectURL(currentPreviews[key]);
      }

      if (file) {
        currentNewFiles[key] = file;
        // Buat URL preview baru untuk file gambar
        if (file.type && file.type.startsWith('image/')) {
          currentPreviews[key] = URL.createObjectURL(file);
        } else {
          // Untuk file non-gambar seperti PDF, kita bisa gunakan path dari server jika ada, atau null
          delete currentPreviews[key];
        }
      } else {
        delete currentNewFiles[key];
        delete currentPreviews[key];
      }

      newFiles.value = currentNewFiles;
      filePreviews.value = currentPreviews;
      emit('update:dokumen', newFiles.value);
    };

    const openPreview = (index) => {
        const url = getFileUrl(requiredDocuments.value[index].key);
        if (url) {
            const actualIndex = previewItems.value.findIndex(item => item.url === url);
            if (actualIndex !== -1) {
                previewStartIndex.value = actualIndex;
                showPreviewModal.value = true;
            }
        }
    };

    watch(() => props.dokumen, (newDokumen) => {
      // Saat data dokumen dari parent berubah, reset state lokal
      // dan buat initial preview dari data yang ada
      Object.values(filePreviews.value).forEach(url => {
        if (url.startsWith('blob:')) URL.revokeObjectURL(url);
      });
      filePreviews.value = {};
      newFiles.value = {};

      if (newDokumen) {
        for (const key in newDokumen) {
            const path = newDokumen[key];
            if (typeof path === 'string' && path) {
                filePreviews.value[key] = path.startsWith('http') ? path : `/storage/${path}`;
            }
        }
      }
    }, { deep: true, immediate: true });

    return { requiredDocuments, handleFileChange, getFileUrl, showPreviewModal, previewStartIndex, previewItems, openPreview };
  }
}
</script>
