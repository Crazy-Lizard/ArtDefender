@extends('layouts.main-layout')

@section('title', 'ArtDefender : Moderate art №' . ($art->id))

@section('content')

    <div class="moder-header">
        <a href='{{ route('requests') }}' class="header-btn left-btn">
            <img src="{{ asset('icons/white/back-white.png') }}">
        </a>

        <h1>
            Moderate Art #{{ $art->id }} by 
            <a href="/profile/{{ $art->user_id }}" style="color: whitesmoke">{{ $art->user->name }}</a>
        </h1>
    </div>

    <style>
        .card {
            display: flex;
            justify-self: center;
            justify-content: center;
            align-items: center;
            width: 372px;
            height: auto;
            border-radius: 20px;
            overflow: hidden;
            cursor: pointer;
        }
        .image {
            max-width: 372px;
            object-fit: cover;
        }
    </style>

    <div class="container">
        <div>
            <div class="card">
                @if ($art->image_url)
                    <img src="{{ $art->image_url }}" class="image card-img-top" alt="Art image">
                @endif
            </div>
            <div class="card-body">
                <h5 class="card-title">{{ $art->creator }}</h5>
                <p class="card-text">{{ $art->description }}</p>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item">Type: {{ $art->art_type }}</li>
                    <li class="list-group-item">Year: {{ $art->art_created_year }}</li>
                    <li class="list-group-item">Status: {{ $art->art_status }}</li>
                </ul>
            </div>
        </div>
    </div>
    
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
@endsection