@extends('layouts.app')

@section('title', 'Account Register')
@section('content')
    <main class="auth-page">
        <section class="auth-card">
            <div class="brand-pill">Benchz Fitness</div>
            <h1 class="header-title">Create Account</h1>
            <p class="auth-subtitle">Join now and start managing your fitness membership.</p>

            <form action="/register" method="POST" enctype="multipart/form-data" class="auth-form">
                @csrf

                <label for="fullName">Full Name</label>
                <input type="text" name="name" id="fullName" value="{{ old('name') }}" required>
                @error('name')
                    <div class="error-box">{{ $message }}</div>
                @enderror

                <label for="email">Email</label>
                <input type="email" name="email" id="email" value="{{ old('email') }}" required>
                @error('email')
                    <div class="error-box">{{ $message }}</div>
                @enderror

                <label for="password">Password</label>
                <input type="password" name="password" id="password" required>
                @error('password')
                    <div class="error-box">{{ $message }}</div>
                @enderror

                <label for="passwordAgain">Re-enter Password</label>
                <input type="password" name="password_confirmation" id="passwordAgain" required>
                @error('password_confirmation')
                    <div class="error-box">{{ $message }}</div>
                @enderror

                <label for="profile">Profile Picture</label>
                <input type="file" name="profile_picture" id="profile" accept="image/*" required>
                @error('profile_picture')
                    <div class="error-box">{{ $message }}</div>
                @enderror

                <input type="submit" value="Register">
            </form>

            <div class="text-divider">Already registered?</div>
            <a href="{{ route('login') }}" class="click-btn">Back to login</a>
        </section>
    </main>
@endsection
