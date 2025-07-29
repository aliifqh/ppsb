@extends('layouts.santri')
@section('title', 'Informasi Pembayaran')

@section('content')
<div id="app">
    <router-view />
</div>
@endsection

@push('scripts')
@vite(['resources/js/app.js'])
@endpush