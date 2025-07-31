<template>
  <div class="min-h-screen bg-gray-100">
    <!-- Sidebar -->
    <div class="fixed inset-y-0 left-0 z-50 w-64 bg-white shadow-lg">
      <div class="flex items-center justify-center h-16 bg-indigo-600">
        <h1 class="text-white text-lg font-semibold">PPIN NGRUKI</h1>
      </div>

      <nav class="mt-8">
        <div class="px-4 space-y-2">
          <router-link
            to="/admin/dashboard"
            class="flex items-center px-4 py-2 text-gray-700 rounded-lg hover:bg-gray-100"
            :class="{ 'bg-indigo-100 text-indigo-700': $route.name === 'admin.dashboard' }"
          >
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5a2 2 0 012-2h4a2 2 0 012 2v6H8V5z"></path>
            </svg>
            Dashboard
          </router-link>

          <router-link
            to="/admin/santri"
            class="flex items-center px-4 py-2 text-gray-700 rounded-lg hover:bg-gray-100"
            :class="{ 'bg-indigo-100 text-indigo-700': $route.name === 'admin.santri' }"
          >
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
            </svg>
            Santri
          </router-link>

          <router-link
            to="/admin/pembayaran"
            class="flex items-center px-4 py-2 text-gray-700 rounded-lg hover:bg-gray-100"
            :class="{ 'bg-indigo-100 text-indigo-700': $route.name === 'admin.pembayaran' }"
          >
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
            </svg>
            Pembayaran
          </router-link>

          <router-link
            to="/admin/gelombang"
            class="flex items-center px-4 py-2 text-gray-700 rounded-lg hover:bg-gray-100"
            :class="{ 'bg-indigo-100 text-indigo-700': $route.name === 'admin.gelombang' }"
          >
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
            </svg>
            Gelombang
          </router-link>

          <router-link
            to="/admin/roles"
            class="flex items-center px-4 py-2 text-gray-700 rounded-lg hover:bg-gray-100"
            :class="{ 'bg-indigo-100 text-indigo-700': $route.name === 'admin.roles' }"
          >
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
            </svg>
            Roles & Permissions
          </router-link>
        </div>
      </nav>
    </div>

    <!-- Main Content -->
    <div class="ml-64">
      <!-- Top Navigation -->
      <header class="bg-white shadow">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div class="flex justify-between h-16">
            <div class="flex items-center">
              <h2 class="text-xl font-semibold text-gray-900">
                {{ pageTitle }}
              </h2>
            </div>

            <div class="flex items-center">
              <button
                @click="logout"
                class="text-gray-500 hover:text-gray-700 px-3 py-2 rounded-md text-sm font-medium"
              >
                Logout
              </button>
            </div>
          </div>
        </div>
      </header>

      <!-- Page Content -->
      <main class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        <router-view />
      </main>
    </div>
  </div>
</template>

<script>
export default {
  name: 'AdminLayout',
  computed: {
    pageTitle() {
      const routeNames = {
        'admin.dashboard': 'Dashboard',
        'admin.santri': 'Data Santri',
        'admin.pembayaran': 'Pembayaran',
        'admin.gelombang': 'Gelombang',
        'admin.roles': 'Roles & Permissions'
      }
      return routeNames[this.$route.name] || 'Admin Panel'
    }
  },
  methods: {
    async logout() {
      try {
        await fetch('/logout', {
          method: 'POST',
          headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json'
          }
        })
        window.location.href = '/login'
      } catch (error) {
        console.error('Logout error:', error)
      }
    }
  }
}
</script>
