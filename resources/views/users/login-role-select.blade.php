@extends('layouts.app')

@section('title', 'Choose Login')
@section('content')
    <main class="auth-page">
        <section class="auth-card">
            <div class="brand-pill">Benchz Fitness</div>
            <h1 class="header-title">Choose your portal</h1>
            <p class="auth-subtitle">Select how you want to sign in.</p>

            <div class="auth-form">
                <a href="{{ route('login.member') }}" class="click-btn">Member Login</a>
                <a href="{{ route('login.admin') }}" class="click-btn">Admin Login</a>
                <a href="{{ route('register') }}" class="click-btn">Create Member Account</a>
            </div>

            <p class="auth-subtitle" style="margin-top: 14px;">Demo accounts after seeding:<br><strong>admin@benchzfitness.local / Admin12345!</strong><br><strong>member@benchzfitness.local / Member12345!</strong></p>
        </section>
    </main>
@endsection
