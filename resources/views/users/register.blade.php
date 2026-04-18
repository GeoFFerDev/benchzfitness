@extends('layouts.app')

@section('title', 'Account Register')
@section('content')
    <main class="auth-page">
        <section class="auth-card">
            <div class="brand-pill">Benchz Fitness</div>
            <h1 class="header-title">Member Registration</h1>
            <p class="auth-subtitle">Create your member profile and complete the digital waiver to continue.</p>

            <form action="{{ route('register.store') }}" method="POST" enctype="multipart/form-data" class="auth-form">
                @csrf

                <label for="fullName">Full Name</label>
                <input type="text" name="name" id="fullName" value="{{ old('name') }}" required>
                @error('name')
                    <div class="error-box">{{ $message }}</div>
                @enderror

                <div class="auth-grid-two">
                    <div>
                        <label for="dob">Date of Birth</label>
                        <input type="date" name="dob" id="dob" value="{{ old('dob') }}">
                    </div>
                    <div>
                        <label for="contactNumber">Contact Number</label>
                        <input type="text" name="contact_number" id="contactNumber" value="{{ old('contact_number') }}" placeholder="09XXXXXXXXX">
                    </div>
                </div>

                <div id="guardianFields" class="hidden-guardian">
                    <label for="guardianName">Parent / Guardian Name</label>
                    <input type="text" name="guardian_name" id="guardianName" value="{{ old('guardian_name') }}">

                    <label for="guardianContact">Parent / Guardian Contact</label>
                    <input type="text" name="guardian_contact" id="guardianContact" value="{{ old('guardian_contact') }}">
                </div>

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

                <div class="waiver-box">
                    <div class="checkbox-row">
                        <input type="checkbox" id="waiver" name="waiver_accepted" value="1" required>
                        <label for="waiver" style="text-transform: none; letter-spacing: 0; font-size: 13px; font-weight: 600;">
                            I acknowledge and agree to the digital gym waiver and safety terms.
                        </label>
                    </div>
                </div>

                <input type="submit" value="Register">
            </form>

            <div class="text-divider">Already registered?</div>
            <a href="{{ route('login') }}" class="click-btn">Back to login</a>
        </section>
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const dobInput = document.getElementById('dob');
            const guardianFields = document.getElementById('guardianFields');

            function toggleGuardianFields() {
                if (!dobInput || !dobInput.value) {
                    guardianFields.classList.remove('show');
                    return;
                }

                const dob = new Date(dobInput.value);
                const today = new Date();
                let age = today.getFullYear() - dob.getFullYear();
                const monthDiff = today.getMonth() - dob.getMonth();

                if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < dob.getDate())) {
                    age--;
                }

                guardianFields.classList.toggle('show', age < 18);
            }

            dobInput?.addEventListener('change', toggleGuardianFields);
            toggleGuardianFields();
        });
    </script>
@endsection
