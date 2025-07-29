@extends('layouts.santri')
@section('title', 'Dashboard Santri')
@section('content')

<div class="bg-white rounded-lg shadow-md p-6 max-w-4xl mx-auto">
    <div class="max-w-6xl mx-auto">
        <h1 class="text-3xl font-bold text-center text-teal-700 mb-8">Dashboard Santri</h1>

        {{-- @include('santri.dashboard.partials._status_badge', ['santri' => $santri]) --}}

        <!-- Progress Overview -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
            @include('santri.dashboard.partials._progress_pembayaran_card', ['santri' => $santri])
            @include('santri.dashboard.partials._progress_formulir_card', ['santri' => $santri])
            @include('santri.dashboard.partials._progress_ujian_card', ['santri' => $santri])
        </div>

        <!-- Data Pendaftaran -->
        <div class="space-y-6">
        @include('santri.dashboard.partials._alur_pendaftaran_card')

            @include('santri.dashboard.partials._data_pribadi_card', ['santri' => $santri])
            @include('santri.dashboard.partials._data_sekolah_card', ['santri' => $santri])
            {{-- @include('santri.dashboard.partials._data_orang_tua_card', ['santri' => $santri]) --}}
            @include('santri.dashboard.partials._hasil_ujian_card', ['santri' => $santri])
            @include('santri.dashboard.partials._hasil_kuisioner_card', ['santri' => $santri])
        </div>
        @include('santri.dashboard.partials._action_buttons', ['santri' => $santri])

        @include('layouts.partials.footersantri')
    </div>
</div>

@endsection
