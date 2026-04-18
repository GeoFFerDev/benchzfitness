@extends('layouts.admin.admin-layout')
@section('title', 'Admin Member Management')
@vite('resources/css/admin/dashboard/admin-member.css')

@section('content')
    <div class="header-section">
        <h1>Members Management</h1>
        <h2>Manage your gym members and their subscription status</h2>
    </div>

    <livewire:member-list />
    <livewire:member-manage />
@endsection