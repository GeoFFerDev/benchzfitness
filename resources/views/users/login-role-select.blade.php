@extends('layouts.app')

@section('title', 'Choose Login')
@section('content')
    <main class="auth-page">
        <section class="auth-card">
            <div class="brand-pill">Benchz Fitness</div>
            <h1 class="header-title">Portal Access</h1>
            <p class="auth-subtitle">
                Continue to the correct portal based on your role. Members use mobile-first self-service,
                while admins use desktop-first operations.
            </p>

            <div class="portal-actions">
                <a href="{{ route('login.member') }}" class="click-btn">Member Login</a>
                <a href="{{ route('login.admin') }}" class="click-btn">Admin Login</a>
                <a href="{{ route('register') }}" class="click-btn">Create Member Account</a>
            </div>

            <div class="text-divider">Preview credentials (seeded demo)</div>
            <div class="info-box">
                <strong>Admin:</strong> admin@benchzfitness.local / Admin12345!<br>
                <strong>Member:</strong> member@benchzfitness.local / Member12345!
            </div>

            <div class="text-divider">Need more information?</div>
            <a href="{{ route('landing') }}" class="click-btn">View System Overview</a>
        </section>
    </main>
@endsection
