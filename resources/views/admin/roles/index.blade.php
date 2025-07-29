@extends('layouts.admin')

@section('title', 'Manajemen Role')
@section('header', 'Manajemen Role')

@section('content')
<div id="roles-vue">
    <!-- Roles component akan di-mount di sini -->
</div>
@endsection

@push('scripts')
<!-- Vue akan di-mount otomatis oleh app.js -->
@endpush