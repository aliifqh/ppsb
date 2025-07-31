<template>
  <div>
    <!-- Sidebar hanya di desktop/tablet besar -->
    <aside class="sidebar w-64 bg-white shadow-lg flex flex-col fixed top-0 left-0 bottom-0 h-screen z-40 overflow-y-auto hidden-desktop">
      <div class="p-6 border-b border-gray-200">
        <a href="/admin" class="block group">
          <img src="/img/LOGO-PPIN-NGRUKI.png" alt="Logo Pondok" class="w-full h-auto transition-transform duration-300 group-hover:scale-110 group-hover:rotate-3">
        </a>
      </div>
      <nav class="flex-1 px-4 py-4 space-y-1">
        <template v-for="item in menu" :key="item.label">
          <a :href="item.href" :class="itemClass(item)" :title="item.tooltip">
            <i :class="item.icon + ' w-5 h-5 mr-3 transition-transform duration-200' + (item.active ? ' text-emerald-500' : ' text-gray-400')"></i>
            {{ item.label }}
          </a>
        </template>
      </nav>
      <div class="user-info-wrapper mt-auto px-4 pb-6 w-full">
        <div class="user-info group flex items-center justify-between w-full relative py-3 border-t border-gray-200">
          <div class="flex-1 ml-0 min-w-0 flex items-center space-x-2">
            <img :src="user.avatar ? user.avatar + '?t=' + Date.now() : 'https://ui-avatars.com/api/?name=' + encodeURIComponent(user.name)" class="w-10 h-10 rounded-full border object-cover" alt="Avatar" />
            <div>
              <div class="font-semibold text-gray-800 text-sm truncate">{{ user.name }}</div>
              <div class="text-xs text-gray-500 truncate">{{ user.email }}</div>
            </div>
          </div>
          <div class="flex items-center ml-2 space-x-1 opacity-0 group-hover:opacity-100 transition-opacity duration-200">
            <button @click="showEditProfile = true" class="p-1.5 rounded-full hover:bg-emerald-100 transition-all duration-200 shadow-sm focus:outline-none flex items-center justify-center" title="Edit Profil">
              <i class="fas fa-pen text-gray-500 text-base"></i>
            </button>
            <button @click="handleLogout" class="p-1.5 rounded-full bg-red-50 hover:bg-red-100 transition-all duration-200 shadow-md focus:outline-none flex items-center justify-center" title="Keluar">
              <i class="fas fa-sign-out-alt text-red-500 text-base"></i>
            </button>
          </div>
        </div>
      </div>
    </aside>
    <!-- End Sidebar -->

    <!-- Bottom Bar untuk HP & Tablet kecil -->
    <nav class="bottom-bar fixed bottom-0 left-0 right-0 bg-white border-t border-gray-200 flex z-50 desktop-hidden">
      <template v-for="item in bottomMenu" :key="item.label">
        <a :href="item.href" :class="bottomItemClass(item)" :title="item.tooltip">
          <i :class="item.icon + ' text-lg mb-1 transition-transform duration-200' + (item.active ? ' text-emerald-500' : ' text-gray-400')"></i>
          <span class="text-xs">{{ item.label }}</span>
        </a>
      </template>
      <button @click="handleLogout" class="flex flex-col items-center flex-1 py-2 hover:bg-red-50 transition-colors duration-200" title="Keluar">
        <i class="fas fa-sign-out-alt text-red-500 text-lg mb-1"></i>
        <span class="text-xs">Keluar</span>
      </button>
    </nav>

    <!-- Modal Edit Profil -->
    <EditProfileModal :show="showEditProfile" :user="user" @close="showEditProfile = false" @updated="handleProfileUpdated" />
  </div>
</template>

<script setup>
import { ref } from 'vue';
import EditProfileModal from '../pages/admin/profile/EditProfileModal.vue';
import axios from 'axios';

