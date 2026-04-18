@extends('layouts.member.member-layout')

@section('title', 'Profile')

@section('content')
<section class="member-page-shell">
    <a href="{{ route('member-portal') }}" class="member-back-btn">← Back to Dashboard</a>

    <article class="member-card-panel profile-view-panel">
        <div class="profile-header-row">
            <img src="{{ asset('storage/' . $user->profile_picture) }}" alt="Profile photo">
            <div>
                <h1>{{ $user->name }}</h1>
                <p>{{ $user->email }}</p>
                <span class="profile-chip">Member Account</span>
            </div>
        </div>

        <div class="profile-detail-grid">
            <div>
                <h2>Full name</h2>
                <p>{{ $user->name }}</p>
            </div>
            <div>
                <h2>Email</h2>
                <p>{{ $user->email }}</p>
            </div>
        </div>

        <div class="profile-action-row">
            <a href="{{ route('member.profile.edit') }}" class="profile-primary-btn">Edit Profile</a>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="profile-secondary-btn" onclick="return confirm('Are you sure you want to log out of the Benchz Portal?')">Logout</button>
            </form>
        </div>
    </article>
</section>
@endsection
