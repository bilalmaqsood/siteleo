@extends('layouts.app')
 
@section('content')
    <div id="content">
        <div class="container">
            <div class="row" id="pjax-zone">
                <div class="col-xs-12 col-md-3">
                    @include('blocks.user-sidebar') 
                </div>
                <div class="col-xs-12 col-md-9">
                    @include('blocks.steps')
                    <div class="user-content new-styles">
                        @include('blocks.messages')


                        @yield('user_content')
                    </div>
                </div>  
            </div>
        </div>
    </div>
    {{--<script type="text/javascript" src="{{asset('js/container.js')}}"></script>--}}
@endsection
