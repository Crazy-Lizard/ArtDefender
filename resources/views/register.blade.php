@extends('layouts.main-layout')

@section('title', 'ArtDefender')

@section('content')

    <a href="{{ route('welcome') }}" class="left-btn">
        <img src="{{ asset('icons/backw.png') }}">
    </a>

    <div class="content">
        <form class="ad-form" method="POST" action="/register/confirm" enctype="multipart/form-data"  style="--i: 0">
            @csrf
            <h3>REGISTRATION</h3>

            <div class="inputs-block">

                <div class="input-block">
                    <label for="name">Login</label><br/>
                    <input type="name" name="name" value="{{ old('name') }}" placeholder="" required>
                    
                    @error('name')
                        <p class="error-text">{{ $message }}</p>
                    @enderror

                </div>

                <div class="input-block">
                    <label for="email">Email</label><br/>
                    <input type="email" name="email" value="{{ old('email') }}" placeholder="" required>
                    
                    @error('email')
                        <p class="error-text">{{ $message }}</p>
                    @enderror

                </div>

                <div class="input-block">
                    <label for="password">Password</label><br/>
                    <input type="password" name="password" value="{{ old('password') }}" placeholder="" required>
                    
                    @error('password')
                        <p class="error-text">{{ $message }}</p>
                    @enderror

                </div>

                <div class="input-block">
                    <label for="confirm_password">Confirm Password</label><br/>
                    <input type="password" name="confirm_password" value="{{ old('password') }}" placeholder="" required>
                    
                    @error('confirm_password')
                        <p class="error-text">{{ $message }}</p>
                    @enderror

                </div>

            </div>

            <div class="buttons">
                <button type="submit" class="form-btn main-btn">Register</button>
            </div>
        </form>
    </div>

@endsection
