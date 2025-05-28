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
        .editable-field {
            gap: 10px;
        }
    </style>

    <div class="container" style="display: flex; justify-content: center">
        <div>
            <div class="card" style="cursor: default">
                @if ($art->image_url)
                    <img src="{{ $art->image_url }}" class="image card-img-top" alt="Art image">
                @endif
            </div>
            <div class="card-body">
                <!-- Поле описания -->
                <div class="editable-field mb-2" data-field="description" data-art-id="{{ $art->id }}">
                    <p class="field-value card-text" data-value="{{ $art->description }}">
                        {{ $art->description ?: 'NON DESCRIPTION' }}
                    </p>
                    @if($editable)
                        <button class="main-btn edit-btn">
                            <img src="{{ asset('icons/white/edit-white.png') }}" width="16">
                        </button>
                    @endif
                </div>

                <ul class="list-group list-group-flush">
                    <!-- Поле автора -->
                    <li class="list-group-item">
                        Artist: 
                        <div class="editable-field mb-2" data-field="creator" data-art-id="{{ $art->id }}">
                            <span class="field-value card-title" data-value="{{ $art->creator }}">
                                {{ $art->creator ?: 'UNKNOWN' }}
                            </span>
                            @if($editable)
                                <button class="main-btn edit-btn">
                                    <img src="{{ asset('icons/white/edit-white.png') }}" width="16">
                                </button>
                            @endif
                        </div>
                    </li>

                    <!-- Поле типа -->
                    <li class="list-group-item">
                        Type: 
                        <div class="editable-field d-inline-block" data-field="art_type" data-art-id="{{ $art->id }}">
                            <span class="field-value" data-value="{{ $art->art_type }}">
                                {{ $art->art_type ?: 'UNKNOWN' }}
                            </span>
                            @if($editable)
                                <button class="main-btn edit-btn">
                                    <img src="{{ asset('icons/white/edit-white.png') }}" width="16">
                                </button>
                            @endif
                        </div>
                    </li>
                    
                    <!-- Поле года -->
                    <li class="list-group-item">
                        Year: 
                        <div class="editable-field d-inline-block" data-field="art_created_year" data-art-id="{{ $art->id }}">
                            <span class="field-value" data-value="{{ $art->art_created_year }}">
                                {{ $art->art_created_year ?: 'UNKNOWN' }}
                            </span>
                            @if($editable)
                                <button class="main-btn edit-btn">
                                    <img src="{{ asset('icons/white/edit-white.png') }}" width="16">
                                </button>
                            @endif
                        </div>
                    </li>
                    
                    <!-- Поле статуса -->
                    <li class="list-group-item">
                        Status: 
                        <div class="editable-field d-inline-block" data-field="art_status" data-art-id="{{ $art->id }}">
                            <span class="field-value" data-value="{{ $art->art_status }}">
                                {{ $art->art_status ?: 'UNKNOWN' }}
                            </span>
                            @if($editable)
                                <button class="main-btn edit-btn">
                                    <img src="{{ asset('icons/white/edit-white.png') }}" width="16">
                                </button>
                            @endif
                        </div>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Кнопка для открытия в Яндекс Картах -->
        {{-- <a href="https://yandex.ru/maps/?pt={{ $art->lng }},{{ $art->lat }}&z=17&l=map" 
            target="_blank" 
            class="moder-btn reject-btn">
            Открыть в Яндекс.Картах
        </a> --}}
    </div>

    <style>
        .edit-form { 
            display: flex; 
            align-items: center; 
            gap: 10px; 
            width: 100%;
        }
        .edit-input { 
            flex-grow: 1; 
            padding: 8px; 
            border: 1px solid #ddd; 
            border-radius: 4px; 
            font-size: inherit;
        }
        .edit-actions { display: flex; gap: 5px; }
        .editable-field { display: inline-flex; align-items: center; }
        textarea.edit-input { min-height: 100px; resize: vertical; }
        
        /* Добавьте этот стиль для выпадающих списков */
        select.edit-input {
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            background-color: white;
        }
    </style>

    <script>
    // Объявляем глобальные константы
    const IS_EDITABLE = @json($editable);
    const STATUS_OPTIONS = ['LIVE', 'BUFFED', 'UNKNOWN'];
    const TYPE_OPTIONS = ['street-art', 'mural', 'tag', 'sticker'];
    
    // Функция для получения отображаемого значения
    function getDisplayValue(value) {
        return value ? value : 'UNKNOWN';
    }

    function handleEditButtonClick() {
        const fieldContainer = this.closest('.editable-field');
        const fieldName = fieldContainer.dataset.field;
        const fieldValueElement = fieldContainer.querySelector('.field-value');
        const currentValue = fieldValueElement.dataset.value;

        // Создаем форму редактирования
        const form = document.createElement('form');
        form.className = 'edit-form';
        
        // Выбираем тип элемента ввода
        let input;
        if(fieldName === 'description') {
            input = document.createElement('textarea');
            input.rows = 4;
            input.value = currentValue;
            input.className = 'edit-input';
        } else if(fieldName === 'art_status') {
            input = document.createElement('select');
            input.className = 'edit-input';
            
            // Добавляем опции для статуса
            STATUS_OPTIONS.forEach(option => {
                const optElement = document.createElement('option');
                optElement.value = option;
                optElement.textContent = option;
                if(option === currentValue) optElement.selected = true;
                input.appendChild(optElement);
            });
        } else if(fieldName === 'art_type') {
            input = document.createElement('select');
            input.className = 'edit-input';
            
            // Добавляем опции для типа
            TYPE_OPTIONS.forEach(option => {
                const optElement = document.createElement('option');
                optElement.value = option;
                optElement.textContent = option;
                if(option === currentValue) optElement.selected = true;
                input.appendChild(optElement);
            });
        } else {
            input = document.createElement('input');
            input.type = 'text';
            input.value = currentValue;
            input.className = 'edit-input';
        }
        
        // Кнопки действий
        const confirmBtn = document.createElement('button');
        confirmBtn.type = 'button';
        confirmBtn.className = 'edit-btn approve-btn';
        confirmBtn.innerHTML = '✓';
        
        const cancelBtn = document.createElement('button');
        cancelBtn.type = 'button';
        cancelBtn.className = 'edit-btn reject-btn';
        cancelBtn.innerHTML = '✕';
        
        const actionsDiv = document.createElement('div');
        actionsDiv.className = 'edit-actions';
        actionsDiv.appendChild(confirmBtn);
        actionsDiv.appendChild(cancelBtn);
        
        form.appendChild(input);
        form.appendChild(actionsDiv);
        
        // Заменяем содержимое
        fieldContainer.innerHTML = '';
        fieldContainer.appendChild(form);
        
        // Фокусируемся на поле
        input.focus();
        
        // Обработчик подтверждения
        confirmBtn.addEventListener('click', function() {
            let value;
            if (input.tagName === 'SELECT') {
                value = input.options[input.selectedIndex].value;
            } else {
                value = input.value;
            }
            
            // Сохраняем реальное значение (пустая строка вместо UNKNOWN)
            const saveValue = value === 'UNKNOWN' ? '' : value;
            updateField(fieldContainer, fieldName, saveValue, currentValue);
        });
        
        // Обработчик отмены
        cancelBtn.addEventListener('click', function() {
            restoreField(fieldContainer, fieldName, currentValue);
        });
    }

    // Функция восстановления поля
    function restoreField(container, fieldName, value) {
        const displayValue = getDisplayValue(value);
        
        let elementTag, elementClass;
        
        switch(fieldName) {
            case 'creator':
                elementTag = 'h5';
                elementClass = 'card-title';
                break;
            case 'description':
                elementTag = 'p';
                elementClass = 'card-text';
                break;
            default:
                elementTag = 'span';
                elementClass = '';
        }
        
        const element = document.createElement(elementTag);
        element.className = `field-value ${elementClass}`;
        element.textContent = displayValue;
        element.dataset.value = value; // Сохраняем реальное значение
        
        container.innerHTML = '';
        container.appendChild(element);
        
        // Добавляем кнопку редактирования только если редактирование разрешено
        if (IS_EDITABLE) {
            const editBtn = document.createElement('button');
            editBtn.className = 'main-btn edit-btn';
            
            const editIcon = document.createElement('img');
            editIcon.src = "{{ asset('icons/white/edit-white.png') }}";
            editIcon.width = 16;
            
            editBtn.appendChild(editIcon);
            container.appendChild(editBtn);
            
            // Вешаем обработчик на кнопку
            editBtn.addEventListener('click', handleEditButtonClick);
        }
    }
    
    // Функция обновления данных
    function updateField(container, fieldName, value, originalValue) {
        const artId = container.dataset.artId;
        
        fetch(`/arts/${artId}/update-field`, {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({ field: fieldName, value })
        })
        .then(response => {
            if (!response.ok) throw new Error('Network response was not ok');
            return response.json();
        })
        .then(data => {
            if(data.success) {
                // Обновляем значение в data-атрибуте и отображаемом тексте
                const newValue = data.newValue || '';
                restoreField(container, fieldName, newValue);
                
                // Обновляем значение в UI, если оно используется в других местах
                if (fieldName === 'creator') {
                    const authorLink = document.querySelector(`a[href="/profile/${data.art.user_id}"]`);
                    if (authorLink) {
                        authorLink.textContent = getDisplayValue(newValue);
                    }
                }
            } else {
                throw new Error(data.message || 'Unknown error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert(`Error updating field: ${error.message}`);
            // Восстанавливаем исходное значение при ошибке
            restoreField(container, fieldName, originalValue);
        });
    }

    document.addEventListener('DOMContentLoaded', function() {
        // Инициализация обработчиков кнопок редактирования
        document.querySelectorAll('.edit-btn').forEach(btn => {
            btn.addEventListener('click', handleEditButtonClick);
        });
    });
</script>

@endsection
