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
      {{-- <a href="{{ route('profile') }}" class="interface-btn left-btn"> --}}
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
    <a class="bottom-interface" id="startBtn"><img src="{{ asset('icons/black/plus-black.png') }}"></a>

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

    @foreach ($locations as $location)
        (function() {
            const marker = L.marker([{{ $location['lat'] }}, {{ $location['lng'] }}]);
            
            // Создаем содержимое попапа
            const popupContent = `
                <div class="leaflet-popup-content">
                    @if($location['image_url'])
                        <div class="popup-image">
                            <img src="{{ asset('storage/' . $location['image_url']) }}" 
                                alt="{{ $location['name'] }}"
                                style="max-width: 200px; max-height: 150px; border-radius: 8px;">
                        </div>
                    @endif
                    
                    <div class="popup-info" style="margin-top: 8px;">
                        <h4 style="margin: 0 0 4px 0;">{{ $location['name'] }}</h4>
                        
                        @if($location['year'])
                            <p style="margin: 2px 0; font-size: 0.9em;">
                                <b>Year:</b> {{ $location['year'] }}
                            </p>
                        @endif
                        
                        @if($location['description'])
                            <p style="margin: 4px 0 0 0; font-size: 0.8em; color: #666;">
                                {{ Str::limit($location['description'], 100) }}
                            </p>
                        @endif
                    </div>
                </div>
            `;

            // Привязываем попап к маркеру
            marker.bindPopup(popupContent, {
                closeButton: false,    // Убираем кнопку закрытия
                closeOnClick: false,   // Не закрывать при клике
                autoClose: false       // Не закрывать автоматически
            });

            // Обработчики событий
            marker.on('mouseover', function() {
                this.openPopup();
            });

            marker.on('mouseout', function() {
                this.closePopup();
            });

            // Клик для перехода на страницу арта
            marker.on('click', function() {
                window.location.href = "{{ route('art.show', $location['id']) }}";
            });

            marker.addTo(map);
        })();
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