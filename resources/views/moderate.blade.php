@extends('layouts.main-layout')

@section('title', 'ArtDefender : Moderator panel')

@section('content')

    <div class="moder-header">
        <a href="/profile/{{ auth()->user()->id }}" class="header-btn left-btn">
            <img src="{{ asset('icons/white/back-white.png') }}">
        </a>

        <h1>MOdERATE THIS</h1>
    </div>

    <div>
        @if ($arts->isEmpty())
        <div class="alert alert-info">No arts awaiting moderation.</div>
        @else
            <div class="row">
                @foreach ($arts as $art)
                    <div class="card">
                        @if ($art->image_url)
                            <img src="{{ $art->image_url }}" class="card-img-top" alt="Art image">
                        @endif
                    </div>
                @endforeach
            </div>
        @endif
    </div>
@endsection