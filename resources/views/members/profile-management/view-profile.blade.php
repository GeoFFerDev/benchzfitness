@extends('layouts.app')

@section('title', 'Profile')

@vite('resources/css/memberPortal.css')

@section('content')
<a href="{{ route('member-portal') }}" class="back-btn">go back</a>   
    <div class="profile-picture">
        <img src="{{asset('storage/' . $user->profile_picture)}}" alt="">
    </div>
    
    <div class="profile-view-text">
        <h1>Full name</h1>
        <h2>{{$user->name}}</h2>
        <h1>Email</h1>
        <h2>{{$user->email}}</h2>
    </div>
    

    <a href="{{route('member.profile.edit')}}" class="edit-view-btn">edit profile</a>
    <form action="{{ route('logout') }}" method="POST" style="display: inline;">
        @csrf
        <button type="submit" 
                class="edit-cancel-btn" 
                onclick="return confirm('Are you sure you want to log out of the Benchz Portal?')">
            Logout
        </button>
    </form>
    <br><br>
    
    
@endsection