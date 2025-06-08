@extends('layouts.main-layout')

@section('title', 'ArtDefender : ' . (auth()->user()->name))

@section('content')
<style>    
    .form-control-file {
        display: none;
    }
    
    .form-btn:hover {
        background-color: #2b6cb0;
    }
    
    /* Стили для области перетаскивания */
    .drop-container {
        position: relative;
        width: 100%;
        height: 200px;
        border: 2px dashed #cbd5e0;
        border-radius: 8px;
        text-align: center;
        cursor: pointer;
        transition: all 0.3s;
        overflow: hidden;
    }
    
    .drop-container.highlight {
        border-color: #4299e1;
        background-color: rgba(66, 153, 225, 0.05);
    }
    
    .drop-content {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        width: 100%;
        padding: 20px;
    }
    
    .drop-icon {
        width: 48px;
        height: 48px;
        margin: 0 auto 15px;
        fill: #816c9a;
    }
    
    .drop-text {
        color: #718096;
        margin-bottom: 10px;
    }
    
    .file-label {
        color: #f2603e;
        font-weight: 600;
        cursor: pointer;
    }
    
    .file-label:hover {
        text-decoration: underline;
    }
    
    .file-info {
        font-size: 12px;
        color: #a0aec0;
        margin-top: 5px;
    }
    
    /* Стили для превью изображения */
    .preview-container {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        display: none;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        background: #502683;
        z-index: 10;
    }
    
    .preview-image {
        width: 372px;
        height: auto;
        max-height: 180px;
        object-fit: contain;
        border-radius: 4px;
    }
    
    .remove-btn {
        color: #e53e3e;
        background: none;
        border: none;
        cursor: pointer;
        font-weight: 600;
    }
    
    .remove-btn:hover {
        text-decoration: underline;
    }
</style>

