@extends('layouts.app')

@section('title', 'Admin Verification')
@section('content')
    <main class="auth-page">
        <section class="auth-card">
            <div class="brand-pill">Benchz Fitness</div>
            <h1 class="header-title">Admin Verification</h1>
            <p class="auth-subtitle">Enter the 6-digit code sent to your admin email.</p>

            @if(session('mfa_status'))
                <div class="info-box">{{ session('mfa_status') }}</div>
            @endif

            @if(session('admin_mfa_preview'))
                <div class="info-box">
                    <strong>Local/Test code:</strong> {{ session('admin_mfa_preview') }}
                    <br>
                    <small>Shown only in local/test or when mail driver is <code>log</code>.</small>
                </div>
            @endif

            <form action="{{ route('admin.mfa.verify') }}" method="POST" class="auth-form">
                @csrf

                <label for="code">Verification Code</label>
                <input type="text" name="code" id="code" maxlength="6" inputmode="numeric" placeholder="123456" required>
                @error('code')
                    <div class="error-box">{{ $message }}</div>
                @enderror

                <input type="submit" value="Verify and continue">
            </form>

            <form action="{{ route('admin.mfa.resend') }}" method="POST" class="auth-form compact-form">
                @csrf
                <button type="submit" class="click-btn click-btn-solid">Resend code</button>
            </form>

            <div class="text-divider">Need to restart login?</div>
            <a href="{{ route('login.admin') }}" class="click-btn">Back to admin login</a>
        </section>
    </main>
@endsection
