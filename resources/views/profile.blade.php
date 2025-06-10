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
        /* .card {
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
            max-height: 128px;
            object-fit: cover;
        } */
        /* Удалите старые стили .card и .image */
        .arts-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr); /* 3 колонки */
            /*gap: 2px;*/ /* Расстояние между элементами */
            width: 100%;
            background-color: #502683; 
            border-radius: 20px;
            box-shadow: inset 0px 0px 10px black;
        }

        .card {
            width: 124px;
            height: 124px;
            border-radius: 20px;
            overflow: hidden;
            cursor: pointer;
            position: relative;
            margin: 2px;
        }

        .image-wrapper {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            min-width: 124px;
            min-height: 124px;
        }

        /* Для вертикальных изображений (высота > ширины) */
        .image-portrait {
            width: 125px;
            height: auto;
            min-height: 128px;
        }

        /* Для горизонтальных изображений (ширина > высоты) */
        .image-landscape {
            height: 128px;
            width: auto;
            min-width: 125px;
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
            <h3>Опубликованные арты: ({{ $approvedArts->count() }})</h3>
            @if($approvedArts->isNotEmpty())
                <div class="arts-grid">
                    @foreach($approvedArts as $art)
                        <div class="card">
                            <a href="{{ route('art.show', $art->id) }}">
                                <div class="image-wrapper">
                                    @php
                                        // Определяем ориентацию изображения
                                        list($width, $height) = getimagesize(public_path(parse_url($art->image_url, PHP_URL_PATH)));
                                        $orientation = ($width > $height) ? 'landscape' : 'portrait';
                                    @endphp
                                    <img 
                                        src="{{ $art->image_url }}" 
                                        class="image-{{ $orientation }}" 
                                        alt="Art image"
                                        onerror="this.onerror=null; this.classList.add('image-error')"
                                    >
                                </div>
                                {{-- <img src="{{ $art->image_url }}" class="image card-img-top" alt="Art image"> --}}
                            </a>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="no-arts">Нет опубликованных артов</p>
            @endif
        </div>

        @auth
            {{-- @if ($art->user_id == auth()->user()->id) --}}
            @if (auth()->user()->id == $user->id)
                <!-- Waiting Arts -->
                <div class="status-block waiting">
                    <h3>Модерирующиеся арты: ({{ $pendingArts->count() }})</h3>
                    @if($pendingArts->isNotEmpty())
                        <div class="arts-grid">
                            @foreach($pendingArts as $art)
                                <div class="card">
                                    <a href="{{ route('art.show', $art->id) }}">
                                        <div class="image-wrapper">
                                            @php
                                                // Определяем ориентацию изображения
                                                list($width, $height) = getimagesize(public_path(parse_url($art->image_url, PHP_URL_PATH)));
                                                $orientation = ($width > $height) ? 'landscape' : 'portrait';
                                            @endphp
                                            <img 
                                                src="{{ $art->image_url }}" 
                                                class="image-{{ $orientation }}" 
                                                alt="Art image"
                                                onerror="this.onerror=null; this.classList.add('image-error')"
                                            >
                                        </div>
                                        {{-- <img src="{{ $art->image_url }}" class="image card-img-top" alt="Art image"> --}}
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="no-arts">Нет модерирующихся артов</p>
                    @endif
                </div>

                <!-- Rejected Arts -->
                <div class="status-block rejected">
                    <h3>Отклонённые арты: ({{ $rejectedArts->count() }})</h3>
                    @if($rejectedArts->isNotEmpty())
                        <div class="arts-grid">
                            @foreach($rejectedArts as $art)
                                <div class="card">
                                    <a href="{{ route('art.show', $art->id) }}">
                                        <div class="image-wrapper">
                                            @php
                                                // Определяем ориентацию изображения
                                                list($width, $height) = getimagesize(public_path(parse_url($art->image_url, PHP_URL_PATH)));
                                                $orientation = ($width > $height) ? 'landscape' : 'portrait';
                                            @endphp
                                            <img 
                                                src="{{ $art->image_url }}" 
                                                class="image-{{ $orientation }}" 
                                                alt="Art image"
                                                onerror="this.onerror=null; this.classList.add('image-error')"
                                            >
                                        </div>
                                        {{-- <img src="{{ $art->image_url }}" class="image card-img-top" alt="Art image"> --}}
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="no-arts">Нет отклонённых артов</p>
                    @endif
                </div>
            @endif
        @endauth
    </div>

    @auth
        @if(auth()->user()->isModerator())
            <div class="buttons">
                <button class="moderator-btn main-btn" onclick="window.location.href='{{ route('moderation') }}'">Панель модерации</button>
            </div>
        @endif
    @endauth

@endsection