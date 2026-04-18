@extends('layouts.app')

@section('title', 'Admin Portal')

@vite('resources/css/adminPortal.css')


@section('content')

    <div class="manage-membership-plan">
        <div class="title">
            Manage Membership Plan   
        </div>

        <a href="{{ route('membershipPlan.index') }}">Manage</a>
        
    </div>

    <br>

    <div class="">
        <div class="title">
            Manage Members   
        </div>

        <div class="summary-card-wrapper">
            <div class="summary-card">
                <h1>Total Members</h1>
                <div>
                    <h2>100</h2>
                </div>
            </div>
            <div class="summary-card">
                <h1>Active Members</h1>
                <div>
                    <h2>100</h2>
                </div>
            </div>
            <div class="summary-card">
                <h1>Expired Members</h1>
                <div>
                    <h2>100</h2>
                </div>
            </div>

        </div>

    

        

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
                        <tr>
                            <td>{{$member->name}}</td>
                            <td>{{$member->email}}</td>
                            <td>{{$member->membershipStatus->planType}}</td>
                            <td>{{$member->membershipStatus->status}}</td>
                            <td>{{$member->membershipStatus->expiry}}</td>
                            <td>{{$member->name}}</td>
                            <td>{{$member->membershipStatus->last_check_in}}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <a href="{{route('memberManagement.index')}}" class="manageMember">Manage Members</a>
        
    </div>
@endsection