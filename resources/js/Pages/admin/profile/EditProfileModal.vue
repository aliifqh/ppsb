<template>
  <ModernModal :show="show" @close="close" title="Edit Profil">
    <form @submit.prevent="submit" class="space-y-4">
      <div>
        <label class="block text-sm font-medium text-gray-700">Nama</label>
        <input v-model="form.name" type="text" class="mt-1 block w-full rounded border-gray-300" required />
      </div>
      <div>
        <label class="block text-sm font-medium text-gray-700">Email</label>
        <input v-model="form.email" type="email" class="mt-1 block w-full rounded border-gray-300" required />
      </div>
      <div>
        <label class="block text-sm font-medium text-gray-700">Password Baru <span class="text-xs text-gray-400">(Opsional)</span></label>
        <input v-model="form.password" type="password" class="mt-1 block w-full rounded border-gray-300" placeholder="Biarkan kosong jika tidak ingin ganti" />
      </div>
      <div class="flex flex-col items-center mb-4">
        <label class="block text-sm font-medium text-gray-700 mb-1">Avatar (Biar makin kece!)</label>
        <img :src="(avatarPreview || user.avatar) ? (avatarPreview || user.avatar) + '?t=' + Date.now() : 'https://ui-avatars.com/api/?name=' + encodeURIComponent(form.name)" class="w-20 h-20 rounded-full object-cover border mb-2" alt="Avatar Preview" />
        <input type="file" accept="image/*" @change="onAvatarChange" class="block w-full text-xs" />
        <span class="text-xs text-gray-400 mt-1">Jangan upload foto mantan ya! 😆</span>
      </div>
      <div class="flex justify-end space-x-2 mt-6">
        <LoadingButton text="Batal" variant="secondary" @click="close" :loading="false" type="button" />
        <LoadingButton text="Simpan" :loading="loading" icon="fas fa-save" type="submit" />
      </div>
    </form>
    <LoadingOverlay :show="loading" title="Menyimpan..." subtitle="Mohon tunggu sebentar" />
  </ModernModal>
</template>

<script>
import ModernModal from '@/components/common/ModernModal.vue';
import LoadingButton from '@/components/common/LoadingButton.vue';
import LoadingOverlay from '@/components/common/LoadingOverlay.vue';
import axios from 'axios';

export default {
  name: 'EditProfileModal',
  components: { ModernModal, LoadingButton, LoadingOverlay },
  props: {
    show: Boolean,
    user: Object
  },
  data() {
    return {
      form: {
        name: this.user?.name || '',
        email: this.user?.email || '',
        password: '',
        avatar: null
      },
      avatarPreview: null,
      loading: false
    };
  },
  watch: {
    user(newVal) {
      this.form.name = newVal?.name || '';
      this.form.email = newVal?.email || '';
      this.form.password = '';
      this.avatarPreview = newVal?.avatar || null;
      this.form.avatar = null;
    },
    show(val) {
      if (val) {
        this.form.name = this.user?.name || '';
        this.form.email = this.user?.email || '';
        this.form.password = '';
        this.avatarPreview = this.user?.avatar || null;
        this.form.avatar = null;
      }
    }
  },
  methods: {
    close() {
      this.$emit('close');
    },
    onAvatarChange(e) {
      const file = e.target.files[0];
      if (file) {
        this.form.avatar = file;
        this.avatarPreview = URL.createObjectURL(file);
      }
    },
    async submit() {
      this.loading = true;
      try {
        // Pastikan CSRF cookie sudah di-set (untuk sanctum)
        await axios.get('/sanctum/csrf-cookie');
        const formData = new FormData();
        formData.append('name', this.form.name);
        formData.append('email', this.form.email);
        if (this.form.password) formData.append('password', this.form.password);
        if (this.form.avatar) formData.append('avatar', this.form.avatar);
        const res = await axios.post('/user/profile', formData, { headers: { 'Content-Type': 'multipart/form-data' } });
        this.$emit('updated', res.data.user);
        this.close();
      } catch (e) {
        alert('Gagal update profil: ' + (e.response?.data?.message || e.message));
      } finally {
        this.loading = false;
      }
    }
  }
};
</script>
