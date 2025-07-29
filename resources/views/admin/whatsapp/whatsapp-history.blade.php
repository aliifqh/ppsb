@extends('layouts.admin')
@section('title', 'Riwayat Notifikasi WhatsApp')
@section('content')
<div class="container mx-auto px-4 py-8" x-data="{history: [
    {date: '2024-07-01', message: 'Pesan broadcast ke seluruh santri', status: 'Terkirim'},
    {date: '2024-06-30', message: 'Pesan pengingat pembayaran', status: 'Gagal'}
]}">
    <x-admin.whatsapp-tab-navigation />
    <h2 class="text-2xl font-bold mb-6 text-gray-800">Riwayat Notifikasi WhatsApp</h2>
    <div class="bg-white p-6 rounded-lg shadow">
        <table class="min-w-full divide-y divide-gray-200">
            <thead>
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pesan</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <template x-for="item in history" :key="item.date">
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap" x-text="item.date"></td>
                        <td class="px-6 py-4 whitespace-nowrap" x-text="item.message"></td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span :class="item.status === 'Terkirim' ? 'px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800' : 'px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800'" x-text="item.status"></span>
                        </td>
                    </tr>
                </template>
            </tbody>
        </table>
    </div>
</div>
@endsection
