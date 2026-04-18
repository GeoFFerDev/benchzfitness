<?php

use function Livewire\Volt\{state, on, computed, uses};
use App\Models\MembershipPlans;
use Livewire\WithFileUploads;
uses(WithFileUploads::class);

state([
    'mode' => 'add',
    'show' => false, 
    'id' => null, 
    'name' => '',  
    'tag' => '',
    'duration' => null,
    'price' => null,
    'description' => '',
    'image' => null,
    'old_image' => null,
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
    $this->reset(['image']);
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

on(['membershipPlanCreate' => function (){
    $this->resetValidation();
    $this->mode = 'add';
    $this->reset(['id', 'name', 'tag', 'duration', 'price', 'description']);
    $this->show = true;
}]);

$updatePlan = function () {
    $validatedData = $this->validate([
        'name' => "required|string|max:255|unique:membership_plans,name,{$this->id}",
        'tag' => 'required|string|max:100',
        'duration' => 'required|integer|min:1',
        'price' => 'required|numeric|min:0|max:1000000',
        'description' => 'nullable|string',
        'image' => 'nullable|image|max:1024',
    ]);

    $plan = MembershipPlans::findOrFail($this->id);

    if($this->image){
        $validatedData['image_path'] = $this->image->store('membership_plans', 'public');    
    }
    $plan->update($validatedData);

    $this->show = false;

    $this->js('window.location.reload()');
};

$storePlan = function () {
    $validatedData = $this->validate([
        'name' => "required|string|max:255|unique:membership_plans,name",
        'tag' => 'required|string|max:100',
        'duration' => 'required|integer|min:1',
        'price' => 'required|numeric|min:0',
        'description' => 'nullable|string',
        'image' => 'nullable|image|max:1024',
    ]);

    if ($this->image){
        $validatedData['image_path'] = $this->image->store('membership_plans', 'public');
    }

    MembershipPlans::create($validatedData);

    $this->show = false;
    $this->js('window.location.reload()');
};

$deletePlan = function () {
    $plan = MembershipPlans::findOrFail($this->id);
    $plan->delete();

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
                            <button wire:click="deletePlan" class="delete-from-btn">
                                Yes, Delete Plan
                            </button>
                            <button wire:click="$set('show', false)" class="cancel-form-btn">
                                Nevermind
                            </button>
                        </div>
                    </div>


                @elseif($mode == 'edit')

                <div class="modal-title">
                    <h1>Edit Plan</h1>
                </div>

                    <form wire:submit="updatePlan">

                        <div class="input-wrap edit-file">
                            <label for="image">Plan Image</label>
                            <input type="file" wire:model="image" id="image" accept="image/*">
                        </div>

                        @if ($image)
                            <div class="preview mt-2">
                                <img src="{{ $image->temporaryUrl() }}" width="100">
                            </div>
                        @endif
                        @error('image') 
                            <div class="error-message">{{ $message }}</div> 
                        @enderror
                        
                        <div class="input-wrap">
                            <label for="name">Name <span>*</span></label>
                            <input type="text" wire:model="name" id="name">
                            @error('name')
                                <div class="error-message">
                                    {{$message}}
                                </div>
                            @enderror
                        </div>

                        <div class="input-wrap">
                            <label for="tag">Tag <span>*</span></label>
                            <input type="text" wire:model="tag" id="tag">
                            @error('tag')
                                <div class="error-message">
                                    {{$message}}
                                </div>
                            @enderror
                        </div>

                        <div class="input-wrap-two">
                            <div class="input-wrap">
                                <label for="duration">Duration (Day/s)<span>*</span></label>
                                <input type="number" wire:model="duration" id="duration">
                                @error('duration')
                                    <div class="error-message">
                                        {{$message}}
                                    </div>
                                @enderror
                            </div>

                            <div class="input-wrap">
                                <label for="price">Price <span>*</span></label>
                                <input type="number" wire:model="price" id="price">
                                @error('price')
                                    <div class="error-message">
                                        {{$message}}
                                    </div>
                                @enderror
                            </div>

                        </div>

                        <div class="input-wrap">
                            <label for="description">Description</label>
                            <textarea wire:model="description" id="description" rows="4"></textarea>
                            @error('description')
                                <div class="error-message">
                                    {{$message}}
                                </div>
                            @enderror
                        </div>

                        <button type="submit" class="submit-form-btn">
                            Save Changes
                        </button>

                        <button type="button" wire:click="$set('show', false)" class="cancel-form-btn">
                                Cancel
                        </button>

        
                    </form>
                @else

                <div class="modal-title">
                    <h1>Create Plan</h1>
                </div>

                    <form wire:submit="storePlan"> 

                            <div class="input-wrap input-file">
                                <label for="image">Plan Image</label>
                                <input type="file" wire:model="image" id="image" accept="image/*">
                            </div>

                            @if ($image)
                                <div class="preview mt-2">
                                    <img src="{{ $image->temporaryUrl() }}" width="100">
                                </div>
                            @endif
                            @error('image') 
                                <div class="error-message">{{ $message }}</div> 
                            @enderror

                            <div class="input-wrap">
                                <label for="name">Name <span>*</span></label>
                                <input type="text" wire:model="name" id="name">
                                @error('name')
                                    <div class="error-message">
                                        {{$message}}
                                    </div>
                                @enderror
                            </div>

                            <div class="input-wrap">
                                <label for="tag">Tag <span>*</span></label>
                                <input type="text" wire:model="tag" id="tag">
                                @error('tag')
                                    <div class="error-message">
                                        {{$message}}
                                    </div>
                                @enderror
                            </div>

                            <div class="input-wrap-two">
                                <div class="input-wrap">
                                    <label for="duration">Duration (Day/s)<span>*</span></label>
                                    <input type="number" wire:model="duration" id="duration">
                                    @error('duration')
                                        <div class="error-message">
                                            {{$message}}
                                        </div>
                                    @enderror
                                </div>

                                <div class="input-wrap">
                                    <label for="price">Price <span>*</span></label>
                                    <input type="number" wire:model="price" id="price">
                                    @error('price')
                                        <div class="error-message">
                                            {{$message}}
                                        </div>
                                    @enderror
                                </div>

                            </div>

                            <div class="input-wrap">
                                <label for="description">Description</label>
                                <textarea wire:model="description" id="description" rows="4"></textarea>
                                @error('description')
                                    <div class="error-message">
                                        {{$message}}
                                    </div>
                                @enderror
                            </div>

                            <button type="submit" class="submit-form-btn">
                                Save Changes
                            </button>

                            <button type="button" wire:click="$set('show', false)" class="cancel-form-btn">
                                Cancel
                            </button>
                    </form>
                @endif
            </div>
        </div>
        
    @endif
</div>