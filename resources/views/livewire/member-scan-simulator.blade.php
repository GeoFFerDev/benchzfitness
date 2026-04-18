<?php
use function Livewire\Volt\{state, computed, on};
use App\Models\User;
use App\Models\MemberAttendanceLog;
use Carbon\Carbon;

state([
    'selectedUserId' => '',
    'showModal' => false,
    'scannedUser' => null
]);

/**
 * Fetch all members for the simulation dropdown
 */
$members = computed(fn() => User::where('role', 'member')->orderBy('name')->get());

/**
 * Logic to simulate a QR scan
 */
$simulateScan = function () {
    if (!$this->selectedUserId) return;

    $this->scannedUser = User::with('membershipStatus')->find($this->selectedUserId);
    $this->showModal = true;
};

/**
 * Approve entry: Log to database and refresh list
 */
$approveEntry = function () {
    MemberAttendanceLog::create([
        'user_id' => $this->scannedUser->id,
        'logged_at' => now(),
    ]);

    $this->reset(['showModal', 'selectedUserId', 'scannedUser']);
    
    // Dispatched to trigger refresh in attendance-list.blade.php
    $this->dispatch('member-updated'); 
};

/**
 * Deny entry: Close modal without logging
 */
$denyEntry = function () {
    $this->reset(['showModal', 'selectedUserId', 'scannedUser']);
};
?>

<div>
    {{-- SIMULATION FORM SECTION --}}
    <div class="simulate">
        <h1>SIMULATE MEMBER ENTRY</h1>
        <div class="flex gap-2">
            <select wire:model="selectedUserId" class="form-input">
                <option value="">Select a member to scan...</option>
                @foreach($this->members as $member)
                    <option value="{{ $member->id }}">{{ $member->name }} ({{ $member->email }})</option>
                @endforeach
            </select>
            <button type="button" wire:click="simulateScan" class="btn-primary">
                Scan QR
            </button>
        </div>
    </div>

    {{-- SCAN RESULTS MODAL --}}
    @if($showModal && $scannedUser)
        <div class="modal-overlay">
            <div class="modal-content">
                
                @php
                    $statusObj = $scannedUser->membershipStatus;
                    $statusName = $statusObj->status ?? 'Inactive';
                    
                    // Determine Background Color based on status
                    $statusBgColor = match(strtolower($statusName)) {
                        'active'    => '#16A34A',
                        'suspended' => '#EF4444',
                        'inactive'  => '#F59E0B',
                        'expired'   => '#EF4444', // Defaulting expired to red as well
                        default     => '#6B7280',
                    };

                    $startDate = $statusObj->start_date ?? 'N/A';
                    $expiry = $statusObj && $statusObj->expiry_date ? \Carbon\Carbon::parse($statusObj->expiry_date) : null;
                    $daysLeft = $expiry ? (int) now()->startOfDay()->diffInDays($expiry->startOfDay(), false) : null;
                @endphp

                {{-- Dynamic Background Color Applied Here --}}
                <div class="status-big-boi" style="background-color: {{ $statusBgColor }};">
                    {{ strtoupper($statusName) }}
                </div>

                <div class="detailed-wrapper">
                    {{-- Profile Information --}}
                    <div class="scan-details-wrapper">
                        <img src="{{ $scannedUser->profile_picture ? asset('storage/' . $scannedUser->profile_picture) : asset('assets/images/svg/default-avatar.svg') }}" 
                            alt="Profile Picture">
                        
                        <h3>{{ $scannedUser->name }}</h3>
                        <p>{{ $scannedUser->email }}</p>
                    </div>

                    {{-- Membership Data Grid --}}
                    <div>
                        <h1 class="info-title">MEMBERSHIP INFORMATION</h1>
                        <div class="info-grid">
                            <div>
                                <strong>Plan Type</strong>
                                {{ $statusObj->planType ?? 'None' }}
                            </div>
                            <div>
                                <strong>Days Left</strong>
                                <span class="{{ ($daysLeft !== null && $daysLeft <= 5) ? 'text-danger' : '' }}">
                                    {{ $daysLeft !== null ? ($daysLeft > 0 ? $daysLeft . ' days' : 'Expired') : '--' }}
                                </span>
                            </div>
                            <div>
                                <strong>Expiry Date</strong>
                                {{ $expiry ? $expiry->format('M d, Y') : 'N/A' }}
                            </div>
                            <div>
                                <strong>Purchased Date</strong>
                                <span>{{ $startDate }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                @php
                    $isDisabled = strtolower($statusName) !== 'active';
                @endphp

                {{-- Action Buttons --}}
                <div class="modal-actions">
                    <button 
                        wire:click="approveEntry" 
                        class="btn-approve" 
                        {{ $isDisabled ? 'disabled' : '' }}
                        wire:loading.attr="disabled"
                    >
                        @if($isDisabled)
                            Access Denied
                        @else
                            Approve Entry
                        @endif
                    </button>
                    
                    <button wire:click="denyEntry" class="btn-primary delete-btn-modify">
                        Close / Deny
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>