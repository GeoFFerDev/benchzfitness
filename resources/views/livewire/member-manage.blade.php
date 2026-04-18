<?php
use function Livewire\Volt\{state, on, computed, usesFileUploads, updated};
use App\Models\User;
use App\Models\MembershipStatus;
use App\Models\MembershipPlans; 
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

usesFileUploads();

state([
    'show' => false, 'mode' => 'edit', 'memberId' => null,
    'name' => '', 'email' => '',
    'profile_picture' => null, 'existing_picture' => null,
    'status' => 'Inactive', 'planType' => 'None', 'expiry_date' => '',
]);

$plans = computed(fn() => MembershipPlans::all());

updated(['planType' => function ($value) {
    if ($value === 'None') { 
        $this->expiry_date = null; 
        $this->status = 'Inactive';
        return; 
    }
    $plan = MembershipPlans::where('name', $value)->first();
    if ($plan) {
        $this->expiry_date = now()->addDays((int)$plan->duration)->format('Y-m-d');
        $this->status = 'Active';
    }
}]);

on(['memberEdit' => function ($id) {
    $this->resetValidation();
    $user = User::findOrFail($id);
    $this->memberId = $id; 
    $this->name = $user->name; 
    $this->email = $user->email;
    $this->existing_picture = $user->profile_picture;
    $this->mode = 'edit'; 
    $this->show = true;
}]);

on(['memberEditStatus' => function ($id) {
    $this->resetValidation();
    $user = User::with('membershipStatus')->findOrFail($id);
    $this->memberId = $id;
    $this->status = $user->membershipStatus->status ?? 'Inactive';
    $this->planType = $user->membershipStatus->planType ?? 'None';
    $this->expiry_date = $user->membershipStatus->expiry_date;
    $this->mode = 'status'; $this->show = true;
}]);

on(['confirmDelete' => function ($id) {
    $user = User::find($id);
    if ($user) {
        $this->memberId = $id;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->mode = 'delete';
        $this->show = true;
    }
}]);

$save = function () {
    if ($this->mode === 'status') {
        $this->validate([
            'status' => 'required|in:Active,Inactive,Suspended,Expired',
            'planType' => 'required',
            'expiry_date' => 'nullable|date',
        ]);

        MembershipStatus::updateOrCreate(
            ['user_id' => $this->memberId],
            ['status' => $this->status, 'planType' => $this->planType, 'expiry_date' => $this->expiry_date]
        );
    } else {
        $rules = [
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users')->ignore($this->memberId)],
            'profile_picture' => 'nullable|image|max:2048',
        ];

        $this->validate($rules);

        $user = User::findOrFail($this->memberId);
        $user->update(['name' => $this->name, 'email' => $this->email]);

        if ($this->profile_picture) {
            if ($user->profile_picture) Storage::disk('public')->delete($user->profile_picture);
            $user->update(['profile_picture' => $this->profile_picture->store('profile_pictures', 'public')]);
        }
    }

    $this->show = false;
    $this->dispatch('member-updated');
};

$deleteMember = function () {
    User::find($this->memberId)?->delete();
    $this->show = false;
    $this->dispatch('member-updated');
};
?>

<div>
    @if($show)
        <div class="modal-overlay">
            <div class="modal-content">
                @if($mode === 'delete')
                    <h2 class="text-danger">DELETE MEMBER?</h2>
                    <p class="modal-subtext">Are you sure you want to delete user <strong>{{$this->name}} ({{$this->email}})</strong>. This action cannot be undone.</p>
                    <div class="modal-actions">
                        <button wire:click="deleteMember" class="btn-primary delete-btn-modify">Delete</button>
                    </div>

                @elseif($mode === 'status')
                    <h2 class="header-title-form">Membership Status</h2>

                    <form wire:submit.prevent="save">
                        <div class="form-group">
                            <label class="form-label">PLAN TYPE</label>
                            <select wire:model.live="planType" class="form-input">
                                <option value="None">No Active Plan</option>
                                @foreach($this->plans as $plan) <option value="{{ $plan->name }}">{{ $plan->name }}</option> @endforeach
                            </select>
                            @error('planType') <span class="error-text">{{ $message }}</span> @enderror
                        </div>
                        <div class="form-group">
                            <label class="form-label">ACCOUNT STATUS</label>
                            <select wire:model="status" class="form-input">
                                <option value="Active">Active</option>
                                <option value="Inactive">Inactive</option>
                                <option value="Suspended">Suspended</option>
                                <option value="Expired">Expired</option>
                            </select>
                            @error('status') <span class="error-text">{{ $message }}</span> @enderror
                        </div>
                        <div class="form-group mb-4">
                            <label class="form-label">EXPIRY DATE</label>
                            <input type="date" wire:model="expiry_date" class="form-input">
                        </div>
                        <button type="submit" class="btn-primary w-full">Update Membership</button>
                    </form>

                @else
                    <h2 class="header-title-form">Edit Profile</h2>
                    <form wire:submit.prevent="save">
                        <div class="avatar-upload-container">
                            <label class="avatar-label">
                                <div class="avatar-preview">
                                    @if($profile_picture)
                                        <img src="{{ $profile_picture->temporaryUrl() }}" class="full-img">
                                    @elseif($existing_picture)
                                        <img src="{{ asset('storage/' . $existing_picture) }}" class="full-img">
                                    @else
                                        <span class="upload-placeholder">UPLOAD</span>
                                    @endif
                                </div>
                                <input type="file" wire:model="profile_picture" class="hidden">
                            </label>
                            @error('profile_picture') <div class="error-text">{{ $message }}</div> @enderror
                        </div>

                        <div class="form-group">
                            <label class="form-label">FULL NAME</label>
                            <input type="text" wire:model="name" class="form-input">
                            @error('name') <span class="error-text">{{ $message }}</span> @enderror
                        </div>
                        <div class="form-group">
                            <label class="form-label">EMAIL ADDRESS</label>
                            <input type="email" wire:model="email" class="form-input">
                            @error('email') <span class="error-text">{{ $message }}</span> @enderror
                        </div>
                        <button type="submit" class="btn-primary w-full">Save</button>
                    </form>
                @endif
                <button wire:click="$set('show', false)" class="btn-dismiss">Cancel</button>
            </div>
        </div>
    @endif
</div>