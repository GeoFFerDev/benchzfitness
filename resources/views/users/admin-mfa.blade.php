@extends('layouts.app')

@section('title', 'Admin Verification')
@section('content')
    <main class="auth-page">
        <section class="auth-card">
            <div class="brand-pill">Benchz Fitness</div>
            <h1 class="header-title">Admin Verification</h1>
            <p class="auth-subtitle">Enter the 6-digit code sent to your admin email.</p>

            <form action="{{ route('admin.mfa.verify') }}" method="POST" class="auth-form">
                @csrf

                <label for="code">Verification Code</label>
                <input type="text" name="code" id="code" maxlength="6" inputmode="numeric" placeholder="123456" required>
                @error('code')
                    <div class="error-box">{{ $message }}</div>
                @enderror

                <input type="submit" value="Verify and continue">
            </form>

            <div class="text-divider">Code expired?</div>
            <a href="{{ route('login') }}" class="click-btn">Login again</a>
        </section>
    </main>
@endsection
