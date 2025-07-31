<template>
  <div v-if="modelValue && currentStep === 3" class="space-y-6">
    <h2 class="text-2xl font-semibold text-teal-700">Lampiran</h2>
    <!-- Required Documents -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
      <!-- Pas Foto -->
      <div class="bg-white p-6 rounded-lg border-2 border-teal-100">
        <div class="flex items-start justify-between mb-4">
          <div>
            <h3 class="text-lg font-medium text-teal-700">Pas Foto</h3>
            <p class="text-sm text-gray-600">Format: JPG/PNG, Max: 3MB</p>
          </div>
          <div class="text-red-500 text-sm">*Wajib</div>
        </div>
        <div class="relative">
          <template v-if="!filePreview.pasfoto">
            <label class="block w-full p-4 border-2 border-dashed border-teal-300 rounded-lg hover:border-teal-500 transition-colors cursor-pointer bg-teal-50">
              <input type="file" name="pasfoto" accept="image/*" class="hidden" @change="e => $emit('handle-file-upload', e, 'pasfoto')" />
              <div class="text-center">
                <svg class="w-8 h-8 mx-auto mb-2 text-teal-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                <span class="text-teal-600">Unggah Pas Foto</span>
              </div>
            </label>
          </template>
          <template v-else>
            <div class="relative">
              <img v-if="filePreview.pasfoto.type === 'image'" :src="filePreview.pasfoto.url" class="w-full h-48 object-cover rounded-lg" />
              <button @click.prevent="$emit('remove-file', 'pasfoto')" class="absolute top-2 right-2 bg-red-500 text-white p-1 rounded-full hover:bg-red-600 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
              </button>
            </div>
          </template>
        </div>
      </div>
      <!-- Akta Kelahiran -->
      <div class="bg-white p-6 rounded-lg border-2 border-teal-100">
        <div class="flex items-start justify-between mb-4">
          <div>
            <h3 class="text-lg font-medium text-teal-700">Akta Kelahiran</h3>
            <p class="text-sm text-gray-600">Format: JPG/PNG/PDF, Max: 3MB</p>
          </div>
          <div class="text-red-500 text-sm">*Wajib</div>
        </div>
        <div class="relative">
          <template v-if="!filePreview.akta_lahir">
            <label class="block w-full p-4 border-2 border-dashed border-teal-300 rounded-lg hover:border-teal-500 transition-colors cursor-pointer bg-teal-50">
              <input type="file" name="akta_lahir" accept="image/*,.pdf" class="hidden" @change="e => $emit('handle-file-upload', e, 'akta_lahir')" />
              <div class="text-center">
                <svg class="w-8 h-8 mx-auto mb-2 text-teal-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                <span class="text-teal-600">Unggah Akta Kelahiran</span>
              </div>
            </label>
          </template>
          <template v-else>
            <div class="relative">
              <img v-if="filePreview.akta_lahir.type === 'image'" :src="filePreview.akta_lahir.url" class="w-full h-48 object-cover rounded-lg" />
              <div v-if="filePreview.akta_lahir.type === 'pdf'" class="w-full h-48 bg-gray-100 rounded-lg flex items-center justify-center">
                <div class="text-center">
                  <svg class="w-12 h-12 mx-auto mb-2 text-teal-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                  </svg>
                  <span class="text-sm text-gray-600">{{ filePreview.akta_lahir.name }}</span>
                </div>
              </div>
              <button @click.prevent="$emit('remove-file', 'akta_lahir')" class="absolute top-2 right-2 bg-red-500 text-white p-1 rounded-full hover:bg-red-600 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
              </button>
            </div>
          </template>
        </div>
      </div>
      <!-- Kartu Keluarga -->
      <div class="bg-white p-6 rounded-lg border-2 border-teal-100">
        <div class="flex items-start justify-between mb-4">
          <div>
            <h3 class="text-lg font-medium text-teal-700">Kartu Keluarga</h3>
            <p class="text-sm text-gray-600">Format: JPG/PNG/PDF, Max: 3MB</p>
          </div>
          <div class="text-red-500 text-sm">*Wajib</div>
        </div>
        <div class="relative">
          <template v-if="!filePreview.kartu_keluarga">
            <label class="block w-full p-4 border-2 border-dashed border-teal-300 rounded-lg hover:border-teal-500 transition-colors cursor-pointer bg-teal-50">
              <input type="file" name="kartu_keluarga" accept="image/*,.pdf" class="hidden" @change="e => $emit('handle-file-upload', e, 'kartu_keluarga')" />
              <div class="text-center">
                <svg class="w-8 h-8 mx-auto mb-2 text-teal-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                <span class="text-teal-600">Unggah Kartu Keluarga</span>
              </div>
            </label>
          </template>
          <template v-else>
            <div class="relative">
              <img v-if="filePreview.kartu_keluarga.type === 'image'" :src="filePreview.kartu_keluarga.url" class="w-full h-48 object-cover rounded-lg" />
              <div v-if="filePreview.kartu_keluarga.type === 'pdf'" class="w-full h-48 bg-gray-100 rounded-lg flex items-center justify-center">
                <div class="text-center">
                  <svg class="w-12 h-12 mx-auto mb-2 text-teal-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                  </svg>
                  <span class="text-sm text-gray-600">{{ filePreview.kartu_keluarga.name }}</span>
                </div>
              </div>
              <button @click.prevent="$emit('remove-file', 'kartu_keluarga')" class="absolute top-2 right-2 bg-red-500 text-white p-1 rounded-full hover:bg-red-600 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
              </button>
            </div>
          </template>
        </div>
      </div>
      <!-- Ijazah/Raport -->
      <div class="bg-white p-6 rounded-lg border-2 border-teal-100">
        <div class="flex items-start justify-between mb-4">
          <div>
            <h3 class="text-lg font-medium text-teal-700">Ijazah/Raport SMP/MTs</h3>
            <p class="text-sm text-gray-600">Format: JPG/PNG/PDF, Max: 3MB</p>
          </div>
          <div class="text-red-500 text-sm">*Wajib</div>
        </div>
        <div class="relative">
          <template v-if="!filePreview.ijazah">
            <label class="block w-full p-4 border-2 border-dashed border-teal-300 rounded-lg hover:border-teal-500 transition-colors cursor-pointer bg-teal-50">
              <input type="file" name="ijazah" accept="image/*,.pdf" class="hidden" @change="e => $emit('handle-file-upload', e, 'ijazah')" />
              <div class="text-center">
                <svg class="w-8 h-8 mx-auto mb-2 text-teal-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                <span class="text-teal-600">Unggah Ijazah/Raport</span>
              </div>
            </label>
          </template>
          <template v-else>
            <div class="relative">
              <img v-if="filePreview.ijazah.type === 'image'" :src="filePreview.ijazah.url" class="w-full h-48 object-cover rounded-lg" />
              <div v-if="filePreview.ijazah.type === 'pdf'" class="w-full h-48 bg-gray-100 rounded-lg flex items-center justify-center">
                <div class="text-center">
                  <svg class="w-12 h-12 mx-auto mb-2 text-teal-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                  </svg>
                  <span class="text-sm text-gray-600">{{ filePreview.ijazah.name }}</span>
                </div>
              </div>
              <button @click.prevent="$emit('remove-file', 'ijazah')" class="absolute top-2 right-2 bg-red-500 text-white p-1 rounded-full hover:bg-red-600 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
              </button>
            </div>
          </template>
        </div>
      </div>
    </div>
    <!-- Dokumen Pendukung (Optional) -->
    <div class="bg-white p-6 rounded-lg border-2 border-teal-100 mb-8">
      <div class="flex items-start justify-between mb-4">
        <div>
          <h3 class="text-lg font-medium text-teal-700">Dokumen Pendukung</h3>
          <p class="text-sm text-gray-600">Format: JPG/PNG/PDF, Max: 3MB per file (Maksimal 5 file)</p>
        </div>
        <div class="text-gray-500 text-sm">Opsional</div>
      </div>
      <div class="relative">
        <label class="block w-full p-4 border-2 border-dashed border-teal-300 rounded-lg hover:border-teal-500 transition-colors cursor-pointer bg-teal-50">
          <input type="file" multiple name="dokumen_pendukung" accept="image/*,.pdf" class="hidden" @change="e => $emit('handle-dokumen-pendukung', e)" />
          <div class="text-center">
            <svg class="w-8 h-8 mx-auto mb-2 text-teal-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
            </svg>
            <span class="text-teal-600">Unggah Dokumen Pendukung</span>
          </div>
        </label>
        <div v-if="filePreview.dokumen_pendukung && filePreview.dokumen_pendukung.length" class="mt-4 space-y-2">
          <div v-for="(file, idx) in filePreview.dokumen_pendukung" :key="idx" class="flex items-center gap-4 bg-gray-50 p-2 rounded-lg border border-gray-200">
            <span class="flex-1 truncate">{{ file.name }}</span>
            <button @click.prevent="$emit('remove-dokumen-pendukung', idx)" class="bg-red-500 text-white p-1 rounded-full hover:bg-red-600 transition-colors">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
              </svg>
            </button>
          </div>
        </div>
      </div>
    </div>
    <!-- Navigation Buttons -->
    <div class="flex justify-between mt-8 pt-6 border-t border-gray-200">
      <button type="button" @click="$emit('prev-step')" class="px-6 py-2.5 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 focus:ring-2 focus:ring-teal-500 focus:ring-offset-2 transition-all duration-300 flex items-center gap-2">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
        </svg>
        <span>Kembali</span>
      </button>
      <!-- Ganti tombol lanjutkan -->
      <button type="button" @click="handleNextStep" class="px-6 py-2.5 bg-gradient-to-r from-teal-600 to-teal-500 text-white rounded-lg hover:from-teal-700 hover:to-teal-600 focus:ring-2 focus:ring-teal-500 focus:ring-offset-2 transition-all duration-300 flex items-center gap-2">
        <span>Lanjutkan</span>
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
        </svg>
      </button>
    </div>
    <!-- Tampilkan error di bawah masing-masing dokumen -->
    <p v-if="pasfotoError" class="mt-1 text-sm text-red-600">{{ pasfotoError }}</p>
    <p v-if="aktaError" class="mt-1 text-sm text-red-600">{{ aktaError }}</p>
    <p v-if="kkError" class="mt-1 text-sm text-red-600">{{ kkError }}</p>
    <p v-if="ijazahError" class="mt-1 text-sm text-red-600">{{ ijazahError }}</p>
    <p v-if="dokumenPendukungError" class="mt-1 text-sm text-red-600">{{ dokumenPendukungError }}</p>
  </div>
