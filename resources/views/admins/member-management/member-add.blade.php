@extends('layouts.app')

@section('title', 'Account Register')
@section('content')

    <h1 class="header-title">
        REGISTER
    </h1>

    <form action="/register" method="POST">
    @csrf

        <label for="fullName">Full Name</label><br>
        <input type="text" name="name" id="fullName" >
        @error('name')
            <div class="error-box">{{ $message }}</div>
        @enderror
        <br><br>

        <label for="email">Email</label><br>
        <input type="email" name="email" id="email" >
        @error('email')
            <div class="error-box">{{ $message }}</div>
        @enderror
        <br><br>

        <label for="password">Password</label><br>
        <input type="password" name="password" id="password" >
        @error('password')
            <div class="error-box" >{{ $message }}</div>
        @enderror
        <br><br>
        
        <label for="passwordAgain">Re-enter Password</label><br>
        <input type="password" name="password_confirmation" id="passwordAgain" >
        @error('password_confirmation')
            <div class="error-box" >{{ $message }}</div>
        @enderror
        <br><br>

        <label for="profile">profile</label><br>
        <input type="text" name="profile_picture" id="profile" >
        <br><br>
        @error('profile_picture')
            <div class="error-box" >{{ $message }}</div>
        @enderror

        <input type="submit">
        
    </form>

@endsection
