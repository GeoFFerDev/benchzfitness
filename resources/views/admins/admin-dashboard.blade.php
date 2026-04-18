@extends('layouts.admin.admin-layout')

@section('title', 'Admin Dashboard')
@vite('resources/css/admin/dashboard/admin-dashboard.css')

@section('content')
    @php
        $occupancy = min(($summary['active_members'] ?? 0), 42);
    @endphp

    <div class="header-section">
        <h1>Analytics Dashboard</h1>
        <h2>Monitor occupancy, members, and operational activity from one control center.</h2>
    </div>

    <section class="profile-section">
        <img src="{{ asset('storage/' . $user->profile_picture) }}" alt="Admin profile">
        <div class="profile-section-info">
            <h1>{{ $user->name }}</h1>
            <div class="profile-user-role">
                <img src="{{ asset('assets/images/svg/user-role.svg') }}" alt="role icon">
                <h2>{{ ucfirst($user->role) }}</h2>
            </div>
        </div>
    </section>

    <section class="stat-grid">
        <article class="stat-card">
            <p>Total Revenue Today</p>
            <h3>₱{{ number_format(($summary['active_members'] ?? 0) * 50, 2) }}</h3>
        </article>
        <article class="stat-card">
            <p>Total Active Members</p>
            <h3>{{ $summary['active_members'] }}</h3>
        </article>
        <article class="stat-card">
            <p>Current Occupancy</p>
            <h3>{{ $occupancy }}</h3>
        </article>
    </section>

    <section class="grid-split-two">
        <article class="panel-card">
            <div class="panel-head">
                <h3>Occupancy Trends</h3>
                <span class="mini-chip">Peak Hours Preview</span>
            </div>
            <div class="trend-list">
                <div><strong>06:00 AM</strong><span>18 check-ins</span></div>
                <div><strong>12:00 PM</strong><span>25 check-ins</span></div>
                <div><strong>06:00 PM</strong><span>37 check-ins</span></div>
                <div><strong>09:00 PM</strong><span>14 check-ins</span></div>
            </div>
        </article>

        <article class="panel-card">
            <div class="panel-head">
                <h3>Retention Alert Panel</h3>
                <span class="mini-chip alert-chip">At-Risk Members</span>
            </div>
            <div class="alert-list">
                @forelse($members->take(5) as $member)
                    <div class="alert-item">
                        <strong>{{ $member->name }}</strong>
                        <span>{{ $member->membershipStatus->status ?? 'Inactive' }}</span>
                    </div>
                @empty
                    <p>No members yet.</p>
                @endforelse
            </div>
        </article>
    </section>

    <section class="panel-card">
        <div class="panel-head">
            <h3>Member Management Table</h3>
            <a href="{{ route('memberManagement.index') }}">Open Member Management</a>
        </div>

        <div class="member-mini-table-wrap">
            <table class="member-mini-table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Plan</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($members as $member)
                        <tr>
                            <td>{{ $member->name }}</td>
                            <td>{{ $member->email }}</td>
                            <td>{{ $member->membershipStatus->planType ?? 'None' }}</td>
                            <td>{{ $member->membershipStatus->status ?? 'Inactive' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4">No members yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
@endsection
