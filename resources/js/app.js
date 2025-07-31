import './bootstrap';
import '../app/js/formulir.js';
import Swal from 'sweetalert2';
import { createApp } from 'vue';
import router from './router';

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

// Konfigurasi Vue SPA - mount ke element #app
document.addEventListener('DOMContentLoaded', () => {
    const app = createApp({
        template: '<router-view />'
    });
    app.use(router);
    app.mount('#app');
});
