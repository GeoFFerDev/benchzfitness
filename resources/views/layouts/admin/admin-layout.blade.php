<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&family=Open+Sans:ital,wght@0,300..800;1,300..800&display=swap" rel="stylesheet">
    <title>@yield('title', 'Document')</title>
    @vite(['resources/css/global.css', 'resources/js/app.js'])
    @livewire('wire-elements-modal')
</head>
<body>
    <div class="side-bar">
        <div class="nav-indicator"></div>

        <div class="site-logo">
            <img src="{{ asset('assets/images/svg/gym-logo.svg') }}" alt="qwe">
            <img src="{{ asset('assets/images/svg/portal-logo.svg') }}" alt="qwe">
        </div>
        
        <div class="line-divider"></div>

        <a href="{{route('admin-portal')}}" class="nav-icon-wrap {{ Request::routeIs('admin-portal') ? 'active' : '' }}">
            <div class="nav-icon">
                <img src="{{ asset('assets/images/svg/dashboard.svg') }}" alt="dashboard">
            </div>
            <div class="nav-icon-text">DASHBOARD</div>
        </a>

        <a href="#" class="nav-icon-wrap {{ Request::is('transactions*') ? 'active' : '' }}">
            <div class="nav-icon">
                <img src="{{ asset('assets/images/svg/TransactionHistory.svg') }}" alt="transaction history">
            </div>
            <div class="nav-icon-text">TRANSACTION HISTORY</div>
        </a>

        <a href="{{route('membershipPlan.index')}}" class="nav-icon-wrap {{ Request::routeIs('membershipPlan.index*') ? 'active' : '' }}">
            <div class="nav-icon">
                <img src="{{ asset('assets/images/svg/membershipplan.svg') }}" alt="membership plan">
            </div>
            <div class="nav-icon-text">MEMBERSHIP PLAN</div>
        </a>

        <a href="{{route('memberManagement.index')}}" class="nav-icon-wrap {{ Request::routeIs('memberManagement.index*') ? 'active' : '' }}">
            <div class="nav-icon">
                <img src="{{ asset('assets/images/svg/Members.svg') }}" alt="members">
            </div>
            <div class="nav-icon-text">MEMBERS</div>
        </a>

        <a href="#" class="nav-icon-wrap {{ Request::is('analytics*') ? 'active' : '' }}">
            <div class="nav-icon">
                <img src="{{ asset('assets/images/svg/Analytics.svg') }}" alt="analytics">
            </div>
            <div class="nav-icon-text">ANALYTICS</div>
        </a>

        <a href="{{route('member.attendanceLogs')}}" class="nav-icon-wrap {{ Request::routeIs('member.attendanceLogs') ? 'active' : '' }}">
            <div class="nav-icon">
                <img src="{{ asset('assets/images/svg/AttendanceLogs.svg') }}" alt="attendance logs">
            </div>
            <div class="nav-icon-text">ATTENDANCE LOGS</div>
        </a>

        <a href="#" class="nav-icon-wrap {{ Request::is('inactive*') ? 'active' : '' }}">
            <div class="nav-icon">
                <img src="{{ asset('assets/images/svg/InactiveMembers.svg') }}" alt="inactive members">
            </div>
            <div class="nav-icon-text">INACTIVE MEMBERS</div>
        </a>

        <a href="#" class="nav-icon-wrap {{ Request::is('discount*') ? 'active' : '' }}">
            <div class="nav-icon">
                <img src="{{ asset('assets/images/svg/Discount.svg') }}" alt="discount">
            </div>
            <div class="nav-icon-text">DISCOUNT</div>
        </a>

        <a href="#" class="nav-icon-wrap {{ Request::is('revenue*') ? 'active' : '' }}">
            <div class="nav-icon">
                <img src="{{ asset('assets/images/svg/Revenue.svg') }}" alt="revenue">
            </div>
            <div class="nav-icon-text">REVENUE</div>
        </a>

        <a href="{{ route('admin.profile') }}" class="nav-icon-wrap {{ Request::routeIs('admin.profile') ? 'active' : '' }}">
            <div class="nav-icon side-bar-profile">
                <img src="{{asset('storage/' . Auth::user()->profile_picture)}}" class="profile-img" alt="profile">
            </div>
            <div class="nav-icon-text">PROFILE</div>
        </a>
    </div>

    <div class="main-content-wrapper">
        <div class="main-content">
            @yield('content')
        </div>
    </div>
</body>
</html>