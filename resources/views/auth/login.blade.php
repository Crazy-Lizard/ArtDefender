@extends('layouts.main-layout')

@section('title', 'ArtDefender')

@section('content')

    <div class="header">
        <a href="{{ route('welcome') }}" class="header-btn left-btn">
            <img src="{{ asset('icons/white/back-white.png') }}">
        </a>
    </div>

    <div class="content">
        <form class="ad-form" method="POST" action="{{--/login/confirm--}}{{ route('login') }}" enctype="multipart/form-data">
            @csrf
            {{-- <h3>AUTH</h3> --}}
            <h3>ВХОД</h3>

            <div class="inputs-block">

                <div class="input-block">
                    <label for="email">Почта</label><br/>
                    <input type="email" name="email" value="{{ old('email') }}" placeholder="">
                    
                    @error('email')
                        <p class="error-text">{{ $message }}</p>
                    @enderror

                </div>

                <div class="input-block">
                    <label for="password">Пароль</label><br/>
                    <input type="password" name="password" value="{{ old('password') }}" placeholder="">
                    
                    @error('password')
                        <p class="error-text">{{ $message }}</p>
                    @enderror

                </div>

            </div>

            <div class="buttons">
                <button type="submit" class="form-btn main-btn">Войти</button>
            </div>
        </form>
    </div>

@endsection
