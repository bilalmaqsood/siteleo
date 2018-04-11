@extends('layouts.app')

@section('body-class', 'page-404')

@section('content')
    <div id="content">
        <div class="container">
            <div class="img-404">
                <img src="img/404.png" alt="405">
            </div>
            <h5>Ooops, looks like a ghost!</h5>
            <p>The page you are looking for can’t be found. Go home by <a href="{{route('home')}}">clicking here!</a></p>
        </div>
    </div>
@endsection