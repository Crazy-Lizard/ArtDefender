@extends('layouts.main-layout')

@section('title', 'ArtDefender : Art №' . ($art->id))

@section('content')

    <div class="header">
        <a href="/profile/{{ auth()->user()->id }}" class="header-btn left-btn">
            <img src="{{ asset('icons/white/back-white.png') }}">
        </a>

        <h1></h1>

        @if ($art->user_id == auth()->user()->id)
            <a {{--href="{{ route('arts.delete') }}"--}} class="header-btn right-btn">
                <img src="{{ asset('icons/red/trash-red.png') }}">
            </a>
        @else
            <a {{--href="{{ route('report.create') }}"--}} class="header-btn right-btn">
                <img src="{{ asset('icons/red/trash-red.png') }}">
            </a>
        @endif
    </div>

@endsection