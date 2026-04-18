@extends('layouts.admin.admin-layout')

@section('title', 'Admin Dashboard')
@vite('resources/css/admin/dashboard/admin-dashboard.css')

@section('content')
    @php
        $occupancy = min(($summary['active_members'] ?? 0), 42);
        $chartPoints = [18, 25, 37, 14, 22, 31, 28];
        $revenueBars = [500, 1200, 1800, 900, 1400, 2000, 1650];
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

            <div class="chart-box line-chart" aria-label="Occupancy trend line chart">
                @foreach($chartPoints as $index => $value)
                    <div class="line-point" style="left: {{ 10 + ($index * 13) }}%; bottom: {{ max(8, min(92, $value * 2)) }}%;"></div>
                @endforeach
                <svg viewBox="0 0 100 100" preserveAspectRatio="none" class="line-svg">
                    <polyline points="
                        10,64
                        23,50
                        36,26
                        49,72
                        62,56
                        75,38
                        88,44
                    " />
                </svg>
            </div>

            <div class="chart-label-row">
                <span>Mon</span><span>Tue</span><span>Wed</span><span>Thu</span><span>Fri</span><span>Sat</span><span>Sun</span>
            </div>
        </article>

        <article class="panel-card">
            <div class="panel-head">
                <h3>Revenue Snapshot</h3>
                <span class="mini-chip">Last 7 Days</span>
            </div>

            <div class="chart-box bar-chart" aria-label="Revenue bar chart">
                @foreach($revenueBars as $bar)
                    <div class="bar" style="height: {{ max(18, min(100, $bar / 22)) }}%;"></div>
                @endforeach
            </div>

            <div class="chart-label-row">
                <span>D1</span><span>D2</span><span>D3</span><span>D4</span><span>D5</span><span>D6</span><span>D7</span>
            </div>
        </article>
    </section>

    <section class="grid-split-two">
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

        <article class="panel-card">
            <div class="panel-head">
                <h3>Live Front Desk Feed</h3>
                <span class="mini-chip">Scan Status</span>
            </div>
            <div class="front-desk-feed">
                <div class="feed-item approved">
                    <strong>ENTRY APPROVED</strong>
                    <span>Member QR validated</span>
                </div>
                <div class="feed-item denied">
                    <strong>ENTRY DENIED</strong>
                    <span>Expired or inactive account</span>
                </div>
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
