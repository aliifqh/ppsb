@extends('layouts.admin')
@section('title', 'Broadcast WhatsApp')
@section('content')
<div class="container mx-auto px-4 py-8" x-data="{pesan: '', alert: null}">
    <x-admin.whatsapp-tab-navigation />
    <h2 class="text-2xl font-bold mb-6 text-gray-800">Broadcast WhatsApp</h2>
    <div class="bg-white p-6 rounded-lg shadow">
        <form @submit.prevent="alert = 'Pesan broadcast berhasil dikirim (dummy)!'; pesan = ''">
            <div class="mb-4">
                <label for="pesan" class="block text-sm font-medium text-gray-700 mb-2">Pesan Broadcast</label>
                <textarea id="pesan" name="pesan" rows="4" class="w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500" placeholder="Tulis pesan di sini..." x-model="pesan"></textarea>
            </div>
            <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-emerald-600 hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500">Kirim Broadcast (Dummy)</button>
        </form>
        <div class="mt-4" x-show="alert">
            <span class="text-green-700"><i class="fas fa-check-circle mr-2"></i> <span x-text="alert"></span></span>
        </div>
    </div>
</div>
@endsection
