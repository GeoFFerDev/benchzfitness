@extends('layouts.member.member-layout')
@section('title', 'Member Portal')

@section('content')
    <div class="profile-info">
        <a href="{{ route('member.profile' )}}">
            <div class="profile-text">
                <img src="{{asset('storage/' . $user->profile_picture)}}" alt="">
                <div class="profile-name">
                    <h1>{{Auth::user()->name}}</h1>
                    <h2>{{Auth::user()->email}}</h2>
                </div>
            </div>
        </a>
        <div class="qr-div">
            {!! QrCode::size(1000)->format('svg')->generate(Auth::user()->id); !!}
            <h1>FA QR</h1>
        </div> 
    </div>

    @php
        $attendanceCount = $attendances->count();
        $latestAttendance = $attendances->sortByDesc('created_at')->first();
        $statusLabel = $status?->status ?? 'Inactive';
    @endphp

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

    <section class="member-actions">
        <a href="{{ route('member.profile') }}" class="member-action-btn">Manage Profile</a>
        <a href="#membership-options" class="member-action-btn">Membership Options</a>
        <a href="#attendance-record" class="member-action-btn">Attendance Record</a>
    </section>


    <h1 class="head-title-status">Membership Status</h1>
    @if(Auth::user()->membershipStatus?->status !== 'Active')
        <div class="no-membership-info">
            <h1>No active membership.</h1>
        </div>
        
        <div class="membership-options" id="membership-options">
            <h1>Membership Options</h1> 
            <div class="available-coupons">
                <div class="coupon-text">
                    <h2>Available Coupons:</h2>
                    <h2><a href="">View all</a></h2>
                </div>
                <div class="coupon-card">
                    <div>10%</div> <div>20%</div>
                </div>
            </div>  

            <div class="membership-cards-wrapper">
                <button class="scroll-btn left" onclick="scrollCards('left', 'membershipCards')">&#10094;</button>
                
                <div class="membership-cards" id="membershipCards">
                    @foreach($tiers as $plan)
                        <a href="{{ route('membership.purchase', $plan->id) }}" class="membership-card-link">
                            <div class="membership-card">
                                <div class="card-accent">
                                    <img src="{{ asset('storage/' . $plan->image_path) }}" alt="{{ $plan->name }}">
                                </div>

                                <div class="membership-card-info">
                                    <h1 class="membership-card-status-h1">{{ $plan->name }}</h1>
                                    
                                    <div class="membership-card-info-duration">
                                        <img src="{{ asset('assets/images/svg/duration.svg') }}" alt="Duration">
                                        {{ $plan->duration }} {{ $plan->duration == 1 ? 'Session' : 'Days' }}
                                    </div>

                                    <div class="membership-card-info-tag">
                                        {{ $plan->tag }}
                                    </div>

                                    <h2 class="card-price">₱{{ number_format($plan->price, 2) }}</h2>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>

                <button class="scroll-btn right" onclick="scrollCards('right', 'membershipCards')">&#10095;</button>
            </div>
        </div>

    @else
        <div class="membership-info">
            <div class="membership-status">

                @php
                    $userId = Auth::id();
                    
                    // 1. Get the current status for this user
                    $userStatus = \App\Models\MembershipStatus::where('user_id', $userId)->first();
                    
                    $planDetails = null;
                    if ($userStatus && $userStatus->planType) {
                        $planDetails = \App\Models\MembershipPlans::where('name', $userStatus->planType)->first();
                    }
                    
                    $planImagePath = ($planDetails && $planDetails->image_path) 
                        ? 'storage/' . $planDetails->image_path 
                        : 'assets/images/placeholder-plan.png';
                @endphp

                <img src="{{ asset($planImagePath) }}" alt="">
                <h1>{{$status->planType}}</h1>
            </div>

            <div class="purchased-at">
                <div>
                    <h1>Date purchased:</h1>
                    <h2>{{ \Carbon\Carbon::parse($status->start_date)->format('M j, Y') }}</h2> 
                </div>
                <div>
                    <h1>Expiry date:</h1>
                    <h2>{{ \Carbon\Carbon::parse($status->expiry_date)->format('M j, Y') }}</h2> 
                </div>
            </div>

            <div class="date-attended">
                <h1>Days Attended:</h1>
                @php
                    $daysAttended = $attendances->where('user_id', Auth::user()->id)
                                    ->groupBy(function($date) {
                                        return \Carbon\Carbon::parse($date->created_at)->format('Y-m-d');
                                    })->count();
                @endphp
                <h2>{{ $daysAttended }}</h2>
            </div>

            <div class="date-expired">

                @php
                    $start = \Carbon\Carbon::parse($status->start_date);
                    $expiry = \Carbon\Carbon::parse($status->expiry_date);
                    $now = \Carbon\Carbon::now();

                    // Use Hours for high precision math
                    $totalHours = $start->diffInHours($expiry) ?: 1;
                    $remainingHours = max(0, $now->diffInHours($expiry, false));
                    
                    // Calculate the total days for the denominator
                    $totalDays = round($totalHours / 24) ?: 1;

                    // Logic for display text
                    if ($remainingHours >= 24) {
                        // Format: 5 / 30 Days Remaining
                        $daysLeft = floor($remainingHours / 24);
                        $remainingText = $daysLeft . " / " . $totalDays . " Days Remaining";
                    } elseif ($remainingHours >= 1) {
                        // Format: 14 Hrs / 30 Days Remaining
                        $remainingText = floor($remainingHours) . " Hrs / " . $totalDays . " Days Remaining";
                    } else {
                        // Format: 45 Mins / 30 Days Remaining
                        $minutes = max(0, $now->diffInMinutes($expiry, false));
                        $remainingText = $minutes . " Mins / " . $totalDays . " Days Remaining";
                    }

                    // Percentage for the bar
                    $percentage = min(100, ($remainingHours / $totalHours) * 100);
                @endphp

                <div class="progress-container">
                    <div class="progress-bar">
                        <div class="progress-fill" style="width: {{ $percentage }}%;"></div>
                    </div>
                    <span class="progress-text">{{ $remainingText }}</span>
                </div>
            </div>
        </div> 
    @endif   

    <h1 class="attendance-logs-title" id="attendance-record">Attendance Record</h1>
    <div class="attendance-logs">
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
                    @foreach($attendances->sortByDesc('created_at')->take(10) as $log)
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($log->created_at)->format('M d, Y') }}</td>
                            <td class="day-text">{{ \Carbon\Carbon::parse($log->created_at)->format('l') }}</td>
                            <td class="time-text">{{ \Carbon\Carbon::parse($log->created_at)->format('h:i A') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>

    @if(Auth::user()->membershipStatus?->status == 'Active')
        <div class="membership-options">
            <h1 class="membership-title">Membership Options</h1> 
            <div class="available-coupons">
                <div class="coupon-text">
                    <h2>Available Coupons:</h2>
                    <h2><a href="">View all</a></h2>
                </div>
                <div class="coupon-card">
                    <div>10%</div> 
                    <div>20%</div>
                </div>
            </div>  
                        <div class="membership-cards-wrapper">
                <button class="scroll-btn left" onclick="scrollCards('left', 'membershipCards')">&#10094;</button>
                
                <div class="membership-cards" id="membershipCards">
                    @foreach($tiers as $plan)
                        <a href="{{ route('membership.purchase', $plan->id) }}" class="membership-card-link">
                            <div class="membership-card">
                                <div class="card-accent">
                                    <img src="{{ asset('storage/' . $plan->image_path)  }}" alt="{{ $plan->name }}">
                                </div>

                                <div class="membership-card-info">
                                    <h1 class="membership-card-status-h1">{{ $plan->name }}</h1>
                                    
                                    <div class="membership-card-info-duration">
                                        <img src="{{ asset('assets/images/svg/duration.svg') }}" alt="Duration">
                                        {{ $plan->duration }} {{ $plan->duration == 1 ? 'Session' : 'Days' }}
                                    </div>

                                    <div class="membership-card-info-tag">
                                        {{ $plan->tag }}
                                    </div>

                                    <h2 class="card-price">₱{{ number_format($plan->price, 2) }}</h2>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>

                <button class="scroll-btn right" onclick="scrollCards('right', 'membershipCards')">&#10095;</button>
            </div>
        </div>
    @endif

    <script>
        function scrollCards(direction, containerId) {
            const container = document.getElementById(containerId);
            const scrollAmount = 220; 
            if (direction === 'left') {
                container.scrollBy({ left: -scrollAmount, behavior: 'smooth' });
            } else {
                container.scrollBy({ left: scrollAmount, behavior: 'smooth' });
            }
        }
    </script>
@endsection