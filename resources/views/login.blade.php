@extends('layouts.main-layout')

@section('title', 'ArtDefender')

@section('content')

    <a href="{{ route('welcome') }}" class="left-btn">
        <img src="{{ asset('icons/backw.png') }}">
    </a>

    <div class="content">
        <form class="ad-form" method="POST" action="/login/confirm" enctype="multipart/form-data">
            @csrf
            <h3>AUTH</h3>

            <div class="inputs-block">

                <div class="input-block">
                    <label for="email">Email</label><br/>
                    <input type="email" name="email" value="{{ old('email') }}" placeholder="">
                    
                    @error('email')
                        <p class="error-text">{{ $message }}</p>
                    @enderror

                </div>

                <div class="input-block">
                    <label for="password">Password</label><br/>
                    <input type="password" name="password" value="{{ old('password') }}" placeholder="">
                    
                    @error('password')
                        <p class="error-text">{{ $message }}</p>
                    @enderror

                </div>

            </div>

            <div class="buttons">
                <button type="submit" class="form-btn main-btn">Log In</button>
            </div>
        </form>
    </div>

@endsection
