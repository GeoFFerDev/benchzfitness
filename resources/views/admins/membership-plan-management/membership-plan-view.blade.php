@extends('layouts.app')

@section('title', 'Membership Tier Create')
@vite('resources/css/membership-plan-management.css')


@section('content')
    <a href="{{ url()->previous() }}" class="clickable-button" style="margin-top: 30px">BACK</a>
    <h1 class="title">Manage Membership Plan</h1>
    
    <div class="search">
        
        <form action="{{route('membershipPlan.search')}}" method="GET">
            <input type="text" name="search" placeholder="search here">
            <input class="search-btn" type="submit" value="search">
        </form>
    </div>
 

    <a class="anchor-block" href="{{route('membershipPlan.create')}}">
        <div class="clickable-button"> 
            + Create new plan
        </div>
    </a>
    
    @foreach($plans as $plan)
        <div class="membership-cards">
            <div class="membership-card">
                <div class="sub-card">
                    <h1 class="card-title">{{$plan->name}}</h1> 
                    <div class="tag">{{$plan->tag}}</div>
                </div>
                
                <h1>{{$plan->duration}} day/s</h1>
                <h1>₱{{$plan->price}}</h1>
                <div class="membership-card-description">
                    <h1>Description</h1>
                    <h1>{{$plan->description}} </h1>
                </div>
            </div>
            <div class="buttons">
                <a href="{{route('membershipPlan.edit', $plan->id)}}"><div class="edit-btn">EDIT</div></a>
                <form action="{{route('membershipPlan.destroy', $plan->id)}}" method="POST" onsubmit="return confirm('Delete this plan?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="delete-btn">
                        DELETE
                    </button>
                </form>
            </div>
        </div>
        <br><br>
    @endforeach

@endsection