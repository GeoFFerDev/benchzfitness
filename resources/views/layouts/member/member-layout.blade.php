<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Member Portal')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&family=Open+Sans:ital,wght@0,300..800;1,300..800&display=swap" rel="stylesheet">

    @vite(['resources/css/global.css', 'resources/css/memberPortal.css', 'resources/js/app.js'])

    @livewire('wire-elements-modal')
</head>
<body>
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <nav id="mainNav">
        <img src="{{ asset('assets/images/svg/site-title.svg') }}" alt="Site Logo">
        <button class="burger-button" id="burgerBtn" aria-label="Toggle Navigation">
            <div class="line1"></div>
            <div class="line2"></div>
            <div class="line3"></div>
        </button>
    </nav>

    <div class="mobile-side-bar" id="mobileSidebar">
        <a href="{{ route('member-portal') }}">
            <div class="nav-wrap">
                <img src="{{ asset('assets/images/svg/dashboard.svg') }}" alt="">
                <h1>HOME</h1>
            </div>
        </a>
        <a href="{{ route('member.profile') }}">
            <div class="nav-wrap">
                <img src="{{ asset('assets/images/svg/profile.svg') }}" alt="">
                <h1>PROFILE SETTINGS</h1>
            </div>
        </a>
        <a href="{{ route('member-portal') }}#membership-options">
            <div class="nav-wrap">
                <img src="{{ asset('assets/images/svg/membershipplan.svg') }}" alt="">
                <h1>MEMBERSHIP OPTIONS</h1>
            </div>
        </a>
        <a href="{{ route('member-portal') }}#attendance-record">
            <div class="nav-wrap">
                <img src="{{ asset('assets/images/svg/AttendanceLogs.svg') }}" alt="">
                <h1>SESSION HISTORY</h1>
            </div>
        </a>

        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button class="member-logout-btn" type="submit">LOGOUT</button>
        </form>
    </div>

    <main class="portal-container">
        @yield('content')
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const burgerBtn = document.getElementById('burgerBtn');
            const sidebar = document.getElementById('mobileSidebar');
            const overlay = document.getElementById('sidebarOverlay');

            function toggleMenu() {
                sidebar.classList.toggle('active');
                overlay.classList.toggle('active');
                burgerBtn.classList.toggle('toggle');
                document.body.style.overflow = sidebar.classList.contains('active') ? 'hidden' : 'auto';
            }

            burgerBtn.addEventListener('click', toggleMenu);
            overlay.addEventListener('click', toggleMenu);
        });
    </script>

</body>
</html>
