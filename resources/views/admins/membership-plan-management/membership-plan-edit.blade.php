@extends('layouts.app')

@section('title', 'Membership Tier Create')
@vite('resources/css/membership-plan-management.css')

@section('content')
    <form action="{{ route('membershipPlan.update', $plan->id) }}" method="POST">
        @csrf
        @method('PUT')

        <h1 class="title">Edit Membership Plan</h1>

        <label for="name">Name</label>
        <input type="text" name="name" id="name" value="{{$plan->name}}">
        @error('name')
            <div class="error-box">{{ $message }}</div>
        @enderror

        <br><br>

        <label for="tag">Tag</label>
        <input type="text" name="tag" id="tag" value="{{$plan->tag}}">
        @error('tag')
            <div class="error-box">{{ $message }}</div>
        @enderror

        <br><br>

        <label for="duration">Duration (days)</label>
        <input type="number" name="duration" id="duration" value="{{$plan->duration}}">
        @error('duration')
            <div class="error-box">{{ $message }}</div>
        @enderror

        <br><br>

        <label for="price">Price</label>
        <input type="number" name="price" id="price" value="{{$plan->price}}">
        @error('price')
            <div class="error-box">{{ $message }}</div>
        @enderror

        <br><br>

        <label for="description">Description</label >
        <textarea name="description" id="description">{{$plan->description}}</textarea>
        @error('price')
            <div class="error-box">{{ $message }}</div>
        @enderror

        <br><br>

        <input type="submit" value="SAVE CHANGES">
    </form>
@endsection



