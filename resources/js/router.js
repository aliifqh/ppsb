import { createRouter, createWebHistory } from 'vue-router';
import Login from './pages/auth/Login.vue';
import Dashboard from './pages/admin/Dashboard.vue';
import Pembayaran from './pages/admin/pembayaran/Pembayaran.vue';
import GelombangIndex from './pages/admin/gelombang/GelombangIndex.vue';
import RolesPermissions from './pages/admin/RolesPermissions.vue';
import Roles from './pages/admin/roles/Roles.vue';
import Santri from './pages/admin/santri/Santri.vue';
import SantriTrashed from './pages/admin/santri/SantriTrashed.vue';
import SantriPembayaran from './pages/santri/pembayaran/Pembayaran.vue';
import SantriData from './pages/santri/data/Data.vue';
import Home from './pages/Home.vue';
import Formulir from './pages/Formulir.vue';
import SantriLogin from './Pages/auth/LoginSantri.vue';

const routes = [
  { path: '/', name: 'home', component: Home },
  { path: '/formulir', name: 'formulir', component: Formulir },
  {
    path: '/login',
    name: 'login',
    component: Login,
  },
  {
    path: '/login-santri',
    name: 'login-santri',
    component: SantriLogin,
  },
  {
    path: '/admin/dashboard',
    name: 'admin.dashboard',
    component: Dashboard,
  },
  {
    path: '/admin/pembayaran',
    name: 'admin.pembayaran',
    component: Pembayaran,
  },
  {
    path: '/admin/gelombang',
    name: 'admin.gelombang',
    component: GelombangIndex,
  },
  {
    path: '/admin/roles-permissions',
    name: 'admin.roles-permissions',
    component: RolesPermissions,
  },
  {
    path: '/admin/roles',
    name: 'admin.roles',
    component: Roles,
  },
  {
    path: '/admin/santri',
    name: 'admin.santri',
    component: Santri,
  },
  {
    path: '/admin/santri/trashed',
    name: 'admin.santri.trashed',
    component: SantriTrashed,
  },
  {
    path: '/santri/pembayaran/:nomor_pendaftaran',
    name: 'santri.pembayaran',
    component: SantriPembayaran,
    props: true
  },
  {
    path: '/santri/data/:nomor_pendaftaran',
    name: 'santri.data',
    component: SantriData,
  }
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

export default router;
