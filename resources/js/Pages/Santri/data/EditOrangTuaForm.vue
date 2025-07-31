<template>
  <BaseModal
    :show="show"
    title="Edit Data Orang Tua"
    icon="fas fa-users"
    @close="$emit('close')"
  >
    <!-- Form Content -->
    <form @submit.prevent="submit" id="form-edit-orangtua" class="space-y-4">
      <div>
        <label class="block text-sm font-medium mb-1">Nama Ayah</label>
        <input v-model="form.nama_ayah" type="text" class="input" required />
      </div>
      <div>
        <label class="block text-sm font-medium mb-1">Nama Ibu</label>
        <input v-model="form.nama_ibu" type="text" class="input" required />
      </div>
      <div>
        <label class="block text-sm font-medium mb-1">Pekerjaan Ayah</label>
        <select v-model="form.pekerjaan_ayah" class="input" required>
          <option v-for="option in pekerjaanOptionsAyah" :key="option" :value="option">{{ option }}</option>
        </select>
      </div>
      <div v-if="showOtherAyah">
        <label class="block text-sm font-medium mb-1">Pekerjaan Ayah (lainnya)</label>
        <input v-model="form.pekerjaan_ayah_lainnya" type="text" class="input" required />
      </div>
      <div>
        <label class="block text-sm font-medium mb-1">Pekerjaan Ibu</label>
        <select v-model="form.pekerjaan_ibu" class="input" required>
          <option v-for="option in pekerjaanOptionsIbu" :key="option" :value="option">{{ option }}</option>
        </select>
      </div>
      <div v-if="showOtherIbu">
        <label class="block text-sm font-medium mb-1">Pekerjaan Ibu (lainnya)</label>
        <input v-model="form.pekerjaan_ibu_lainnya" type="text" class="input" required />
      </div>
      <div>
        <label class="block text-sm font-medium mb-1">Pendidikan Ayah</label>
        <select v-model="form.pendidikan_ayah" class="input" required>
          <option v-for="option in pendidikanOptions" :key="option" :value="option">{{ option }}</option>
        </select>
      </div>
      <div>
        <label class="block text-sm font-medium mb-1">Pendidikan Ibu</label>
        <select v-model="form.pendidikan_ibu" class="input" required>
          <option v-for="option in pendidikanOptions" :key="option" :value="option">{{ option }}</option>
        </select>
      </div>
      <div>
        <label class="block text-sm font-medium mb-1">WA Ayah</label>
        <input v-model="form.wa_ayah" type="text" class="input" required />
        <div v-if="waAyahError" class="text-xs text-red-500 mt-1">{{ waAyahError }}</div>
      </div>
      <div>
        <label class="block text-sm font-medium mb-1">WA Ibu</label>
        <input v-model="form.wa_ibu" type="text" class="input" required />
        <div v-if="waIbuError" class="text-xs text-red-500 mt-1">{{ waIbuError }}</div>
      </div>
    </form>

    <!-- Footer Buttons -->
    <template #footer>
      <button type="button" @click="$emit('close')" class="px-4 py-2 bg-gray-200 rounded hover:bg-gray-300">Batal</button>
      <button type="submit" form="form-edit-orangtua" class="px-4 py-2 bg-teal-600 text-white rounded hover:bg-teal-700">Simpan</button>
    </template>
  </BaseModal>
</template>
<script>
import Swal from 'sweetalert2';
import BaseModal from '@/components/common/BaseModal.vue';

