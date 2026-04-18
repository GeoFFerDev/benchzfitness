@extends('layouts.app')

@section('title', 'Account Login')
@section('content')
    @php
        $currentRole = old('login_role', $loginRole ?? 'member');
    @endphp

    <main class="auth-page">
        <section class="auth-card">
            <div class="brand-pill">Benchz Fitness</div>
            <h1 class="header-title">{{ $currentRole === 'admin' ? 'Admin Login' : 'Member Login' }}</h1>
            <p class="auth-subtitle">Sign in to continue to your {{ $currentRole }} portal.</p>

            <div class="role-switcher">
                <a href="{{ route('member.login') }}" class="role-chip {{ $currentRole === 'member' ? 'active' : '' }}">Member</a>
                <a href="{{ route('admin.login') }}" class="role-chip {{ $currentRole === 'admin' ? 'active' : '' }}">Admin</a>
            </div>

            <form action="{{ route('login.submit') }}" method="POST" class="auth-form">
                @csrf
                <input type="hidden" name="login_role" value="{{ $currentRole }}">

                <label for="userName">Email</label>
                <input type="email" name="email" id="userName" value="{{ old('email') }}" placeholder="you@example.com" required>
                @error('email')
                    <div class="error-box">{{ $message }}</div>
                @enderror

                <label for="userPass">Password</label>
                <input type="password" name="password" id="userPass" placeholder="Enter your password" required>
                @error('password')
                    <div class="error-box">{{ $message }}</div>
                @enderror
                @error('login_role')
                    <div class="error-box">{{ $message }}</div>
                @enderror

                <input type="submit" value="Login">

                @if($currentRole === 'member')
                    <div class="text-divider">Don’t have an account yet?</div>
                    <a href="{{ route('register') }}" class="click-btn">Create account</a>
                @endif
            </form>
        </section>
    </main>
@endsection
