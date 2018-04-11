@extends('layouts.app')
 
@section('body-class', 'conditions-page')
 
@section('content')
    <div id="content">
            <div class="container">
            @include('blocks.breadcrumbs')
            <div class="block-wrap">
                <div class="conditions-title">{{trans('main.preguntas_frecuentes')}}</div>
                <div class="conditions-subtitle">{{trans('main.recuerda_que_elprofesionalenca')}}</div>
                <div class="row">
                    <div class="col-sm-6">
                        <div class="conditions-block-title">{{trans('main.para_profesionales')}}</div>
                        {!! $conditions->for_professionals !!}
                    </div>
                    <div class="col-sm-6">
                        <div class="conditions-block-title">{{trans('main.para_clientes')}}</div>
                        {!! $conditions->for_clients !!}
                    </div>
                </div>
            </div>
            </div>
    </div>
@endsection