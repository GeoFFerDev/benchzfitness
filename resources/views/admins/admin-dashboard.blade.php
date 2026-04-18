@extends('layouts.admin.admin-layout')

@section('title', 'Admin Dashboard')
@vite('resources/css/admin/dashboard/admin-dashboard.css')

@section('content')
    <div class="header-section">
        <h1>Dashboard</h1>
        <h2>Monitor members, activity, and account health in one place.</h2>
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
            <p>Total Members</p>
            <h3>{{ $summary['total_members'] }}</h3>
        </article>
        <article class="stat-card">
            <p>Active Members</p>
            <h3>{{ $summary['active_members'] }}</h3>
        </article>
        <article class="stat-card">
            <p>Inactive Members</p>
            <h3>{{ $summary['inactive_members'] }}</h3>
        </article>
    </section>

    <section class="panel-card">
        <div class="panel-head">
            <h3>Recent Member Accounts</h3>
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
