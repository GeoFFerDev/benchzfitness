<?php
use function Livewire\Volt\{state, computed, on};
use App\Models\User;
use App\Models\MembershipStatus; // Fixed capitalization
use Carbon\Carbon;

state(['search' => '', 'openMenuId' => null]);

$toggleMenu = function ($id) {
    $this->openMenuId = ($this->openMenuId === $id) ? null : $id;
};

on(['member-updated' => function () {
    // This will force the computed properties to refresh
}]);

$stats = computed(function () {
    // We query the User model filtered by 'member' role first, 
    // then check the relationship status.
    return [
        'total' => User::where('role', 'member')->count(),
        
        'active' => User::where('role', 'member')
            ->whereHas('membershipStatus', fn($q) => $q->where('status', 'Active'))
            ->count(),
            
        'inactive' => User::where('role', 'member')
            ->whereHas('membershipStatus', fn($q) => $q->where('status', 'Inactive'))
            ->count(),
            
        'suspended' => User::where('role', 'member')
            ->whereHas('membershipStatus', fn($q) => $q->where('status', 'Suspended'))
            ->count(),
    ];
});

$members = computed(function () {
    return User::where('role', 'member')
        ->with('membershipStatus')
        ->where(function ($query) {
            $term = '%' . $this->search . '%';
            $query->where('name', 'like', $term)
                  ->orWhere('email', 'like', $term)
                  ->orWhereHas('membershipStatus', function ($subQuery) use ($term) {
                      $subQuery->where('planType', 'like', $term)
                               ->orWhere('status', 'like', $term);
                  });
        })
        ->latest()
        ->get();
});
?>

{{-- Wrapper Start: Livewire requires exactly ONE root element --}}
<div>
    <div class="member-stat-card-wrapper">
        <div class="member-stat-card">
            <h1>MEMBERS</h1>
            <div class="member-stat-card-second"><h2>{{ $this->stats['total'] }}</h2></div>
        </div>
        <div class="member-stat-card">
            <h1>ACTIVE</h1>
            <div class="member-stat-card-second"><h2>{{ $this->stats['active'] }}</h2></div>
        </div>
        <div class="member-stat-card">
            <h1>INACTIVE</h1>
            <div class="member-stat-card-second"><h2>{{ $this->stats['inactive'] }}</h2></div>
        </div>
        <div class="member-stat-card">
            <h1>SUSPENDED</h1>
            <div class="member-stat-card-second"><h2>{{ $this->stats['suspended'] }}</h2></div>
        </div>
    </div>

    <div class="member-management-container">
        <div class="member-header">
            <input type="text" placeholder="Search members..." wire:model.live.debounce.300ms="search" class="search-input">
        </div>

        <div class="member-table-wrapper">
            <table class="member-table">
                <thead>
                    <tr>
                        <th>Profile</th>
                        <th>Name</th>
                        <th>Plan</th>
                        <th>Status</th>
                        <th>Expiry</th>
                        <th>Days Left</th>
                        <th style="text-align: right;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($this->members as $member)
                        @php
                            $status = $member->membershipStatus;
                            $expiry = $status && $status->expiry_date ? Carbon::parse($status->expiry_date) : null;
                            $daysLeft = $expiry ? (int) Carbon::now()->startOfDay()->diffInDays($expiry->startOfDay(), false) : null;
                        @endphp
                        <tr>
                            <td>
                                <img src="{{ $member->profile_picture ? asset('storage/' . $member->profile_picture) : asset('assets/images/svg/default-avatar.svg') }}" 
                                     class="avatar-sm">
                            </td>
                            <td>
                                <div class="member-name-text">{{ $member->name }}</div>
                                <div class="member-email-text">{{ $member->email }}</div>
                            </td>
                            <td>{{ $status->planType ?? 'None' }}</td>
                            <td>
                                <span class="status-badge {{ match(strtolower($status->status ?? '')) { 'active' => 'status-active', 'suspended' => 'status-suspended', 'expired' => 'status-expired', default => 'status-default' } }}">
                                    {{ $status->status ?? 'Inactive' }}
                                </span>
                            </td>
                            <td>{{ $expiry ? $expiry->format('M d, Y') : 'N/A' }}</td>
                            <td>
                                @if($daysLeft !== null)
                                    <span class="days-left {{ $daysLeft <= 5 ? 'text-danger' : 'text-success' }}">
                                        {{ $daysLeft > 0 ? $daysLeft . 'd' : 'Expired' }}
                                    </span>
                                @else -- @endif
                            </td>
                            <td style="text-align: right; position: relative;" x-data="{ open: false }">
                                <button 
                                    @click="open = !open" 
                                    wire:click="toggleMenu({{ $member->id }})" 
                                    class="action-dots"
                                >
                                    &#8942;
                                </button>
                                
                                @if($openMenuId === $member->id)
                                    <div 
                                        class="dropdown-menu" 
                                        @click.away="open = false; $wire.set('openMenuId', null)"
                                    >
                                        <button wire:click="$dispatch('memberEdit', {id: {{ $member->id }}})" class="dropdown-item">Edit Profile</button>
                                        <button wire:click="$dispatch('memberEditStatus', {id: {{ $member->id }}})" class="dropdown-item border-top">Membership Status</button>
                                        <button wire:click="$dispatch('confirmDelete', {id: {{ $member->id }}})" class="dropdown-item delete border-top">Delete</button>
                                    </div>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="empty-state">No members found matching your search.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>