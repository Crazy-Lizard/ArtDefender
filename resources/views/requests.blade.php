@extends('layouts.main-layout')

@section('title', 'ArtDefender : Moderator panel')

@section('content')

    <div class="moder-header">
        <a href="/profile/{{ auth()->user()->id }}" class="header-btn left-btn">
            <img src="{{ asset('icons/white/back-white.png') }}">
        </a>

        <h1>MODERATE THIS</h1>
    </div>

    <style>
        .card {
            display: flex;
            justify-content: center;
            align-items: center;
            width: 124px;
            height: 124px;
            border-radius: 20px;
            overflow: hidden;
            cursor: pointer;
        }
        .image {
            /* max-width: 124px; */
            max-height: 128px;
            object-fit: cover;
        }
    </style>

    <div>
        @if ($arts->isEmpty())
            <div class="alert alert-info">No arts awaiting moderation.</div>
        @else
            <div class="row">
                @foreach ($arts as $art)
                    <div class="card">
                        @if ($art->image_url)
                            <a href="{{ route('arts.moderate', $art->id) }}">
                                <img src="{{ $art->image_url }}" class="image card-img-top" alt="Art image">
                            </a>
                            {{-- !!!!!!!!!!!!!!!!!!!! --}}
                            <div class="moder-btns">
                                <form method="POST" action="{{ route('arts.approve', $art->id) }}">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" class="moder-btn approve-btn">Approve</button>
                                </form>
                                <form method="POST" action="{{ route('arts.reject', $art->id) }}">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" class="moder-btn reject-btn">Reject</button>
                                </form>
                            </div>
                            {{-- !!!!!!!!!!!!!!!!!!!!! --}}
                        @endif
                    </div>
                @endforeach
            </div>
        @endif
    </div>
@endsection