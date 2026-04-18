<?php
use function Livewire\Volt\{state, computed, on};
use App\Models\MemberAttendanceLog;
use Carbon\Carbon;

state(['search' => '']);


on(['member-updated' => function () {

}]);

/**
 * Logic for the Data-Driven Stats Cards
 */
$stats = computed(function () {
    return [
        'today' => MemberAttendanceLog::whereDate('logged_at', Carbon::today())->count(),
        'week'  => MemberAttendanceLog::whereBetween('logged_at', [
                        Carbon::now()->startOfWeek(), 
                        Carbon::now()->endOfWeek()
                    ])->count(),
        'month' => MemberAttendanceLog::whereMonth('logged_at', Carbon::now()->month)
                                      ->whereYear('logged_at', Carbon::now()->year)
                                      ->count(),
    ];
});

/**
 * Logic for the Searchable Attendance Table
 */
$logs = computed(function () {
    return MemberAttendanceLog::with('user')
        ->where(function ($query) {
            $term = '%' . $this->search . '%';
            
            // Search in User Name and Email
            $query->whereHas('user', function ($subQuery) use ($term) {
                $subQuery->where('name', 'like', $term)
                         ->orWhere('email', 'like', $term);
            })
            // Search in raw timestamp and formatted strings
            ->orWhere('logged_at', 'like', $term)
            ->orWhereRaw("DATE_FORMAT(logged_at, '%b %d, %Y') LIKE ?", [$term])
            ->orWhereRaw("DATE_FORMAT(logged_at, '%h:%i %p') LIKE ?", [$term]);
        })
        ->orderBy('logged_at', 'desc')
        ->get();
});
?>

<div>
    {{-- STATS CARDS SECTION --}}
    <div class="stats-grid-main">
        <div class="stats-grid-daily">
            <h1>Today's attendance</h1>
            <div>
                <img src="{{ asset('assets/images/svg/user-red.svg') }}" alt="Users">
                <h2>{{ number_format($this->stats['today']) }}</h2>
            </div>
        </div>
        <div class="stats-grid-weekly">
            <h1>This week's</h1>
            <div>
                <img src="{{ asset('assets/images/svg/user-red.svg') }}" alt="Users">
                <h2>{{ number_format($this->stats['week']) }}</h2>
            </div>
        </div>
        <div class="stats-grid-monthly">
            <h1>This month's</h1>
            <div>
                <img src="{{ asset('assets/images/svg/user-red.svg') }}" alt="Users">
                <h2>{{ number_format($this->stats['month']) }}</h2>
            </div>
        </div>
        <div class="stats-grid-graph">
            Graph dito
        </div>
    </div>

    {{-- SEARCH AND TABLE SECTION --}}
    <div class="attendance-container">
        <div class="attendance-header">
            <div class="search-container">
                <input type="text" 
                       placeholder="Search name, email, date, or time..." 
                       wire:model.live.debounce.300ms="search" 
                       class="search-input">
            </div>
        </div>

        <div class="attendance-table-wrapper">
            <table class="attendance-table">
                <thead>
                    <tr>
                        <th style="width: 70px;">Profile</th>
                        <th>Name</th>
                        <th>Check-in Time</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($this->logs as $log)
                        <tr>
                            {{-- Profile Column --}}
                            <td>
                                <img src="{{ $log->user->profile_picture ? asset('storage/' . $log->user->profile_picture) : asset('assets/images/svg/default-avatar.svg') }}" 
                                     class="avatar-sm"
                                     alt="Profile">
                            </td>
                            
                            {{-- Name & Email Column --}}
                            <td>
                                <div class="member-name-text">{{ $log->user->name }}</div>
                                <div class="member-email-text">{{ $log->user->email }}</div>
                            </td>

                            {{-- Time Column --}}
                            <td>
                                <span class="log-time">
                                    {{ Carbon::parse($log->logged_at)->format('h:i A') }}
                                </span>
                            </td>

                            {{-- Date Column --}}
                            <td>
                                <span class="log-date">
                                    {{ Carbon::parse($log->logged_at)->format('M d, Y') }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="empty-state">No attendance records found matching your search.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>