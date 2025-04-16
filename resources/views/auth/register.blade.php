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

{{-- @extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Register') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('register') }}">
                        @csrf

                        <div class="row mb-3">
                            <label for="name" class="col-md-4 col-form-label text-md-end">{{ __('Name') }}</label>

                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>

                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="email" class="col-md-4 col-form-label text-md-end">{{ __('Email Address') }}</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="password" class="col-md-4 col-form-label text-md-end">{{ __('Password') }}</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="password-confirm" class="col-md-4 col-form-label text-md-end">{{ __('Confirm Password') }}</label>

                            <div class="col-md-6">
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                            </div>
                        </div>

                        <div class="row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Register') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection --}}
