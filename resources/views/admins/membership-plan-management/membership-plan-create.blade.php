@extends('layouts.app')
@vite('resources/css/membership-plan-management.css')

@section('title', 'Membership Tier Create')

@section('content')
    <form action="{{route('membershipPlan.store')}}" method="POST">
        @csrf

        <h1 class="title">Membership Tier</h1>

        <label for="name">Name</label>
        <input type="text" name="name" id="name">
        @error('name')
            <div class="error-box">{{ $message }}</div>
        @enderror


        <br><br>

        <label for="tag">Tag</label>
        <input type="text" name="tag" id="tag">
        @error('tag')
            <div class="error-box">{{ $message }}</div>
        @enderror

        <br><br>

        <label for="duration">Duration (days)</label>
        <input type="number" name="duration" id="duration">
        @error('duration')
            <div class="error-box">{{ $message }}</div>
        @enderror

        <br><br>

        <label for="price">Price</label>
        <input type="number" name="price" id="price">
        @error('price')
            <div class="error-box">{{ $message }}</div>
        @enderror

        <br><br>

        <label for="description">Description</label>
        <textarea name="description" id="description"></textarea>
        @error('description')
            <div class="error-box">{{ $message }}</div>
        @enderror

        <br><br>

        <input type="submit" value="Create Membership Tier">
    </form>
@endsection



