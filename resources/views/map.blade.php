@extends('layouts.core-layout')

@section('title', 'ArtDefender')

@section('content')
    
  <div class="interface top-interface">
    @guest
      <a href="{{ route('welcome') }}" class="interface-btn left-btn">
        <img src="{{ asset('icons/black/back-black.png') }}">
      </a>
    @endguest

    @auth
      <a href="/profile/{{ auth()->user()->id }}" class="interface-btn left-btn">
        <img src="{{ asset('icons/black/user-black.png') }}">
      </a>
    @endauth

    <form id="ad-search" action="" method="GET" style="--i: 3">
      <div class="search-bar">
        <input type="text" name="query" class="search" id="search" placeholder="Search">
        <button class="search-btn" type="submit"><img src="icons/black/search-black.png"></button>
      </div>
    </form>

    <a {{-- href="{{ route('route.name') }}" --}} class="interface-btn right-btn">
      <img src="{{ asset('icons/black/filter-black.png') }}">
    </a>
  
  </div>

  @auth
    <a {{-- href="{{ route('route.name') }}" --}} class="bottom-interface">
      <img src="{{ asset('icons/black/plus-black.png') }}">
    </a>
  @endauth

  <div id="map"></div>

  <script>
    // Инициализация карты
    const map = L.map('map').setView([44.6167, 33.5254], 10);
    map.zoomControl.setPosition('bottomleft');

    // Добавление слоя OpenStreetMap
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>'
    }).addTo(map);

    // Добавление маркеров из данных Laravel
    @foreach ($locations as $location)
      L.marker([{{ $location['lat'] }}, {{ $location['lng'] }}])
        .bindPopup('{{ $location['name'] }}')
        .addTo(map);
    @endforeach

  </script>

@endsection