export default {
  name: 'EditOrangTuaForm',
  components: {
    BaseModal
  },
  props: {
    show: Boolean,
    data: Object
  },
  emits: ['close', 'update'],
  data() {
    return {
      form: { ...this.data },
      pekerjaanOptionsAyah: [
        'PNS / ASN', 'TNI / Polri', 'Karyawan Swasta', 'Petani / Pekebun', 'Pedagang / Wirausaha',
        'Buruh / Karyawan Harian', 'Sopir / Ojek / Pengemudi', 'Pensiunan', 'Tidak Bekerja', 'Lainnya'
      ],
      pekerjaanOptionsIbu: [
        'PNS / ASN', 'TNI / Polri', 'Karyawan Swasta', 'Petani / Pekebun', 'Pedagang / Wirausaha',
        'Buruh / Karyawan Harian', 'Ibu Rumah Tangga', 'Pensiunan', 'Tidak Bekerja', 'Lainnya'
      ],
      pendidikanOptions: ['SD', 'SLTP', 'SLTA', 'Diploma', 'S1', 'S2', 'S3'],
      waAyahError: '',
      waIbuError: '',
      showOtherAyah: false,
      showOtherIbu: false
    }
  },
  watch: {
    data(val) {
      this.form = { ...val }
    },
    'form.pekerjaan_ayah'(val) {
      this.showOtherAyah = val === 'Lainnya';
      if (val !== 'Lainnya') this.form.pekerjaan_ayah_lainnya = '';
    },
    'form.pekerjaan_ibu'(val) {
      this.showOtherIbu = val === 'Lainnya';
      if (val !== 'Lainnya') this.form.pekerjaan_ibu_lainnya = '';
    },
    'form.wa_ayah'(val) {
      this.formatAndValidateWA('ayah');
    },
    'form.wa_ibu'(val) {
      this.formatAndValidateWA('ibu');
    }
  },
  methods: {
    formatAndValidateWA(type) {
      let wa = this.form[`wa_${type}`] || '';
      // Hapus non-digit
      wa = wa.replace(/[^0-9]/g, '');
      // Jika diawali 0, ubah ke 62
      if (wa.startsWith('0')) wa = '62' + wa.slice(1);
      // Jika tidak diawali 62, tambahkan 62
      if (!wa.startsWith('62')) wa = '62' + wa;
      this.form[`wa_${type}`] = wa;
      // Validasi panjang
      if (wa.length < 10 || wa.length > 15) {
        this[`${type === 'ayah' ? 'waAyahError' : 'waIbuError'}`] = 'Nomor WhatsApp harus 10-15 digit dan diawali 62.';
      } else {
        this[`${type === 'ayah' ? 'waAyahError' : 'waIbuError'}`] = '';
      }
    },
    submit() {
      // Validasi semua field wajib
      if (!this.form.nama_ayah || !this.form.nama_ibu || !this.form.pekerjaan_ayah || !this.form.pekerjaan_ibu || !this.form.pendidikan_ayah || !this.form.pendidikan_ibu || !this.form.wa_ayah || !this.form.wa_ibu) {
        Swal.fire({
          title: 'Error!',
          text: 'Semua field orang tua wajib diisi!',
          icon: 'error',
          confirmButtonText: 'OK'
        });
        return;
      }
      if (this.showOtherAyah && !this.form.pekerjaan_ayah_lainnya) {
        Swal.fire({
          title: 'Error!',
          text: 'Pekerjaan ayah (lainnya) wajib diisi!',
          icon: 'error',
          confirmButtonText: 'OK'
        });
        return;
      }
      if (this.showOtherIbu && !this.form.pekerjaan_ibu_lainnya) {
        Swal.fire({
          title: 'Error!',
          text: 'Pekerjaan ibu (lainnya) wajib diisi!',
          icon: 'error',
          confirmButtonText: 'OK'
        });
        return;
      }
      if (this.waAyahError || this.waIbuError) {
        Swal.fire({
          title: 'Error!',
          text: 'Nomor WhatsApp ayah/ibu tidak valid!',
          icon: 'error',
          confirmButtonText: 'OK'
        });
        return;
      }
      this.$emit('update', { ...this.form });
      this.$emit('close');
    }
  }
}
</script>
<style scoped>
.input { @apply w-full px-3 py-2 border border-gray-300 rounded focus:ring-2 focus:ring-teal-500; }
</style>

