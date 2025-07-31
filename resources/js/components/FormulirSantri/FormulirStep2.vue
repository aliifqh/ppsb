<template>
  <div v-if="modelValue && currentStep === 2" class="space-y-6">
    <h2 class="text-2xl font-semibold text-teal-700">Data Orang Tua</h2>
    <!-- Data Ayah -->
    <div class="mb-8">
      <h3 class="text-lg font-medium text-teal-600 mb-4">Data Ayah</h3>
      <!-- Nama Ayah -->
      <div class="mb-6">
        <label for="nama_ayah" class="block text-teal-700 font-medium mb-2">Nama Ayah:</label>
        <input type="text" id="nama_ayah" name="nama_ayah" v-model="localForm.nama_ayah" @input="onNamaAyahInput" class="w-full px-4 py-2 border-2 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-colors duration-300" required />
      </div>
      <!-- Pekerjaan Ayah -->
      <div class="mb-6">
        <label for="pekerjaan_ayah" class="block text-teal-700 font-medium mb-2">Pekerjaan Ayah:</label>
        <select id="pekerjaan_ayah" name="pekerjaan_ayah" v-model="localForm.pekerjaan_ayah" @change="onPekerjaanAyahInput" class="w-full px-4 py-2 border-2 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-colors duration-300" required>
          <option value="">Pilih Pekerjaan</option>
          <option value="PNS / ASN">PNS / ASN</option>
          <option value="TNI / Polri">TNI / Polri</option>
          <option value="Karyawan Swasta">Karyawan Swasta</option>
          <option value="Petani / Pekebun">Petani / Pekebun</option>
          <option value="Pedagang / Wirausaha">Pedagang / Wirausaha</option>
          <option value="Buruh / Karyawan Harian">Buruh / Karyawan Harian</option>
          <option value="Sopir / Ojek / Pengemudi">Sopir / Ojek / Pengemudi</option>
          <option value="Pensiunan">Pensiunan</option>
          <option value="Tidak Bekerja">Tidak Bekerja</option>
          <option value="Lainnya">Lainnya</option>
        </select>
        <div v-if="localForm.pekerjaan_ayah === 'Lainnya'" class="mt-3">
          <input type="text" id="pekerjaan_ayah_lainnya" name="pekerjaan_ayah_lainnya" v-model="localForm.pekerjaan_ayah_lainnya" @input="emitUpdate('pekerjaan_ayah_lainnya', $event.target.value)" class="w-full px-4 py-2 border-2 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-colors duration-300" placeholder="Sebutkan pekerjaan ayah" :required="localForm.pekerjaan_ayah === 'Lainnya'" />
        </div>
      </div>
      <!-- Pendidikan Ayah -->
      <div class="mb-6">
        <label for="pendidikan_ayah" class="block text-teal-700 font-medium mb-2">Pendidikan Ayah:</label>
        <select id="pendidikan_ayah" name="pendidikan_ayah" v-model="localForm.pendidikan_ayah" @change="onPendidikanAyahInput" class="w-full px-4 py-2 border-2 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-colors duration-300" required>
          <option value="">Pilih Pendidikan</option>
          <option value="SD">SD</option>
          <option value="SLTP">SLTP</option>
          <option value="SLTA">SLTA</option>
          <option value="Diploma">Diploma</option>
          <option value="S1">S1</option>
          <option value="S2">S2</option>
          <option value="S3">S3</option>
        </select>
      </div>
      <!-- WhatsApp Ayah -->
      <div class="space-y-2">
        <label for="wa_ayah" class="block text-teal-700 font-medium">WhatsApp Ayah:</label>
        <div class="relative">
          <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
            </svg>
          </div>
          <input type="text" id="wa_ayah" name="wa_ayah" v-model="localForm.wa_ayah" @input="onWaAyahInput" class="w-full pl-10 pr-4 py-2 border-2 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-colors duration-300" :class="{'border-red-500': waAyahError}" placeholder="62812345678" required />
          <div v-if="waAyahOperator" class="absolute right-3 top-1/2 -translate-y-1/2">
            <span class="px-2 py-1 text-xs font-medium rounded-full" :class="operatorClass(waAyahOperator)">{{ waAyahOperator }}</span>
          </div>
        </div>
        <p v-if="waAyahError" class="mt-1 text-sm text-red-600">{{ waAyahError }}</p>
      </div>
    </div>
    <!-- Data Ibu -->
    <div class="mb-8">
      <h3 class="text-lg font-medium text-teal-600 mb-4">Data Ibu</h3>
      <!-- Nama Ibu -->
      <div class="mb-6">
        <label for="nama_ibu" class="block text-teal-700 font-medium mb-2">Nama Ibu:</label>
        <input type="text" id="nama_ibu" name="nama_ibu" v-model="localForm.nama_ibu" @input="onNamaIbuInput" class="w-full px-4 py-2 border-2 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-colors duration-300" required />
      </div>
      <!-- Pekerjaan Ibu -->
      <div class="mb-6">
        <label for="pekerjaan_ibu" class="block text-teal-700 font-medium mb-2">Pekerjaan Ibu:</label>
        <select id="pekerjaan_ibu" name="pekerjaan_ibu" v-model="localForm.pekerjaan_ibu" @change="onPekerjaanIbuInput" class="w-full px-4 py-2 border-2 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-colors duration-300" required>
          <option value="">Pilih Pekerjaan</option>
          <option value="PNS / ASN">PNS / ASN</option>
          <option value="TNI / Polri">TNI / Polri</option>
          <option value="Karyawan Swasta">Karyawan Swasta</option>
          <option value="Petani / Pekebun">Petani / Pekebun</option>
          <option value="Pedagang / Wirausaha">Pedagang / Wirausaha</option>
          <option value="Buruh / Karyawan Harian">Buruh / Karyawan Harian</option>
          <option value="Ibu Rumah Tangga">Ibu Rumah Tangga</option>
          <option value="Pensiunan">Pensiunan</option>
          <option value="Tidak Bekerja">Tidak Bekerja</option>
          <option value="Lainnya">Lainnya</option>
        </select>
        <div v-if="localForm.pekerjaan_ibu === 'Lainnya'" class="mt-3">
          <input type="text" id="pekerjaan_ibu_lainnya" name="pekerjaan_ibu_lainnya" v-model="localForm.pekerjaan_ibu_lainnya" @input="emitUpdate('pekerjaan_ibu_lainnya', $event.target.value)" class="w-full px-4 py-2 border-2 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-colors duration-300" placeholder="Sebutkan pekerjaan ibu" :required="localForm.pekerjaan_ibu === 'Lainnya'" />
        </div>
      </div>
      <!-- Pendidikan Ibu -->
      <div class="mb-6">
        <label for="pendidikan_ibu" class="block text-teal-700 font-medium mb-2">Pendidikan Ibu:</label>
        <select id="pendidikan_ibu" name="pendidikan_ibu" v-model="localForm.pendidikan_ibu" @change="onPendidikanIbuInput" class="w-full px-4 py-2 border-2 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-colors duration-300" required>
          <option value="">Pilih Pendidikan</option>
          <option value="SD">SD</option>
          <option value="SLTP">SLTP</option>
          <option value="SLTA">SLTA</option>
          <option value="Diploma">Diploma</option>
          <option value="S1">S1</option>
          <option value="S2">S2</option>
          <option value="S3">S3</option>
        </select>
      </div>
      <!-- WhatsApp Ibu -->
      <div class="space-y-2">
        <label for="wa_ibu" class="block text-teal-700 font-medium">WhatsApp Ibu:</label>
        <div class="relative">
          <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
            </svg>
          </div>
          <input type="text" id="wa_ibu" name="wa_ibu" v-model="localForm.wa_ibu" @input="onWaIbuInput" class="w-full pl-10 pr-4 py-2 border-2 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-colors duration-300" :class="{'border-red-500': waIbuError}" placeholder="62812345678" required />
          <div v-if="waIbuOperator" class="absolute right-3 top-1/2 -translate-y-1/2">
            <span class="px-2 py-1 text-xs font-medium rounded-full" :class="operatorClass(waIbuOperator)">{{ waIbuOperator }}</span>
          </div>
        </div>
        <p v-if="waIbuError" class="mt-1 text-sm text-red-600">{{ waIbuError }}</p>
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
      <button type="button" @click="handleNextStep" class="px-6 py-2.5 bg-gradient-to-r from-teal-600 to-teal-500 text-white rounded-lg hover:from-teal-700 hover:to-teal-600 focus:ring-2 focus:ring-teal-500 focus:ring-offset-2 transition-all duration-300 flex items-center gap-2">
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
  waAyahOperator: { type: String, default: '' },
  waIbuOperator: { type: String, default: '' },
});
const emit = defineEmits(['update:modelValue', 'next-step', 'prev-step', 'update-form-data', 'validate-whatsapp']);
const currentStep = 2;
const localForm = computed({
  get: () => props.modelValue,
  set: (val) => emit('update:modelValue', val),
});
watch(localForm, (val) => emit('update:modelValue', val), { deep: true });