<div class="content" style="min-height: 100vh">
    <form action="{{ route('arts.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="inputs-block">
            <input type="hidden" name="lat" value="{{ $lat }}">
            <input type="hidden" name="lng" value="{{ $lng }}">
            
            <!-- Drag & Drop поле для картинки -->
            <div class="input-block">
                <div class="drop-container" id="drop-container">
                    <div class="drop-content">
                        <svg class="drop-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                        </svg>
                        
                        <div class="drop-text">
                            <label for="file-input" class="file-label">Нажмите для выбора файла</label>
                            <span> или перетащите сюда</span>
                        </div>
                        <p class="file-info">PNG, JPG, JPEG до 2MB</p>
                    </div>
                    
                    <!-- Превью изображения (изначально скрыто) -->
                    <div class="preview-container" id="preview-container">
                        <img id="preview" class="preview-image" alt="Превью изображения">
                        <button type="button" id="remove-image" class="remove-btn">Удалить изображение</button>
                    </div>
                    
                    <input type="file" name="image" id="file-input" class="form-control-file" accept="image/jpeg,image/png,image/jpg">
                </div>

                <!-- Вывод ошибок -->
                @error('image')
                    <div class="error-text">{{ $message }}</div>
                @enderror
            </div>

            <!-- Остальные поля формы -->
            <div class="input-block">
                <select class="form-control" name="art_status" required>
                    <option value="" class="form-option">Статус работы</option>
                    <option value="LIVE" class="form-option">Live</option>
                    <option value="BUFFED" class="form-option">Buffed</option>
                    <option value="UNKNOWN" class="form-option">Unknown</option>
                </select>

                @error('art_status')
                    <p class="error-text">{{ $message }}</p>
                @enderror
            </div>
            <div class="input-block">
                <input type="text" name="creator" placeholder="Автор работы" class="form-control">
                
                @error('creator')
                    <p class="error-text">{{ $message }}</p>
                @enderror
            </div>
            <div class="input-block">
                <input type="number" class="form-control" name="art_created_year" min="1900" max="{{ date('Y') }}" placeholder="Год создания">
                
                @error('art_created_year')
                    <p class="error-text">{{ $message }}</p>
                @enderror
            </div>
            <div class="input-block">
                <select class="form-control" name="art_type" required>
                    <option value="" class="form-option">Тип работы</option>
                    <option value="street-art" class="form-option">Street Art</option>
                    <option value="mural" class="form-option">Mural</option>
                    <option value="tag" class="form-option">Tag</option>
                    <option value="sticker" class="form-option">Sticker</option>
                </select>

                @error('art_type')
                    <p class="error-text">{{ $message }}</p>
                @enderror
            </div>
            <div class="input-block">
                <textarea class="form-control form-text" name="description" rows="3" placeholder="Добавьте описание"></textarea>
                
                @error('description')
                    <p class="error-text">{{ $message }}</p>
                @enderror
            </div>
        </div>
        
        <div class="button">
            <button type="submit" class="form-btn main-btn">Создать Арт</button>
        </div>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const dropContainer = document.getElementById('drop-container');
        const fileInput = document.getElementById('file-input');
        const previewContainer = document.getElementById('preview-container');
        const previewImg = document.getElementById('preview');
        const removeBtn = document.getElementById('remove-image');
        
        // Клик по всей области контейнера открывает выбор файла
        dropContainer.addEventListener('click', function() {
            fileInput.click();
        });
        
        // Обработка выбора файла через input
        fileInput.addEventListener('change', function(e) {
            if (this.files && this.files[0]) {
                handleFile(this.files[0]);
            }
        });
        
        // Обработчик удаления изображения
        removeBtn.addEventListener('click', function() {
            resetFileInput();
            previewContainer.style.display = 'none';
            dropContainer.classList.remove('highlight');
        });
        
        // Обработчики событий перетаскивания
        ['dragover', 'dragenter'].forEach(event => {
            dropContainer.addEventListener(event, function(e) {
                e.preventDefault();
                this.classList.add('highlight');
            });
        });
        
        ['dragleave', 'dragexit'].forEach(event => {
            dropContainer.addEventListener(event, function(e) {
                e.preventDefault();
                if (!isInside(e, this)) {
                    this.classList.remove('highlight');
                }
            });
        });
        
        dropContainer.addEventListener('drop', function(e) {
            e.preventDefault();
            this.classList.remove('highlight');
            
            if (e.dataTransfer.files && e.dataTransfer.files[0]) {
                handleFile(e.dataTransfer.files[0]);
            }
        });
        
        // Проверка находится ли курсор внутри элемента
        function isInside(event, element) {
            const rect = element.getBoundingClientRect();
            return (
                event.clientX >= rect.left &&
                event.clientX <= rect.right &&
                event.clientY >= rect.top &&
                event.clientY <= rect.bottom
            );
        }
        
        // Обработка загруженного файла
        function handleFile(file) {
            // Проверка типа файла
            const validTypes = ['image/jpeg', 'image/png', 'image/jpg'];
            if (!validTypes.includes(file.type)) {
                alert('Пожалуйста, загрузите только изображения (JPEG, PNG, JPG)!');
                return;
            }
            
            // Проверка размера файла (5MB)
            if (file.size > 5 * 1024 * 1024) {
                alert('Файл слишком большой. Максимальный размер 5MB.');
                return;
            }
            
            // Обновляем input
            const dataTransfer = new DataTransfer();
            dataTransfer.items.add(file);
            fileInput.files = dataTransfer.files;
            
            // Показываем превью
            const reader = new FileReader();
            reader.onload = function(e) {
                previewImg.src = e.target.result;
                previewContainer.style.display = 'flex';
            };
            reader.readAsDataURL(file);
        }
        
        // Сброс input файла
        function resetFileInput() {
            fileInput.value = '';
            previewImg.src = '';
        }
        
        // Глобальные обработчики для предотвращения стандартного поведения браузера
        document.addEventListener('dragover', function(e) {
            e.preventDefault();
        });
        
        document.addEventListener('drop', function(e) {
            e.preventDefault();
        });
    });
</script>

@endsection