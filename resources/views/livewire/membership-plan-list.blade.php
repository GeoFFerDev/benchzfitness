<?php
use function Livewire\Volt\{state, computed};
use App\Models\MembershipPlans;

state(['search' => '']);

$plans = computed(function () {
    return MembershipPlans::where('name', 'like', '%' . $this->search . '%')
        ->orWhere('tag', 'like', '%' . $this->search . '%')
        ->get();
});
?>

<div class="view-membership-wrapper">
    <div class="view-membership-header">
          <input type="text" placeholder="Search membership plan" class="search-input"
                wire:model.live.debounce.300ms="search">
        <button type="button" onclick="Livewire.dispatch('membershipPlanCreate')">+ Add Plan</button>
              
    </div>

    <div class="membership-card-wrapper" wire:target="search">
        @foreach($this->plans as $plan)
        
            <div class="membership-card">
                @if($plan->image_path)
                    <img src="{{ asset('storage/' . $plan->image_path) }}" alt="{{ $plan->name }}">
                @else
                    <img src="{{ asset('assets/images/svg/platinum.png') }}" alt="Default Plan Image">
                @endif

                <div class="membership-card-info">
                    <h1>{{ $plan->name }}</h1>
                        
                    <div class="membership-card-info-duration">
                        <img src="{{ asset('assets/images/svg/duration.svg') }}" alt="">
                        {{ $plan->duration }} 
                        {{ $plan->duration == 1 ? 'Session' : 'Days' }}
                    </div>

                    <div class="membership-card-info-tag">
                        {{ $plan->tag }}
                    </div>

                    <h2>₱{{ number_format($plan->price, 2) }}</h2>
                </div>

                <div class="membership-card-actions">
                    <button class="edit-btn-crud" type="button" 
                        onclick="Livewire.dispatch('membershipPlanEdit', {id: {{ $plan->id }}})">
                        <img src="{{ asset('assets/images/svg/edit.svg') }}" alt=""> 
                        Edit
                    </button>

                    <button class="delete-btn-crud" type="button" 
                        onclick="Livewire.dispatch('membershipPlanDelete', {id: {{ $plan->id }}})">
                        <img src="{{ asset('assets/images/svg/trash.svg') }}" alt=""> 
                        Delete
                    </button>
                </div>
            </div>
        @endforeach
    </div>
</div>