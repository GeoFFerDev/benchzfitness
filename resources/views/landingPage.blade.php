@extends('layouts.app')

@section('title', 'Benchz Fitness')
@section('content')
<main class="landing-page">
    <header class="landing-top">
        <div class="landing-logo">
            <img src="{{ asset('assets/images/svg/gym-logo.svg') }}" alt="Benchz Fitness logo">
            <span>Benchz Fitness Portal</span>
        </div>
        <a href="{{ route('login.select') }}" class="landing-cta">Open Portal</a>
    </header>

    <section class="hero-wrap">
        <article class="hero-copy">
            <div class="brand-pill">Gym Access & Analytics</div>
            <h1>One System for Members and Admin Operations</h1>
            <p>
                Benchz Fitness supports mobile-first member self-service and desktop-first admin control.
                Members can register, manage profile, generate QR access, subscribe to plans, and view attendance history.
                Admins can manage members, monitor attendance, review plan activity, and control front-desk operations.
            </p>

            <div class="hero-btns">
                <a href="{{ route('login.member') }}" class="landing-cta">Member Login</a>
                <a href="{{ route('login.admin') }}" class="soft-btn">Admin Login</a>
                <a href="{{ route('register') }}" class="soft-btn">Create Account</a>
            </div>
        </article>

        <aside class="hero-cards">
            <h2 class="header-title" style="font-size: 1.8rem;">System Capabilities</h2>
            <div class="capability-list">
                <div class="capability-item">
                    <h3>Member Portal</h3>
                    <p>QR Access, Membership Status, Session History, and Cashless Subscription Flow.</p>
                </div>
                <div class="capability-item">
                    <h3>Admin Dashboard</h3>
                    <p>Attendance Monitoring, Member Management, Plan Controls, and Actionable Insights.</p>
                </div>
                <div class="capability-item">
                    <h3>Security</h3>
                    <p>Role-based authentication with MFA verification for admin access.</p>
                </div>
            </div>
        </aside>
    </section>
</main>
@endsection
