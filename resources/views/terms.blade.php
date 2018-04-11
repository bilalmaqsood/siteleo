@extends('layouts.app')

@section('body-class', 'conditions-page')

@section('content')
    <div id="content">
        <div class="container">
            @include('blocks.breadcrumbs')
            <div class="block-wrap">
                <div class="conditions-block-title">CONDICIONES GENERALES DE USO de SITIO WEB Y POLITICA DE PRIVACIDAD DE EL PROFESIONAL EN CASA.</div>
                <div class="row">
                    <div class="col-sm-6">
                        {!! $conditions->for_professionals !!}
                    </div>
                    <div class="col-sm-6">
                        <!-- <div class="conditions-block-title">Privacy policy</div> -->
                        {!! $conditions->for_clients !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection