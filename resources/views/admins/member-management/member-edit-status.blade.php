@extends('layouts.app')

@section('title', 'Admin Portal')




@section('content')
    <form action="{{ route('memberManagement.updateStatus', $member->id) }}" method="POST">
        
        @csrf
        @method('PUT')
        
        <label for="">Name</label>
        <input type="text" name="planType" value="{{$member->membershipStatus->planType}}">
        <br><br>

        <label for="status">Membership Status</label>
        <br>
        <select name="status" id="status">
            <option value="Active" {{ $member->membershipStatus->status == 'Active' ? 'selected' : '' }}>
                Active
            </option>
            <option value="Inactive" {{ $member->membershipStatus->status == 'Inactive' ? 'selected' : '' }}>
                Inactive
            </option>
            <option value="Suspended" {{ $member->membershipStatus->status == 'Suspended' ? 'selected' : '' }}>
                Suspended
            </option>
        </select>
        <br><br>

        <input type="submit" value="SAVE CHANGES">
        <br>
        <br>
        
    </form>
        <a href="{{route('memberManagement.index')}}">CANCEL</a>
@endsection