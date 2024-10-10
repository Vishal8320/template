@extends('layout')

@section('content')
    <h1>Welcome, {{ $user['name'] }}</h1>
    <p>This is your dashboard.</p>

    @include('partials/sidebar')
@endsection

