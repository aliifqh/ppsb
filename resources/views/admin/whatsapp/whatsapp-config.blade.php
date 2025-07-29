@extends('layouts.admin')
@section('title', 'Pengaturan WhatsApp')
@section('header', 'Pengaturan WhatsApp')

@section('content')
<div class="container mx-auto px-4 py-8">
    <x-admin.card>
        <h2 class="text-2xl font-bold mb-6 text-gray-800">Pengaturan Notifikasi WhatsApp</h2>
        
        <!-- Tab Navigation -->
        <x-admin.whatsapp-tab-navigation />
        
        <!-- Konfigurasi API Section -->
        <form action="#" method="POST" x-data="{
            isActive: true,
            provider: 'fonnte',
            connectionStatus: null,
            testSendStatus: null,
            showProviderConfig(provider) {
                let configs = ['fonnte','wablas','woowa','dripsender','watzap','whacenter','whapi'];
                configs.forEach(id => {
                    let el = document.getElementById(id + '_config');
                    if (el) el.classList.add('hidden');
                });
                let showEl = document.getElementById(provider + '_config');
                if (showEl) showEl.classList.remove('hidden');
            }
        }" x-init="showProviderConfig(provider)">
            <div class="space-y-6">
                @if(session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert">
                    <p>{{ session('success') }}</p>
                </div>
                @endif

                @if($errors->any())
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4" role="alert">
                    <p class="font-bold">Terjadi kesalahan:</p>
                    <ul>
                        @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <!-- Status Aktif -->
                <div class="bg-gray-50 p-4 rounded-lg">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-semibold">Status Notifikasi WhatsApp</h3>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" class="sr-only peer" x-model="isActive">
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-emerald-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-emerald-600"></div>
                            <span class="ml-3 text-sm font-medium text-gray-700" x-text="isActive ? 'Aktif' : 'Nonaktif'"></span>
                        </label>
                    </div>
                </div>

                <!-- Pilih Penyedia Layanan -->
                <div class="bg-gray-50 p-4 rounded-lg">
                    <h3 class="text-lg font-semibold mb-4">Penyedia Layanan WhatsApp</h3>
                    <div class="space-y-4">
                        <div>
                            <label for="provider" class="block text-sm font-medium text-gray-700 mb-2">Pilih Penyedia</label>
                            <select id="provider" name="provider" class="w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500" x-model="provider" @change="showProviderConfig(provider)">
                                <option value="">-- Pilih Penyedia Layanan --</option>
                                <option value="fonnte">Fonnte</option>
                                <option value="wablas">Wablas</option>
                                <option value="woowa">Woowa</option>
                                <option value="dripsender">Dripsender</option>
                                <option value="watzap">Watzap</option>
                                <option value="whacenter">Whacenter</option>
                                <option value="whapi">Whapi</option>
                                <option value="ultramsg">Ultramsg</option>
                                <option value="twilio">Twilio</option>
                                <option value="whatsapp_business">WhatsApp Business API</option>
                                <option value="other">Lainnya</option>
                            </select>
                        </div>
                    </div>
                </div>
                
                <!-- Konfigurasi Fonnte (dummy, tetap tampil jika provider dipilih) -->
                <div id="fonnte_config" class="bg-gray-50 p-4 rounded-lg">
                    <h3 class="text-lg font-semibold mb-4">Konfigurasi Fonnte</h3>
                    <div class="space-y-4">
                        <div>
                            <label for="fonnte_token" class="block text-sm font-medium text-gray-700 mb-2">API Token</label>
                            <input type="text" id="fonnte_token" name="fonnte_token" value="dummy_token" class="w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500" placeholder="Masukkan API Token dari Fonnte">
                            <p class="mt-1 text-sm text-gray-500">Token dapat diperoleh dari dashboard Fonnte</p>
                        </div>
                        <div>
                            <label for="fonnte_device" class="block text-sm font-medium text-gray-700 mb-2">Device ID (opsional)</label>
                            <input type="text" id="fonnte_device" name="fonnte_device" value="dummy_device" class="w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500" placeholder="ID Perangkat (jika menggunakan multi device)">
                        </div>
                    </div>
                </div>
                
                <!-- Konfigurasi Wablas (ditampilkan jika provider Wablas dipilih) -->
                <div id="wablas_config" class="bg-gray-50 p-4 rounded-lg hidden">
                    <h3 class="text-lg font-semibold mb-4">Konfigurasi Wablas</h3>
                    <div class="space-y-4">
                        <div>
                            <label for="wablas_token" class="block text-sm font-medium text-gray-700 mb-2">API Token</label>
                            <input type="text" id="wablas_token" name="wablas_token" value="dummy_token" class="w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500" placeholder="Masukkan API Token dari Wablas">
                        </div>
                        <div>
                            <label for="wablas_domain" class="block text-sm font-medium text-gray-700 mb-2">Domain API</label>
                            <div class="flex">
                                <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500">https://</span>
                                <input type="text" id="wablas_domain" name="wablas_domain" value="dummy_domain.wablas.com" class="flex-1 min-w-0 block w-full px-3 py-2 rounded-none rounded-r-md border-gray-300 focus:border-emerald-500 focus:ring-emerald-500" placeholder="domain-anda.wablas.com">
                            </div>
                            <p class="mt-1 text-sm text-gray-500">Contoh: pesantren.wablas.com</p>
                        </div>
                    </div>
                </div>
                
                <!-- Konfigurasi Woowa (ditampilkan jika provider Woowa dipilih) -->
                <div id="woowa_config" class="bg-gray-50 p-4 rounded-lg hidden">
                    <h3 class="text-lg font-semibold mb-4">Konfigurasi Woowa</h3>
                    <div class="space-y-4">
                        <div>
                            <label for="woowa_api_key" class="block text-sm font-medium text-gray-700 mb-2">API Key</label>
                            <input type="text" id="woowa_api_key" name="woowa_api_key" value="dummy_key" class="w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500" placeholder="Masukkan API Key dari Woowa">
                        </div>
                        <div>
                            <label for="woowa_instance_id" class="block text-sm font-medium text-gray-700 mb-2">Instance ID</label>
                            <input type="text" id="woowa_instance_id" name="woowa_instance_id" value="dummy_instance" class="w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500" placeholder="Masukkan Instance ID">
                        </div>
                    </div>
                </div>
                
                <!-- Konfigurasi Dripsender (ditampilkan jika provider Dripsender dipilih) -->
                <div id="dripsender_config" class="bg-gray-50 p-4 rounded-lg hidden">
                    <h3 class="text-lg font-semibold mb-4">Konfigurasi Dripsender</h3>
                    <div class="space-y-4">
                        <div>
                            <label for="dripsender_token" class="block text-sm font-medium text-gray-700 mb-2">API Token</label>
                            <input type="text" id="dripsender_token" name="dripsender_token" value="dummy_token" class="w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500" placeholder="Masukkan API Token dari Dripsender">
                        </div>
                        <div>
                            <label for="dripsender_endpoint" class="block text-sm font-medium text-gray-700 mb-2">API Endpoint</label>
                            <input type="text" id="dripsender_endpoint" name="dripsender_endpoint" value="https://api.dripsender.id" class="w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500" placeholder="https://api.dripsender.id">
                            <p class="mt-1 text-sm text-gray-500">Endpoint default: https://api.dripsender.id</p>
                        </div>
                    </div>
                </div>
                
                <!-- Konfigurasi Watzap (ditampilkan jika provider Watzap dipilih) -->
                <div id="watzap_config" class="bg-gray-50 p-4 rounded-lg hidden">
                    <h3 class="text-lg font-semibold mb-4">Konfigurasi Watzap</h3>
                    <div class="space-y-4">
                        <div>
                            <label for="watzap_api_key" class="block text-sm font-medium text-gray-700 mb-2">API Key</label>
                            <input type="text" id="watzap_api_key" name="watzap_api_key" value="dummy_key" class="w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500" placeholder="Masukkan API Key dari Watzap">
                        </div>
                        <div>
                            <label for="watzap_device_id" class="block text-sm font-medium text-gray-700 mb-2">Device ID</label>
                            <input type="text" id="watzap_device_id" name="watzap_device_id" value="dummy_device" class="w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500" placeholder="Masukkan Device ID">
                        </div>
                    </div>
                </div>
                
                <!-- Konfigurasi Whacenter (ditampilkan jika provider Whacenter dipilih) -->
                <div id="whacenter_config" class="bg-gray-50 p-4 rounded-lg hidden">
                    <h3 class="text-lg font-semibold mb-4">Konfigurasi Whacenter</h3>
                    <div class="space-y-4">
                        <div>
                            <label for="whacenter_api_key" class="block text-sm font-medium text-gray-700 mb-2">API Key</label>
                            <input type="text" id="whacenter_api_key" name="whacenter_api_key" value="dummy_key" class="w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500" placeholder="Masukkan API Key dari Whacenter">
                        </div>
                        <div>
                            <label for="whacenter_device" class="block text-sm font-medium text-gray-700 mb-2">Nama Device</label>
                            <input type="text" id="whacenter_device" name="whacenter_device" value="dummy_device" class="w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500" placeholder="Masukkan nama device">
                        </div>
                    </div>
                </div>
                
                <!-- Konfigurasi Whapi (ditampilkan jika provider Whapi dipilih) -->
                <div id="whapi_config" class="bg-gray-50 p-4 rounded-lg hidden">
                    <h3 class="text-lg font-semibold mb-4">Konfigurasi Whapi</h3>
                    <div class="space-y-4">
                        <div>
                            <label for="whapi_token" class="block text-sm font-medium text-gray-700 mb-2">API Token</label>
                            <input type="text" id="whapi_token" name="whapi_token" value="dummy_token" class="w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500" placeholder="Masukkan API Token dari Whapi">
                        </div>
                        <div>
                            <label for="whapi_instance" class="block text-sm font-medium text-gray-700 mb-2">Instance ID</label>
                            <input type="text" id="whapi_instance" name="whapi_instance" value="dummy_instance" class="w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500" placeholder="Masukkan Instance ID">
                        </div>
                    </div>
                </div>
                
                <!-- Status Koneksi -->
                <div class="bg-gray-50 p-4 rounded-lg">
                    <h3 class="text-lg font-semibold mb-4">Status Koneksi</h3>
                    <div class="flex items-center space-x-4">
                        <div class="flex items-center">
                            <div class="w-3 h-3 rounded-full" :class="isActive ? 'bg-green-500' : 'bg-red-500'" mr-2></div>
                            <span class="text-sm text-gray-700" x-text="isActive ? 'Terhubung' : 'Tidak Terhubung'"></span>
                        </div>
                        <button type="button" @click="connectionStatus = (Math.random() > 0.5 ? 'success' : 'fail')" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-emerald-600 hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500">
                            <i class="fas fa-sync-alt mr-2"></i> Uji Koneksi
                        </button>
                    </div>
                    <div class="mt-4" x-show="connectionStatus">
                        <div :class="connectionStatus === 'success' ? 'p-3 bg-green-100 text-green-700 rounded-md' : 'p-3 bg-red-100 text-red-700 rounded-md'">
                            <div class="flex">
                                <i :class="connectionStatus === 'success' ? 'fas fa-check-circle mr-2 mt-0.5' : 'fas fa-exclamation-circle mr-2 mt-0.5'"></i>
                                <div>
                                    <p class="font-medium" x-text="connectionStatus === 'success' ? 'Koneksi berhasil' : 'Koneksi gagal'"></p>
                                    <p class="text-sm" x-text="connectionStatus === 'success' ? 'Token valid dan layanan tersedia.' : 'Token tidak valid atau layanan tidak tersedia.'"></p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Form Uji Kirim Pesan WhatsApp -->
                    <div class="mt-6">
                        <form @submit.prevent="testSendStatus = (Math.random() > 0.5 ? 'success' : 'fail')" class="flex flex-col md:flex-row md:items-center space-y-2 md:space-y-0 md:space-x-4">
                            <input type="text" id="test_wa_number" name="test_wa_number" class="w-full md:w-64 rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500" placeholder="Masukkan nomor WhatsApp (cth: 6281234567890)" required>
                            <button type="submit" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                <i class="fas fa-paper-plane mr-2"></i> Uji Kirim Pesan
                            </button>
                        </form>
                        <div class="mt-3" x-show="testSendStatus">
                            <span x-show="testSendStatus === 'success'" class="text-green-700"><i class="fas fa-check-circle mr-2"></i>Pesan berhasil dikirim!</span>
                            <span x-show="testSendStatus === 'fail'" class="text-red-700"><i class="fas fa-exclamation-circle mr-2"></i>Gagal mengirim pesan!</span>
                        </div>
                    </div>
                </div>
                
                <!-- Pengaturan Default -->
                <div class="bg-gray-50 p-4 rounded-lg">
                    <h3 class="text-lg font-semibold mb-4">Pengaturan Default</h3>
                    <div class="space-y-4">
                        <div>
                            <label for="default_sender" class="block text-sm font-medium text-gray-700 mb-2">Nomor Pengirim Default</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                    <span class="text-gray-500">+62</span>
                                </div>
                                <input type="tel" id="default_sender" name="default_sender" value="8123456789" class="w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 pl-12" placeholder="8123456789">
                            </div>
                            <p class="mt-1 text-sm text-gray-500">Nomor yang digunakan untuk mengirim pesan WhatsApp</p>
                        </div>
                        <div>
                            <label for="sender_name" class="block text-sm font-medium text-gray-700 mb-2">Nama Pengirim</label>
                            <input type="text" id="sender_name" name="sender_name" value="Panitia PSB" class="w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500" placeholder="Panitia PSB">
                        </div>
                        <div>
                            <div class="flex items-center">
                                <input id="enable_read_receipt" name="enable_read_receipt" type="checkbox" checked class="h-4 w-4 text-emerald-600 border-gray-300 rounded focus:ring-emerald-500">
                                <label for="enable_read_receipt" class="ml-2 block text-sm text-gray-700">Aktifkan notifikasi terbaca</label>
                            </div>
                            <p class="mt-1 text-sm text-gray-500 ml-6">Menerima notifikasi saat pesan dibaca oleh penerima</p>
                        </div>
                    </div>
                </div>
                
                <!-- Batas Pengiriman -->
                <div class="bg-gray-50 p-4 rounded-lg">
                    <h3 class="text-lg font-semibold mb-4">Batas Pengiriman</h3>
                    <div class="space-y-4">
                        <div>
                            <label for="daily_limit" class="block text-sm font-medium text-gray-700 mb-2">Batas Harian</label>
                            <input type="number" id="daily_limit" name="daily_limit" value="1000" class="w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500" placeholder="1000">
                            <p class="mt-1 text-sm text-gray-500">Jumlah maksimum pesan yang dapat dikirim per hari</p>
                        </div>
                        <div>
                            <label for="queue_limit" class="block text-sm font-medium text-gray-700 mb-2">Batas Antrian</label>
                            <input type="number" id="queue_limit" name="queue_limit" value="100" class="w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500" placeholder="100">
                            <p class="mt-1 text-sm text-gray-500">Jumlah maksimum pesan dalam antrian pada satu waktu</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Action Buttons -->
            <div class="mt-6 flex justify-end space-x-4">
                <a href="/admin/whatsapp/config" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500">
                    Batal
                </a>
                <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-emerald-600 hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500" @click.prevent="alert('Konfigurasi disimpan (dummy)!')">
                    Simpan Konfigurasi
                </button>
            </div>
        </form>
    </x-admin.card>
</div>
@endsection 
