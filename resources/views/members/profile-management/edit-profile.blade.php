@extends('layouts.member.member-layout')

@section('title', 'Edit Profile')

@section('content')
<section class="member-page-shell">
    <a href="{{ route('member.profile') }}" class="member-back-btn">← Back to Profile</a>

    <article class="member-card-panel profile-edit-panel">
        <h1>Edit Profile</h1>
        <p class="panel-subtitle">Update your account details and profile photo.</p>

        <form action="{{ route('member.profile.update') }}" method="POST" enctype="multipart/form-data" class="member-form-grid">
            @csrf

            <label for="name">Full Name</label>
            <input type="text" name="name" id="name" value="{{ $user->name }}">

            <label for="email">Email</label>
            <input type="email" name="email" id="email" value="{{ $user->email }}">

            <label for="profile">Profile Picture</label>
            <input type="file" name="profile_picture" id="profile" accept="image/*">

            @error('profile_picture')
                <div class="error-box">{{ $message }}</div>
            @enderror

            <button type="submit" class="profile-primary-btn">Save Changes</button>
        </form>

        <div class="profile-action-row">
            <a href="{{ route('member.profile.edit.password') }}" class="profile-secondary-btn">Change Password</a>
            <a href="{{ route('member.profile') }}" class="profile-secondary-btn">Cancel</a>
        </div>
    </article>
</section>
@endsection
