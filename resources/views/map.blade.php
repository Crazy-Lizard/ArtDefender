@extends('layouts.core-layout')

@section('title', 'ArtDefender')

@section('content')
    
  <div class="interface top-interface">
    <a href="{{ route('welcome') }}" class="interface-btn left-btn">
      <img src="{{ asset('icons/back.png') }}">
    </a>

    <form id="ad-search" action="" method="GET" style="--i: 3">
      <div class="search-bar">
        <input type="text" name="query" class="search" id="search" placeholder="Search">
        <button class="search-btn" type="submit"><img src="icons/search.png"></button>
      </div>
    </form>

    <a {{-- href="{{ route('route.name') }}" --}} class="interface-btn right-btn">
      <img src="{{ asset('icons/filter.png') }}">
    </a>
  
  </div>

    <a {{-- href="{{ route('route.name') }}" --}} class="bottom-interface">
      <img src="{{ asset('icons/plus.png') }}">
    </a>

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