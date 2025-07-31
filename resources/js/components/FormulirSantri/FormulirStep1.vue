<template>
  <div v-if="modelValue && currentStep === 1" class="space-y-6">
    <h2 class="text-2xl font-semibold text-teal-700">Data Calon Santri</h2>
    <!-- Unit Selection -->
    <div class="space-y-2">
      <label class="block text-teal-700 font-medium">Pilihan Unit:</label>
      <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <label class="relative">
          <input type="radio" name="unit" value="ppim" v-model="localForm.unit" class="peer sr-only" required />
          <div class="p-4 border-2 rounded-lg cursor-pointer peer-checked:border-teal-600 peer-checked:bg-teal-50 hover:border-teal-400 hover:bg-teal-50/50">
            <span class="block text-center">PPIM (Lulusan SD/MI)</span>
          </div>
        </label>
        <label class="relative">
          <input type="radio" name="unit" value="tks" v-model="localForm.unit" class="peer sr-only" required />
          <div class="p-4 border-2 rounded-lg cursor-pointer peer-checked:border-teal-600 peer-checked:bg-teal-50 hover:border-teal-400 hover:bg-teal-50/50">
            <span class="block text-center">TKS (Lulusan SMP/MTs)</span>
          </div>
        </label>
      </div>
    </div>
    <!-- Nama Lengkap -->
    <div class="space-y-2">
      <label for="nama" class="block text-teal-700 font-medium">Nama Lengkap:</label>
      <input type="text" id="nama" name="nama" v-model="localForm.nama" @input="onNamaInput" class="w-full px-4 py-2 border-2 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500" :class="{'border-red-500': namaError}" placeholder="Masukkan nama lengkap sesuai ijazah" required />
      <p v-if="namaError" class="mt-1 text-sm text-red-600">{{ namaError }}</p>
    </div>
    <!-- NISN -->
    <div class="space-y-2">
      <label for="NISN" class="block text-teal-700 font-medium flex items-center justify-between">
        <span>NISN:</span>
        <a href="https://nisn.data.kemdikbud.go.id/index.php/Cindex/caribynama/" target="_blank" class="text-sm text-teal-600 hover:text-teal-700 underline">Cek NISN</a>
      </label>
      <input type="text" id="NISN" name="NISN" v-model="localForm.nisn" @input="onNisnInput" maxlength="10" class="w-full px-4 py-2 border-2 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500" :class="{'border-red-500': nisnError}" placeholder="Masukkan 10 digit NISN" required />
      <p v-if="nisnError" class="mt-1 text-sm text-red-600">{{ nisnError }}</p>
    </div>
    <!-- Jenis Kelamin -->
    <div class="space-y-2">
      <label for="jenis_kelamin" class="block text-teal-700 font-medium">Jenis Kelamin:</label>
      <select id="jenis_kelamin" name="jenis_kelamin" v-model="localForm.jenis_kelamin" class="w-full px-4 py-2 border-2 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500" required>
        <option value="">Pilih Jenis Kelamin</option>
        <option value="Laki-laki">Laki-laki</option>
        <option value="Perempuan">Perempuan</option>
      </select>
    </div>
    <!-- Tempat Lahir -->
    <div class="space-y-2">
      <label for="tempat_lahir" class="block text-teal-700 font-medium">Tempat Lahir:</label>
      <input type="text" id="tempat_lahir" name="tempat_lahir" v-model="localForm.tempat_lahir" @input="onTempatLahirInput" class="w-full px-4 py-2 border-2 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500" :class="{'border-red-500': tempatLahirError}" placeholder="Masukkan kota/kabupaten tempat lahir" required />
      <p v-if="tempatLahirError" class="mt-1 text-sm text-red-600">{{ tempatLahirError }}</p>
    </div>
    <!-- Tanggal Lahir -->
    <div class="space-y-2">
      <label for="tanggal_lahir" class="block text-teal-700 font-medium">Tanggal Lahir:</label>
      <input type="text" id="tanggal_lahir" name="tanggal_lahir" v-model="localForm.tanggal_lahir" @input="onTanggalLahirInput" class="w-full px-4 py-2 border-2 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500" :class="{'border-red-500': tanggalLahirError}" placeholder="Contoh: 29 Juni 2005" required />
      <p v-if="tanggalLahirError" class="mt-1 text-sm text-red-600">{{ tanggalLahirError }}</p>
    </div>
    <!-- Asal Sekolah -->
    <div class="space-y-2">
      <label for="asal_sekolah" class="block text-teal-700 font-medium">Asal Sekolah:</label>
      <input type="text" id="asal_sekolah" name="asal_sekolah" v-model="localForm.asal_sekolah" @input="onAsalSekolahInput" class="w-full px-4 py-2 border-2 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500" :class="{'border-red-500': asalSekolahError}" placeholder="Masukkan nama sekolah asal" required />
      <p v-if="asalSekolahError" class="mt-1 text-sm text-red-600">{{ asalSekolahError }}</p>
    </div>
    <!-- Data Saudara -->
    <div class="space-y-4">
      <label class="block text-teal-700 font-medium">Data Saudara:</label>
      <div class="bg-teal-50 rounded-lg p-6 border border-teal-100">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <!-- Anak Ke -->
          <div class="space-y-2">
            <label class="block text-teal-700 font-medium">Anak ke:</label>
            <div class="flex items-center gap-3">
              <button type="button" @click="$emit('decrement-number', 'anak_ke')" class="w-10 h-10 rounded-lg bg-teal-100 text-teal-600 hover:bg-teal-200 flex items-center justify-center transition-colors" :class="{'opacity-50 cursor-not-allowed': localForm.anak_ke <= 1}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>
                </svg>
              </button>
              <input type="number" name="anak_ke" v-model="localForm.anak_ke" @input="onSaudaraInput" class="w-20 text-center px-3 py-2 border-2 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500 appearance-none" min="1" required readonly />
              <button type="button" @click="$emit('increment-number', 'anak_ke')" class="w-10 h-10 rounded-lg bg-teal-100 text-teal-600 hover:bg-teal-200 flex items-center justify-center transition-colors" :class="{'opacity-50 cursor-not-allowed': localForm.anak_ke >= localForm.jumlah_saudara}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
              </button>
            </div>
          </div>
          <!-- Jumlah Saudara -->
          <div class="space-y-2">
            <label class="block text-teal-700 font-medium">Jumlah Saudara:</label>
            <div class="flex items-center gap-3">
              <button type="button" @click="$emit('decrement-number', 'jumlah_saudara')" class="w-10 h-10 rounded-lg bg-teal-100 text-teal-600 hover:bg-teal-200 flex items-center justify-center transition-colors" :class="{'opacity-50 cursor-not-allowed': localForm.jumlah_saudara <= localForm.anak_ke || localForm.jumlah_saudara <= 1}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>
                </svg>
              </button>
              <input type="number" name="jumlah_saudara" v-model="localForm.jumlah_saudara" @input="onSaudaraInput" class="w-20 text-center px-3 py-2 border-2 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500 appearance-none" min="1" required readonly />
              <button type="button" @click="$emit('increment-number', 'jumlah_saudara')" class="w-10 h-10 rounded-lg bg-teal-100 text-teal-600 hover:bg-teal-200 flex items-center justify-center transition-colors" :class="{'opacity-50 cursor-not-allowed': localForm.jumlah_saudara >= 20}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
              </button>
            </div>
          </div>
        </div>
        <p v-if="saudaraError" class="mt-4 text-sm text-red-600">{{ saudaraError }}</p>
      </div>
    </div>
    <!-- Navigation Buttons -->
    <div class="flex justify-between mt-8 pt-6 border-t border-gray-200">
      <div></div>
      <button type="button" @click="handleNextStep" class="px-6 py-2.5 bg-gradient-to-r from-teal-600 to-teal-500 text-white rounded-lg hover:from-teal-700 hover:to-teal-600 focus:ring-2 focus:ring-teal-500 focus:ring-offset-2 transition-all duration-300 flex items-center gap-2" :disabled="isLoading">
        <span>Lanjutkan</span>
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
        </svg>
      </button>
    </div>
  </div>
