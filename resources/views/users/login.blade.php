@extends('layouts.app')

@section('title', 'Account Login')
@section('content')


    <h1 class="header-title">
        LOGIN
    </h1>
    
    <form action="/login" method="POST">
        @csrf
        <label for="userName">Email</label>
        <input type="text" name="email" id="userName">
        @error('email')
            <div class="error-box" >
                {{ $message }}
            </div>
        @enderror
        <br><br>
        <label for="userPass">password</label>
        <input type="password" name="password" id="userPass">
        @error('password')
            <div class="error-box" >
                {{ $message }}
            </div>
        @enderror
        <br><br>
        <input type="submit" value="LOGIN">

        <div class="text-divider">Already a member?</div>

        <a href="{{route('register')}}" class="click-btn">REGISTER</a>

        
    </form>
@endsection