// Error state
const namaAyahError = ref('');
const pekerjaanAyahError = ref('');
const pekerjaanAyahLainnyaError = ref('');
const pendidikanAyahError = ref('');
const waAyahError = ref('');
const namaIbuError = ref('');
const pekerjaanIbuError = ref('');
const pekerjaanIbuLainnyaError = ref('');
const pendidikanIbuError = ref('');
const waIbuError = ref('');

function capitalizeWords(str) {
  return str.replace(/\b\w+/g, w => w.charAt(0).toUpperCase() + w.slice(1).toLowerCase());
}
function onNamaAyahInput(e) {
  let val = e.target.value.replace(/[^a-zA-Z\s']/g, '');
  val = val.replace(/\s{2,}/g, ' ');
  localForm.value.nama_ayah = capitalizeWords(val);
  if (!val.trim()) {
    namaAyahError.value = 'Mohon isi nama ayah dengan benar.';
  } else {
    namaAyahError.value = '';
  }
}
function onPekerjaanAyahInput(e) {
  let val = localForm.value.pekerjaan_ayah;
  if (!val) {
    pekerjaanAyahError.value = 'Mohon pilih pekerjaan ayah.';
  } else if (val === 'Lainnya' && !localForm.value.pekerjaan_ayah_lainnya?.trim()) {
    pekerjaanAyahLainnyaError.value = 'Mohon isi pekerjaan ayah lainnya.';
  } else {
    pekerjaanAyahError.value = '';
    pekerjaanAyahLainnyaError.value = '';
  }
}
function onPendidikanAyahInput(e) {
  let val = localForm.value.pendidikan_ayah;
  if (!val) {
    pendidikanAyahError.value = 'Mohon pilih pendidikan ayah.';
  } else {
    pendidikanAyahError.value = '';
  }
}
function onWaAyahInput(e) {
  let val = e.target.value.replace(/\D/g, '');
  localForm.value.wa_ayah = val;
  if (!val) {
    waAyahError.value = 'Mohon isi nomor WhatsApp ayah.';
  } else if (!val.startsWith('62')) {
    waAyahError.value = 'Nomor WhatsApp ayah harus diawali 62.';
  } else if (val.length < 11 || val.length > 14) {
    waAyahError.value = 'Nomor WhatsApp ayah harus 11-14 digit.';
  } else {
    waAyahError.value = '';
  }
}
function onNamaIbuInput(e) {
  let val = e.target.value.replace(/[^a-zA-Z\s']/g, '');
  val = val.replace(/\s{2,}/g, ' ');
  localForm.value.nama_ibu = capitalizeWords(val);
  if (!val.trim()) {
    namaIbuError.value = 'Mohon isi nama ibu dengan benar.';
  } else {
    namaIbuError.value = '';
  }
}
function onPekerjaanIbuInput(e) {
  let val = localForm.value.pekerjaan_ibu;
  if (!val) {
    pekerjaanIbuError.value = 'Mohon pilih pekerjaan ibu.';
  } else if (val === 'Lainnya' && !localForm.value.pekerjaan_ibu_lainnya?.trim()) {
    pekerjaanIbuLainnyaError.value = 'Mohon isi pekerjaan ibu lainnya.';
  } else {
    pekerjaanIbuError.value = '';
    pekerjaanIbuLainnyaError.value = '';
  }
}
function onPendidikanIbuInput(e) {
  let val = localForm.value.pendidikan_ibu;
  if (!val) {
    pendidikanIbuError.value = 'Mohon pilih pendidikan ibu.';
  } else {
    pendidikanIbuError.value = '';
  }
}
function onWaIbuInput(e) {
  let val = e.target.value.replace(/\D/g, '');
  localForm.value.wa_ibu = val;
  if (!val) {
    waIbuError.value = 'Mohon isi nomor WhatsApp ibu.';
  } else if (!val.startsWith('62')) {
    waIbuError.value = 'Nomor WhatsApp ibu harus diawali 62.';
  } else if (val.length < 11 || val.length > 14) {
    waIbuError.value = 'Nomor WhatsApp ibu harus 11-14 digit.';
  } else {
    waIbuError.value = '';
  }
}
function handleNextStep() {
  onNamaAyahInput({ target: { value: localForm.value.nama_ayah || '' } });
  onPekerjaanAyahInput();
  onPendidikanAyahInput();
  onWaAyahInput({ target: { value: localForm.value.wa_ayah || '' } });
  onNamaIbuInput({ target: { value: localForm.value.nama_ibu || '' } });
  onPekerjaanIbuInput();
  onPendidikanIbuInput();
  onWaIbuInput({ target: { value: localForm.value.wa_ibu || '' } });
  if (
    namaAyahError.value || pekerjaanAyahError.value || pekerjaanAyahLainnyaError.value || pendidikanAyahError.value || waAyahError.value ||
    namaIbuError.value || pekerjaanIbuError.value || pekerjaanIbuLainnyaError.value || pendidikanIbuError.value || waIbuError.value
  ) {
    window.scrollTo({ top: 0, behavior: 'smooth' });
    return;
  }
  emit('next-step');
}
function emitUpdate(field, value) {
  emit('update-form-data', field, value);
}
function operatorClass(operator) {
  return {
    'bg-red-100 text-red-800': operator === 'Telkomsel',
    'bg-blue-100 text-blue-800': operator === 'XL',
    'bg-yellow-100 text-yellow-800': operator === 'Indosat',
    'bg-green-100 text-green-800': operator === 'Tri',
    'bg-purple-100 text-purple-800': operator === 'Axis',
    'bg-orange-100 text-orange-800': operator === 'Smartfren',
  };
}
</script> 