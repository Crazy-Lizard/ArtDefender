@extends('layouts.main-layout')

@section('title', 'ArtDefender : ' . (auth()->user()->name))

@section('content')

    <div class="content">
        <form action="{{ route('arts.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="inputs-block">
                <input type="hidden" name="lat" value="{{ $lat }}">
                <input type="hidden" name="lng" value="{{ $lng }}">
                
                <!-- Другие поля формы -->
                <div class="input-block">
                    <input type="file" class="form-control-file" name="image" required accept="image/*">
                    
                    @error('image')
                        <p class="error-text">{{ $message }}</p>
                    @enderror
                </div>
                <div class="input-block">
                    <select class="form-control" name="art_status" required>
                        <option value="">Статус работы</option>
                        <option value="LIVE">Live</option>
                        <option value="BUFFED">Buffed</option>
                        <option value="UNKNOWN">Unknown</option>
                    </select>

                    @error('art_status')
                        <p class="error-text">{{ $message }}</p>
                    @enderror
                </div>
                <div class="input-block">
                    <input type="text" name="creator" placeholder="Автор работы">
                    
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
                        <option value="">Тип работы</option>
                        <option value="street-art">Street Art</option>
                        <option value="mural">Mural</option>
                        <option value="tag">Tag</option>
                        <option value="sticker">Sticker</option>
                    </select>

                    @error('art_type')
                        <p class="error-text">{{ $message }}</p>
                    @enderror
                </div>
                <div class="input-block">
                    <textarea class="form-control" name="description" rows="3" placeholder="Добавьте описание"></textarea>
                    
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

@endsection