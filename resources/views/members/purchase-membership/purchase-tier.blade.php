@extends('layouts.member.member-layout')

@section('title', 'Membership Purchase')

@section('content')
<section class="member-page-shell">
    <a href="{{ url()->previous() }}" class="member-back-btn">← Back</a>

    <article class="member-card-panel purchase-panel plan-{{ strtolower($tier->name) }}">
        <div class="purchase-plan-band">{{ strtoupper($tier->name) }} PLAN</div>

        <h1>{{ $tier->name }}</h1>
        <p class="panel-subtitle">{{ $tier->tag }}</p>

        <div class="purchase-detail-grid">
            <div>
                <h2>Duration</h2>
                <p>{{ $tier->duration }} {{ $tier->duration == 1 ? 'Session' : 'Days' }}</p>
            </div>
            <div>
                <h2>Price</h2>
                <p>₱{{ number_format($tier->price, 2) }}</p>
            </div>
        </div>

        <div class="purchase-description">{{ $tier->description }}</div>

        <form action="{{ route('membership.purchase.store', $tier->id) }}" method="POST">
            @csrf
            <button type="submit" class="profile-primary-btn">Proceed to Checkout</button>
        </form>
    </article>
</section>
@endsection