</template>

<script setup>
import { computed, watch, ref } from 'vue';
const props = defineProps({
  modelValue: { type: Object, required: true },
  errors: { type: Object, default: () => ({}) },
  isLoading: { type: Boolean, default: false },
});
const emit = defineEmits(['update:modelValue', 'next-step', 'increment-number', 'decrement-number', 'validate-nisn', 'validate-tanggal-lahir']);
const currentStep = 1;
const localForm = computed({
  get: () => props.modelValue,
  set: (val) => emit('update:modelValue', val),
});
watch(localForm, (val) => emit('update:modelValue', val), { deep: true });

// Validasi & error state
const namaError = ref('');
const nisnError = ref('');
const tempatLahirError = ref('');
const tanggalLahirError = ref('');
const asalSekolahError = ref('');
const saudaraError = ref('');

// Watcher untuk validasi data saudara setiap kali berubah
watch(
  () => [localForm.value.anak_ke, localForm.value.jumlah_saudara],
  ([anakKe, jumlahSaudara]) => {
    let anak = parseInt(anakKe);
    let jumlah = parseInt(jumlahSaudara);
    if (isNaN(anak) || anak < 1) anak = 1;
    if (isNaN(jumlah) || jumlah < 1) jumlah = 1;
    if (jumlah > 20) jumlah = 20;
    if (anak > jumlah) anak = jumlah;
    if (localForm.value.anak_ke !== anak) localForm.value.anak_ke = anak;
    if (localForm.value.jumlah_saudara !== jumlah) localForm.value.jumlah_saudara = jumlah;
    if (anak > jumlah) {
      saudaraError.value = 'Urutan anak tidak boleh melebihi jumlah saudara kandung.';
    } else {
      saudaraError.value = '';
    }
  },
  { immediate: true }
);

function capitalizeWords(str) {
  return str.replace(/\b\w+/g, w => w.charAt(0).toUpperCase() + w.slice(1).toLowerCase());
}

