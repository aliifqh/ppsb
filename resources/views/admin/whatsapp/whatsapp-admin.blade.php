@extends('layouts.admin')
@section('title', 'Pengaturan Admin WhatsApp')
@section('content')
<div class="container mx-auto px-4 py-8">
    <x-admin.whatsapp-tab-navigation />
    <h2 class="text-2xl font-bold mb-6 text-gray-800">Pengaturan Admin WhatsApp</h2>
    <div class="bg-white p-6 rounded-lg shadow" x-data="{admins: [
        {name: 'Admin 1', phone: '6281234567890', active: true},
        {name: 'Admin 2', phone: '6289876543210', active: false}
    ]}">
        <table class="min-w-full divide-y divide-gray-200">
            <thead>
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Admin</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nomor WhatsApp</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <template x-for="(admin, idx) in admins" :key="idx">
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap" x-text="admin.name"></td>
                        <td class="px-6 py-4 whitespace-nowrap" x-text="admin.phone"></td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span :class="admin.active ? 'px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800' : 'px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800'" x-text="admin.active ? 'Aktif' : 'Nonaktif'"></span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <button @click="admin.active = !admin.active" class="text-emerald-600 hover:text-emerald-900 mr-3 text-xs">Toggle</button>
                            <button @click="admins.splice(idx,1)" class="text-red-600 hover:text-red-900 text-xs">Hapus</button>
                        </td>
                    </tr>
                </template>
            </tbody>
        </table>
        <div class="mt-6">
            <button @click="admins.push({name: 'Admin Baru', phone: '6280000000000', active: true})" class="px-4 py-2 bg-emerald-600 text-white rounded hover:bg-emerald-700 text-sm">Tambah Admin Dummy</button>
        </div>
    </div>
</div>
@endsection