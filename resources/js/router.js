import { createRouter, createWebHistory } from 'vue-router';
import Login from './Pages/auth/Login.vue';
import Dashboard from './Pages/admin/Dashboard.vue';
import Pembayaran from './Pages/admin/pembayaran/Pembayaran.vue';
import GelombangIndex from './Pages/admin/gelombang/GelombangIndex.vue';
import RolesPermissions from './Pages/admin/RolesPermissions.vue';
import Roles from './Pages/admin/roles/Roles.vue';
import Santri from './Pages/admin/santri/Santri.vue';
import SantriTrashed from './Pages/admin/santri/SantriTrashed.vue';
import SantriPembayaran from './Pages/Santri/pembayaran/Pembayaran.vue';
import SantriData from './Pages/Santri/data/Data.vue';
import UjianIndex from './Pages/Santri/ujian/UjianIndex.vue';
import Home from './Pages/Home.vue';
import Formulir from './Pages/Formulir.vue';
import SantriLogin from './Pages/auth/LoginSantri.vue';
import AppLayout from './layouts/AppLayout.vue';
import AdminLayout from './layouts/AdminLayout.vue';

const routes = [
  {
    path: '/',
    component: AppLayout,
    children: [
      { path: '', name: 'home', component: Home },
      { path: 'formulir', name: 'formulir', component: Formulir },
      { path: 'login', name: 'login', component: Login },
      { path: 'login-santri', name: 'login-santri', component: SantriLogin },
    ]
  },
  {
    path: '/admin',
    component: AdminLayout,
    children: [
      { path: 'dashboard', name: 'admin.dashboard', component: Dashboard },
      { path: 'pembayaran', name: 'admin.pembayaran', component: Pembayaran },
      { path: 'gelombang', name: 'admin.gelombang', component: GelombangIndex },
      { path: 'roles-permissions', name: 'admin.roles-permissions', component: RolesPermissions },
      { path: 'roles', name: 'admin.roles', component: Roles },
      { path: 'santri', name: 'admin.santri', component: Santri },
      { path: 'santri/trashed', name: 'admin.santri.trashed', component: SantriTrashed },
    ]
  },
  {
    path: '/santri',
    component: AppLayout,
    children: [
      {
        path: 'pembayaran/:nomor_pendaftaran',
        name: 'santri.pembayaran',
        component: SantriPembayaran,
        props: true
      },
      {
        path: 'data/:nomor_pendaftaran',
        name: 'santri.data',
        component: SantriData,
        props: true
      },
      {
        path: 'ujian/:nomor_pendaftaran',
        name: 'santri.ujian',
        component: UjianIndex,
        props: true
      },
    ]
  },
  // Redirect untuk route lama
  { path: '/admin', redirect: '/admin/dashboard' },
  { path: '/santri', redirect: '/santri/data' },
  // Catch all route untuk SPA
  { path: '/:pathMatch(.*)*', redirect: '/' }
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

export default router;
