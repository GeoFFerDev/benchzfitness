<?php
use function Livewire\Volt\{state, on, usesFileUploads, computed};
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

usesFileUploads();

state([
    'showModal' => false,
    'name' => '',
    'email' => '',
    'password' => '',
    'password_confirmation' => '',
    'profile_picture' => null,
    'existing_picture' => null,
]);

// Fetch the current authenticated admin
$admin = computed(fn() => auth()->user());

// Initialize form data
$loadData = function () {
    $this->name = $this->admin->name;
    $this->email = $this->admin->email;
    $this->existing_picture = $this->admin->profile_picture;
    $this->password = '';
    $this->password_confirmation = '';
};

$openEditModal = function () {
    $this->loadData();
    $this->resetValidation();
    $this->showModal = true;
};

$save = function () {
    $this->validate([
        'name' => 'required|string|max:255',
        'email' => ['required', 'email', Rule::unique('users')->ignore($this->admin->id)],
        'profile_picture' => 'nullable|image|max:2048',
        'password' => 'nullable|min:8|confirmed',
    ]);

    $data = [
        'name' => $this->name,
        'email' => $this->email,
    ];

    if ($this->password) {
        $data['password'] = Hash::make($this->password);
    }

    if ($this->profile_picture) {
        if ($this->admin->profile_picture) {
            Storage::disk('public')->delete($this->admin->profile_picture);
        }
        $data['profile_picture'] = $this->profile_picture->store('profile_pictures', 'public');
    }

    $this->admin->update($data);
    $this->showModal = false;
    $this->dispatch('profile-updated'); // Optional: for other components
};

$logout = function () {
    auth()->logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect()->route('login');
};
?>

<div>
    {{-- Profile Display Section --}}
    <div class="profile-wrapper">
        <div class="preview-wrap">
            <div class="preview-container">
                <img src="{{ $this->admin->profile_picture ? asset('storage/' . $this->admin->profile_picture) : asset('assets/images/svg/default-avatar.svg') }}" alt="Profile" class="avatar-sm" style="width: 100px; height: 100px;">
                <div class="profile-info-text">
                    <h1>{{ strtoupper($this->admin->name) }}</h1>
                    <h2>{{ $this->admin->email }}</h2>
                </div>
                <div class="profile-role">
                    <img src="{{ asset('assets/images/svg/user-role.svg') }}" alt="">
                    <h3>{{ ucfirst($this->admin->role) }}</h3>
                </div>
            </div>

            <div class="profile-actions">
                <button wire:click="openEditModal" class="preview-edit">
                    <img src="{{asset('assets/images/svg/edit.svg')}}" alt="">Edit Profile
                </button>
                <button wire:click="logout" class="preview-logout">
                    <img src="{{asset('assets/images/svg/logout.svg')}}" alt="">Logout
                </button>
            </div>
        </div>

        <div class="account-container">
            <h1>ACCOUNT INFORMATION</h1>
            <div class="info-wrapper">
                <div class="info-wrap">
                    <img src="{{asset('assets/images/svg/profile-name.png')}}" alt="">
                    <div>
                        <h2>Full Name</h2>
                        <h3>{{ $this->admin->name }}</h3>
                    </div>
                </div>
                <div class="info-wrap">
                    <img src="{{asset('assets/images/svg/profile-email.png')}}" alt="">
                    <div>
                        <h2>Email</h2>
                        <h3>{{ $this->admin->email }}</h3>
                    </div>
                </div>
                <div class="info-wrap">
                    <img src="{{asset('assets/images/svg/profile-role.png')}}" alt="">
                    <div>
                        <h2>Role</h2>
                        <h3>{{ ucfirst($this->admin->role) }}</h3>
                    </div>
                </div>
                <div class="info-wrap">
                    <img src="{{asset('assets/images/svg/profile-date.png')}}" alt="">
                    <div>
                        <h2>Member since</h2>
                        <h3>{{ $this->admin->created_at->format('F d, Y') }}</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Edit Profile Modal (Reusing your CSS classes) --}}
    @if($showModal)
        <div class="modal-overlay">
            <div class="modal-content">
                <h2 class="header-title-form">Edit Admin Profile</h2>
                
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

                    <div class="form-group">
                        <label class="form-label">NEW PASSWORD (LEAVE BLANK TO KEEP CURRENT)</label>
                        <input type="password" wire:model="password" class="form-input">
                        @error('password') <span class="error-text">{{ $message }}</span> @enderror
                    </div>

                    <div class="form-group mb-4">
                        <label class="form-label">CONFIRM NEW PASSWORD</label>
                        <input type="password" wire:model="password_confirmation" class="form-input">
                    </div>

                    <button type="submit" class="btn-primary w-full">Save Changes</button>
                </form>

                <button wire:click="$set('showModal', false)" class="btn-dismiss">Cancel</button>
            </div>
        </div>
    @endif
</div>