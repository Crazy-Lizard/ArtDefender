@extends('layouts.main-layout')

@section('title', 'ArtDefender')

@section('content')

    <div class="header">
        <a href="{{ route('welcome') }}" class="header-btn left-btn">
            <img src="{{ asset('icons/white/back-white.png') }}">
        </a>
    </div>

    <div class="content">
        <form class="ad-form" method="POST" action="{{--/register/confirm--}}{{ route('register') }}" enctype="multipart/form-data"  style="--i: 0">
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
                    <label for="password_confirmation">Confirm Password</label><br/>
                    <input type="password" name="password_confirmation" value="{{ old('password') }}" placeholder="" required>
                    
                    @error('password_confirmation')
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
