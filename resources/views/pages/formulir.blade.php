@extends('layouts.main')
@section('title', 'Profil Pondok Pesantren')
@section('content')

<div class="max-w-4xl mx-auto py-8">
    @if($gelombang)
<div class="bg-white rounded-lg shadow-md p-6"
    x-data="formHandler"
    x-init="init()"
    x-cloak
>
    <!-- Loading Overlay -->
    <div x-show="isLoading"
        class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center">
        <div class="bg-white p-6 rounded-lg shadow-xl flex items-center space-x-4">
            <div class="animate-spin rounded-full h-8 w-8 border-t-2 border-b-2 border-teal-600"></div>
            <span class="text-lg text-teal-700" x-text="loadingMessage || 'Memproses...'"></span>
        </div>
    </div>

    <div class="max-w-4xl mx-auto">
        <h1 class="text-3xl font-bold text-center text-teal-700 mb-8">Formulir Pendaftaran</h1>

        <!-- Form Status Message -->
        <div x-show="formMessage"
            :class="{
                'bg-red-50 text-red-700 border-red-200': formMessageType === 'error',
                'bg-yellow-50 text-yellow-700 border-yellow-200': formMessageType === 'warning',
                'bg-green-50 text-green-700 border-green-200': formMessageType === 'success',
                'bg-blue-50 text-blue-700 border-blue-200': formMessageType === 'info'
            }"
            class="mb-6 p-4 rounded-lg border"
            x-text="formMessage">
        </div>

        <!-- Progress Steps -->
        <div class="bg-white rounded-xl shadow-sm overflow-hidden mb-8">
            <div class="p-6 bg-gradient-to-br from-teal-50/40 to-white">
                <div class="max-w-3xl mx-auto">
                    <div class="relative">
                        <!-- Progress Line Background -->
                        <div class="absolute top-5 left-0 w-full h-1 bg-gradient-to-r from-gray-200/60 to-gray-200/80 rounded-full"></div>

                        <!-- Active Progress Line -->
                        <div
                            class="absolute top-5 left-0 h-1 bg-gradient-to-r from-teal-500 to-teal-400 rounded-full"
                            :style="'width: ' + (((currentStep - 1) / (steps.length - 1)) * 100) + '%'"
                            style="will-change: width;"
                        ></div>

                        <!-- Steps -->
                        <div class="relative flex justify-between">
                            <template x-for="(step, index) in steps" :key="index">
                                <div class="flex flex-col items-center flex-1">
                                    <!-- Step Circle -->
                                    <div
                                        class="w-10 h-10 rounded-full flex items-center justify-center mb-2"
                                        :class="{
                                            'bg-gradient-to-br from-teal-500 to-teal-400 text-white': currentStep > index + 1,
                                            'bg-gradient-to-br from-teal-600 to-teal-500 text-white ring-4 ring-teal-100': currentStep === index + 1,
                                            'bg-white border-2 border-gray-200 text-gray-400': currentStep < index + 1
                                        }"
                                        style="will-change: transform;"
                                    >
                                        <template x-if="currentStep > index + 1">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                            </svg>
                                        </template>
                                        <template x-if="currentStep <= index + 1">
                                            <span x-text="index + 1"></span>
                                        </template>
                                    </div>

                                    <!-- Step Label -->
                                    <span
                                        class="text-sm font-medium text-center"
                                        :class="{
                                            'text-teal-600': currentStep === index + 1,
                                            'text-teal-500': currentStep > index + 1,
                                            'text-gray-400': currentStep < index + 1
                                        }"
                                        x-text="step"
                                    ></span>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if($gelombang)
        <div class="mb-6 p-4 bg-teal-50 border-l-4 border-teal-400 rounded text-center">
            <div class="text-teal-700 font-semibold text-lg">{{ $gelombang->nama }}</div>
            <div class="text-gray-600 text-sm">Periode: {{ \App\Helpers\DateHelper::toIndonesianFormat($gelombang->tanggal_mulai) }} - {{ \App\Helpers\DateHelper::toIndonesianFormat($gelombang->tanggal_selesai) }}</div>
        </div>
        @endif

        <!-- Form -->
        <form @submit.prevent="handleSubmit"
            action="{{ route('formulir.store') }}"
            method="POST"
            enctype="multipart/form-data"
            class="space-y-8">
            @csrf

            @include('formulir.step1')
            @include('formulir.step2')
            @include('formulir.step3')
            @include('formulir.step4')

        </div>
    </div>
    @else
        <!-- Pesan jika tidak ada gelombang aktif -->
        <div class="bg-white rounded-lg shadow-md p-8 text-center">
            <div class="text-yellow-500 mb-4">
                <svg class="w-16 h-16 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4H12a9 9 0 007.545-4.545l3.051-5.085a1.95 1.95 0 00-.187-2.24A1.95 1.95 0 0020 5.958V6a2 2 0 00-2-2h-2a2 2 0 00-2 2v2a2 2 0 00-2 2H8a2 2 0 00-2-2V6a2 2 0 00-2-2H2a2 2 0 00-2 2v12a2 2 0 002 2h7.062z"></path>
                </svg>
            </div>
            <h2 class="text-2xl font-bold text-gray-800 mb-4">Pendaftaran Belum Dibuka</h2>
            <p class="text-gray-600 mb-6">
                Mohon maaf, saat ini periode pendaftaran santri baru belum dibuka atau sudah ditutup.
            </p>
            <p class="text-gray-600">
                Informasi terkait jadwal pendaftaran selanjutnya akan diumumkan melalui website resmi pondok.
            </p>
        </div>
    @endif
    </div>
</div>
@vite(['resources/app/js/formulir.js'])
@endsection
