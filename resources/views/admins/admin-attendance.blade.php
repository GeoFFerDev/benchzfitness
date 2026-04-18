@extends('layouts.admin.admin-layout')
@section('title', 'Attendance Logs')

@vite('resources/css/admin/dashboard/admin-attendance.css')

@section('content')
    <div class="header-section">
        <h1>Attendance Logs</h1>
        <h2>Track real-time check-ins for gym facility access</h2>
    </div>

    
    <livewire:member-scan-simulator />
    <livewire:attendance-list />
@endsection