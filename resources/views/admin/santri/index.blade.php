@extends('layouts.admin')

@section('title', 'Data Santri')
@section('header', 'Data Santri')

@section('content')
<div id="app">
    <router-view />
</div>
@endsection

@vite(['resources/js/app.js'])
