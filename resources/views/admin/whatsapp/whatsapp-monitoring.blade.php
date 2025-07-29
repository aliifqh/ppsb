@extends('layouts.admin')
@section('title', 'Monitoring WhatsApp')
@section('content')
<div class="container mx-auto px-4 py-8">
    <x-admin.whatsapp-tab-navigation />
    <h2 class="text-2xl font-bold mb-6 text-gray-800">Monitoring WhatsApp</h2>
    <div class="bg-white p-6 rounded-lg shadow" x-data="{logs: [
        {time: '2024-07-01 10:00', message: 'Pesan broadcast ke seluruh santri', status: 'Terkirim'},
        {time: '2024-07-01 09:30', message: 'Pesan pengingat pembayaran', status: 'Tertunda'}
    ]}">
        <table class="min-w-full divide-y divide-gray-200">
            <thead>
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Waktu</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pesan</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <template x-for="log in logs" :key="log.time">
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap" x-text="log.time"></td>
                        <td class="px-6 py-4 whitespace-nowrap" x-text="log.message"></td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span :class="log.status === 'Terkirim' ? 'px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800' : 'px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800'" x-text="log.status"></span>
                        </td>
                    </tr>
                </template>
            </tbody>
        </table>
    </div>
</div>
@endsection