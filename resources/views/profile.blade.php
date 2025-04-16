@extends('layouts.main-layout')

@section('title', 'ArtDefender')

@section('content')

    <div class="header">
        <a href="{{ route('map') }}" class="header-btn left-btn">
            <img src="{{ asset('icons/white/back-white.png') }}">
        </a>

        <h1> 
            {{ auth()->user()->name }}
        </h1>
        
        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
            @csrf
        </form>
        <a class="header-btn right-btn" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
            <img src="{{ asset('icons/red/logout-red.png') }}">
        </a>
    </div>

    @if(auth()->user()->isModerator())
        <!-- Контент для модераторов -->
        {{-- <a>ddfdfsdfsdfsdf</a> --}}
    @endif

@endsection