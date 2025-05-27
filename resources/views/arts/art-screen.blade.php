@extends('layouts.main-layout')

@section('title', 'ArtDefender : Art №' . ($art->id))

@section('content')

    <div class="header">
        {{-- <a href="/profile/{{ auth()->user()->id }}" class="header-btn left-btn">
            <img src="{{ asset('icons/white/back-white.png') }}">
        </a> --}}
        @auth
            <a href="/profile/{{ auth()->user()->id }}" class="header-btn left-btn">
                <img src="{{ asset('icons/white/back-white.png') }}">
            </a>

            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    let backButton = document.querySelector('.header-btn.left-btn');
                    if (backButton) {
                        backButton.addEventListener('click', function(event) {
                            event.preventDefault();
                            let preReportReferrer = sessionStorage.getItem('preReportReferrer');
                            let previousPage = document.referrer;

                            if (previousPage.includes('/report')) {
                                if (preReportReferrer) {
                                    window.location.href = preReportReferrer;
                                } else {
                                    window.location.href = '/map'; // Изменено на переход к карте
                                }
                                sessionStorage.removeItem('preReportReferrer');
                            } else if (previousPage.includes('/moderation')) {
                                window.location.href = '/map';
                            } else {
                                window.history.back();
                            }
                        });
                    }
                });
            </script>
        @endauth
        @guest
            <a class="header-btn left-btn" onclick="event.preventDefault(); window.history.back();">
                <img src="{{ asset('icons/white/back-white.png') }}">
            </a>
        @endguest

        <h1>
            Art #{{ $art->id }} by 
            <a href="/profile/{{ $art->user_id }}" style="color: whitesmoke">{{ $art->user->name }}</a>
        </h1>

        @auth
            @if (($art->user_id == auth()->user()->id) || (auth()->user()->isModerator()))
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
                <a href="{{ route('reports.create', $art) }}" class="header-btn right-btn">
                    <img src="{{ asset('icons/red/report-red.png') }}">
                </a>
            @endif
        @endauth
        @guest
            <a class="header-btn right-btn" style="cursor:default"></a>
        @endguest
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
            <div class="card" style="cursor: default">
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

    <!-- Кнопка для открытия в Яндекс Картах -->
    {{-- <a href="https://yandex.ru/maps/?pt={{ $art->lng }},{{ $art->lat }}&z=17&l=map" 
        target="_blank" 
        class="moder-btn reject-btn">
        Открыть в Яндекс.Картах
    </a> --}}
</div>

@endsection