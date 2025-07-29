<template>
  <BaseModal
    :show="show"
    title="Edit Data Calon Santri"
    icon="fas fa-user-edit"
    @close="$emit('close')"
  >
    <!-- Form Content -->
    <form @submit.prevent="submit" id="form-edit-santri" class="space-y-4">
      <div>
        <label class="block text-sm font-medium mb-1">Nama Lengkap</label>
        <input v-model="form.nama" type="text" class="input" required @input="formatNama" @blur="validateNama" />
        <div v-if="namaError" class="text-xs text-red-500 mt-1">{{ namaError }}</div>
      </div>
      <div>
        <label class="block text-sm font-medium mb-1">NISN</label>
        <input v-model="form.nisn" type="text" class="input" required maxlength="10" @input="validateNISN" @blur="validateNISN" />
        <div v-if="nisnError" class="text-xs text-red-500 mt-1">{{ nisnError }}</div>
      </div>
      <div>
        <label class="block text-sm font-medium mb-1">Unit</label>
        <select v-model="form.unit" class="input" required>
          <option value="ppim">PPIM</option>
          <option value="tks">TKS</option>
        </select>
      </div>
      <div>
        <label class="block text-sm font-medium mb-1">Jenis Kelamin</label>
        <select v-model="form.jenis_kelamin" class="input" required>
          <option value="Laki-laki">Laki-laki</option>
          <option value="Perempuan">Perempuan</option>
        </select>
      </div>
      <div>
        <label class="block text-sm font-medium mb-1">Tempat Lahir</label>
        <input v-model="form.tempat_lahir" type="text" class="input" required />
      </div>
      <div>
        <label class="block text-sm font-medium mb-1">Tanggal Lahir</label>
        <input v-model="form.tanggal_lahir" type="text" class="input" required placeholder="23 Mei 2005" @blur="formatTanggalLahir" />
        <div v-if="tanggalError" class="text-xs text-red-500 mt-1">{{ tanggalError }}</div>
      </div>
      <div>
        <label class="block text-sm font-medium mb-1">Asal Sekolah</label>
        <input v-model="form.asal_sekolah" type="text" class="input" required />
      </div>
      <div>
        <label class="block text-sm font-medium mb-1">Anak Ke</label>
        <input v-model="form.anak_ke" type="number" min="1" class="input" required @input="validateAnakKe" />
        <div v-if="anakKeError" class="text-xs text-red-500 mt-1">{{ anakKeError }}</div>
      </div>
      <div>
        <label class="block text-sm font-medium mb-1">Jumlah Saudara</label>
        <input v-model="form.jumlah_saudara" type="number" min="1" class="input" required @input="validateAnakKe" />
        <div v-if="jumlahSaudaraError" class="text-xs text-red-500 mt-1">{{ jumlahSaudaraError }}</div>
      </div>
    </form>

    <!-- Footer Buttons -->
    <template #footer>
      <button type="button" @click="$emit('close')" class="px-4 py-2 bg-gray-200 rounded hover:bg-gray-300">Batal</button>
      <button type="submit" form="form-edit-santri" class="px-4 py-2 bg-teal-600 text-white rounded hover:bg-teal-700">Simpan</button>
    </template>
  </BaseModal>
</template>
<script>
import BaseModal from '@/components/common/BaseModal.vue';

