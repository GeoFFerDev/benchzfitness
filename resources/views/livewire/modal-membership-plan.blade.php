<?php

use function Livewire\Volt\{state, on};
use App\Models\MembershipPlans;

state([
    'planOptions' => ['Bronze', 'Silver', 'Gold', 'Platinum'],
    'mode' => 'add',
    'show' => false,
    'id' => null,
    'name' => '',
    'tag' => '',
    'duration' => null,
    'price' => null,
    'description' => '',
]);

on(['membershipPlanDelete' => function ($id) {
    $this->mode = 'delete';
    $this->id = $id;

    $plan = MembershipPlans::findOrFail($id);
    $this->name = $plan->name;
    $this->show = true;
}]);

on(['membershipPlanEdit' => function ($id) {
    $this->resetValidation();
    $this->mode = 'edit';
    $this->id = $id;

    $plan = MembershipPlans::findOrFail($this->id);

    $this->name = $plan->name;
    $this->tag = $plan->tag;
    $this->duration = $plan->duration;
    $this->price = $plan->price;
    $this->description = $plan->description;

    $this->show = true;
}]);

on(['membershipPlanCreate' => function () {
    $this->resetValidation();
    $this->mode = 'add';
    $this->reset(['id', 'name', 'tag', 'duration', 'price', 'description']);
    $this->show = true;
}]);

$updatePlan = function () {
    $validatedData = $this->validate([
        'name' => 'required|in:' . implode(',', $this->planOptions) . "|unique:membership_plans,name,{$this->id}",
        'tag' => 'required|string|max:100',
        'duration' => 'required|integer|min:1',
        'price' => 'required|numeric|min:0|max:1000000',
        'description' => 'nullable|string',
    ]);

    MembershipPlans::findOrFail($this->id)->update($validatedData);

    $this->show = false;
    $this->js('window.location.reload()');
};

$storePlan = function () {
    $validatedData = $this->validate([
        'name' => 'required|in:' . implode(',', $this->planOptions) . '|unique:membership_plans,name',
        'tag' => 'required|string|max:100',
        'duration' => 'required|integer|min:1',
        'price' => 'required|numeric|min:0',
        'description' => 'nullable|string',
    ]);

    MembershipPlans::create($validatedData);

    $this->show = false;
    $this->js('window.location.reload()');
};

$deletePlan = function () {
    MembershipPlans::findOrFail($this->id)->delete();

    $this->show = false;
    $this->js('window.location.reload()');
};
?>

<div>
    @if($show)
        <div class="membership-plan-modal-overlay">
            <div class="modal-box">
                @if($mode == 'delete')
                    <div class="modal-title delete-title">
                        <h1>Delete Membership Plan?</h1>
                    </div>

                    <div class="delete-confirmation">
                        <p>Are you sure you want to delete <b>{{ $name }}</b>? This action cannot be undone.</p>

                        <div class="modal-actions">
                            <button wire:click="deletePlan" class="delete-from-btn">Yes, Delete Plan</button>
                            <button wire:click="$set('show', false)" class="cancel-form-btn">Nevermind</button>
                        </div>
                    </div>
                @else
                    <div class="modal-title">
                        <h1>{{ $mode == 'edit' ? 'Edit Plan' : 'Create Plan' }}</h1>
                    </div>

                    <form wire:submit="{{ $mode == 'edit' ? 'updatePlan' : 'storePlan' }}">
                        <div class="input-wrap">
                            <label for="name">Name <span>*</span></label>
                            <select wire:model="name" id="name">
                                <option value="">Select Plan</option>
                                @foreach($this->planOptions as $planOption)
                                    <option value="{{ $planOption }}">{{ $planOption }}</option>
                                @endforeach
                            </select>
                            @error('name') <div class="error-message">{{ $message }}</div> @enderror
                        </div>

                        <div class="input-wrap">
                            <label for="tag">Tag <span>*</span></label>
                            <input type="text" wire:model="tag" id="tag">
                            @error('tag') <div class="error-message">{{ $message }}</div> @enderror
                        </div>

                        <div class="input-wrap-two">
                            <div class="input-wrap">
                                <label for="duration">Duration (Day/s)<span>*</span></label>
                                <input type="number" wire:model="duration" id="duration">
                                @error('duration') <div class="error-message">{{ $message }}</div> @enderror
                            </div>

                            <div class="input-wrap">
                                <label for="price">Price <span>*</span></label>
                                <input type="number" wire:model="price" id="price">
                                @error('price') <div class="error-message">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <div class="input-wrap">
                            <label for="description">Description</label>
                            <textarea wire:model="description" id="description" rows="4"></textarea>
                            @error('description') <div class="error-message">{{ $message }}</div> @enderror
                        </div>

                        <button type="submit" class="submit-form-btn">Save Changes</button>
                        <button type="button" wire:click="$set('show', false)" class="cancel-form-btn">Cancel</button>
                    </form>
                @endif
            </div>
        </div>
    @endif
</div>
