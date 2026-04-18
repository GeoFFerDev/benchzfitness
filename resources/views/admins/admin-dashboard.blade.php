@extends('layouts.admin.admin-layout')

@section('title', 'Admin Dashboard')

@vite('resources/css/admin/dashboard/admin-dashboard.css')

@section('content')
    <div class="dashboard-header">
        <div>
            <h1>DASHBOARD</h1>
            <p>Overview of members, statuses, and latest activity.</p>
        </div>

        <div class="profile-section">
            <img src="{{ asset('storage/' . $user->profile_picture) }}" alt="{{ $user->name }} profile">
            <div class="profile-section-info">
                <h2>{{ $user->name }}</h2>
                <span>{{ ucfirst($user->role) }}</span>
            </div>
        </div>
    </div>

    <section class="summary-grid">
        <article class="summary-card">
            <h3>Total Members</h3>
            <p>{{ $summary['total'] }}</p>
        </article>
        <article class="summary-card">
            <h3>Active</h3>
            <p>{{ $summary['active'] }}</p>
        </article>
        <article class="summary-card">
            <h3>Inactive</h3>
            <p>{{ $summary['inactive'] }}</p>
        </article>
        <article class="summary-card">
            <h3>Suspended</h3>
            <p>{{ $summary['suspended'] }}</p>
        </article>
    </section>

    <section class="latest-members-panel">
        <div class="panel-head">
            <h2>Latest Members</h2>
            <a href="{{ route('memberManagement.index') }}">Open member management</a>
        </div>

        <div class="member-table-div">
            <table class="member-table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Plan</th>
                        <th>Status</th>
                        <th>Expiry Date</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($members as $member)
                        <tr>
                            <td>{{ $member->name }}</td>
                            <td>{{ $member->email }}</td>
                            <td>{{ $member->membershipStatus->planType ?? 'None' }}</td>
                            <td>{{ $member->membershipStatus->status ?? 'Inactive' }}</td>
                            <td>
                                {{ $member->membershipStatus && $member->membershipStatus->expiry_date
                                    ? \Carbon\Carbon::parse($member->membershipStatus->expiry_date)->format('M d, Y')
                                    : 'N/A' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="empty-row">No members found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
@endsection
