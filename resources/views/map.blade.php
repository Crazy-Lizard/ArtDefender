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
    <a {{-- href="{{ route('route.name') }}" --}} class="bottom-interface" id="startBtn">
      <img src="{{ asset('icons/black/plus-black.png') }}">
    </a>

    <a id="confirmBtn" class="bottom-interface" style="display: none;"><img src="{{ asset('icons/black/tick-black.png') }}"></a>
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

    
    let marker = null;
    let isPlacing = false;
    
    // Активация режима размещения маркера
    document.getElementById('startBtn').addEventListener('click', () => {
        isPlacing = true;
        document.getElementById('confirmBtn').style.display = 'block';
        document.getElementById('startBtn').style.display = 'none';
    });

    // Обработка клика по карте
    map.on('click', (e) => {
        if (!isPlacing) return;
        
        if (marker) map.removeLayer(marker);
        
        marker = L.marker(e.latlng, {
            draggable: true
        }).addTo(map);
    });

    // Подтверждение координат
    document.getElementById('confirmBtn').addEventListener('click', () => {
        if (!marker) return;
        
        const lat = marker.getLatLng().lat.toFixed(7);
        const lng = marker.getLatLng().lng.toFixed(7);
        
        // Отправка данных на сервер
        fetch('/check-point', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                lat: lat,
                lng: lng
            })
        }).then(response => {
            window.location.href = response.url;
        });
    });

  </script>

@endsection