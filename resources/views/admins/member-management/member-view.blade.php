@extends('layouts.app')

@section('title', 'Admin Portal')




@section('content')

    <h1 class="head-title">Member List</h1>
    <div class="member-table-div">
        <table class="member-table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Plan</th>
                        <th>Status</th>
                        <th>Expiry</th>
                        <th>Days Left</th>
                        <th>Last Check-in</th>
                    </tr>
                </thead>
                <tbody>
                   @foreach($members as $member)
                        <tr class="member-row cursor-pointer" data-id="{{ $member->id }}" data-name="{{ $member->name }}">
                            <td>{{$member->name}}</td>
                            <td>{{$member->email}}</td>
                            <td>{{$member->membershipStatus->planType}}</td>
                            <td>{{$member->membershipStatus->status}}</td>
                            <td>{{$member->membershipStatus->expiry}}</td>
                            <td></td>
                            <td>{{$member->membershipStatus->last_check_in}}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>


        <div id="memberActionModal" class="modal hidden">
        <div class="modal-content">
            <h3 id="modalName"></h3>

            <a id="editLink" href="#">Edit Profile</a>
            <a id="editLinkStatus" href="#">Edit Membership Status</a>

            <form id="deleteForm" method="POST">
                @csrf
                @method('DELETE')

                <button type="submit" onclick="return confirm('Delete this member?')">
                    Delete Member
                </button>
            </form>

            <button onclick="closeModal()">Close</button>
        </div>
    </div>
@endsection