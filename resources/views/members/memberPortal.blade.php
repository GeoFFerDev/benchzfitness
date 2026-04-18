@extends('layouts.member.member-layout')
@section('title', 'Member Portal')

@section('content')
    @php
        $statusLabel = $status?->status ?? 'Non-Member';
        $statusClass = strtolower($statusLabel) === 'active' ? 'status-active' : 'status-inactive';
        $planName = $status?->planType ?? 'No Active Plan';
        $expiryText = $status?->expiry_date ? \Carbon\Carbon::parse($status->expiry_date)->format('M d, Y') : 'N/A';
        $attendanceCount = $attendances->count();
    @endphp

    <section class="profile-info member-hero">
        <a href="{{ route('member.profile') }}">
            <div class="profile-text">
                <img src="{{ asset('storage/' . $user->profile_picture) }}" alt="Member profile">
                <div class="profile-name">
                    <h1>{{ $user->name }}</h1>
                    <h2>{{ $user->email }}</h2>
                </div>
            </div>
        </a>

        <div class="qr-div qr-main">
            {!! QrCode::size(1000)->format('svg')->generate($user->id); !!}
            <h1>Entry QR</h1>
        </div>
    </section>

    <section class="member-quick-stats">
        <article class="quick-stat-card">
            <span>Membership</span>
            <strong>{{ $statusLabel }}</strong>
        </article>
        <article class="quick-stat-card">
            <span>Days Remaining</span>
            <strong>{{ $daysRemaining !== null ? max($daysRemaining, 0) : 'N/A' }}</strong>
        </article>
        <article class="quick-stat-card">
            <span>Total Check-ins</span>
            <strong>{{ $attendanceCount }}</strong>
        </article>
    </section>

    <section class="member-status-panel">
        <div>
            <h2 class="section-title">Membership Status</h2>
            <p class="plan-details">Plan: {{ $planName }} | Expires: {{ $expiryText }}</p>
        </div>
        <span class="member-status-badge {{ $statusClass }}">{{ strtoupper($statusLabel) }}</span>
    </section>

    <section class="membership-options" id="membership-options">
        <h1 class="membership-title">Membership Options</h1>

        <div class="membership-cards-wrapper">
            <button class="scroll-btn left" type="button" onclick="scrollCards('left', 'membershipCardsMain')">&#10094;</button>

            <div class="membership-cards" id="membershipCardsMain">
                @forelse($tiers as $plan)
                    <a href="{{ route('membership.purchase', $plan->id) }}" class="membership-card-link">
                        <article class="membership-card">
                            <div class="card-accent">
                                <img src="{{ asset('storage/' . $plan->image_path) }}" alt="{{ $plan->name }}">
                            </div>

                            <div class="membership-card-info">
                                <h1 class="membership-card-status-h1">{{ $plan->name }}</h1>
                                <div class="membership-card-info-duration">
                                    <img src="{{ asset('assets/images/svg/duration.svg') }}" alt="Duration">
                                    {{ $plan->duration }} {{ $plan->duration == 1 ? 'Session' : 'Days' }}
                                </div>
                                <div class="membership-card-info-tag">{{ $plan->tag }}</div>
                                <h2 class="card-price">₱{{ number_format($plan->price, 2) }}</h2>
                            </div>
                        </article>
                    </a>
                @empty
                    @foreach([
                        ['name' => 'Bronze', 'price' => '299.00', 'tag' => 'Starter Access'],
                        ['name' => 'Silver', 'price' => '499.00', 'tag' => 'Most Popular'],
                        ['name' => 'Gold', 'price' => '699.00', 'tag' => 'Unlimited Flex'],
                        ['name' => 'Platinum', 'price' => '999.00', 'tag' => 'VIP + Perks'],
                    ] as $sample)
                        <article class="membership-card sample-plan-card">
                            <div class="card-accent sample-accent sample-{{ strtolower($sample['name']) }}">
                                <span>{{ $sample['name'] }} PLAN</span>
                            </div>
                            <div class="membership-card-info">
                                <h1 class="membership-card-status-h1">{{ $sample['name'] }}</h1>
                                <div class="membership-card-info-tag">{{ $sample['tag'] }}</div>
                                <h2 class="card-price">₱{{ $sample['price'] }}</h2>
                            </div>
                        </article>
                    @endforeach
                @endforelse
            </div>

            <button class="scroll-btn right" type="button" onclick="scrollCards('right', 'membershipCardsMain')">&#10095;</button>
        </div>
    </section>

    <h1 class="attendance-logs-title" id="attendance-record">Attendance History</h1>
    <section class="attendance-logs">
        @if($attendances->isEmpty())
            <div class="no-logs">
                <p>No attendance recorded yet.</p>
            </div>
        @else
            <table class="attendance-table">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Day</th>
                        <th>Time</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($attendances->sortByDesc('created_at')->take(15) as $log)
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($log->created_at)->format('M d, Y') }}</td>
                            <td class="day-text">{{ \Carbon\Carbon::parse($log->created_at)->format('l') }}</td>
                            <td class="time-text">{{ \Carbon\Carbon::parse($log->created_at)->format('h:i A') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </section>

    <script>
        function scrollCards(direction, containerId) {
            const container = document.getElementById(containerId);
            if (!container) return;

            const scrollAmount = 280;
            const offset = direction === 'left' ? -scrollAmount : scrollAmount;

            container.classList.add('is-scrolling');
            container.scrollBy({ left: offset, behavior: 'smooth' });
            clearTimeout(container._scrollTimeout);
            container._scrollTimeout = setTimeout(() => container.classList.remove('is-scrolling'), 350);
        }
    </script>
@endsection