export default {
  name: 'EditSantriForm',
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
      tanggalError: '',
      namaError: '',
      nisnError: '',
      anakKeError: '',
      jumlahSaudaraError: ''
    }
  },
  mounted() {
    this.autoFormatTanggalLahir();
  },
  watch: {
    data(val) {
      this.form = { ...val }
      this.tanggalError = ''
      this.namaError = ''
      this.nisnError = ''
      this.anakKeError = ''
      this.jumlahSaudaraError = ''
      this.autoFormatTanggalLahir();
    }
  },
  methods: {
    async submit() {
      this.tanggalError = '';
      this.namaError = '';
      this.nisnError = '';
      this.anakKeError = '';
      this.jumlahSaudaraError = '';
      // Validasi semua field wajib
      if (!this.form.nama || !this.form.nisn || !this.form.unit || !this.form.jenis_kelamin || !this.form.tempat_lahir || !this.form.tanggal_lahir || !this.form.asal_sekolah || !this.form.anak_ke || !this.form.jumlah_saudara) {
        Swal.fire({
          title: 'Error!',
          text: 'Semua field wajib diisi!',
          icon: 'error',
          confirmButtonText: 'OK'
        });
        return;
      }

      // Validasi nama
      if (this.form.nama.length < 3) {
        this.namaError = 'Nama harus memiliki minimal 3 karakter';
        return;
      }

      // Validasi NISN
      if (this.form.nisn.length !== 10 || !/^\d{10}$/.test(this.form.nisn)) {
        this.nisnError = 'NISN harus tepat 10 digit angka';
        return;
      }

      // Validasi anak_ke vs jumlah_saudara
      if (parseInt(this.form.anak_ke) > parseInt(this.form.jumlah_saudara)) {
        this.anakKeError = 'Anak ke tidak boleh lebih besar dari jumlah saudara';
        return;
      }

      // Validasi tanggal lahir
      if (!this.validateTanggal(this.form.tanggal_lahir)) {
        this.tanggalError = 'Format tanggal harus seperti 23 Mei 2005';
        return;
      }

      let tanggalYMD = this.form.tanggal_lahir;
      if (!/^\d{4}-\d{2}-\d{2}$/.test(tanggalYMD)) {
        tanggalYMD = this.convertTanggalToYMD(this.form.tanggal_lahir);
      }
      const namaCapitalized = this.form.nama.replace(/\b\w/g, l => l.toUpperCase());
      const payload = {
        ...this.form,
        nama: namaCapitalized,
        tanggal_lahir: tanggalYMD,
        status_tes: this.data.status_tes
      };
      this.$emit('update', payload);
      this.$emit('close');
    },
    formatTanggalLahir() {
      if (this.form.tanggal_lahir && this.validateTanggal(this.form.tanggal_lahir)) {
        // Capitalize bulan
        this.form.tanggal_lahir = this.capitalizeTanggal(this.form.tanggal_lahir);
        this.tanggalError = '';
      } else if (this.form.tanggal_lahir) {
        this.tanggalError = 'Format tanggal harus seperti 23 Mei 2005';
      }
    },
    validateTanggal(val) {
      // 23 mei 2005, 23 Mei 2005, 23 MEI 2005
      return /^\d{1,2}\s+(januari|februari|maret|april|mei|juni|juli|agustus|september|oktober|november|desember)$/i.test(val.replace(/\s+\d{4}$/, '')) && /\d{4}$/.test(val);
    },
    capitalizeTanggal(val) {
      // 23 mei 2005 -> 23 Mei 2005
      return val.replace(/\b([a-z])([a-z]*)\b/gi, (m, a, b) => a.toUpperCase() + b.toLowerCase());
    },
    convertTanggalToYMD(val) {
      // 23 Mei 2005 -> 2005-05-23
      const bulan = {
        januari: '01', februari: '02', maret: '03', april: '04', mei: '05', juni: '06',
        juli: '07', agustus: '08', september: '09', oktober: '10', november: '11', desember: '12'
      };
      const [tgl, bln, thn] = val.split(' ');
      return `${thn}-${bulan[bln.toLowerCase()]}-${tgl.padStart(2, '0')}`;
    },
    autoFormatTanggalLahir() {
      if (this.form.tanggal_lahir) {
        let raw = this.form.tanggal_lahir;
        // Handle Date object
        if (typeof raw === 'object' && raw instanceof Date) {
          const y = raw.getFullYear();
          const m = raw.getMonth() + 1;
          const d = raw.getDate();
          this.form.tanggal_lahir = `${d} ${this.bulanIndo(m)} ${y}`;
          return;
        }
        // Handle ISO string (2008-11-19T17:00:00.000000Z, 2008-11-19T00:00:00Z, dst)
        if (/^\d{4}-\d{2}-\d{2}T/.test(raw)) {
          raw = raw.split('T')[0];
        }
        // Handle YYYY-MM-DD
        if (/^\d{4}-\d{2}-\d{2}$/.test(raw)) {
          const [y, m, d] = raw.split('-');
          this.form.tanggal_lahir = `${parseInt(d)} ${this.bulanIndo(parseInt(m))} ${y}`;
          return;
        }
        // Kalau sudah format manusiawi, biarin aja
      }
    },
    bulanIndo(m) {
      const bulan = [
        '', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
        'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
      ];
      return bulan[m];
    },
    formatNama() {
      // Capitalize setiap kata dalam nama
      this.form.nama = this.form.nama.replace(/\b\w/g, l => l.toUpperCase());
      this.namaError = '';
    },
    validateNama() {
      if (this.form.nama.length < 3) {
        this.namaError = 'Nama harus memiliki minimal 3 karakter';
      } else {
        this.namaError = '';
      }
    },
    validateNISN() {
      if (this.form.nisn.length !== 10) {
        this.nisnError = 'NISN harus memiliki 10 digit';
      } else {
        this.nisnError = '';
      }
    },
    validateAnakKe() {
      if (this.form.anak_ke > this.form.jumlah_saudara) {
        this.anakKeError = 'Anak ke tidak boleh lebih besar dari jumlah saudara';
      } else {
        this.anakKeError = '';
      }
    }
  }
}
</script>
<style scoped>
.input { @apply w-full px-3 py-2 border border-gray-300 rounded focus:ring-2 focus:ring-teal-500; }
</style>
