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

    {{-- <div class="slide-space">
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
                    {{-- </div>
                </div> --}}

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

            {{--</div>
        </div>

        <div class="notification-list"></div>
    </div> --}}
    <div class="art-status-blocks slide-space">
        <!-- Approved Arts -->
        <div class="status-block approved">
            <h3>Approved Arts ({{ $approvedArts->count() }})</h3>
            @if($approvedArts->isNotEmpty())
                <div class="arts-grid">
                    @foreach($approvedArts as $art)
                        <div class="card">
                            <a href="{{ route('art.show', $art->id) }}">
                                <img src="{{ $art->image_url }}" class="image card-img-top" alt="Art image">
                            </a>
                            {{-- <img src="{{ $art->image_url }}" alt="Art image"> --}}
                            {{-- <p>{{ $art->description }}</p> --}}
                        </div>
                    @endforeach
                </div>
            @else
                <p class="no-arts">No approved arts</p>
            @endif
        </div>

        @if ($art->user_id == auth()->user()->id)
            <!-- Waiting Arts -->
            <div class="status-block waiting">
                <h3>Pending Arts ({{ $waitingArts->count() }})</h3>
                @if($waitingArts->isNotEmpty())
                    <div class="arts-grid">
                        @foreach($waitingArts as $art)
                            <div class="card">
                                <a href="{{ route('art.show', $art->id) }}">
                                    <img src="{{ $art->image_url }}" class="image card-img-top" alt="Art image">
                                </a>
                                {{-- <img src="{{ $art->image_url }}" alt="Art image"> --}}
                                {{-- <p>{{ $art->description }}</p> --}}
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="no-arts">No pending arts</p>
                @endif
            </div>

            <!-- Rejected Arts -->
            <div class="status-block rejected">
                <h3>Rejected Arts ({{ $rejectedArts->count() }})</h3>
                @if($rejectedArts->isNotEmpty())
                    <div class="arts-grid">
                        @foreach($rejectedArts as $art)
                            <div class="card">
                                <a href="{{ route('art.show', $art->id) }}">
                                    <img src="{{ $art->image_url }}" class="image card-img-top" alt="Art image">
                                </a>
                                {{-- <img src="{{ $art->image_url }}" alt="Art image">
                                <p>{{ $art->description }}</p> --}}
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="no-arts">No rejected arts</p>
                @endif
            </div>
        @endif
    </div>

    @if(auth()->user()->isModerator())
        <div class="buttons">
            <button class="moderator-btn main-btn" onclick="window.location.href='{{ route('requests') }}'">Moderation board</button>
        </div>
    @endif

@endsection