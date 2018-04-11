@extends('layouts.app')
 
@section('body-class', 'login-page registrate-page')
 
@section('content')
    <div id="content">
            <div class="login-form">
            <div class="label-form">
                <span>{{trans('main.registrate')}}</span>
            </div>
            <form method="POST" action="{{ route('register') }}" enctype="multipart/form-data">
                {{ csrf_field() }}
                <div class="avatar">
                    <div class="user-img" style='background-image: url({{old('photo') ? old('photo') : asset('img/user-login.png')}})'></div>
                    <!--<input type="file" accept="image/*" class="input-photo" id="inputPhoto" name="photo" value="{{old('photo')}}">-->
                    {{Form::file('photo', ['accept'=>"image/*", 'class'=>"input-photo", 'id'=>"inputPhoto", 'value'=>old('photo')]) }}
                    <label for="inputPhoto"><i class="fa fa-cloud-download" aria-hidden="true"></i> {{trans('main.subir_foto')}}</label>
                    @if ($errors->has('photo')) <p>{{ $errors->first('photo') }}</p> @endif
                </div>
                <div class="radio">
                    <input type="radio" @if (old('registrate') == '' || old('registrate') == 'visitor') checked @endif name="registrate" value="visitor" id="registrate-customer" class="hidden">
                    <label for="registrate-customer">{{trans('main.as_a_customer')}}</label>
                    
                    <input type="radio" @if (old('registrate') == 'worker') checked @endif name="registrate" value="worker" id="registrate-worker" class="hidden">
                    <label for="registrate-worker">{{trans('main.as_a_worker')}}</label>
                </div>            
                <div class="input">
                    <input data-attr type="text" id="registrate-name" name="name" value="{{ old('name') }}" required />
                    <label for="registrate-name">{{trans('main.nombre')}}</label>
                    <span class="pe-7s-user"></span>
                    <div class="tip">el nombre es muy corto</div>
                    @if ($errors->has('name')) <p>{{ $errors->first('name') }}</p> @endif
                </div>
                <div class="input">
                    <input data-attr type="text" id="registrate-surname" name="surname" value="{{ old('surname') }}" required />
                    <label for="registrate-surname">{{trans('main.apellido')}}</label>
                    <span class="pe-7s-add-user"></span>
                    <div class="tip">el apellido es muy corto</div>
                    @if ($errors->has('surname')) <p>{{ $errors->first('surname') }}</p> @endif
                </div>
                <div class="input">
                    <input data-attr type="email" id="registrate-email" name="email" value="{{ old('email') }}" required />
                    <label for="registrate-email">{{trans('main.email')}}</label>
                    <span class="pe-7s-mail-open"></span>
                    <div class="tip">Correo introducido no es válido</div>
                    @if ($errors->has('email')) <p>{{ $errors->first('email') }}</p> @endif
                </div>
                <div class="input">
                    <input data-attr type="text" class="registrate-phone" id="registrate-phone" name="phone" value="{{ old('phone') }}" required />
                    <label for="registrate-phone">{{trans('main.telefono')}}</label>
                    <span class="pe-7s-call"></span>
                    <div class="tip">Número de teléfono no válido</div>
                    @if ($errors->has('phone')) <p>{{ $errors->first('phone') }}</p> @endif
                </div>
                
                
                <div class="input">
                    <input type="password" class="password" id="registrate-password" name="password" required>
                    <label for="registrate-password">{{trans('main.password')}}</label>
                    <span class="pe-7s-lock"></span>
                    @if ($errors->has('password')) <p>{{ $errors->first('password') }}</p> @endif
                </div>
                <div class="input">
                    <input data-attr type="password" class="password-confirm" id="password-confirm" name="password_confirmation" required>
                    <label for="password-confirm">{{trans('main.confirm_password')}}</label>
                    <span class="pe-7s-lock"></span>
                    <div class="tip">Las contraseñas no coinciden</div>
                </div>
                
                
                <div class="checkbox">
                    <input data-attr type="checkbox" id="accept" name="conditions" class="hidden">
                    <label for="accept"><span>{{trans('main.acepto_las_condiciones_de_uso')}}</span></label>
                    @if ($errors->has('conditions')) <p>{{ $errors->first('conditions') }}</p> @endif
                </div>
                <button type="button" class="check-status disable">{{trans('main.registrate')}}</button>
            </form> 
        </div>
    </div>
@endsection

<!-- test -->