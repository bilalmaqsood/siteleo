@extends('layouts.app')

@section('body-class', 'page-404')

@section('content')
    <div id="content">
        <div class="container">
            <div class="img-404">
                <img src="{{asset('img/404.png')}}" alt="404">
            </div>
            <h5>¡Ups! La página que buscas no ha sido encontrada.</h5>
            <p>Vuelve a la página principal <a href="{{route('home')}}">pulsando aquí!</a></p>
        </div>
    </div>
@endsection



