@extends('layouts.app')

@section('title', 'Member Portal')

@vite('resources/css/memberPortal.css')
@section('content')

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <a href="{{ url()->previous() }}">back</a>
        <form action="{{ route('membership.purchase.store', $tier->id)}}" method="POST">
        @csrf
        <h1>Membership Tier Purchase</h1>
        <br><br>
        <h1>{{$tier->name}}</h1>
        <br><br>
        <h1>{{$tier->tag}}</h1>
        <br><br>
        <h1>{{$tier->duration}}</h1>
        <br><br>
        <h1>{{$tier->price}}</h1>
        <br><br>
        <h1>{{$tier->description}}</h1>

        <input type="submit" value="Proceed">
    </form>

</body>
</html>

@endsection