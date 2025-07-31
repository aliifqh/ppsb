import './bootstrap';
import '../app/js/formulir.js';
import Swal from 'sweetalert2';
import { createApp } from 'vue';
import router from './router';
import GelombangIndex from './pages/admin/gelombang/GelombangIndex.vue';
import Roles from './pages/admin/roles/Roles.vue';
import AdminNav from './layouts/AdminNav.vue';
import UjianIndex from './pages/santri/ujian/UjianIndex.vue';

// Konfigurasi SweetAlert2
window.Swal = Swal;
window.Toast = Swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 3000,
    timerProgressBar: true,
    didOpen: (toast) => {
        toast.addEventListener('click', Swal.close)
    }
});

// Konfigurasi Vue SPA - hanya mount jika element ada
document.addEventListener('DOMContentLoaded', () => {
    const appElement = document.getElementById('app');
    if (appElement) {
        const app = createApp({});
        app.use(router);
        app.mount('#app');
    }

    // Mount gelombang component jika element ada
    const gelombangElement = document.getElementById('gelombang-vue');
    if (gelombangElement) {
        const gelombangApp = createApp(GelombangIndex);
        gelombangApp.use(router);
        gelombangApp.mount('#gelombang-vue');
    }

    // Mount roles component jika element ada
    const rolesElement = document.getElementById('roles-vue');
    if (rolesElement) {
        const rolesApp = createApp(Roles);
        rolesApp.use(router);
        rolesApp.mount('#roles-vue');
    }

    // Mount AdminNav jika element ada
    const adminNavElement = document.getElementById('admin-nav');
    if (adminNavElement) {
        const adminNavApp = createApp(AdminNav);
        adminNavApp.mount('#admin-nav');
    }

    // Mount UjianIndex jika element ada
    const ujianElement = document.getElementById('ujian-vue');
    if (ujianElement) {
        createApp(UjianIndex, {
            santri: JSON.parse(ujianElement.dataset.santri),
            progress: JSON.parse(ujianElement.dataset.progress),
            tesLisanData: JSON.parse(ujianElement.dataset.tesLisan),
            tesTulisData: JSON.parse(ujianElement.dataset.tesTulis),
            tesPsikotesData: JSON.parse(ujianElement.dataset.tesPsikotes),
            tesKesehatanData: JSON.parse(ujianElement.dataset.tesKesehatan),
        }).mount(ujianElement);
    }
});
