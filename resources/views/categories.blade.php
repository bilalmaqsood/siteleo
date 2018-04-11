@extends('layouts.app')
 
@section('body-class', 'categories-page')
@section('body-id', 'categories-block')
 
@section('content')
<div id="content">
    <div class="container">
        <div class="row grid">
            @foreach(\App\Models\Category::where('parent_id', 0)->orderBy('order')->get() as $item)
            <div class="col-xs-6  col-sm-4 grid-item">
                <div class="category-item">
                    <div class="img-block">
                        <img src="{{asset($item->icon)}}" alt="{{$item->title}}">
                    </div>
                    <div class="description-block">
                        <h6>{{$item->title}}</h6>
                        <div class="category-list">
                            @foreach(\App\Models\Category::where('parent_id', $item->id)->orderBy('order')->get() as $item)
                                <p>@if($count = count($item->ads))<a href="{{route('search', ['uri_category' => $item->uri])}}">{{$item->title}} ({{$count}})</a>@else {{$item->title}} @endif</p>
                            @endforeach
                        </div>
                        <div class="show-more">{{trans('main.show_more')}} <i class="fa fa-angle-right" aria-hidden="true"></i></div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endsection

<!-- test -->