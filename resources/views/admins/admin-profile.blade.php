@extends('layouts.admin.admin-layout')
@section('title', 'Admin Profile Management')
@vite(['resources/css/admin/dashboard/admin-member.css', 'resources/css/admin/dashboard/admin-profile.css'])

@section('content')

<div class="header-section">
    <h1>Profile</h1>
    <h2>Manage your profile</h2>
</div>

<livewire:profile-manage />

@endsection