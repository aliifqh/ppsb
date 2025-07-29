document.addEventListener('alpine:init', () => {
    Alpine.store('formData', {
        pekerjaanAyah: '',
        pekerjaanIbu: '',
        pekerjaanAyahLainnya: '',
        pekerjaanIbuLainnya: '',
        alamat: ''
    });

    Alpine.data('formHandler', () => ({
        // State variables
        steps: ['Data Santri', 'Data Orang Tua', 'Lampiran', 'Konfirmasi'],
        currentStep: 1,
        isLoading: false,
        loadingMessage: '',
        formMessage: '',
        formMessageType: 'info',

        // Loading states
        isLoadingProvinsi: false,
        isLoadingKabupaten: false,
        isLoadingKecamatan: false,
        isLoadingDesa: false,

        // Lists for dropdowns
        provinsiList: [],
        kabupatenList: [],
        kecamatanList: [],
        desaList: [],

        // Form data
        formData: {
            // Step 1: Data Santri
            unit: '',
            nama: '',
            nisn: '',
            jenis_kelamin: '',
            tempat_lahir: '',
            tanggal_lahir: '',
            asal_sekolah: '',
            anak_ke: 1,
            jumlah_saudara: 1,

            // Step 2: Data Orang Tua
            nama_ayah: '',
            nama_ibu: '',
            pekerjaan_ayah: '',
            pekerjaan_ibu: '',
            pekerjaan_ayah_lainnya: '',
            pekerjaan_ibu_lainnya: '',
            pendidikan_ayah: '',
            pendidikan_ibu: '',
            wa_ayah: '',
            wa_ibu: '',

            // Alamat
            alamat: '',
            kode_pos: ''
        },

        // Validation state
        errors: {
            nisn: '',
            tanggal_lahir: '',
            sibling: '',
            wa_ayah: '',
            wa_ibu: '',
            alamat: '',
            provinsi: '',
            kabupaten: '',
            kecamatan: '',
            desa: '',
            kode_pos: ''
        },

        // Selected values for wilayah
        selectedProvinsi: '',
        selectedKabupaten: '',
        selectedKecamatan: '',
        selectedDesa: '',

        // File handling
        filePreview: {
            pasfoto: null,
            akta_lahir: null,
            kartu_keluarga: null,
            ijazah: null,
            dokumen_pendukung: []
        },

        // Operator data
        waAyahOperator: '',
        waIbuOperator: '',

        // Form Errors
        formErrors: [],

        init() {
            this.loadProvinsi();
            this.errors = {};
        },

        // Helper function to get field labels
        getFieldLabel(fieldName) {
            const labels = {
                'pekerjaan_ayah': 'Pekerjaan Ayah',
                'pekerjaan_ibu': 'Pekerjaan Ibu',
                'provinsi': 'Provinsi'
            };
            return labels[fieldName] || fieldName;
        },

        // Get formatted pekerjaan
        getPekerjaan(type) {
            if (type === 'ayah') {
                const pekerjaan = this.formData.pekerjaan_ayah;
                if (pekerjaan === 'Lainnya') {
                    return this.formData.pekerjaan_ayah_lainnya || '-';
                }
                return pekerjaan || '-';
            } else if (type === 'ibu') {
                const pekerjaan = this.formData.pekerjaan_ibu;
                if (pekerjaan === 'Lainnya') {
                    return this.formData.pekerjaan_ibu_lainnya || '-';
                }
                return pekerjaan || '-';
            }
            return '-';
        },

        // Get formatted kode pos
        getKodePos() {
            return this.formData.kode_pos || document.getElementById('kode_pos')?.value || '-';
        },

        // Helper Functions
        resetKabupaten() {
            this.selectedKabupaten = '';
            this.kabupatenList = [];
            this.errors.kabupaten = '';
            this.resetKecamatan();
        },

        resetKecamatan() {
            this.selectedKecamatan = '';
            this.kecamatanList = [];
            this.errors.kecamatan = '';
            this.resetDesa();
        },

        resetDesa() {
            this.selectedDesa = '';
            this.desaList = [];
            this.errors.desa = '';
        },

        // Navigation Methods
        nextStep() {
            let isValid = true;

            // Validate current step
            switch(this.currentStep) {
                case 1:
                    isValid = this.validateStep1();
                    break;
                case 2:
                    isValid = this.validateStep2();
                    break;
                case 3:
                    isValid = this.validateStep3();
                    break;
                case 4:
                    isValid = this.validateStep4();
                    break;
            }

            if (isValid && this.currentStep < this.steps.length) {
                this.currentStep++;
                // Gunakan scrollIntoView sebagai alternatif
                document.querySelector('.max-w-4xl').scrollIntoView({ behavior: 'instant' });
            }
        },

        prevStep() {
            if (this.currentStep > 1) {
                this.currentStep--;
                // Gunakan scrollIntoView sebagai alternatif
                document.querySelector('.max-w-4xl').scrollIntoView({ behavior: 'instant' });
            }
        },

        // Validation Methods
        validateCurrentStep() {
            let isValid = true;
            let errors = [];

            try {
            switch(this.currentStep) {
                case 1:
                        isValid = this.validateStep1();
                        break;
                case 2:
                        isValid = this.validateStep2();
                        break;
                case 3:
                        isValid = this.validateStep3();
                        break;
                    case 4:
                        isValid = this.validateStep4();
                        break;
                default:
                        isValid = true;
                }

                if (!isValid) {
                    console.log(`Validation failed at step ${this.currentStep}`);
                }

                return isValid;
            } catch (error) {
                console.error('Validation error:', error);
                this.showMessage('Terjadi kesalahan saat validasi: ' + error.message, 'error');
                return false;
            }
        },

        validateStep1() {
            let isValid = true;
            let errors = [];

            try {
                // Reset errors
                this.errors = {
                    ...this.errors,
                    nisn: '',
                    tanggal_lahir: '',
                    sibling: ''
                };

            // Validate Unit Selection
                if (!this.formData.unit) {
                isValid = false;
                errors.push('Pilihan Unit wajib dipilih');
            }

            // Validate Nama
                if (!this.formData.nama?.trim()) {
                isValid = false;
                errors.push('Nama Lengkap wajib diisi');
            }

            // Validate NISN
            if (!this.validateNISN()) {
                isValid = false;
                    errors.push(this.errors.nisn);
            }

            // Validate Jenis Kelamin
                if (!this.formData.jenis_kelamin) {
                isValid = false;
                errors.push('Jenis Kelamin wajib dipilih');
            }

            // Validate Tempat Lahir
                if (!this.formData.tempat_lahir?.trim()) {
                isValid = false;
                errors.push('Tempat Lahir wajib diisi');
            }

            // Validate Tanggal Lahir
            if (!this.validateTanggalLahir()) {
                isValid = false;
                    errors.push(this.errors.tanggal_lahir);
            }

            // Validate Asal Sekolah
                if (!this.formData.asal_sekolah?.trim()) {
                isValid = false;
                errors.push('Asal Sekolah wajib diisi');
            }

            // Validate Sibling Numbers
            if (!this.validateSiblingNumbers()) {
                isValid = false;
                    errors.push(this.errors.sibling);
            }

            if (!isValid) {
                    this.showMessage('Data tidak lengkap:\n' + errors.join('\n'), 'error');
            }

            return isValid;
            } catch (error) {
                console.error('Error in validateStep1:', error);
                this.showMessage('Terjadi kesalahan saat validasi step 1', 'error');
                return false;
            }
        },

        validateNISN() {
            const nisnRegex = /^\d{10}$/;
            if (!this.formData.nisn) {
                this.errors.nisn = 'NISN wajib diisi';
                return false;
            }
            if (!nisnRegex.test(this.formData.nisn)) {
                this.errors.nisn = 'NISN harus terdiri dari 10 digit angka';
                return false;
            }
            this.errors.nisn = '';
            return true;
        },

        validateTanggalLahir() {
            if (!this.formData.tanggal_lahir) {
                this.errors.tanggal_lahir = 'Tanggal lahir wajib diisi';
                return false;
            }

            // Format yang diharapkan: DD bulan YYYY (contoh: 29 juni 2005)
            const regex = /^(\d{1,2})\s+(januari|februari|maret|april|mei|juni|juli|agustus|september|oktober|november|desember)\s+(\d{4})$/i;
            const match = this.formData.tanggal_lahir.toLowerCase().match(regex);

            if (!match) {
                this.errors.tanggal_lahir = 'Format tanggal lahir tidak valid (contoh: 29 juni 2005)';
                return false;
            }

            const day = parseInt(match[1]);
            const month = this.getMonthNumber(match[2]);
            const year = parseInt(match[3]);
            const date = new Date(year, month - 1, day);

            // Validasi tanggal valid
            if (date.getDate() !== day || date.getMonth() + 1 !== month || date.getFullYear() !== year) {
                this.errors.tanggal_lahir = 'Tanggal tidak valid';
                return false;
            }

            // Validasi rentang usia yang diperbolehkan
            const today = new Date();
            const age = today.getFullYear() - year;
            const isUnitPPIM = this.formData.unit === 'ppim';

            if (isUnitPPIM && (age < 11 || age > 15)) {
                this.errors.tanggal_lahir = 'Usia harus antara 11-15 tahun untuk unit PPIM';
                return false;
            } else if (!isUnitPPIM && (age < 14 || age > 18)) {
                this.errors.tanggal_lahir = 'Usia harus antara 14-18 tahun untuk unit TKS';
                return false;
            }

            this.errors.tanggal_lahir = '';
            return true;
        },

        getMonthNumber(monthName) {
            const months = {
                'januari': 1, 'februari': 2, 'maret': 3, 'april': 4,
                'mei': 5, 'juni': 6, 'juli': 7, 'agustus': 8,
                'september': 9, 'oktober': 10, 'november': 11, 'desember': 12
            };
            return months[monthName.toLowerCase()];
        },

        validateSiblingNumbers() {
            if (!this.formData.anak_ke || !this.formData.jumlah_saudara) {
                this.errors.sibling = 'Data saudara wajib diisi';
                return false;
            }

            const anakKe = parseInt(this.formData.anak_ke);
            const jumlahSaudara = parseInt(this.formData.jumlah_saudara);

            // Validasi nilai minimum
            if (anakKe < 1 || jumlahSaudara < 1) {
                this.errors.sibling = 'Nilai minimal adalah 1';
                return false;
            }

            // Validasi nilai maksimum
            if (jumlahSaudara > 20) {
                this.errors.sibling = 'Jumlah saudara tidak valid (maksimal 20)';
                return false;
            }

            // Validasi anak ke tidak boleh lebih besar dari jumlah saudara
            if (anakKe > jumlahSaudara) {
                this.errors.sibling = 'Anak ke-n tidak boleh lebih besar dari jumlah saudara';
                return false;
            }

            this.errors.sibling = '';
            return true;
        },

        incrementNumber(field) {
            if (field === 'anak_ke') {
                if (this.formData.anak_ke < this.formData.jumlah_saudara) {
                    this.formData.anak_ke++;
                    this.validateSiblingNumbers();
                }
            } else if (field === 'jumlah_saudara') {
                if (this.formData.jumlah_saudara < 20) {
                    this.formData.jumlah_saudara++;
            this.validateSiblingNumbers();
                }
            }
        },

        decrementNumber(field) {
            if (field === 'anak_ke') {
                if (this.formData.anak_ke > 1) {
                    this.formData.anak_ke--;
                    this.validateSiblingNumbers();
                }
            } else if (field === 'jumlah_saudara') {
                if (this.formData.jumlah_saudara > this.formData.anak_ke && this.formData.jumlah_saudara > 1) {
                    this.formData.jumlah_saudara--;
            this.validateSiblingNumbers();
                }
            }
        },

        // Error Handling
        showError(message) {
            // You can implement this using your preferred notification system
            console.error(message);
        },

        // Show message using SweetAlert2
        showMessage(message, type = 'info') {
            Swal.fire({
                title: type === 'error' ? 'Error' : 'Sukses',
                text: message,
                icon: type,
                confirmButtonText: 'OK',
                confirmButtonColor: '#0D9488'
            });
        },

        // Form Submission
        async handleSubmit() {
            try {
                if (!this.validateCurrentStep()) {
                    return;
                }

                this.isLoading = true;
                this.loadingMessage = 'Mengirim formulir...';

                // Create FormData object
                const formData = new FormData();

                // Add form data
                Object.entries(this.formData).forEach(([key, value]) => {
                        if (value !== null && value !== undefined) {
                            formData.append(key, value);
                    }
                });

                // Add selected wilayah
                if (this.selectedProvinsi) formData.append('provinsi_id', this.selectedProvinsi);
                if (this.selectedKabupaten) formData.append('kabupaten_id', this.selectedKabupaten);
                if (this.selectedKecamatan) formData.append('kecamatan_id', this.selectedKecamatan);
                if (this.selectedDesa) formData.append('desa_id', this.selectedDesa);

                // Add file data
                Object.entries(this.filePreview).forEach(([key, value]) => {
                    if (key === 'dokumen_pendukung') {
                        if (Array.isArray(value)) {
                            value.forEach(doc => {
                                if (doc.file) formData.append(`dokumen_pendukung[]`, doc.file);
                            });
                        }
                    } else if (value?.file) {
                        formData.append(key, value.file);
                    }
                });

                // Add CSRF token
                const token = document.querySelector('meta[name="csrf-token"]')?.content;
                if (token) {
                    formData.append('_token', token);
                }

                // Get form action URL
                const formElement = document.querySelector('form');
                const actionUrl = formElement.getAttribute('action');

                // Submit form
                const response = await fetch(actionUrl, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'Accept': 'application/json'
                    }
                });

                const result = await response.json();

                if (!response.ok) {
                    throw new Error(result.message || 'Gagal mengirim formulir');
                }

                // Show success message
                this.showMessage('Formulir berhasil dikirim', 'success');

                // Redirect if URL provided
                if (result.redirect_url) {
                    window.location.href = result.redirect_url;
                }

            } catch (error) {
                console.error('Error submitting form:', error);
                this.showMessage(error.message || 'Terjadi kesalahan saat mengirim formulir', 'error');
            } finally {
                this.isLoading = false;
                this.loadingMessage = '';
            }
        },

        detectOperator(number) {
            // Hapus semua karakter non-digit
            number = number.replace(/\D/g, '');

            // Hapus kode negara jika ada
            if (number.startsWith('62')) {
                number = number.substring(2);
            }

            // Daftar prefix operator
            const operators = {
                Telkomsel: ['0811', '0812', '0813', '0821', '0822', '0823', '0851', '0852', '0853'],
                Indosat: ['0814', '0815', '0816', '0855', '0856', '0857', '0858'],
                XL: ['0817', '0818', '0819', '0859', '0877', '0878', '0879'],
                Tri: ['0895', '0896', '0897', '0898', '0899'],
                Smartfren: ['0881', '0882', '0883', '0884', '0885', '0886', '0887', '0888', '0889'],
                Axis: ['0831', '0832', '0833', '0838']
            };

            // Cek setiap operator
            for (const [operator, prefixes] of Object.entries(operators)) {
                if (prefixes.some(prefix => number.startsWith(prefix.substring(1)))) {
                    return operator;
                }
            }

            return null;
        },

        validateWhatsApp(event, parent) {
            const field = parent === 'ayah' ? 'wa_ayah' : 'wa_ibu';
            const errorField = `${field}Error`;
            const operatorField = `${field}Operator`;

            let value = event.target.value.replace(/\D/g, '');

            // Konversi nomor lokal ke format internasional
            if (value.startsWith('0')) {
                value = '62' + value.substring(1);
            }

            this.formData[field] = value;

            // Validasi dasar
            if (!value) {
                this.errors[field] = 'Nomor WhatsApp wajib diisi';
                this.formData[operatorField] = null;
                return false;
            }

            // Validasi format nomor
            if (!value.startsWith('62')) {
                this.errors[field] = 'Nomor harus diawali dengan 62';
                this.formData[operatorField] = null;
                return false;
            }

            // Validasi panjang nomor
            if (value.length < 11 || value.length > 14) {
                this.errors[field] = 'Panjang nomor tidak valid (11-14 digit)';
                this.formData[operatorField] = null;
                return false;
            }

            // Deteksi operator
            const operator = this.detectOperator(value);
            if (!operator) {
                this.errors[field] = 'Nomor operator tidak valid';
                this.formData[operatorField] = null;
                return false;
            }

            // Format nomor untuk tampilan
            event.target.value = this.formatWhatsApp(value);

            // Set operator dan hapus error
            this.formData[operatorField] = operator;
            this.errors[field] = '';
                return true;
        },

        formatWhatsApp(number) {
            if (!number) return '';

            // Hapus semua karakter non-digit
            number = number.replace(/\D/g, '');

            // Pastikan nomor dimulai dengan 62
            if (number.startsWith('0')) {
                number = '62' + number.substring(1);
            }

            // Format: +62 812-3456-7890
            return number.replace(/(\d{2})(\d{3})(\d{4})(\d{4})/, '+$1 $2-$3-$4');
        },

        // API Calls
        async loadProvinsi() {
            this.isLoadingProvinsi = true;
            this.errors.provinsi = '';

            try {
                const response = await fetch('https://www.emsifa.com/api-wilayah-indonesia/api/provinces.json');
                if (!response.ok) throw new Error('Gagal memuat data provinsi');

                this.provinsiList = await response.json();
            } catch (error) {
                console.error('Error loading provinsi:', error);
                this.provinsiList = [];
                this.errors.provinsi = 'Gagal memuat data provinsi';

                // Show error with retry button
                const result = await Swal.fire({
                    title: 'Gagal Memuat Data',
                    text: 'Terjadi kesalahan saat memuat data provinsi. Silakan coba lagi.',
                    icon: 'error',
                    showCancelButton: true,
                    confirmButtonText: 'Coba Lagi',
                    cancelButtonText: 'Tutup',
                    confirmButtonColor: '#0D9488',
                    cancelButtonColor: '#6B7280'
                });

                if (result.isConfirmed) {
                    await this.loadProvinsi();
                }
            } finally {
                this.isLoadingProvinsi = false;
            }
        },

        async loadKabupaten() {
            if (!this.selectedProvinsi) {
                this.resetKabupaten();
                return;
            }

            this.isLoadingKabupaten = true;
            this.errors.kabupaten = '';

            try {
                const response = await fetch(`https://www.emsifa.com/api-wilayah-indonesia/api/regencies/${this.selectedProvinsi}.json`);
                if (!response.ok) throw new Error('Gagal memuat data kabupaten');

                this.kabupatenList = await response.json();
                this.resetKecamatan();
            } catch (error) {
                console.error('Error loading kabupaten:', error);
                this.kabupatenList = [];
                this.errors.kabupaten = 'Gagal memuat data kabupaten';

                Swal.fire({
                    title: 'Gagal Memuat Data',
                    text: 'Terjadi kesalahan saat memuat data kabupaten. Silakan coba lagi.',
                    icon: 'error',
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#0D9488'
                });
            } finally {
                this.isLoadingKabupaten = false;
            }
        },

        async loadKecamatan() {
            if (!this.selectedKabupaten) {
                this.resetKecamatan();
                return;
            }

            this.isLoadingKecamatan = true;
            this.errors.kecamatan = '';

            try {
                const response = await fetch(`https://www.emsifa.com/api-wilayah-indonesia/api/districts/${this.selectedKabupaten}.json`);
                if (!response.ok) throw new Error('Gagal memuat data kecamatan');

                this.kecamatanList = await response.json();
                this.resetDesa();
            } catch (error) {
                console.error('Error loading kecamatan:', error);
                this.kecamatanList = [];
                this.errors.kecamatan = 'Gagal memuat data kecamatan';

                Swal.fire({
                    title: 'Gagal Memuat Data',
                    text: 'Terjadi kesalahan saat memuat data kecamatan. Silakan coba lagi.',
                    icon: 'error',
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#0D9488'
                });
            } finally {
                this.isLoadingKecamatan = false;
            }
        },

        async loadDesa() {
            if (!this.selectedKecamatan) {
                this.resetDesa();
                return;
            }

            this.isLoadingDesa = true;
            this.errors.desa = '';

            try {
                const response = await fetch(`https://www.emsifa.com/api-wilayah-indonesia/api/villages/${this.selectedKecamatan}.json`);
                if (!response.ok) throw new Error('Gagal memuat data desa');

                this.desaList = await response.json();
            } catch (error) {
                console.error('Error loading desa:', error);
                this.desaList = [];
                this.errors.desa = 'Gagal memuat data desa';

                Swal.fire({
                    title: 'Gagal Memuat Data',
                    text: 'Terjadi kesalahan saat memuat data desa. Silakan coba lagi.',
                    icon: 'error',
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#0D9488'
                });
            } finally {
                this.isLoadingDesa = false;
            }
        },

        validateWilayah() {
            let isValid = true;
            let errors = [];

            // Validate Provinsi
            if (!this.selectedProvinsi) {
                isValid = false;
                this.errors.provinsi = 'Provinsi wajib dipilih';
                errors.push('Provinsi wajib dipilih');
            }

            // Validate Kabupaten
            if (!this.selectedKabupaten) {
                isValid = false;
                this.errors.kabupaten = 'Kabupaten/Kota wajib dipilih';
                errors.push('Kabupaten/Kota wajib dipilih');
            }

            // Validate Kecamatan
            if (!this.selectedKecamatan) {
                isValid = false;
                this.errors.kecamatan = 'Kecamatan wajib dipilih';
                errors.push('Kecamatan wajib dipilih');
            }

            // Validate Desa
            if (!this.selectedDesa) {
                isValid = false;
                this.errors.desa = 'Desa/Kelurahan wajib dipilih';
                errors.push('Desa/Kelurahan wajib dipilih');
            }

            // Show errors if any
            if (!isValid) {
                Swal.fire({
                    title: 'Data Wilayah Tidak Lengkap',
                    html: errors.map(err => `• ${err}`).join('<br>'),
                    icon: 'error',
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#0D9488'
                });
            }

            return isValid;
        },

        validateStep2() {
            let isValid = true;
            let errors = [];

            try {
                // Reset errors
                this.errors = {
                    ...this.errors,
                    wa_ayah: '',
                    wa_ibu: '',
                    alamat: '',
                    kode_pos: '',
                    wilayah: ''
                };

            // Validate Nama Ayah
                if (!this.formData.nama_ayah?.trim()) {
                isValid = false;
                errors.push('Nama Ayah wajib diisi');
            }

            // Validate Pekerjaan Ayah
                if (!this.formData.pekerjaan_ayah) {
                isValid = false;
                errors.push('Pekerjaan Ayah wajib dipilih');
                } else if (this.formData.pekerjaan_ayah === 'Lainnya' && !this.formData.pekerjaan_ayah_lainnya?.trim()) {
                    isValid = false;
                    errors.push('Pekerjaan Ayah Lainnya wajib diisi');
            }

            // Validate Pendidikan Ayah
                if (!this.formData.pendidikan_ayah) {
                isValid = false;
                errors.push('Pendidikan Ayah wajib dipilih');
            }

            // Validate WhatsApp Ayah
                if (!this.formData.wa_ayah) {
                isValid = false;
                    errors.push('Nomor WhatsApp Ayah wajib diisi');
                } else if (this.errors.wa_ayah) {
                    isValid = false;
                    errors.push(this.errors.wa_ayah);
            }

            // Validate Nama Ibu
                if (!this.formData.nama_ibu?.trim()) {
                isValid = false;
                errors.push('Nama Ibu wajib diisi');
            }

            // Validate Pekerjaan Ibu
                if (!this.formData.pekerjaan_ibu) {
                isValid = false;
                errors.push('Pekerjaan Ibu wajib dipilih');
                } else if (this.formData.pekerjaan_ibu === 'Lainnya' && !this.formData.pekerjaan_ibu_lainnya?.trim()) {
                    isValid = false;
                    errors.push('Pekerjaan Ibu Lainnya wajib diisi');
            }

            // Validate Pendidikan Ibu
                if (!this.formData.pendidikan_ibu) {
                isValid = false;
                errors.push('Pendidikan Ibu wajib dipilih');
            }

            // Validate WhatsApp Ibu
                if (!this.formData.wa_ibu) {
                isValid = false;
                    errors.push('Nomor WhatsApp Ibu wajib diisi');
                } else if (this.errors.wa_ibu) {
                    isValid = false;
                    errors.push(this.errors.wa_ibu);
            }

            // Validate Alamat
                if (!this.formData.alamat?.trim()) {
                isValid = false;
                    errors.push('Alamat lengkap wajib diisi');
                } else if (this.formData.alamat.trim().length < 10) {
                    isValid = false;
                    errors.push('Alamat terlalu singkat, mohon lengkapi dengan detail');
            }

                // Validate Wilayah
                if (!this.selectedProvinsi) {
                isValid = false;
                    errors.push('Provinsi wajib dipilih');
                }
                if (!this.selectedKabupaten) {
                isValid = false;
                    errors.push('Kabupaten/Kota wajib dipilih');
                }
                if (!this.selectedKecamatan) {
                    isValid = false;
                    errors.push('Kecamatan wajib dipilih');
                }
                if (!this.selectedDesa) {
                    isValid = false;
                    errors.push('Desa/Kelurahan wajib dipilih');
                }

                // Validate Kode Pos
                if (!this.formData.kode_pos) {
                isValid = false;
                errors.push('Kode Pos wajib diisi');
                } else if (!/^\d{5}$/.test(this.formData.kode_pos)) {
                isValid = false;
                errors.push('Kode Pos harus terdiri dari 5 digit angka');
            }

                if (!isValid) {
                    this.showMessage('Data tidak lengkap:\n' + errors.join('\n'), 'error');
            }

            return isValid;
            } catch (error) {
                console.error('Error in validateStep2:', error);
                this.showMessage('Terjadi kesalahan saat validasi step 2', 'error');
                return false;
            }
        },

        validateStep3() {
            let isValid = true;
            let errors = [];

            try {
                // Reset errors
                this.errors = {
                    ...this.errors,
                    files: ''
                };

            // Validate required files
                const requiredFiles = [
                    { key: 'pasfoto', label: 'Pas Foto' },
                    { key: 'akta_lahir', label: 'Akta Kelahiran' },
                    { key: 'kartu_keluarga', label: 'Kartu Keluarga' },
                    { key: 'ijazah', label: 'Ijazah/Raport' }
                ];

                requiredFiles.forEach(({ key, label }) => {
                    if (!this.filePreview[key]?.file) {
                    isValid = false;
                    errors.push(`${label} wajib diunggah`);
                }
            });

            if (!isValid) {
                    this.showMessage('Dokumen tidak lengkap:\n' + errors.join('\n'), 'error');
            }

            return isValid;
            } catch (error) {
                console.error('Error in validateStep3:', error);
                this.showMessage('Terjadi kesalahan saat validasi step 3', 'error');
                return false;
            }
        },

        validateStep4() {
            let isValid = true;
            let errors = [];

            try {
                // Reset errors
                this.errors = {
                    ...this.errors,
                    confirmation: ''
                };

                // Validate all previous steps
                if (!this.validateStep1() || !this.validateStep2() || !this.validateStep3()) {
                isValid = false;
                    errors.push('Mohon lengkapi semua data pada step sebelumnya');
            }

            if (!isValid) {
                    this.showMessage('Validasi gagal:\n' + errors.join('\n'), 'error');
            }

            return isValid;
            } catch (error) {
                console.error('Error in validateStep4:', error);
                this.showMessage('Terjadi kesalahan saat validasi step 4', 'error');
                return false;
            }
        },

        // Update form data when input changes
        updateFormData(field, value) {
            this.formData[field] = value;
        },

        validateAlamat(event) {
            const alamat = event.target.value;

            // Reset error
            delete this.errors.alamat;

            // Validasi panjang minimal
            if (alamat.length < 10) {
                this.errors.alamat = 'Alamat harus diisi minimal 10 karakter';
                return;
            }

            // Validasi karakter yang diperbolehkan
            const validChars = /^[a-zA-Z0-9\s.,\-/'()]+$/;
            if (!validChars.test(alamat)) {
                this.errors.alamat = 'Alamat hanya boleh mengandung huruf, angka, spasi, dan tanda baca umum';
                return;
            }

            // Validasi kata-kata yang masuk akal
            const words = alamat.split(/\s+/);
            if (words.length < 3) {
                this.errors.alamat = 'Alamat harus terdiri dari minimal 3 kata';
                return;
            }

            // Validasi panjang maksimal
            if (alamat.length > 255) {
                this.errors.alamat = 'Alamat tidak boleh lebih dari 255 karakter';
                return;
            }
        },

        validateKodePos(event) {
            const kodePos = event.target.value;

            // Reset error
            delete this.errors.kode_pos;

            // Validasi hanya angka
            if (!/^\d*$/.test(kodePos)) {
                this.errors.kode_pos = 'Kode pos hanya boleh berisi angka';
                return;
            }

            // Validasi panjang tepat 5 digit
            if (kodePos.length > 0 && kodePos.length !== 5) {
                this.errors.kode_pos = 'Kode pos harus terdiri dari 5 digit angka';
                return;
            }

            // Validasi tidak boleh 00000
            if (kodePos === '00000') {
                this.errors.kode_pos = 'Kode pos tidak valid';
                return;
            }
        },

        handleFileUpload(event, fileType) {
            try {
                const file = event.target.files[0];
                if (!file) return;

                // Reset error
                this.errors.files = '';

                // Validate file size (max 3MB)
                const maxSize = 3 * 1024 * 1024; // 3MB in bytes
                if (file.size > maxSize) {
                    this.errors.files = `Ukuran file ${file.name} terlalu besar (maksimal 3MB)`;
                    event.target.value = '';
                    this.showMessage(this.errors.files, 'error');
                    return;
                }

                // Validate file type
                const allowedTypes = {
                    pasfoto: ['image/jpeg', 'image/png'],
                    akta_lahir: ['image/jpeg', 'image/png', 'application/pdf'],
                    kartu_keluarga: ['image/jpeg', 'image/png', 'application/pdf'],
                    ijazah: ['image/jpeg', 'image/png', 'application/pdf'],
                    dokumen_pendukung: ['image/jpeg', 'image/png', 'application/pdf']
                };

                if (!allowedTypes[fileType]?.includes(file.type)) {
                    const allowedExt = allowedTypes[fileType].map(type =>
                        type.replace('image/', '').replace('application/', '').toUpperCase()
                    ).join(', ');
                    this.errors.files = `Format file tidak valid. Format yang diizinkan: ${allowedExt}`;
                    event.target.value = '';
                    this.showMessage(this.errors.files, 'error');
                    return;
                }

                // Create file preview
                this.createFilePreview(file, fileType);
            } catch (error) {
                console.error('Error handling file upload:', error);
                this.showMessage('Terjadi kesalahan saat mengunggah file', 'error');
            }
        },

        handleDokumenPendukung(event) {
            try {
                const files = Array.from(event.target.files);
                if (!files.length) return;

                // Reset error
                this.errors.files = '';

                // Validate number of files
                if (files.length > 5) {
                    this.errors.files = 'Maksimal 5 dokumen pendukung yang dapat diunggah';
                    event.target.value = '';
                    this.showMessage(this.errors.files, 'error');
                    return;
                }

                // Validate each file
                const maxSize = 3 * 1024 * 1024; // 3MB in bytes
                const allowedTypes = ['image/jpeg', 'image/png', 'application/pdf'];

                const invalidFiles = files.filter(file => {
                    if (file.size > maxSize) {
                        this.errors.files = `Ukuran file ${file.name} terlalu besar (maksimal 3MB)`;
                        return true;
                    }
                    if (!allowedTypes.includes(file.type)) {
                        this.errors.files = `Format file ${file.name} tidak valid (JPG/PNG/PDF)`;
                        return true;
                    }
                    return false;
                });

                if (invalidFiles.length) {
                    event.target.value = '';
                    this.showMessage(this.errors.files, 'error');
                    return;
                }

                // Process valid files
                files.forEach(file => this.createFilePreview(file, 'dokumen_pendukung'));
            } catch (error) {
                console.error('Error handling dokumen pendukung:', error);
                this.showMessage('Terjadi kesalahan saat mengunggah dokumen pendukung', 'error');
            }
        },

        createFilePreview(file, fileType) {
            const reader = new FileReader();
            reader.onload = (e) => {
                const preview = {
                    name: file.name,
                    type: file.type.startsWith('image/') ? 'image' : 'pdf',
                    url: file.type.startsWith('image/') ? e.target.result : null,
                    file: file
                };

                if (fileType === 'dokumen_pendukung') {
                    this.filePreview.dokumen_pendukung.push(preview);
                } else {
                    this.filePreview[fileType] = preview;
                }
            };
            reader.readAsDataURL(file);
        },

        removeFile(fileType) {
            this.filePreview[fileType] = null;
            // Reset file input
            const fileInput = document.querySelector(`input[name="${fileType}"]`);
            if (fileInput) fileInput.value = '';
        },

        removeDokumenPendukung(index) {
            this.filePreview.dokumen_pendukung.splice(index, 1);
        },
    }));
});

