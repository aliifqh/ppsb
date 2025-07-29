import { createRouter, createWebHistory } from 'vue-router';
import Login from './components/auth/Login.vue';
import Dashboard from './components/admin/Dashboard.vue';
import Pembayaran from './components/admin/pembayaran/Pembayaran.vue';
import GelombangIndex from './components/admin/gelombang/GelombangIndex.vue';
import RolesPermissions from './components/admin/RolesPermissions.vue';
import Roles from './components/admin/roles/Roles.vue';
import Santri from './components/admin/santri/Santri.vue';
import SantriTrashed from './components/admin/santri/SantriTrashed.vue';
import SantriPembayaran from './components/santri/pembayaran/Pembayaran.vue';
import SantriData from './components/santri/data/Data.vue';

const routes = [
  {
    path: '/login',
    name: 'login',
    component: Login,
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
