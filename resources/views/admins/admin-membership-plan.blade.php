@extends('layouts.admin.admin-layout')
@section('title', 'Admin Membership Plan Management')
@vite('resources/css/admin/dashboard/admin-membership-plan.css')

@section('content')

<div class="header-section">
    <h1>Membership Management</h1>
    <h2>Configure and manage membership plans</h2>
</div>

<livewire:membership-plan-list />

<livewire:modal-membership-plan />

@endsection