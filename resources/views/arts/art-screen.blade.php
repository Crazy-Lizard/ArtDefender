@extends('layouts.main-layout')

@section('title', 'ArtDefender : Art №' . ($art->id))

<head><meta name="csrf-token" content="{{ csrf_token() }}"></head>

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
            <div class="card">
                @if ($art->image_url)
                    <img src="{{ $art->image_url }}" class="image card-img-top" alt="Art image">
                @endif
            </div>
            <div class="card-body">
                <!-- Поле описания -->
                <div class="editable-field mb-2" data-field="description" data-art-id="{{ $art->id }}">
                    <p class="field-value card-text" data-value="{{ $art->description }}">
                        {{ $art->description ?: 'НЕТ ОПИСАНИЯ' }}
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
                        Автор: 
                        <div class="editable-field mb-2" data-field="creator" data-art-id="{{ $art->id }}">
                            <span class="field-value card-title" data-value="{{ $art->creator }}">
                                {{ $art->creator ?: 'НЕИЗВЕСТЕН' }}
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
                        Тип: 
                        <div class="editable-field d-inline-block" data-field="art_type" data-art-id="{{ $art->id }}">
                            <span class="field-value" data-value="{{ $art->art_type }}">
                                {{ $art->art_type ?: 'НЕИЗВЕСТЕН' }}
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
                        Год: 
                        <div class="editable-field d-inline-block" data-field="art_created_year" data-art-id="{{ $art->id }}">
                            <span class="field-value" data-value="{{ $art->art_created_year }}">
                                {{ $art->art_created_year ?: 'НЕИЗВЕСТЕН' }}
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
                        Статус: 
                        <div class="editable-field d-inline-block" data-field="art_status" data-art-id="{{ $art->id }}">
                            <span class="field-value" data-value="{{ $art->art_status }}">
                                {{ $art->art_status ?: 'НЕИЗВЕСТЕН' }}
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
    @if ($art->request_status == 'approved')
        <div class="flex justify-center w-full">
            <div class="comments-section mt-8 w-full max-w-2xl">
                <h3 class="text-xl font-bold mb-4 text-center">Комментарии</h3>

                <!-- Форма добавления комментария - теперь по центру -->
                @auth
                    <form id="add-comment-form" class="mb-6 flex flex-col items-center" action="{{ route('comments.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="art_id" value="{{ $art->id }}">
                        <textarea 
                            name="body" 
                            class="comment-text" 
                            placeholder="Добавьте комментарий..."
                            required
                        ></textarea>
                        <button type="submit" class="comment-btn">
                            <img src="{{ asset('icons/black/chat-black.png') }}">
                            {{-- Отправить --}}
                        </button>
                    </form>
                @else
                    <div class="mb-4 text-center">
                        <a href="{{ route('login') }}" class="text-blue-500">Войдите</a>, чтобы оставлять комментарии
                    </div>
                @endauth

                <!-- Список комментариев - центрированный -->
                <div id="comments-container" class="flex flex-col items-center" style="display:flex; flex-direction: column; min-width: 300px">
                    @if($comments->isNotEmpty())
                        @foreach($comments as $comment)
                            @include('comments.comment', ['comment' => $comment])
                        @endforeach
                    @else
                        <p>Комментариев пока нет</p>
                    @endif
                </div>
            </div>
        </div>
    @endif

    <style>
        .comments-section {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-bottom: 50px
        }
        
        .comment {
            width: 100%;
            max-width: 300px;
            /* margin-bottom: 1rem;
            padding: 1rem; */
            border-radius: 0.5rem;
            justify-content: flex-start;
            word-break: break-word;      
        }
        
        .comment-actions {
            display: flex;
            gap: 1rem;
            margin-top: 0.5rem;
        }
    </style>

    <!-- Скрипт для обработки комментариев -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Глобальные переменные для управления состоянием
            let commentHandlersInitialized = false;
            const authUser = @json(auth()->check() ? [
                'id' => auth()->id(),
                'isModerator' => auth()->user()->isModerator()
            ] : null);
            
            // Инициализация обработчиков событий
            function initCommentHandlers() {
                // Удаляем старые обработчики для предотвращения дублирования
                if (commentHandlersInitialized) {
                    const newContainer = document.getElementById('comments-container').cloneNode(true);
                    document.getElementById('comments-container').replaceWith(newContainer);
                }
                
                // Обработчики для всего контейнера комментариев
                document.getElementById('comments-container').addEventListener('click', function(e) {
                    const target = e.target;
                    
                    // Обработка кнопки reply
                    if (target.classList.contains('reply-btn')) {
                        handleReplyClick(target);
                    }
                    
                    // Обработка кнопки cancel
                    if (target.classList.contains('cancel-reply')) {
                        target.closest('.reply-form').style.display = 'none';
                    }
                    
                    // Обработка кнопки delete
                    if (target.classList.contains('delete-comment')) {
                        handleDeleteClick(target);
                    }
                    
                    // Обработка кнопки report
                    if (target.classList.contains('report-comment')) {
                        handleReportClick(target);
                    }
                });

                // Обработчики для форм ответов
                document.querySelectorAll('.reply-comment-form').forEach(form => {
                    form.addEventListener('submit', handleReplySubmit);
                });
                
                commentHandlersInitialized = true;
            }
            
            // Функция обработки кнопки Reply
            function handleReplyClick(button) {
                const commentId = button.dataset.commentId;
                const commentEl = button.closest('.comment');
                const replyForm = commentEl.querySelector('.reply-form');
                
                // Скрываем все другие формы ответов
                document.querySelectorAll('.reply-form').forEach(form => {
                    if (form !== replyForm) form.style.display = 'none';
                });
                
                // Показываем/скрываем текущую форму
                replyForm.style.display = replyForm.style.display === 'none' ? 'block' : 'none';
            }
            
            // Функция обработки отправки ответа
            function handleReplySubmit(e) {
                e.preventDefault();
                const form = this;
                const formData = new FormData(form);
                
                fetch(form.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Обновляем весь список комментариев
                        fetchComments();
                        form.reset();
                    } else {
                        alert('Ошибка: ' + (data.message || 'Неизвестная ошибка'));
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Произошла ошибка при отправке ответа');
                });
            }
            
            // Функция обработки удаления комментария
            function handleDeleteClick(button) {
                const commentId = button.dataset.id;
                const commentEl = button.closest('.comment');
                const hasReplies = commentEl.querySelector('.replies')?.children.length > 0;
                
                let message = 'Удалить этот комментарий?';
                if (hasReplies) {
                    message = 'Этот комментарий имеет ответы. Удалить все вместе?';
                }
                
                if (confirm(message)) {
                    fetch(`/comments/${commentId}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        }
                    })
                    .then(response => {
                        if (response.ok) {
                            // Если это ответ на другой комментарий - обновляем весь список
                            if (commentEl.closest('.replies')) {
                                fetchComments();
                            } 
                            // Если корневой комментарий - удаляем элемент
                            else {
                                commentEl.remove();
                            }
                        } else {
                            console.error('Ошибка при удалении комментария');
                        }
                    });
                }
            }
            
            // Функция обработки жалобы на комментарий
            function handleReportClick(button) {
                const commentId = button.dataset.id;
                alert(`Жалоба на комментарий ${commentId} отправлена модераторам`);
                // Здесь должна быть реализация отправки жалобы
            }
            
            // Функция загрузки комментариев (рекурсивная)
            async function fetchComments() {
                try {
                    const response = await fetch(`/comments/{{ $art->id }}`);
                    const comments = await response.json();
                    
                    let commentsHtml = '';
                    
                    if (comments.length === 0) {
                        commentsHtml = '<p>Комментариев пока нет</p>';
                    } else {
                        // Рекурсивная функция для генерации комментариев
                        const renderComment = (comment) => {
                            const isAuthenticated = authUser !== null;
                            const isAuthor = isAuthenticated && comment.user_id === authUser.id;
                            const isModerator = isAuthenticated && authUser.isModerator;
                            
                            // Определение действий
                            let actionButtons = '';
                            if (isAuthenticated) {
                                actionButtons += `
                                    <button class="btn btn-sm btn-outline-secondary reply-btn" 
                                        data-comment-id="${comment.id}" style="cursor:pointer; border: none; background: none; color:#43E6B1">
                                        ответить
                                    </button>`;
                                
                                if (isAuthor || isModerator) {
                                    actionButtons += `
                                        <button class="delete-comment text-red-500 text-sm" 
                                            data-id="${comment.id}" style="cursor:pointer; border: none; background: none; color:#F2603E">
                                            удалить
                                        </button>`;
                                // } else {
                                //     actionButtons += `
                                //         <button class="report-comment text-red-500 text-sm" 
                                //             data-id="${comment.id}">
                                //             жалоба
                                //         </button>`;
                                }
                            }
                            
                            // Форма ответа
                            let replyForm = '';
                            if (isAuthenticated) {
                                replyForm = `
                                    <div class="reply-form mt-3" style="display: none;">
                                        <form class="reply-comment-form" 
                                            action="{{ route('comments.store') }}" 
                                            method="POST" 
                                            data-parent-id="${comment.id}">
                                            @csrf
                                            <input type="hidden" name="art_id" value="{{ $art->id }}">
                                            <input type="hidden" name="parent_id" value="${comment.id}">
                                            <div class="form-group">
                                                <textarea name="body" class="reply-text" rows="2" 
                                                    placeholder="Ваш ответ..." required></textarea>
                                            </div>                                            
                                            <button type="submit" class="btn btn-primary btn-sm" style="cursor:pointer; border: none; background: none; color:#43E6B1">отправить</button>
                                            <button type="button" class="btn btn-secondary btn-sm cancel-reply" style="cursor:pointer; border: none; background: none; color:#F2603E">отмена</button>
                                        </form>
                                    </div>`;
                            }
                            
                            // Рекурсивная обработка ответов
                            let repliesHtml = '';
                            if (comment.replies && comment.replies.length > 0) {
                                repliesHtml = '<div class="replies" style="padding-left: 30px; margin-bottom:10px">';
                                comment.replies.forEach(reply => {
                                    repliesHtml += renderComment(reply);
                                });
                                repliesHtml += '</div>';
                            }
                            
                            return `
                            <div class="comment card mb-3"  data-id="${comment.id}">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between" style="display: flex; justify-content: flex-start; align-items:baseline; gap: 10px; height: 30px">
                                        <h3 class="card-title">
                                            <a href="/profile/${comment.user_id}" style="color: whitesmoke">${comment.user.name}</a>
                                        </h3>
                                        <small class="text-muted" style="font-weight: normal; font-size: 12px; opacity: 50%">${comment.created_at_diff}</small>
                                    </div>
                                    <p class="card-text" style="font-weight: normal; font-size: 14px;">${comment.body}</p>
                                    <div class="comment-actions mt-2">
                                        ${actionButtons}
                                    </div>
                                    ${replyForm}
                                    ${repliesHtml}
                                </div>
                            </div>`;
                        };
                        
                        // Рендерим все корневые комментарии
                        comments.forEach(comment => {
                            commentsHtml += renderComment(comment);
                        });
                    }
                    
                    // Обновляем контейнер
                    document.getElementById('comments-container').innerHTML = commentsHtml;
                    
                    // Переинициализируем обработчики
                    initCommentHandlers();
                    
                } catch (error) {
                    console.error('Ошибка загрузки комментариев:', error);
                    document.getElementById('comments-container').innerHTML = 
                        '<p>Ошибка загрузки комментариев</p>';
                }
            }
            
            // Отправка нового комментария
            const commentForm = document.getElementById('add-comment-form');
            if (commentForm) {
                commentForm.addEventListener('submit', async function(e) {
                    e.preventDefault();
                    const form = this;
                    const button = form.querySelector('button[type="submit"]');
                    // const originalButtonText = button.textContent;
                    
                    // button.textContent = 'Отправка...';
                    button.disabled = true;
                    
                    try {
                        const formData = new FormData(form);
                        const response = await fetch(form.action, {
                            method: 'POST',
                            body: formData,
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json'
                            }
                        });
                        
                        if (response.ok) {
                            fetchComments();
                            form.reset();
                        } else {
                            const error = await response.json();
                            throw new Error(error.message || 'Ошибка сервера');
                        }
                    } catch (error) {
                        console.error('Ошибка:', error);
                        alert('Ошибка при отправке комментария: ' + error.message);
                    } finally {
                        // button.textContent = originalButtonText;
                        button.disabled = false;
                    }
                });
            }
            
            // Инициализация при загрузке
            initCommentHandlers();
        });
    </script>
    @auth
        <script>
            window.authUser = {
                id: {{ auth()->user()->id }},
                isModerator: @json(auth()->user()->isModerator())
            };
        </script>
    @endauth

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