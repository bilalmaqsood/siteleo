@extends('layouts.user')
 
@section('body-class', 'user-office-page user-favorite-page')
 
@section('user_content')
    @if(!count(Auth::user()->favorites))
        <!-- виводити коли нема вподобаних -->
        <div class="not-favorite">
            <i class="fa fa-heart" aria-hidden="true"></i>
            <span>{{trans('main.nothing_has_been_added_yet1')}}</span>
        </div>
    @else
        @foreach(Auth::user()->favorites as $favorite)
        <div class="class1-item paid">
                <a href="/advertise/{{$favorite->ads->uri}}">
                    <div class="class1-header">
                        <div class="class1-avatar"></div>
                        <div class="class1-description">{{$favorite->ads->name}}</div>
                        <div class="class1-rewievs">
                            <!-- якщо лайкнуте до a додати клас like + в i поміняти "fa-heart-o" на "fa-heart" -->
                            <div class="like">
                                <i class="fa fa-heart" aria-hidden="true"></i>
                                <span>{{$favorite->ads->like}}</span>
                            </div>
                        </div>
                    </div>
                    <div class="class1-body">
                        <div class="class1-avatar" data-mh="class1">
                            <div class="class1-avatar-photo" style="background-image: url({{asset($favorite->ads->user->photo)}})"></div>
                        </div>
                        <div class="class1-description" data-mh="class1">
                            <div class="class1-description-title">
                                <img src="{{asset('img/flag.png')}}" alt="">
                                <span>
                                    @if(isset($favorite->ads->user->location))
                                        {{$favorite->ads->user->location->city}} |
                                    @endif
                                        {{\App\Models\Category::find($favorite->ads->category[(count($favorite->ads->category)-1)])->title}}
                                </span>
                            </div>
                            @if($favorite->ads->payable) <div class="class1-description-status">{{trans('main.anuncio_destacado')}}</div> @endif
                            <div class="class1-description-text">{!! $favorite->ads->description !!}</div>
                        </div>
                        <div class="class1-rewievs" data-mh="class1">
                            @php
                                $total_points = 0;
                                $total_voices = 0;

                                foreach ($favorite->ads->comments as $comment) {
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
                            <div class="class1-rewievs-count">{{trans('main.reviews')}}: <span>{{count($favorite->ads->comments)}}</span></div>
                        </div>
                    </div>
                </a>
            </div>
        @endforeach
    @endif
@endsection
