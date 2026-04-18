@extends('layouts.app')

@section('title', 'Edit Profile')

@vite('resources/css/memberPortal.css')

@section('content')
    <form action="{{ route('member.profile.update') }}" method="POST" class="edit-view">
        @csrf
        
        <label for="">Full Name</label>
        <input type="text" name="name" value="{{$user->name}}"><br><br>
        <label for="">Email</label>
        <input type="email" name="email" value="{{$user->email}}"><br><br>

        <label for="profile">Profile Picture</label>
        <input type="file" name="profile_picture" id="profile" accept="image/*" >

        <br><br>
        @error('profile_picture')
            <div class="error-box" >{{ $message }}</div>
        @enderror

        <input type="submit" value="SAVE CHANGES">

    </form>
    <a href="{{route('member.profile')}}" class="edit-cancel-btn"> CANCEL</a>

    <div class="edit-pass-change">
        <h1>Password</h1>
        <a href="{{route('member.profile.edit.password')}}">CHANGE PASSWORD</a>
    </div>

@endsection