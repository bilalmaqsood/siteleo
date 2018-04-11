@extends('layouts.user')
 
@section('body-class', 'user-office-page user-chat-page')
 
@section('user_content')


        <div class="message-avatar">
            <a href="{{route('profile', ['user_id' => $partner->id])}}" class="message-avatar-photo" style="background-image: url({{asset($partner->photo)}})"></a>
            <div class="message-name">
                <a href="{{route('profile', ['user_id' => $partner->id])}}">{{$partner->name}} {{$partner->surname}}</a>
                @if($partner->isOnline()) <span class="online">{{trans('main.on_line')}}</span> @else <span class="offline">{{trans('main.off_line')}}</span> @endif
            </div>
        </div>

<div class="user-chat-block add-scroll" id="user-chat-block">
@foreach($mess as $message)
    @if(Auth::user()->id != $message->user_id)
    <div class="user-message-item">
        <div class="message-description">
            <div class="message-text">{!! $message->message !!}</div>
        </div>
        <div class="message-time">{{$message->created_at}}</div>
    </div>
    @else
    <div class="user-message-item me-message">
        <div class="message-time">{{$message->created_at}}</div>
        <div class="message-description">
            <div class="message-text">{!! $message->message !!}</div>
        </div>
    </div>
    @endif
@endforeach
</div>
<div class="text-form">
    <form method="post" action="{{route('user-chat-say', ['id' => $partner->id])}}" pjax-container="" onsubmit="sendMessage($(this)); return false;">
        {{ csrf_field() }}
        <textarea placeholder="Enter message" name="message" onkeypress='textArea(event, $(this));'></textarea>
        <div class="upload-block">
            <button type="submit" data-loading-text="<i class='fa fa-spinner fa-spin '></i> Loaing">{{trans('main.send')}}</button>
        </div>
    </form>
</div>
<script>
    function sendMessage(obj){
        var val = obj.find('textarea').val();
        if(val=='') return false;
        obj.trigger('reset');
        $.get('{{route('user-chat-say-ajax', ['id' => $partner->id])}}', {message:val}, function(data){
            if(data!=='none') {
                $('#mCSB_1_container').append(data);
                $("#mCSB_1_container").css("top", $(".user-chat-block").height() - $("#mCSB_1_container").height());
            }
        });
    }

    function textArea(event, obj){
        if(event.keyCode==13) {
            event.preventDefault();
            if(event.shiftKey) {
                obj.val(obj.val()+"\n");
                return false;
            }
            obj.parent('form').submit();
            return false;
        }
    }
    function liveChat(){
        $.get('/live-chat', { width:{{$partner->id}} }, function(data){
            if(data!='none'){
                $('#mCSB_1_container').append(data);
                $("#mCSB_1_container").css("top", $(".user-chat-block").height() - $("#mCSB_1_container").height());
            }
        });
    }

    $(function(){ setInterval(liveChat, 2000); });
</script>
@endsection
@push('scripts')
    <script>

        $(document).ready(function(){
            $("#mCSB_1_container").css("top", $(".user-chat-block").height() - $("#mCSB_1_container").height())
        });

        
    </script>
@endpush