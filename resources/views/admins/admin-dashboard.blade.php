@extends('layouts.admin.admin-layout')

   
@section('title', 'Admin Dashboard')


@vite('resources/css/admin/dashboard/admin-dashboard.css')


@section('content')
    <div class="header-section">
        <h1>DASHBOARD</h1>
        <h2>Dito man yung description</h2>
    </div>

    <div class="profile-section">
        <img src="{{ asset('storage/' . $user->profile_picture) }}" alt="">
        <div class="profile-section-info">
            <h1>{{ $user->name }}</h1>
            <div class="profile-user-role">
                <img src="{{ asset('assets/images/svg/user-role.svg') }}" alt="">
                <h2>{{$user->role}}</h2>
            </div>
        </div>
    </div>

    <h1 class="dashboard-welcome-msg">Welcome back <span>{{$user->role}}</span>, Here's your gym overview.</h1>
@endsection
