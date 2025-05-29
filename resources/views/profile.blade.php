@extends('layouts.main-layout')

@section('title', 'ArtDefender : ' . ($user->name))

@section('content')

    <div class="header">
        <a href="{{ route('map') }}" class="header-btn left-btn">
            <img src="{{ asset('icons/white/back-white.png') }}">
        </a>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                let backButton = document.querySelector('.header-btn.left-btn');
                if (backButton) {
                    backButton.addEventListener('click', function(event) {
                        event.preventDefault();
                        
                        // Проверяем специальный флаг для переходов со страницы арта
                        const fromArt = "{{ session('from_art', false) }}";
                        if (fromArt === '1') {
                            // Возвращаемся по истории браузера
                            window.history.back();
                        } else {
                            // Используем сохраненный реферер
                            let backUrl = "{{ session('profile_referrer', route('map')) }}";
                            window.location.href = backUrl;
                        }
                    });
                }
            });
        </script>

        <h1> 
            {{ $user->name }}
        </h1>
        
        @auth
            @if(auth()->user()->id == $user->id)
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
                <a class="header-btn right-btn" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <img src="{{ asset('icons/red/logout-red.png') }}">
                </a>
            @else
            <a class="header-btn right-btn" style="cursor:default"></a>
            @endif
        @endauth
        @guest
            <a class="header-btn right-btn" style="cursor:default"></a>
        @endguest
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

    {{--<div class="toggle">
        <label>
            <div class="toggle-states">
                <input type="checkbox" class="double-toggle-checkbox">
                <span class="point-state">Points</span>
                <span class="comment-state">Comments</span>
                <div class="double-toggle-key"></div>
            </div>
        </label>
    </div>--}}

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
                        </div>
                    @endforeach
                </div>
            @else
                <p class="no-arts">No approved arts</p>
            @endif
        </div>

        @auth
            {{-- @if ($art->user_id == auth()->user()->id) --}}
            @if (auth()->user()->id == $user->id)
                <!-- Waiting Arts -->
                <div class="status-block waiting">
                    <h3>Pending Arts ({{ $pendingArts->count() }})</h3>
                    @if($pendingArts->isNotEmpty())
                        <div class="arts-grid">
                            @foreach($pendingArts as $art)
                                <div class="card">
                                    <a href="{{ route('art.show', $art->id) }}">
                                        <img src="{{ $art->image_url }}" class="image card-img-top" alt="Art image">
                                    </a>
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
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="no-arts">No rejected arts</p>
                    @endif
                </div>
            @endif
        @endauth
    </div>

    @auth
        @if(auth()->user()->isModerator())
            <div class="buttons">
                <button class="moderator-btn main-btn" onclick="window.location.href='{{ route('moderation') }}'">Moderation board</button>
            </div>
        @endif
    @endauth

@endsection