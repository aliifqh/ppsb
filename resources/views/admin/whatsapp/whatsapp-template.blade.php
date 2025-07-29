@extends('layouts.admin')
@section('title', 'Template Pesan WhatsApp')
@section('content')
<div class="container mx-auto px-4 py-8" x-data="{selected: 0, templates: [
    {title: 'Template 1', content: 'Selamat, Anda telah terdaftar!'},
    {title: 'Template 2', content: 'Mohon segera melakukan pembayaran.'},
    {title: 'Template 3', content: 'Jadwal ujian Anda: Senin, 10 Juli 2024.'}
]}" >
    <x-admin.whatsapp-tab-navigation />
    <h2 class="text-2xl font-bold mb-6 text-gray-800">Template Pesan WhatsApp</h2>
    <div class="bg-white p-6 rounded-lg shadow">
        <div class="mb-4 flex gap-2">
            <template x-for="(tpl, idx) in templates" :key="idx">
                <button @click="selected = idx" :class="selected === idx ? 'bg-emerald-600 text-white' : 'bg-gray-100 text-gray-700'" class="px-3 py-1 rounded text-sm">{{ tpl.title }}</button>
            </template>
        </div>
        <div class="mt-4">
            <div class="font-mono text-lg" x-text="templates[selected].content"></div>
        </div>
    </div>
</div>
@endsection
