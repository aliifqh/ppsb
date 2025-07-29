<template>
  <div class="py-2 md:py-3">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-semibold text-gray-800">Manajemen Role & Permission</h2>
        <button @click="openModal()" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-all duration-200 focus:ring-2 focus:ring-blue-400 focus:outline-none flex items-center space-x-2">
            <i class="fas fa-plus"></i>
            <span>Tambah Role</span>
        </button>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-lg shadow-[0_2px_15px_-3px_rgba(0,0,0,0.07),0_10px_20px_-2px_rgba(0,0,0,0.04)] hover:shadow-lg transition-shadow duration-300 p-6">
      <ModernTable
        :columns="columns"
        :items="roles"
        :loading="loading"
      >
        <template #no="{ index }">
          {{ index + 1 }}
        </template>
        <template #name="{ item }">
          <span class="font-semibold text-gray-800">{{ item.name }}</span>
        </template>
        <template #permissions="{ item }">
          <div class="flex flex-wrap gap-2">
            <span v-for="permission in item.permissions" :key="permission.id" class="px-2.5 py-1 text-xs font-medium text-cyan-800 bg-cyan-100 rounded-full">
              {{ permission.name }}
            </span>
          </div>
        </template>
        <template #actions="{ item }">
          <ActionButtons
            :actions="[
              { type: 'edit', handler: () => editRole(item) },
              { type: 'delete', handler: () => deleteRole(item.id) }
            ]"
          />
        </template>
      </ModernTable>
    </div>

    <!-- Modal Form Create/Edit Role -->
    <ModernModal :show="showRoleModal" @close="closeModal" :title="isEditing ? 'Edit Role' : 'Tambah Role'" size="2xl">
      <form @submit.prevent="saveRole" class="space-y-6">
        <div>
          <label for="roleName" class="block text-sm font-medium text-gray-700">Nama Role</label>
          <input type="text" id="roleName" v-model="form.name" @blur="v$.form.name.$touch" required class="mt-1 block w-full px-3 py-2 bg-white border rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
          :class="{ 'border-red-500': v$.form.name.$error }">
          <span v-if="v$.form.name.$error" class="text-xs text-red-600 mt-1">
            Nama role wajib diisi.
          </span>
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-2">Permissions</label>
          <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 max-h-64 overflow-y-auto pr-2 border rounded-lg p-4"
          :class="{ 'border-red-500': v$.form.permissions.$error }">
            <label v-for="permission in permissions" :key="permission.id" :for="'permission-' + permission.id"
                   class="flex items-center space-x-3 p-3 rounded-lg border border-gray-200 hover:bg-gray-50 transition-colors cursor-pointer">
              <input type="checkbox"
                     class="h-4 w-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500"
                     :id="'permission-' + permission.id"
                     v-model="form.permissions"
                     :value="permission.id">
              <span class="text-sm font-medium text-gray-700">{{ permission.name }}</span>
            </label>
          </div>
          <span v-if="v$.form.permissions.$error" class="text-xs text-red-600 mt-1">
            Minimal pilih satu permission.
          </span>
        </div>
      </form>
      <template #footer>
        <div class="flex justify-end space-x-3">
            <button type="button" @click="closeModal" class="px-5 py-2.5 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 font-semibold transition-all">
                Batal
            </button>
            <button type="submit" @click="saveRole" :disabled="v$.$invalid" class="px-5 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-semibold transition-all disabled:opacity-50 disabled:cursor-not-allowed">
                Simpan
            </button>
        </div>
      </template>
    </ModernModal>
  </div>
</template>

<script>
import ModernModal from '@/components/common/ModernModal.vue';
import ModernTable from '@/components/common/ModernTable.vue';
import ActionButtons from '@/components/common/ActionButtons.vue';
import { swalConfirm } from '@/components/common/Swal2Dialog.js';
import useVuelidate from '@vuelidate/core';
import { required, minLength } from '@vuelidate/validators';

export default {
    components: { ModernModal, ModernTable, ActionButtons },
    setup () {
      return { v$: useVuelidate() }
    },
    data() {
        return {
            roles: [],
            permissions: [],
            loading: false,
            showRoleModal: false,
            isEditing: false,
            form: {
                id: null,
                name: '',
                permissions: []
            },
            columns: [
              { key: 'no', label: 'No', thClass: 'text-center w-16', tdClass: 'text-center' },
              { key: 'name', label: 'Nama Role' },
              { key: 'permissions', label: 'Permissions', thClass: 'w-3/5' },
              { key: 'actions', label: 'Aksi', thClass: 'text-center w-32', tdClass: 'text-center' },
            ]
        }
    },
    validations() {
      return {
        form: {
          name: { required },
          permissions: {
            required,
            minLength: minLength(1)
          }
        }
      }
    },
    mounted() {
        this.fetchRoles()
        this.fetchPermissions()
    },
    methods: {
        async fetchRoles() {
            this.loading = true;
            try {
                const response = await axios.get('/api/admin/roles');
                this.roles = response.data;
            } catch (error) {
                console.error('Error fetching roles:', error)
                Toast.fire({ icon: 'error', title: 'Gagal mengambil data roles' });
            } finally {
                this.loading = false;
            }
        },
        async fetchPermissions() {
            try {
                const response = await axios.get('/api/admin/permissions');
                this.permissions = response.data;
            } catch (error) {
                console.error('Error fetching permissions:', error);
            }
        },
        openModal() {
            this.isEditing = false;
            this.form = { id: null, name: '', permissions: [] };
            this.v$.$reset();
            this.showRoleModal = true;
        },
        editRole(role) {
            this.isEditing = true
            this.form = {
                id: role.id,
                name: role.name,
                permissions: role.permissions.map(p => p.id)
            }
            this.v$.$reset();
            this.showRoleModal = true
        },
        async deleteRole(id) {
            const result = await swalConfirm({
                title: 'Apakah Anda yakin?',
                text: "Role ini akan dihapus. Aksi ini tidak dapat dibatalkan!",
                icon: 'warning',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal',
            });

            if (result.isConfirmed) {
                try {
                    await axios.delete(`/api/admin/roles/${id}`);
                    this.fetchRoles();
                    Toast.fire({ icon: 'success', title: 'Role berhasil dihapus' });
                } catch (error) {
                    console.error('Error deleting role:', error);
                    const errorMessage = error.response?.data?.message || 'Gagal menghapus role';
                    Toast.fire({ icon: 'error', title: errorMessage });
                }
            }
        },
        async saveRole() {
            const isFormCorrect = await this.v$.$validate()
            if (!isFormCorrect) {
                Toast.fire({ icon: 'error', title: 'Form tidak valid!', text: 'Silakan periksa kembali isian Anda.' });
                return;
            }

            try {
                const action = this.isEditing
                    ? axios.put(`/api/admin/roles/${this.form.id}`, this.form)
                    : axios.post('/api/admin/roles', this.form);

                await action;
                this.closeModal();
                this.fetchRoles();
                Toast.fire({ icon: 'success', title: 'Data berhasil disimpan' });
            } catch (error) {
                console.error('Error saving role:', error);
                const errorMessage = error.response?.data?.errors
                    ? Object.values(error.response.data.errors).flat().join('<br>')
                    : (error.response?.data?.message || 'Gagal menyimpan data');

                Toast.fire({ icon: 'error', title: 'Oops...', html: errorMessage });
            }
        },
        closeModal() {
            this.showRoleModal = false
        }
    }
}
</script>

<style scoped>
.modal {
    display: block;
    background-color: rgba(0,0,0,0.5);
}
</style>
