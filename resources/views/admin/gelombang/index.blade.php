@extends('layouts.admin')

@section('title', 'Data Gelombang')
@section('header', 'Data Gelombang')

@section('content')
<div id="app">
    <router-view />
</div>
@endsection

@push('scripts')
@vite(['resources/js/app.js'])
@endpush
