@extends('layouts.member.member-layout')

@section('title', 'Change Password')

@section('content')
<section class="member-page-shell">
    <a href="{{ route('member.profile.edit') }}" class="member-back-btn">← Back to Edit Profile</a>

    <article class="member-card-panel profile-edit-panel">
        <h1>Change Password</h1>
        <p class="panel-subtitle">Set a stronger password to keep your account secure.</p>

        <form action="{{ route('member.profile.edit.password.update') }}" method="POST" class="member-form-grid">
            @csrf

            <label for="current_password">Current Password</label>
            <input type="password" name="current_password" id="current_password">

            <label for="new_password">New Password</label>
            <input type="password" name="new_password" id="new_password">

            <label for="new_password_confirmation">Confirm New Password</label>
            <input type="password" name="new_password_confirmation" id="new_password_confirmation">

            <button type="submit" class="profile-primary-btn">Save Password</button>
        </form>
    </article>
</section>
@endsection
