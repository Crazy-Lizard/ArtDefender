@extends('layouts.main-layout')

@section('title', 'ArtDefender')

@section('content')

    <div class="content">
        <img src="{{ asset('icons/logo/logo.png') }}" alt="icon" id="logo">
        <div class="buttons">
            <button class="welcome-btn main-btn" onclick="window.location.href='{{ route('map') }}'">КАРТА</button>
            @guest
                <button class="welcome-btn main-btn" onclick="window.location.href='{{ route('login') }}'">ВХОД</button>
                <button class="welcome-btn main-btn" onclick="window.location.href='{{ route('register') }}'">РЕГИСТРАЦИЯ</button>
            @endguest
            @auth
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            
                <button class="welcome-btn main-btn" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">LOGOUT</button>
            @endauth
        </div>
    </div>

@endsection
