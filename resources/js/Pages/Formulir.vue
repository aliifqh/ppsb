<template>
  <div class="max-w-2xl mx-auto py-8">
    <h1 class="text-3xl font-bold text-center text-teal-700 mb-8">Formulir Pendaftaran</h1>
    <p class="text-center text-gray-600 mb-6">Silakan isi formulir pendaftaran santri baru secara lengkap.</p>
    <!-- Placeholder form, bisa diintegrasikan dengan komponen step atau API -->
    <form @submit.prevent="onSubmit" class="space-y-6">
      <div>
        <label class="block mb-1 font-medium">Nama Lengkap</label>
        <input type="text" class="w-full border rounded px-3 py-2" v-model="nama" required />
      </div>
      <div>
        <label class="block mb-1 font-medium">Nomor HP</label>
        <input type="text" class="w-full border rounded px-3 py-2" v-model="hp" required />
      </div>
      <button type="submit" class="px-6 py-2 bg-teal-600 text-white rounded hover:bg-teal-700">Kirim</button>
    </form>
  </div>
</template>

<script setup>
import { ref } from 'vue';
const nama = ref('');
const hp = ref('');
function onSubmit() {
  alert(`Pendaftaran atas nama ${nama.value} dengan HP ${hp.value} berhasil (dummy SPA)`);
}
</script>
