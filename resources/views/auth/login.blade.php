@extends('layouts.app')
 
@section('body-class', 'login-page')
 
@section('content')

    <div id="content">
            <div class="login-form">
            <div class="label-form">
                <img src="{{asset('img/login.png')}}" alt="login">
                <span>{{trans('main.login')}}</span>
            </div>
            <div class="user-img" style='background-image: url({{asset('img/user-login.png')}});'></div>
            <form method="POST" action="{{ route('login') }}">
                {{ csrf_field() }}
                <div class="input">
                    <input type="email" id="login-name" name="email" value="{{ old('email') }}" required autofocus>
                    <label for="login-name">{{trans('main.email')}}</label>
                    <img src="{{ asset('img/password-icon.png') }}" alt="icon">
                    @if ($errors->has('email')) <p>{{ $errors->first('email') }}</p> @endif
                </div>
                <div class="input">
                    <input type="password" id="login-password" name="password" required>
                    <label for="login-password">{{trans('main.password')}}</label>
                    <img src="{{ asset('img/password-icon.png') }}" alt="icon">
                    @if ($errors->has('password')) <p>{{ $errors->first('password') }}</p> @endif
                </div>
                
                <div class="checkbox">
                    <input type="checkbox" class="hidden" id="remember" name="remember" {{ old('remember') ? 'checked' : '' }}>
                    <label for="remember"><span>{{trans('main.remember_me')}}</span></label>
                </div>
                
                <p><a href="{{ route('password.request') }}">{{trans('main.do_not_you_remember_your_passw')}}</a></p>
                <button type="submit">{{trans('main.avtorizirovatsya')}}</button>
            </form> 
        </div>
    </div>

@endsection