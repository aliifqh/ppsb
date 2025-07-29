@extends('layouts.admin')

@section('title', 'Roles & Permissions')
@section('header', 'Roles & Permissions')

@section('content')
<div id="app">
    <admin-roles-permissions></admin-roles-permissions>
</div>
@endsection

@push('scripts')
@vite(['resources/js/app.js'])
@endpush 