</template>

<script setup>
// Note: Integrasi backend, file harus diupload ke storage/app/public/dokumen-santri
import { computed, watch, ref } from 'vue';
const props = defineProps({
  modelValue: { type: Object, required: true },
  filePreview: { type: Object, required: true },
  errors: { type: Object, default: () => ({}) },
});
const emit = defineEmits([
  'update:modelValue',
  'next-step',
  'prev-step',
  'handle-file-upload',
  'remove-file',
  'handle-dokumen-pendukung',
  'remove-dokumen-pendukung',
]);
const currentStep = 3;
const localForm = computed({
  get: () => props.modelValue,
  set: (val) => emit('update:modelValue', val),
});
watch(localForm, (val) => emit('update:modelValue', val), { deep: true });

// Error state
const pasfotoError = ref('');
const aktaError = ref('');
const kkError = ref('');
const ijazahError = ref('');
const dokumenPendukungError = ref('');

function validateFiles() {
  // Pas Foto
  if (!props.filePreview.pasfoto || !props.filePreview.pasfoto.file) {
    pasfotoError.value = 'Mohon unggah pas foto santri.';
  } else {
    pasfotoError.value = '';
  }
  // Akta Kelahiran
  if (!props.filePreview.akta_lahir || !props.filePreview.akta_lahir.file) {
    aktaError.value = 'Mohon unggah akta kelahiran santri.';
  } else {
    aktaError.value = '';
  }
  // Kartu Keluarga
  if (!props.filePreview.kartu_keluarga || !props.filePreview.kartu_keluarga.file) {
    kkError.value = 'Mohon unggah kartu keluarga santri.';
  } else {
    kkError.value = '';
  }
  // Ijazah/Raport
  if (!props.filePreview.ijazah || !props.filePreview.ijazah.file) {
    ijazahError.value = 'Mohon unggah ijazah atau rapor santri.';
  } else {
    ijazahError.value = '';
  }
  // Dokumen Pendukung (opsional, maksimal 5 file)
  if (props.filePreview.dokumen_pendukung && props.filePreview.dokumen_pendukung.length > 5) {
    dokumenPendukungError.value = 'Maksimal 5 dokumen pendukung yang dapat diunggah.';
  } else {
    dokumenPendukungError.value = '';
  }
}
function handleNextStep() {
  validateFiles();
  if (
    pasfotoError.value ||
    aktaError.value ||
    kkError.value ||
    ijazahError.value ||
    dokumenPendukungError.value
  ) {
    window.scrollTo({ top: 0, behavior: 'smooth' });
    return;
  }
  emit('next-step');
}
</script> 