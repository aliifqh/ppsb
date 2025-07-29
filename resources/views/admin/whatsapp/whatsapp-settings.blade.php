@extends('layouts.admin')
@section('title', 'Pengaturan WhatsApp')
@section('content')
<div class="container mx-auto px-4 py-8" x-data="{provider: 'Fonnte', status: 'Aktif', sender: '6281234567890'}">
    <x-admin.whatsapp-tab-navigation />
    <h2 class="text-2xl font-bold mb-6 text-gray-800">Pengaturan WhatsApp (Dummy)</h2>
    <div class="bg-white p-6 rounded-lg shadow">
        <p>Contoh pengaturan WhatsApp statis. Semua data di sini hanya dummy dan tidak terhubung ke backend.</p>
        <ul class="list-disc pl-6 mt-4">
            <li>Provider: <span x-text="provider"></span></li>
            <li>Status: <span x-text="status"></span></li>
            <li>Default Sender: <span x-text="sender"></span></li>
        </ul>
    </div>
</div>
@endsection
