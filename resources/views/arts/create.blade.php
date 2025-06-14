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

        .drop-container {
            position: relative;
            width: 100%;
            height: 170px;
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
            width: 290px;
            padding: 20px;
        }

        .drop-icon {
            width: 48px;
            height: 48px;
            margin: 0 auto 1px;
            fill: #816c9a;
        }

        .drop-text {
            color: #718096;
            margin-bottom: 10px;
            font-size: 18px;
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
            max-height: 130px;
            object-fit: contain;
            border-radius: 4px;
        }

        .remove-btn {
            color: #e53e3e;
            background: none;
            border: none;
            cursor: pointer;
            font-weight: 600;
            position: relative;
            top: 5px;
        }

        .remove-btn:hover {
            text-decoration: underline;
        }

        /* Styles for additional images */
        .additional-drop-container {
            position: relative;
            width: 100%;
            height: 170px;
            border: 2px dashed #cbd5e0;
            border-radius: 8px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s;
            overflow: hidden;
        }

        .additional-drop-container.highlight {
            border-color: #4299e1;
            background-color: rgba(66, 153, 225, 0.05);
        }

        .additional-preview-container {
            /* display: flex;
            flex-direction: column;
            flex-wrap: nowrap;
            align-items: center;
            justify-content: flex-start; */

            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 90%;
            display: none;
            flex-wrap: wrap;
            justify-content: center;
            align-content: center;
            gap: 10px;
            background: #502683;
            padding: 10px;
            overflow-y: auto;
            z-index: 10;
        }

        .additional-preview-item {
            position: relative;
            width: 100px;
            height: 100px;
        }

        .additional-preview-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 4px;
        }

        .additional-remove-btn {
            position: absolute;
            top: 5px;
            right: 5px;
            color: #e53e3e;
            background: rgba(255, 255, 255, 0.8);
            border: none;
            cursor: pointer;
            font-size: 12px;
            padding: 2px 5px;
            border-radius: 3px;
        }

        .additional-remove-all-btn,
        .additional-add-more-btn {
            color: #e53e3e;
            background: none;
            border: none;
            cursor: pointer;
            font-weight: 600;
            margin: 5px;
        }

        .additional-remove-all-btn:hover,
        .additional-add-more-btn:hover {
            text-decoration: underline;
        }

        .additional-add-more-btn {
            color: #4299e1;
        }
    </style>

    <div class="header">
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
                                window.location.href = '/map';
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
        <a class="header-btn right-btn" style="cursor:default"></a>
    </div>

    <div class="content" style="position: relative; bottom: 20px;">
        <form action="{{ route('arts.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="inputs-block">
                <input type="hidden" name="lat" value="{{ $lat }}">
                <input type="hidden" name="lng" value="{{ $lng }}">

                <!-- Main Image Field -->
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
                        <div class="preview-container" id="preview-container">
                            <img id="preview" class="preview-image" alt="Превью изображения">
                            <button type="button" id="remove-image" class="remove-btn">Удалить изображение</button>
                        </div>
                        <input type="file" name="image" id="file-input" class="form-control-file" accept="image/jpeg,image/png,image/jpg">
                    </div>
                    @error('image')
                        <div class="error-text">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Additional Images Field -->
                <div class="input-block additional-images-container">
                    <div class="additional-drop-container" id="additional-drop-container">
                        <div class="drop-content">
                            <svg class="drop-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                    d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                            </svg>
                            <div class="drop-text">
                                <label for="additional-file-input" class="file-label">Добавить дополнительные изображения</label>
                                <span> или перетащите сюда</span>
                            </div>
                            <p class="file-info">PNG, JPG, JPEG до 2MB</p>
                        </div>
                        <div class="additional-preview-container" id="additional-preview-container">
                            <div class="additional-preview-items"></div>
                            <div class="additional-buttons" style="display: none;">
                                <button type="button" id="add-more-images" class="additional-add-more-btn">Добавить еще изображения</button>
                                <button type="button" id="remove-all-images" class="additional-remove-all-btn">Удалить все изображения</button>
                            </div>
                        </div>
                    </div>
                    <input type="file" name="additional_images[]" id="additional-file-input" class="form-control-file" accept="image/jpeg,image/png,image/jpg" multiple>
                    @error('additional_images.*')
                        <div class="error-text">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Other Form Fields -->
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
            // Main Image Handling
            const dropContainer = document.getElementById('drop-container');
            const fileInput = document.getElementById('file-input');
            const previewContainer = document.getElementById('preview-container');
            const previewImg = document.getElementById('preview');
            const removeBtn = document.getElementById('remove-image');
            const fileLabel = dropContainer.querySelector('.file-label');

            ['dragover', 'dragenter'].forEach(event => {
                dropContainer.addEventListener(event, function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    this.classList.add('highlight');
                });
            });

            ['dragleave', 'dragexit'].forEach(event => {
                dropContainer.addEventListener(event, function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    if (!isInside(e, this)) {
                        this.classList.remove('highlight');
                    }
                });
            });

            dropContainer.addEventListener('drop', function(e) {
                e.preventDefault();
                e.stopPropagation();
                this.classList.remove('highlight');
                if (e.dataTransfer.files && e.dataTransfer.files[0]) {
                    handleFile(e.dataTransfer.files[0], previewImg, previewContainer, fileInput);
                }
            });

            fileLabel.addEventListener('click', function(e) {
                e.preventDefault();
                fileInput.click();
            });

            fileInput.addEventListener('change', function(e) {
                if (this.files && this.files[0]) {
                    handleFile(this.files[0], previewImg, previewContainer, this);
                }
            });

            removeBtn.addEventListener('click', function() {
                resetFileDrop(fileInput, previewImg, previewContainer, dropContainer);
            });

            // Additional Images Handling
            const additionalDropContainer = document.getElementById('additional-drop-container');
            const additionalFileInput = document.getElementById('additional-file-input');
            const additionalPreviewContainer = document.getElementById('additional-preview-container');
            const additionalPreviewItems = additionalPreviewContainer.querySelector('.additional-preview-items');
            const additionalFileLabel = additionalDropContainer.querySelector('.file-label');
            const addMoreBtn = document.getElementById('add-more-images');
            const removeAllBtn = document.getElementById('remove-all-images');

            ['dragover', 'dragenter'].forEach(event => {
                additionalDropContainer.addEventListener(event, function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    this.classList.add('highlight');
                });
            });

            ['dragleave', 'dragexit'].forEach(event => {
                additionalDropContainer.addEventListener(event, function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    if (!isInside(e, this)) {
                        this.classList.remove('highlight');
                    }
                });
            });

            additionalDropContainer.addEventListener('drop', function(e) {
                e.preventDefault();
                e.stopPropagation();
                this.classList.remove('highlight');
                if (e.dataTransfer.files) {
                    handleAdditionalFiles(e.dataTransfer.files, additionalPreviewContainer, additionalDropContainer, additionalFileInput);
                }
            });

            additionalFileLabel.addEventListener('click', function(e) {
                e.preventDefault();
                additionalFileInput.click();
            });

            additionalFileInput.addEventListener('change', function(e) {
                if (this.files) {
                    handleAdditionalFiles(this.files, additionalPreviewContainer, additionalDropContainer, additionalFileInput);
                }
            });

            addMoreBtn.addEventListener('click', function() {
                additionalFileInput.click();
            });

            removeAllBtn.addEventListener('click', function() {
                resetAdditionalDrop(additionalFileInput, additionalPreviewContainer, additionalDropContainer);
            });

            function isInside(event, element) {
                const rect = element.getBoundingClientRect();
                return (
                    event.clientX >= rect.left &&
                    event.clientX <= rect.right &&
                    event.clientY >= rect.top &&
                    event.clientY <= rect.bottom
                );
            }

            function handleFile(file, imgElement, container, input) {
                const validTypes = ['image/jpeg', 'image/png', 'image/jpg'];
                if (!validTypes.includes(file.type)) {
                    alert('Пожалуйста, загрузите только изображения (JPEG, PNG, JPG)!');
                    return;
                }
                if (file.size > 2 * 1024 * 1024) {
                    alert('Файл слишком большой. Максимальный размер 2MB.');
                    return;
                }
                const dataTransfer = new DataTransfer();
                dataTransfer.items.add(file);
                input.files = dataTransfer.files;
                const reader = new FileReader();
                reader.onload = function(e) {
                    imgElement.src = e.target.result;
                    container.style.display = 'flex';
                };
                reader.readAsDataURL(file);
            }

            function resetFileDrop(input, imgElement, container, dropContainer) {
                input.value = '';
                imgElement.src = '';
                container.style.display = 'none';
                dropContainer.querySelector('.drop-content').style.display = 'block';
            }

            function handleAdditionalFiles(files, previewContainer, dropContainer, input) {
                const validTypes = ['image/jpeg', 'image/png', 'image/jpg'];
                const maxSize = 2 * 1024 * 1024;
                const dataTransfer = new DataTransfer();

                // Add existing files to DataTransfer
                if (input.files) {
                    Array.from(input.files).forEach(file => dataTransfer.items.add(file));
                }

                // Add new files
                Array.from(files).forEach(file => {
                    if (!validTypes.includes(file.type)) {
                        alert('Пожалуйста, загрузите только изображения (JPEG, PNG, JPG)!');
                        return;
                    }
                    if (file.size > maxSize) {
                        alert('Файл слишком большой. Максимальный размер 2MB.');
                        return;
                    }
                    dataTransfer.items.add(file);

                    const previewItem = document.createElement('div');
                    previewItem.className = 'additional-preview-item';
                    const img = document.createElement('img');
                    img.className = 'additional-preview-image';
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        img.src = e.target.result;
                        const removeBtn = document.createElement('button');
                        removeBtn.className = 'additional-remove-btn';
                        removeBtn.innerText = 'X';
                        removeBtn.onclick = function() {
                            previewItem.remove();
                            updateAdditionalFiles(previewContainer, input);
                            if (!previewContainer.querySelector('.additional-preview-item')) {
                                resetAdditionalDrop(input, previewContainer, dropContainer);
                            }
                        };
                        previewItem.appendChild(img);
                        previewItem.appendChild(removeBtn);
                        previewContainer.insertBefore(previewItem, previewContainer.querySelector('.additional-buttons'));
                        previewContainer.style.display = 'flex';
                        dropContainer.querySelector('.drop-content').style.display = 'none';
                        previewContainer.querySelector('.additional-buttons').style.display = 'flex';
                    };
                    reader.readAsDataURL(file);
                });

                input.files = dataTransfer.files;
            }

            function resetAdditionalDrop(input, previewContainer, dropContainer) {
                input.value = '';
                const buttonsContainer = previewContainer.querySelector('.additional-buttons');
                previewContainer.innerHTML = '';
                previewContainer.appendChild(buttonsContainer);
                buttonsContainer.style.display = 'none';
                previewContainer.style.display = 'none';
                dropContainer.querySelector('.drop-content').style.display = 'block';
            }

            function updateAdditionalFiles(previewContainer, input) {
                const previewItems = previewContainer.querySelectorAll('.additional-preview-image');
                const dataTransfer = new DataTransfer();
                Promise.all(
                    Array.from(previewItems).map(item => {
                        return fetch(item.src)
                            .then(res => res.blob())
                            .then(blob => {
                                const file = new File([blob], `image-${Date.now()}.jpg`, { type: blob.type });
                                dataTransfer.items.add(file);
                            });
                    })
                ).then(() => {
                    input.files = dataTransfer.files;
                });
            }

            document.addEventListener('dragover', function(e) {
                e.preventDefault();
            });

            document.addEventListener('drop', function(e) {
                e.preventDefault();
            });
        });
        /*document.addEventListener('DOMContentLoaded', function() {
            // Main Image Handling
            const dropContainer = document.getElementById('drop-container');
            const fileInput = document.getElementById('file-input');
            const previewContainer = document.getElementById('preview-container');
            const previewImg = document.getElementById('preview');
            const removeBtn = document.getElementById('remove-image');
            const fileLabel = dropContainer.querySelector('.file-label');

            ['dragover', 'dragenter'].forEach(event => {
                dropContainer.addEventListener(event, function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    this.classList.add('highlight');
                });
            });

            ['dragleave', 'dragexit'].forEach(event => {
                dropContainer.addEventListener(event, function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    if (!isInside(e, this)) {
                        this.classList.remove('highlight');
                    }
                });
            });

            dropContainer.addEventListener('drop', function(e) {
                e.preventDefault();
                e.stopPropagation();
                this.classList.remove('highlight');
                if (e.dataTransfer.files && e.dataTransfer.files[0]) {
                    handleFile(e.dataTransfer.files[0], previewImg, previewContainer, fileInput);
                }
            });

            fileLabel.addEventListener('click', function(e) {
                e.preventDefault();
                fileInput.click();
            });

            fileInput.addEventListener('change', function(e) {
                if (this.files && this.files[0]) {
                    handleFile(this.files[0], previewImg, previewContainer, this);
                }
            });

            removeBtn.addEventListener('click', function() {
                resetFileDrop(fileInput, previewImg, previewContainer, dropContainer);
            });

            // Additional Images Handling
            const additionalDropContainer = document.getElementById('additional-drop-container');
            const additionalFileInput = document.getElementById('additional-file-input');
            const additionalPreviewContainer = document.getElementById('additional-preview-container');
            const additionalPreviewItems = additionalPreviewContainer.querySelector('.additional-preview-items');
            const additionalFileLabel = additionalDropContainer.querySelector('.file-label');
            const addMoreBtn = document.getElementById('add-more-images');
            const removeAllBtn = document.getElementById('remove-all-images');

            ['dragover', 'dragenter'].forEach(event => {
                additionalDropContainer.addEventListener(event, function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    this.classList.add('highlight');
                });
            });

            ['dragleave', 'dragexit'].forEach(event => {
                additionalDropContainer.addEventListener(event, function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    if (!isInside(e, this)) {
                        this.classList.remove('highlight');
                    }
                });
            });

            additionalDropContainer.addEventListener('drop', function(e) {
                e.preventDefault();
                e.stopPropagation();
                this.classList.remove('highlight');
                if (e.dataTransfer.files) {
                    handleAdditionalFiles(e.dataTransfer.files, additionalPreviewItems, additionalDropContainer, additionalFileInput);
                }
            });

            additionalFileLabel.addEventListener('click', function(e) {
                e.preventDefault();
                additionalFileInput.click();
            });

            additionalFileInput.addEventListener('change', function(e) {
                if (this.files) {
                    handleAdditionalFiles(this.files, additionalPreviewItems, additionalDropContainer, additionalFileInput);
                }
            });

            addMoreBtn.addEventListener('click', function() {
                additionalFileInput.click();
            });

            removeAllBtn.addEventListener('click', function() {
                resetAdditionalDrop(additionalFileInput, additionalPreviewItems, additionalDropContainer);
            });

            function isInside(event, element) {
                const rect = element.getBoundingClientRect();
                return (
                    event.clientX >= rect.left &&
                    event.clientX <= rect.right &&
                    event.clientY >= rect.top &&
                    event.clientY <= rect.bottom
                );
            }

            function handleFile(file, imgElement, container, input) {
                const validTypes = ['image/jpeg', 'image/png', 'image/jpg'];
                if (!validTypes.includes(file.type)) {
                    alert('Пожалуйста, загрузите только изображения (JPEG, PNG, JPG)!');
                    return;
                }
                if (file.size > 2 * 1024 * 1024) {
                    alert('Файл слишком большой. Максимальный размер 2MB.');
                    return;
                }
                const dataTransfer = new DataTransfer();
                dataTransfer.items.add(file);
                input.files = dataTransfer.files;
                const reader = new FileReader();
                reader.onload = function(e) {
                    imgElement.src = e.target.result;
                    container.style.display = 'flex';
                };
                reader.readAsDataURL(file);
            }

            function resetFileDrop(input, imgElement, container, dropContainer) {
                input.value = '';
                imgElement.src = '';
                container.style.display = 'none';
                dropContainer.querySelector('.drop-content').style.display = 'block';
            }

            function handleAdditionalFiles(files, previewItemsContainer, dropContainer, input) {
                const validTypes = ['image/jpeg', 'image/png', 'image/jpg'];
                const maxSize = 2 * 1024 * 1024;
                const dataTransfer = new DataTransfer();

                // Add existing files to DataTransfer
                if (input.files) {
                    Array.from(input.files).forEach(file => dataTransfer.items.add(file));
                }

                // Add new files
                Array.from(files).forEach(file => {
                    if (!validTypes.includes(file.type)) {
                        alert('Пожалуйста, загрузите только изображения (JPEG, PNG, JPG)!');
                        return;
                    }
                    if (file.size > maxSize) {
                        alert('Файл слишком большой. Максимальный размер 2MB.');
                        return;
                    }
                    dataTransfer.items.add(file);

                    const previewItem = document.createElement('div');
                    previewItem.className = 'additional-preview-item';
                    const img = document.createElement('img');
                    img.className = 'additional-preview-image';
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        img.src = e.target.result;
                        const removeBtn = document.createElement('button');
                        removeBtn.className = 'additional-remove-btn';
                        removeBtn.innerText = 'X';
                        removeBtn.onclick = function() {
                            previewItem.remove();
                            updateAdditionalFiles(previewItemsContainer, input);
                            if (!previewItemsContainer.querySelector('.additional-preview-item')) {
                                resetAdditionalDrop(input, previewItemsContainer, dropContainer);
                            }
                        };
                        previewItem.appendChild(img);
                        previewItem.appendChild(removeBtn);
                        previewItemsContainer.appendChild(previewItem);
                        additionalPreviewContainer.style.display = 'flex';
                        dropContainer.querySelector('.drop-content').style.display = 'none';
                        additionalPreviewContainer.querySelector('.additional-buttons').style.display = 'flex';
                    };
                    reader.readAsDataURL(file);
                });

                input.files = dataTransfer.files;
            }

            function resetAdditionalDrop(input, previewItemsContainer, dropContainer) {
                input.value = '';
                previewItemsContainer.innerHTML = '';
                additionalPreviewContainer.style.display = 'none';
                dropContainer.querySelector('.drop-content').style.display = 'block';
                additionalPreviewContainer.querySelector('.additional-buttons').style.display = 'none';
            }

            function updateAdditionalFiles(previewItemsContainer, input) {
                const previewItems = previewItemsContainer.querySelectorAll('.additional-preview-image');
                const dataTransfer = new DataTransfer();
                Promise.all(
                    Array.from(previewItems).map(item => {
                        return fetch(item.src)
                            .then(res => res.blob())
                            .then(blob => {
                                const file = new File([blob], `image-${Date.now()}.jpg`, { type: blob.type });
                                dataTransfer.items.add(file);
                            });
                    })
                ).then(() => {
                    input.files = dataTransfer.files;
                });
            }

            document.addEventListener('dragover', function(e) {
                e.preventDefault();
            });

            document.addEventListener('drop', function(e) {
                e.preventDefault();
            });
        });*/
    </script>
@endsection