const showEditProfile = ref(false);
const user = {
  name: document.body.dataset.userName || 'Admin',
  email: document.body.dataset.userEmail || '',
  avatar: document.body.dataset.userAvatar || ''
};
const menu = [
  { label: 'Dashboard', href: '/admin', icon: 'fas fa-home', active: window.location.pathname === '/admin' || window.location.pathname === '/admin/dashboard', tooltip: 'Dashboard' },
  { label: 'Data Santri', href: '/admin/santri', icon: 'fas fa-users', active: window.location.pathname.startsWith('/admin/santri'), tooltip: 'Data Santri Baru' },
  { label: 'Pembayaran', href: '/admin/pembayaran', icon: 'fas fa-money-bill-wave', active: window.location.pathname.startsWith('/admin/pembayaran'), tooltip: 'Pembayaran Pendaftaran' },
  { label: 'Gelombang', href: '/admin/gelombang', icon: 'fas fa-calendar-alt', active: window.location.pathname.startsWith('/admin/gelombang'), tooltip: 'Gelombang Penerimaan' },
  { label: 'Role & Akses', href: '/admin/roles', icon: 'fas fa-user-tag', active: window.location.pathname.startsWith('/admin/roles'), tooltip: 'Manajemen Role & Akses' },
  { label: 'Pengaturan WhatsApp', href: '/admin/whatsapp/admin', icon: 'fab fa-whatsapp', active: window.location.pathname.startsWith('/admin/whatsapp'), tooltip: 'Pengaturan WhatsApp' }
];
const bottomMenu = [
  menu[0], // Dashboard
  menu[1], // Data Santri
  menu[2], // Pembayaran
];
function itemClass(item) {
  return [
    'flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors duration-200',
    item.active ? 'bg-emerald-50 text-emerald-700 shadow-md' : 'text-gray-700 hover:bg-gray-50 hover:text-emerald-700'
  ];
}
function bottomItemClass(item) {
  return [
    'flex flex-col items-center flex-1 py-2',
    item.active ? 'text-emerald-700 font-bold' : 'text-gray-700 hover:bg-gray-50 hover:text-emerald-700'
  ];
}
function handleProfileUpdated(newUser) {
  if (newUser) {
    user.name = newUser.name;
    user.email = newUser.email;
    user.avatar = newUser.avatar;
  }
  showEditProfile.value = false;
}
async function handleLogout() {
  try {
    await axios.post('/logout');
    window.location.href = '/login';
  } catch (err) {
    alert('Logout gagal, coba lagi!');
    console.error('Logout error:', err);
  }
}
</script>

<style scoped>
.sidebar {
  transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
  background: #fff;
  border-right: 1px solid #e5e7eb;
}
.sidebar a {
  transition: background 0.2s, color 0.2s, transform 0.2s;
}
.sidebar a:hover {
  transform: scale(1.04);
  background: #f0fdf4;
  color: #059669;
}
.sidebar a[aria-current="page"], .sidebar a.bg-emerald-50 {
  font-weight: bold;
  color: #059669;
}
/* Responsive: sidebar hanya di 769px ke atas, bottom bar hanya di 768px ke bawah */
@media (max-width: 768px) {
  .hidden-desktop { display: none !important; }
  .desktop-hidden { display: flex !important; }
}
@media (min-width: 769px) {
  .hidden-desktop { display: flex !important; }
  .desktop-hidden { display: none !important; }
}
.bottom-bar {
  box-shadow: 0 -2px 10px rgba(0,0,0,0.04);
  height: 60px;
}
.user-info-wrapper {
  margin-top: auto;
}
.user-info {
  border-top: 1px solid #e5e7eb;
  padding-top: 1rem;
  padding-bottom: 1rem;
  margin-top: 1rem;
}
.user-info .flex.items-center.ml-2 {
  margin-left: 0.5rem;
}
.user-info .flex.items-center.ml-2.space-x-1 > button {
  width: 32px;
  height: 32px;
  padding: 0;
  display: flex;
  align-items: center;
  justify-content: center;
}
.user-info .flex.items-center.ml-2.space-x-1 {
  opacity: 0;
  transition: opacity 0.2s;
}
.user-info.group:hover .flex.items-center.ml-2.space-x-1 {
  opacity: 1;
}
.user-info .flex.items-center.ml-2.space-x-1 > button i {
  font-size: 1.1rem;
}
</style>
