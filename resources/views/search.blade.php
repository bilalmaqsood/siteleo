@extends('layouts.app')
 
@section('body-class', 'search-page')
 
@section('content')
<div id="content">
        <div class="container">
        <div class="row">
            <div class="col-sm-3 col-sm-push-9">
                <div class="search-sidebar">
                    <div class="search-result">
                        <span>{{count($ads)}}</span>
                        <div>{{trans('main.profesionales')}} <br> {{trans('main.en_esta_categoria')}}</div>
                    </div>

                    <div class="search-breadcrumbs">
                        <div class="breadcrumbs-title">{{trans('main.categorias')}}</div>
                        <ul>
                            <li><a href="/categories/">{{trans('main.all_services')}}</a></li>
                            @if($uri_category)
                                <li @if(!$uri_subcategory) class="active" @endif><a href="{{ route('search', ['uri_category' => $uri_category]) }}">{{$category->title}}</a></li>
                                @foreach(\App\Models\Category::where('parent_id', $category->id)->get() as $sub)
                                    <li @if($sub->uri==$uri_subcategory) class="active" @endif><a href="{{route('search', ['uri_category' => $uri_category, 'uri_subcategory' => $sub->uri])}}">{{$sub->title}}</a></li>
                                @endforeach
                            @else
                                @foreach($cities as $key => $city_in_list)
                                    <li><a href="{{route('search-q', ['city' => $key])}}">{{$key}} ({{$city_in_list}})</a></li>
                                @endforeach
                            @endif
                        </ul>
                    </div>
                    <!-- <div class="banners">
                        <div class="banner-item">
                            <iframe src="http://focusweb.lviv.ua/vitalii/Banner/Buson/300x250/" width="262.5" height="254"></iframe>
                        </div>
                        <div class="banner-item">
                            <iframe src="http://focusweb.lviv.ua/vitalii/Banner/Vivat/300x250/" width="262.5" height="254"></iframe>
                        </div>
                        <div class="banner-item">
                            <iframe src="http://focusweb.lviv.ua/vitalii/Banner/LadyMary/300x250/" width="262.5" height="254"></iframe>
                        </div>
                    </div> -->
                </div>
            </div>
            <div class="col-sm-9 col-sm-pull-3">                        
                <div class="class1-block">
                    <div class="search-title">
                        <i class="fa fa-angle-left" aria-hidden="true"></i>
                        @if($uri_category)
                            @if(!$uri_subcategory)
                                The
                                <a href="{{ route('search', ['uri_category' => $uri_category]) }}"> {{$category->title}}</a>
                            @else
                                @php $sub = \App\Models\Category::where('uri', $uri_subcategory)->first() @endphp
                                The
                                <a href="{{ route('search', ['uri_category' => $uri_category, 'uri_subcategory' => $uri_subcategory]) }}">{{$sub->title}}</a>
                                in
                                <a href="{{ route('search', ['uri_category' => $uri_category]) }}"> {{$category->title}}</a>
                            @endif
                        @else
                            The @if(isset($q))<a href="{{route('search-q', ['q' => $q])}}">{{$q}}</a> in @endif
                            <a href="{{route('search-q', ['city' => $city])}}">{{$city}} @if($city=='todas') cities @endif</a>

                            @if(!isset($q) && !isset($city))
                                <a href="@if(isset($provincia)){{route('search-q', ['provincia' => $provincia])}}@else{{route('categories')}}@endif">
                                    all ads @if(isset($provincia)) in {{$provincia}}@endif
                                </a>
                            @endif

                        @endif
                    </div>
                    <!-- якщо проплачене оголошення додати клас paid -->

                    @foreach($ads as $ad)
                    <div class="class1-item @if($ad->payable) paid @endif">
                        <a href="{{route('advertise', ['uri' => $ad->uri])}}">
                            <div class="class1-header">
                                <div class="class1-avatar"></div>
                                <div class="class1-description">{{$ad->name}}</div>
                                <div class="class1-rewievs">
                                    <!-- якщо лайкнуте до a додати клас like + в i поміняти "fa-heart-o" на "fa-heart" -->
                                    @php
                                        $favorites = [];
                                        if(isset(Auth::user()->id)){
                                            foreach (Auth::user()->favorites as $favorite) {
                                                $favorites[$favorite->ads_id] = true;
                                            }
                                        }
                                    @endphp

                                    <div @if(isset($favorites[$ad->id])) class="like"@endif>
                                        <i class="fa fa-heart" aria-hidden="true"></i>
                                        <span>{{$ad->like}}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="class1-body">
                                <div class="class1-avatar" data-mh="class1">
                                    <div class="class1-avatar-photo" style="background-image: url({{asset($ad->user->photo)}})"></div>
                                </div>
                                <div class="class1-description" data-mh="class1">
                                    <div class="class1-description-title">
                                        <img src="{{asset('img/flag.png')}}" alt="">
@if(isset($ad->user->location->provincia))
                                        <span>{{$ad->user->location->provincia}} ({{$ad->user->location->city}})</span>

 @endif                                   </div>
                                    @if($ad->payable) <div class="class1-description-status">{{trans('main.anuncio_destacado')}}</div> @endif
                                    <div class="class1-description-text">{!! $ad->description !!}</div>
                                </div>
                                <div class="class1-rewievs" data-mh="class1">
                                    @php
                                        $total_points = 0;
                                        $total_voices = 0;

                                        foreach (\App\Models\AdsComments::active()->where('ads_id', $ad->id)->get() as $comment) {
                                            if(!$comment->parent_id){
                                                $total_points = $total_points+$comment->sumRating;
                                                $total_voices++;
                                            }
                                        }

                                        $stars = $total_points && $total_voices ? round($total_points/$total_voices) : '';
                                    @endphp
                                    <div class="rating">
                                        @for($i=1;$i<=10;$i=$i+2)
                                            <span class="fa @if($stars>=$i) fa-star @else fa-star-o @endif" aria-hidden="true"></span>
                                        @endfor
                                    </div>
                                    <div class="class1-rewievs-count">{{trans('main.reviews')}}: <span>{{count(\App\Models\AdsComments::where('ads_id', $ad->id)->active()->get())}}</span></div>
                                </div>
                            </div>         
                        </a>                      
                    </div>
                    @endforeach
                    @if(!count($ads))
                    <div class="empty-search">
                        <span>Lo sentimos, tu búsqueda no ha generado ningún resultado, inténtalo con otras palabras</span>
                        <a href="{{route('home')}}" class="button">Ir a la página principal</a>
                    </div>
                    @endif
                </div>
                <div class="pagination">
                    {{$ads->links()}}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection        
