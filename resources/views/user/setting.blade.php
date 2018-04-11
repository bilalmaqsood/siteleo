@extends('layouts.user')
 
@section('body-class', 'user-office-page user-setting-page')
 
@section('user_content')
    <form class="pass-form" method="post" action="{{ route('user-setting-update') }}">
        {{ csrf_field() }}
        <div class="user-title">{{trans('main.changepassword')}}</div>
        <div class="input-block">
            <label><span>{{trans('main.old_password')}} *</span>
                <input type="password" name="old_password" required>
            </label>
            <label><span>{{trans('main.new_password')}} *</span>
                <input type="password" name="new_password" required>
            </label>
            <label><span>{{trans('main.the_password_again')}} *</span>
                <input type="password" name="new_password_confirmation" required>
            </label>
            <div class="show-pass checkbox">
                <input type="checkbox" id="user-pass" class="hidden">
                <label for="user-pass">{{trans('main.show_password')}}</label>
            </div>
            <button class="save">{{trans('main.save')}}</button>
        </div>
    </form>            
@endsection