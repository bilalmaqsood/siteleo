@extends('layouts.user')
 
@section('body-class', 'user-office-page user-feedback-page')
 
@section('user_content')
    @php
        $ads = \App\Models\Ads::where('user_id', Auth::user()->id)->orderBy('created_at', 'asc')->get();
        $show=1;
    @endphp
    {{--<div class="user-title">{{trans('main.profesora_nativa_biling_e_de_f')}}</div>--}}
    @foreach($ads as $ad)
        @php
            $comments = \App\Models\AdsComments::where('ads_id', $ad->id)->where('parent_id', 0)->where('active', 1)->orderBy('created_at', 'desc')->active()->get();
        @endphp
        @if(!count($comments) && $show)
            <div class="user-title">{{trans('main.you_do_not_have_any_reviews')}}</div>
            @php $show=0; @endphp
        @endif
        @foreach($comments as $comment)
            <div class="user-feedbacks-item">
                <div class="feedback-avatar">
                    <a href="{{route('profile', ['user_id' => $comment->user->id])}}" class="feedback-avatar-photo" style="background-image: url({{asset($comment->user->photo)}})"></a>
                </div>
                <div class="feedback-description">
                    <div class="feedback-name"><a href="{{route('profile', ['user_id' => $comment->user->id])}}">{{$comment->user->name}} {{$comment->user->surname}}</a></div>
                    <div class="feedback-time">
@php
$dates=$comment->created_at;
$dates1=explode(' ',$dates);
$dates2=explode('-',$dates1[0]);
$dates3=explode(':',$dates1[1]);
$test=$dates2[2].'-'.$dates2[1].'-'.$dates2[0].' '.$dates3[0].':'.$dates3[1];
echo $test;
@endphp
@if(isset($comment->user->location)), {{$comment->user->location->provincia}}, ({{$comment->user->location->city}})@endif</div>
                    <div class="feedback-text" style="overflow: auto;">{{$comment->message}}</div>
                    <div class="feedback-rating">
                        @for($i=1;$i<=10;$i=$i+2)
                            <span class="fa @if($comment->sumRating>=$i) fa-star @else fa-star-o @endif" aria-hidden="true"></span>
                        @endfor
                    </div>
                    @php $answers = App\Models\AdsComments::where('parent_id', $comment->id)->get(); @endphp
                    @foreach($answers as $answers)
                    <div class="feedback-inner">
                        <div class="feedback-avatar">
                            <a href="{{route('profile', ['user_id' => $answers->user->id])}}" class="feedback-avatar-photo" style="background-image: url({{asset($answers->user->photo)}})"></a>
                        </div>
                        <div class="feedback-description">
                            <div class="feedback-name"><a href="{{route('profile', ['user_id' => $answers->user->id])}}">{{$answers->user->name}} {{$answers->user->surname}}</a></div>
                            <div class="feedback-time">
@php
$dates=$answers->created_at;
$dates1=explode(' ',$dates);
$dates2=explode('-',$dates1[0]);
$dates3=explode(':',$dates1[1]);
$test=$dates2[2].'-'.$dates2[1].'-'.$dates2[0].' '.$dates3[0].':'.$dates3[1];
echo $test;
@endphp
@if(isset($answers->user->location)), {{$answers->user->location->provincia}}, ({{$answers->user->location->city}})@endif</div>
                            <div class="feedback-text">{{$answers->message}}</div>
                        </div>
                    </div>
                    @endforeach

                    @if(!count($answers)) <div class="answer">{{trans('main.answer')}}</div> @endif
                </div>
                @if(!count($answers))
                <div class="answer-block">
                    <form id="answer_{{$comment->id}}" method="post" action="{{route('user-feedback-answer', ['id'=>$comment->id])}}">
                        {{ csrf_field() }}
                        <div class="unswer-name">{{$comment->user->name}} {{$comment->user->surname}}</div>
                        <textarea name="message">{{old('message')}}</textarea>
                        <button onclick="$('#answer_{{$comment->id}}').submit(); return false;" class="save">{{trans('main.send')}}</button>
                    </form>
                </div>
                @endif
            </div>
        @endforeach
    @endforeach
@endsection                   
