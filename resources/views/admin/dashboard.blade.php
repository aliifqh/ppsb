@extends('layouts.admin')

@section('title', 'Dashboard')
@section('header', 'Dashboard')

@section('content')
<div id="app">
    <router-view />
</div>
@endsection

@push('scripts')
@vite(['resources/js/app.js'])
@endpush