function onNamaInput(e) {
  let val = e.target.value.replace(/[^a-zA-Z\s']/g, '');
  val = val.replace(/\s{2,}/g, ' ');
  localForm.value.nama = capitalizeWords(val);
  // Validasi: wajib diisi
  if (!val.trim()) {
    namaError.value = 'Mohon isi nama lengkap santri dengan benar.';
  } else {
    namaError.value = '';
  }
}

function onNisnInput(e) {
  let val = e.target.value.replace(/\D/g, '');
  localForm.value.nisn = val;
  if (!val) {
    nisnError.value = 'Mohon isi NISN santri.';
  } else if (val.length !== 10) {
    nisnError.value = 'NISN harus terdiri dari 10 digit angka sesuai data resmi.';
  } else {
    nisnError.value = '';
  }
}

function onTempatLahirInput(e) {
  let val = e.target.value.replace(/[^a-zA-Z\s']/g, '');
  val = val.replace(/\s{2,}/g, ' ');
  localForm.value.tempat_lahir = capitalizeWords(val);
  if (!val.trim()) {
    tempatLahirError.value = 'Mohon isi tempat lahir santri dengan benar.';
  } else if (val.trim().length < 3) {
    tempatLahirError.value = 'Tempat lahir minimal 3 karakter.';
  } else {
    tempatLahirError.value = '';
  }
}

function onTanggalLahirInput(e) {
  let val = e.target.value;
  localForm.value.tanggal_lahir = val;
  // Format: DD MMMM YYYY
  const regex = /^(\d{1,2})\s+([A-Za-z]+)\s+(\d{4})$/;
  const match = val.match(regex);
  if (!val.trim()) {
    tanggalLahirError.value = 'Mohon isi tanggal lahir santri sesuai format yang ditentukan.';
  } else if (!match) {
    tanggalLahirError.value = 'Format tanggal lahir tidak sesuai. Contoh: 20 Juni 2010';
  } else {
    // Cek tanggal valid
    const day = parseInt(match[1]);
    const month = match[2].toLowerCase();
    const year = parseInt(match[3]);
    const months = ['januari','februari','maret','april','mei','juni','juli','agustus','september','oktober','november','desember'];
    const monthNum = months.indexOf(month) + 1;
    if (monthNum < 1) {
      tanggalLahirError.value = 'Nama bulan pada tanggal lahir tidak valid.';
      return;
    }
    const date = new Date(year, monthNum - 1, day);
    if (date.getDate() !== day || date.getMonth() + 1 !== monthNum || date.getFullYear() !== year) {
      tanggalLahirError.value = 'Tanggal lahir tidak valid.';
      return;
    }
    // Validasi range umur (contoh: 10-18 tahun)
    const today = new Date();
    const age = today.getFullYear() - year;
    if (age < 10 || age > 18) {
      tanggalLahirError.value = 'Usia santri harus antara 10 hingga 18 tahun.';
      return;
    }
    tanggalLahirError.value = '';
  }
}

function onAsalSekolahInput(e) {
  let val = e.target.value.replace(/[^a-zA-Z\s']/g, '');
  val = val.replace(/\s{2,}/g, ' ');
  localForm.value.asal_sekolah = capitalizeWords(val);
  if (!val.trim()) {
    asalSekolahError.value = 'Mohon isi asal sekolah santri dengan benar.';
  } else {
    asalSekolahError.value = '';
  }
}

function onSaudaraInput() {
  let anakKe = parseInt(localForm.value.anak_ke);
  let jumlahSaudara = parseInt(localForm.value.jumlah_saudara);
  if (isNaN(anakKe) || anakKe < 1) anakKe = 1;
  if (isNaN(jumlahSaudara) || jumlahSaudara < 1) jumlahSaudara = 1;
  if (jumlahSaudara > 20) jumlahSaudara = 20;
  if (anakKe > jumlahSaudara) anakKe = jumlahSaudara;
  localForm.value.anak_ke = anakKe;
  localForm.value.jumlah_saudara = jumlahSaudara;
  if (anakKe > jumlahSaudara) {
    saudaraError.value = 'Urutan anak tidak boleh melebihi jumlah saudara kandung.';
  } else {
    saudaraError.value = '';
  }
}

function handleNextStep() {
  // Cek semua error dan field wajib
  onNamaInput({ target: { value: localForm.value.nama || '' } });
  onNisnInput({ target: { value: localForm.value.nisn || '' } });
  onTempatLahirInput({ target: { value: localForm.value.tempat_lahir || '' } });
  onTanggalLahirInput({ target: { value: localForm.value.tanggal_lahir || '' } });
  onAsalSekolahInput({ target: { value: localForm.value.asal_sekolah || '' } });
  onSaudaraInput();
  // Jika ada error, jangan lanjut
  if (
    namaError.value ||
    nisnError.value ||
    tempatLahirError.value ||
    tanggalLahirError.value ||
    asalSekolahError.value ||
    saudaraError.value
  ) {
    // Optionally, bisa munculin alert swal atau scroll ke error
    window.scrollTo({ top: 0, behavior: 'smooth' });
    return;
  }
  emit('next-step');
}
</script> 