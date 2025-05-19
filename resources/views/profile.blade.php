@extends('layouts.main-layout')

@section('title', 'ArtDefender : ' . (auth()->user()->name))

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

    <div class="slide-space">
        <div class="points-comments">
            
            <div class="toggle">
                <label>
                    <div class="toggle-states">
                        <input type="checkbox" class="double-toggle-checkbox">
                        <span class="point-state">Points</span>
                        <span class="comment-state">Comments</span>
                        <div class="double-toggle-key"></div>
                    </div>
                </label>
            </div>

            <div class="content-list">
                <div class="toggle">
                    <div class="toggle-states">
                        <span class="approve-state">YE</span>
                        <span class="waiting-state">???</span>
                        <span class="reject-state">NO</span>
                        <button class="tripple-toggle-key approve-toggle"></button>
                        <button class="tripple-toggle-key waiting-toggle" style="visibility: hidden"></button>
                        <button class="tripple-toggle-key reject-toggle" style="visibility: hidden"></button>
                        {{-- <label>
                            <div class="toggle-states">
                                <input type="checkbox" class="tripple-toggle-checkbox">
                                <div class="tripple-toggle-key"></div>
                            </div>
                        </label> --}}
                    </div>
                </div>

                {{-- <div>
                    @if ($arts->isEmpty())
                        <div class="alert alert-info">No arts.</div>
                    @else
                        <div class="row">
                            @foreach ($arts as $art)
                                <div class="card">
                                    @if ($art->image_url)
                                        <a href="{{ route('arts.show', $art->id) }}">
                                            <img src="{{ $art->image_url }}" class="image card-img-top" alt="Art image">
                                        </a>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div> --}}

            </div>
        </div>

        <div class="notification-list"></div>
    </div>

    @if(auth()->user()->isModerator())
        <div class="buttons">
            <button class="moderator-btn main-btn" onclick="window.location.href='{{ route('requests') }}'">Requests for moderation: </button>
        </div>
    @endif

@endsection