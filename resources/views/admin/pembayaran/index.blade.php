@extends('layouts.admin')

@section('title', 'Data Pembayaran')
@section('header', 'Data Pembayaran')

@section('content')
<div id="app">
    <router-view />
</div>
@endsection

@push('scripts')
@vite(['resources/js/app.js'])
@endpush
