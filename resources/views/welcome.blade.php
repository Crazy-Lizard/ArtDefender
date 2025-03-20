@extends('layouts.main-layout')

@section('title', 'ArtDefender')

@section('content')

    <div class="content">
        <img src="{{ asset('icons/logo.png') }}" alt="icon" id="logo">
        <div class="buttons">
            <button class="main-btn" onclick="window.location.href='{{ route('map') }}'">GO TO MAP</button>
            @guest
                <button class="main-btn" onclick="window.location.href='{{ route('login') }}'">LOGIN</button>
                <button class="main-btn" onclick="window.location.href='{{ route('register') }}'">REGISTER</button>
            @endguest
            @auth
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            
                <button class="main-btn" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">LOGOUT</button>
            @endauth
        </div>
    </div>

@endsection
