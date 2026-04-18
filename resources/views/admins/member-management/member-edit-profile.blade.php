@extends('layouts.app')

@section('title', 'Admin Portal')




@section('content')
    <form action="{{ route('memberManagement.update', $member->id) }}" method="POST">
        
        @csrf
        @method('PUT')

        <input type="file">
        
        <label for="">Name</label>
        <input type="text" name="name" value="{{$member->name}}">
        <br><br>

        <label for="">Email</label>
        <input type="text" name="email" value="{{$member->email}}">
        <br><br>

        <input type="submit" value="SAVE CHANGES">
        <br>
        <br>
        
    </form>
        <a href="{{route('memberManagement.index')}}">CANCEL</a>
@endsection