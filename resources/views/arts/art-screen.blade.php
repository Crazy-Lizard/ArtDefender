@extends('layouts.main-layout')

@section('title', 'ArtDefender : Art №' . ($art->id))

@section('content')

    <div class="header">
        {{-- <a href="/profile/{{ auth()->user()->id }}" class="header-btn left-btn">
            <img src="{{ asset('icons/white/back-white.png') }}">
        </a> --}}
        <a href="/profile/{{ auth()->user()->id }}" class="header-btn left-btn" onclick="event.preventDefault(); window.history.back();">
            <img src="{{ asset('icons/white/back-white.png') }}">
        </a>

        <h1>Art #{{ $art->id }}</h1>

        @if (($art->user_id == auth()->user()->id) || (auth()->user()->isModerator()))
            {{-- <a href="{{ route('arts.delete') }}" class="header-btn right-btn">
                <img src="{{ asset('icons/red/trash-red.png') }}">
            </a> --}}


            <a href="#" class="header-btn right-btn" 
                onclick="event.preventDefault();
                        if(confirm('Удалить этот арт?')) {
                            document.getElementById('delete-form-{{ $art->id }}').submit();
                        }">
                <img src="{{ asset('icons/red/trash-red.png') }}">
            </a>
            <form id="delete-form-{{ $art->id }}" 
                action="{{ route('arts.delete', $art->id) }}" 
                method="POST" 
                style="display: none;">
                @csrf
                @method('DELETE')
            </form>

        @else
            <a {{--href="{{ route('report.create') }}"--}} class="header-btn right-btn">
                <img src="{{ asset('icons/red/trash-red.png') }}">
            </a>
        @endif
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
                    {{-- <a href="{{ route('arts.moderate', $art->id) }}"> --}}
                        <img src="{{ $art->image_url }}" class="image card-img-top" alt="Art image">
                    {{-- </a> --}}
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

@endsection