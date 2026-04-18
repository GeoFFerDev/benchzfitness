@extends('layouts.app')

@section('title', 'Account Login')
@section('content')
    <main class="auth-page">
        <section class="auth-card">
            <div class="brand-pill">Benchz Fitness</div>
            <h1 class="header-title">Welcome Back</h1>
            <p class="auth-subtitle">Sign in to continue to your account.</p>

            <form action="/login" method="POST" class="auth-form">
                @csrf

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

                <input type="submit" value="Login">

                <div class="text-divider">Don’t have an account yet?</div>

                <a href="{{ route('register') }}" class="click-btn">Create account</a>
            </form>
        </section>
    </main>
@endsection
