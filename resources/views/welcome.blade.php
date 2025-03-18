@extends('layouts.main-layout')

@section('title', 'ArtDefender')

@section('content')

    <div class="content">
        <img src="{{ asset('icons/logo.png') }}" alt="icon" id="logo">
        <div class="buttons">
            <button class="main-btn" onclick="window.location.href='{{ route('map') }}'">GO TO MAP</button>
            <button class="main-btn" onclick="window.location.href='{{ route('login') }}'">LOGIN</button>
            <button class="main-btn" onclick="window.location.href='{{ route('register') }}'">REGISTER</button>
        </div>
    </div>

@endsection
