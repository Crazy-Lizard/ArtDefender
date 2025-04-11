@extends('layouts.core-layout')

@section('title', 'ArtDefender')

@section('content')

    <div class="interface top-interface">
        <a href="{{ route('map') }}" class="interface-btn left-btn">
            <img src="{{ asset('icons/white/back-white.png') }}">
        </a>
        
        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
            @csrf
        </form>    
        <a class="interface-btn right-btn" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
            <img src="{{ asset('icons/red/logout-red.png') }}">
        </a>
    </div>

    @if(auth()->user()->isModerator())
        <!-- Контент для модераторов -->
        {{-- <a>ddfdfsdfsdfsdf</a> --}}
    @endif

@endsection