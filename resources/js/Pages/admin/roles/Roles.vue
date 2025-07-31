<template>
    <div class="py-2 md:py-3">
        <!-- Modal untuk edit role user -->
        <ModernModal
            :show="showRoleModal"
            @close="closeRoleModal"
            title="Pilih Role"
            size="md"
        >
            <div class="space-y-4">
                <div class="text-sm text-gray-600 mb-4">
                    Pilih role untuk <span class="font-semibold">{{ selectedUser?.name }}</span>
                </div>

                <div class="space-y-2">
                    <div
                        v-for="role in availableRoles"
                        :key="role.id"
                        class="flex items-center p-3 bg-white border rounded-lg hover:bg-gray-50 cursor-pointer transition-colors"
                        :class="{ 'border-emerald-500 bg-emerald-50': selectedRole === role.name }"
                        @click="selectedRole = role.name"
                    >
                        <input
                            type="radio"
                            :value="role.name"
                            v-model="selectedRole"
                            class="rounded-full border-gray-300 text-emerald-600 focus:ring-emerald-500"
                        >
                        <div class="ml-3">
                            <p class="text-sm font-medium text-gray-900">{{ ucfirst(role.name) }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <template #footer>
                <div class="flex justify-end space-x-3">
                    <button
                        @click="closeRoleModal"
                        class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors"
                    >
                        Batal
                    </button>
                    <button
                        @click="updateUserRole"
                        :disabled="!selectedRole || updating"
                        class="px-4 py-2 text-sm font-medium text-white bg-emerald-600 rounded-lg hover:bg-emerald-700 disabled:opacity-50 disabled:cursor-not-allowed transition-colors flex items-center"
                    >
                        <LoadingSpinner v-if="updating" class="w-4 h-4 mr-2" />
                        <span v-else>Simpan Perubahan</span>
                    </button>
                </div>
            </template>
        </ModernModal>

        <div class="w-full px-2">
            <!-- Header -->
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-semibold text-gray-800">Manajemen Role</h2>
                <div class="flex space-x-2">
                    <button
                        @click="refreshData"
                        :disabled="loading"
                        class="inline-flex items-center px-3 py-1.5 bg-gray-200 text-gray-700 hover:bg-gray-300 rounded-lg transition-all duration-200 focus:ring-2 focus:ring-gray-400 focus:outline-none disabled:opacity-50"
                    >
                        <i class="fas fa-sync-alt mr-2" :class="{ 'fa-spin': loading }"></i>
                        <span class="hidden md:inline">Refresh</span>
                    </button>
                </div>
            </div>

            <!-- Search -->
            <div class="mb-6">
                <SearchBar
                    v-model="search"
                    placeholder="Cari nama atau email user..."
                    :loading="loading"
                    :modalActive="showRoleModal"
                    @input="filterUsers"
                />
            </div>

            <!-- Table Card -->
            <div class="bg-white rounded-lg shadow-[0_2px_15px_-3px_rgba(0,0,0,0.07),0_10px_20px_-2px_rgba(0,0,0,0.04)] hover:shadow-[0_8px_17px_2px_rgba(0,0,0,0.14),0_3px_14px_2px_rgba(0,0,0,0.12),0_5px_5px_-3px_rgba(0,0,0,0.2)] transition-shadow duration-300 p-4">
                <ModernTable
                    :columns="columns"
                    :items="filteredUsers"
                    :loading="loading"
                    :sortable-columns="['name', 'email']"
                    :visible-columns="visibleColumns"
                    :row-clickable="false"
                    :selectable="false"
                    @sort-changed="handleSort"
                >
                    <!-- Custom cell content -->
                    <template #no="{ index }">
                        {{ index + 1 }}
                    </template>

                    <template #name="{ item }">
                        <div class="flex items-center">
                            <img
                                class="h-8 w-8 rounded-full"
                                :src="item.google_avatar || `https://ui-avatars.com/api/?name=${encodeURIComponent(item.name)}&background=0D8ABC&color=fff`"
                                :alt="item.name"
                            >
                            <div class="ml-2">
                                <div class="text-sm font-medium text-gray-900">{{ item.name }}</div>
                            </div>
                        </div>
                    </template>

                    <template #email="{ item }">
                        <div class="text-sm text-gray-500">{{ item.email }}</div>
                    </template>

                    <template #roles="{ item }">
                        <div class="flex flex-wrap justify-center gap-1">
                            <span
                                v-for="role in item.roles"
                                :key="role.id"
                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800"
                            >
                                {{ ucfirst(role.name) }}
                            </span>
                        </div>
                    </template>

                    <template #actions="{ item }">
                        <div class="flex justify-center">
                            <ActionButtons
                                :actions="getActionsForUser(item)"
                                @action="(key) => handleAction(key, item)"
                            />
                        </div>
                    </template>
                </ModernTable>
            </div>
        </div>
    </div>
</template>

<script>
import { ref, computed, onMounted } from 'vue'
import ModernTable from '../../../components/common/ModernTable.vue'
import SearchBar from '../../../components/common/SearchBar.vue'
import ActionButtons from '../../../components/common/ActionButtons.vue'
import ModernModal from '../../../components/common/ModernModal.vue'
import LoadingSpinner from '../../../components/common/LoadingSpinner.vue'
import { swalSuccess, swalError } from '../../../components/common/Swal2Dialog.js'

export default {
    name: 'Roles',
    components: {
        ModernTable,
        SearchBar,
        ActionButtons,
        ModernModal,
        LoadingSpinner
    },
    setup() {
        const users = ref([])
        const availableRoles = ref([])
        const loading = ref(false)
        const search = ref('')
        const showRoleModal = ref(false)
        const selectedUser = ref(null)
        const selectedRole = ref('')
        const updating = ref(false)
        const sortBy = ref('name')
        const sortOrder = ref('asc')

        // Table columns
        const columns = [
            { key: 'no', label: 'No', sortable: false, width: '60px' },
            { key: 'name', label: 'User', sortable: true },
            { key: 'email', label: 'Email', sortable: true },
            { key: 'roles', label: 'Role Saat Ini', sortable: false, width: '150px', textAlign: 'center' },
            { key: 'actions', label: 'Aksi', sortable: false, width: '120px', textAlign: 'center' }
        ]

        const visibleColumns = ['no', 'name', 'email', 'roles', 'actions']

        // Computed properties
        const filteredUsers = computed(() => {
            let filtered = users.value

            if (search.value) {
                const searchLower = search.value.toLowerCase()
                filtered = filtered.filter(user =>
                    user.name.toLowerCase().includes(searchLower) ||
                    user.email.toLowerCase().includes(searchLower)
                )
            }

            // Sort
            filtered.sort((a, b) => {
                let aVal = a[sortBy.value]
                let bVal = b[sortBy.value]

                if (sortBy.value === 'roles') {
                    aVal = a.roles.map(r => r.name).join(', ')
                    bVal = b.roles.map(r => r.name).join(', ')
                }

                if (typeof aVal === 'string') {
                    aVal = aVal.toLowerCase()
                    bVal = bVal.toLowerCase()
                }

                if (sortOrder.value === 'asc') {
                    return aVal > bVal ? 1 : -1
                } else {
                    return aVal < bVal ? 1 : -1
                }
            })

            return filtered
        })

        // Methods
        const fetchData = async () => {
            loading.value = true
            try {
                const [usersResponse, rolesResponse] = await Promise.all([
                    fetch('/api/admin/users-with-roles'),
                    fetch('/api/admin/roles')
                ])

                if (usersResponse.ok && rolesResponse.ok) {
                    users.value = await usersResponse.json()
                    availableRoles.value = await rolesResponse.json()
                } else {
                    throw new Error('Failed to fetch data')
                }
            } catch (error) {
                console.error('Error fetching data:', error)
                swalError({
                    title: 'Oops...',
                    text: 'Gagal memuat data! Silakan coba lagi.'
                })
            } finally {
                loading.value = false
            }
        }

        const filterUsers = () => {
            // Triggered by search input
        }

        const handleSort = (column, order) => {
            sortBy.value = column
            sortOrder.value = order
        }

        const getActionsForUser = (user) => {
            // Cek jika user adalah satu-satunya Super Admin
            const superAdminUsers = users.value.filter(u =>
                u.roles.some(r => r.name.toLowerCase() === 'super admin')
            )
            const isSingleSuperAdmin = superAdminUsers.length === 1
            const isThisUserSuperAdmin = user.roles.some(r => r.name.toLowerCase() === 'super admin')

            const isDisabled = isSingleSuperAdmin && isThisUserSuperAdmin

            return [{
                key: 'edit',
                label: 'Pilih Role',
                icon: 'fas fa-user-tag',
                color: 'bg-emerald-500 text-white',
                disabled: isDisabled,
                tooltip: isDisabled ? 'Tidak dapat mengubah role satu-satunya Super Admin' : 'Ubah role user'
            }]
        }

        const handleAction = (key, user) => {
            if (key === 'edit') {
                openRoleModal(user)
            }
        }

        const openRoleModal = (user) => {
            selectedUser.value = user
            selectedRole.value = user.roles.length > 0 ? user.roles[0].name : ''
            showRoleModal.value = true
        }

        const closeRoleModal = () => {
            showRoleModal.value = false
            selectedUser.value = null
            selectedRole.value = ''
        }

        const updateUserRole = async () => {
            if (!selectedRole.value || !selectedUser.value) return

            updating.value = true
            try {
                const response = await fetch(`/admin/roles/update-user-role/${selectedUser.value.id}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        role: selectedRole.value
                    })
                })

                if (response.ok) {
                    const result = await response.json()

                    swalSuccess({
                        title: 'Berhasil!',
                        text: result.message || 'Role user berhasil diperbarui'
                    })

                    closeRoleModal()
                    await fetchData() // Refresh data
                } else {
                    const error = await response.json()
                    throw new Error(error.message || 'Gagal memperbarui role')
                }
            } catch (error) {
                console.error('Error updating user role:', error)
                swalError({
                    title: 'Oops...',
                    text: error.message || 'Gagal memperbarui role user!'
                })
            } finally {
                updating.value = false
            }
        }

        const refreshData = () => {
            fetchData()
        }

        const ucfirst = (str) => {
            return str.charAt(0).toUpperCase() + str.slice(1)
        }

        // Lifecycle
        onMounted(() => {
            fetchData()
        })

        return {
            users,
            availableRoles,
            loading,
            search,
            showRoleModal,
            selectedUser,
            selectedRole,
            updating,
            columns,
            visibleColumns,
            filteredUsers,
            filterUsers,
            handleSort,
            getActionsForUser,
            handleAction,
            openRoleModal,
            closeRoleModal,
            updateUserRole,
            refreshData,
            ucfirst
        }
    }
}
</script>
