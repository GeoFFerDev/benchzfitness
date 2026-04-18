@extends('layouts.app')

@section('title', 'Edit Profile')

@vite('resources/css/memberPortal.css')

@section('content')
    <form action="{{route('member.profile.edit.password.update')}}" method="POST">
        @csrf    

        <label for="current_password">Current Password</label>
        <input type="password" name="current_password" id="current_password">
        <br><br>

        <label for="new_password">New Password</label>
        <input type="password" name="new_password" id="new_password">
        <br><br>

        <label for="new_password_confirmation">Confirm New Password</label>
        <input type="password" name="new_password_confirmation" id="new_password_confirmation">
        <br><br>

        <input type="submit" value="SAVE CHANGES">
    </form>

@endsection