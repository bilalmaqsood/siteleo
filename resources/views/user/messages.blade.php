@extends('layouts.user')
 
@section('body-class', 'user-office-page user-messages-page')
 
@section('user_content')

@if(!count($rooms))
    <div class="not-favorite">
        <span>{{trans('main.nothing_has_been_added_yet')}}</span>
    </div>
@else
    @foreach($rooms as $room)
        @php
            $user = \App\User::where('id', $room->user_id)->first();
            //dd($user->name);
        @endphp

        @if(!empty($user))
        <div class="user-message-item" @if($room->new) style="background-color: #efefef;" @endif >
            <a href="{{route('user-chat', ['id' => $room->user_id])}}">
                <div class="message-avatar">
                    <div class="message-avatar-photo" style="background-image: url({{isset($user->photo)?asset($user->photo):''}})"></div>
                    <!-- виводити коли ofline -->
                    @if($user->isOnline()) <span class="online">{{trans('main.on_line')}}</span> @else <span class="offline">{{trans('main.off_line')}}</span> @endif
                </div>
                <div class="message-description">
                    <div class="message-name"><div>{{$user->name}} {{$user->surname}}</div><span>{{isset($user->location) ? $user->location->city : ''}}</span></div>
                    <div class="message-text">{{$room->message}}</div>
                </div>
                <div class="message-time">{{$room->updated_at}}</div>
            </a>
        </div>
        @endif
    @endforeach
@endif
@endsection