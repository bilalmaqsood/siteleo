@extends('layouts.app')
 
@section('body-class', 'class2-page')
 
@section('content')
<div id="content">
        <div class="container">
        <div class="row">
            <div class="col-sm-3 col-sm-push-9 sticky">
                @if(isset(Auth::user()->id) && $ad->user->id!=Auth::user()->id)
                <div class="quick-contact">
                    <form method="post" action="{{route('advertise-chat', ['id' => $ad->id])}}">
                        <div class="quick-contact-title">Contacta sin compromiso</div>
                        {{ csrf_field() }}
                        @guest
                        <label>
                            <span>{{trans('main.name')}}</span>
                            <input type="text">
                        </label>
                        <label>
                            <span>{{trans('main.email')}}</span>
                            <input type="email">
                        </label>
                        @endguest
                        <label>
                            <span>{{trans('main.comments')}}</span>
                            <textarea name="message">{{old('message')}}</textarea>
                        </label>
                        {{--<button data-toggle="modal" data-target="#gless">Enviar consulta <i class="fa fa-angle-right" aria-hidden="true"></i></button>--}}
                        <button type="submit">{{trans('main.enviar_consulta')}} <i class="fa fa-angle-right" aria-hidden="true"></i></button>
                    </form>
                </div>
                @endif
            </div>
            <div class="col-sm-9 col-sm-pull-3">
                <div class="class2-wrapper">
                    @include('blocks.breadcrumbs')
                    <div class="class2-block">
                        <!-- якщо проплачене оголошення додати клас paid -->
                        <div class="class1-header @if($ad->payable) paid @endif">
                                @php $like = isset(Auth::user()->id) ? count(Auth::user()->favorites()->where('ads_id',$ad->id)->get()) : 1; @endphp
                                {{--<a @auth @if(!$like) href="#like" data-id="{{$ad->id}}" @else class="like" onclick="deleteFavorite(); return false;" @endif @endauth>
                                    <i class="fa @if(!$like) fa-heart-o @else fa-heart @endif" aria-hidden="true"></i>
                                    <span>{{$ad->like}}</span>
                                </a>--}}

@if(isset(Auth::user()->id))
                                <a href="#likes" data-id="{{$ad->id}}" class="@if($like)like @endif">
                                    <i class="fa fa-heart" aria-hidden="true"></i>
                                    <span>{{$ad->like}}</span>
                                </a>
@endif


                        </div>                            
                        <div class="user-info">
                            <div class="class1-avatar" data-mh="user">
                                <a href="{{route('profile', ['user_id' => $ad->user->id])}}" class="class1-avatar-photo" style="background-image: url({{asset($ad->user->photo)}})"></a>
                            </div>
                            <div class="class1-description" data-mh="user">
                                <div class="user-name"><a href="{{route('profile', ['user_id' => $ad->user->id])}}">{{$ad->user->name}}</a></div>
                                <div class="user-proffesion">{{$ad->name}}</div>
                                <div class="user-city">
                                @if(isset($ad->user->location->provincia)) {{$ad->user->location->provincia}}@endif 
                                @if(isset($ad->user->location->city))({{$ad->user->location->city}})@endif 
                                @if(isset($ad->user->location->postcode)) {{$ad->user->location->postcode}}@endif
                                </div>
                                <a href="{{route('profile', ['user_id' => $ad->user->id])}}" class="more-profile">más información</a>
                            </div>
                            <div class="class1-rewievs" data-mh="user">
                                <div class="profile-status">
                                    <i class="fa fa-clock-o" aria-hidden="true"></i>
                                    <span>@if($ad->user->isOnline()) En línea @else Desconectado @endif</span>
                                </div>
                                @if($ad->user->response_time['hours'])<div class="profile-response">Responde en {{$ad->user->response_time['hours']/$ad->user->response_time['num']}} horas</div>@endif
                                <!-- https://github.com/AbdullahGhanem/rating -->
                                <div class="rating">
                                    @for($i=1;$i<=10;$i=$i+2)
                                        <span class="fa @if($stars>=$i) fa-star @else fa-star-o @endif" aria-hidden="true"></span>
                                    @endfor
                                </div>
                                <div class="class1-rewievs-count">{{trans('main.reviews')}}: <span>{{count(\App\Models\AdsComments::where('ads_id', $ad->id)->where("parent_id","<=",0)->active()->get())}}</span></div>
                            </div>
                        </div>
                        <div class="user-description">           
                            <div class="сharacteristic-item">
                                <div class="user-title">{{implode(" / ", $cats_name)}}</div>
                                {!! $ad->terms_service !!}
                            </div>
                            <div class="user-text">
                                {!! $ad->description !!}
                            </div>
                            <div class="user-location">
                                <div class="user-title">{{trans('main.ubicaci_n')}}</div>
                                {{-- <p>Natalia imparte clases cerca de metro Méndez Álvaro, metro Puente de Vallecas</p>
                                <p>Se desplaza a domicilio a Madrid Capital</p> --}}
                                <input type="hidden" id="search-map">
                                <div id="map"></div>
                            </div>
                            <div class="user-time">
                                <div class="user-title">{{trans('main.disponibilidad_horaria_de')}} {{$ad->user->name}}</div>
                                <div class="user-time-block">
                                    <div class="user-clock">
                                        <canvas id="quote-diagram" height="100px" width="100px"></canvas>
                                    </div>

                                    @php
                                        if(isset($ad->user->graphics->begining_work_day)){
                                            $result_end = explode(' ', $ad->user->graphics->begining_work_day);
                                            $ampm = $result_end[1];
                                            $clock = str_replace([':00','12'], ['','0'], $result_end[0]);
                                        }else{
                                            $ampm = 0;
                                            $clock = 'am';
                                        }

                                    @endphp
                                    <script>
                                        var begining_work_day_ampm = '{{$ampm}}';
                                        var begining_work_day_clock = '{{$clock}}';
                                    </script>

                                    @php

                                        if(isset($ad->user->graphics->end_working_day)){
                                            $result_end = explode(' ', $ad->user->graphics->end_working_day);
                                            $ampm = $result_end[1];
                                            $clock = str_replace([':00','12'], ['','0'], $result_end[0]);
                                        }else{
                                            $ampm = 0;
                                            $clock = 'am';
                                        }

                                    @endphp
                                    <script>
                                        var end_working_day_ampm = '{{$ampm}}';
                                        var end_working_day_clock = '{{$clock}}';
                                        var map_address = "@if($ad->user->location){{$ad->user->location->provincia}} {{$ad->user->location->city}} {{$ad->user->location->postcode}}@endif";
                                        var map_radius = @if($ad->user->location){{$ad->user->location->radius}}@else 0 @endif;
                                    </script>

                                    <div class="user-clock-explanation">
                                        <h5>{{trans('main.disponibilidad_horaria')}}:</h5>
                                        <p>{{trans('main.from')}} {{$ad->user->graphics?$ad->user->graphics->begining_work_day:''}}
                                            {{trans('main.to')}} {{$ad->user->graphics?$ad->user->graphics->end_working_day:''}}</p>
                                        <h5>{{trans('main.dias_disponibles')}}:</h5>
                                        <p>{{implode(", ", $week_name)}}</p>
                                        <div class="user-clock-explanation-item">
                                            <span style="background-color: #ff6d2e"></span>{{trans('main.am_este_clolor_indica_la_dispo')}}
                                        </div>
                                        <div class="user-clock-explanation-item">
                                            <span style="background-color: #fd4b4b"></span>{{trans('main.este_color_indica_la_disponibi')}}
                                        </div>
                                        <div class="user-clock-explanation-item">
                                            <span style="background-color: #d0d4d9"></span>{{trans('main.en_este_horario_el_profesional')}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="user-feedbacks">
                                <div class="user-title">{{trans('main.reviews')}}</div>
                                <div>

                                    @foreach(\App\Models\AdsComments::where('ads_id', $ad->id)->active()->get() as $comment)
                                        @if($comment->parent_id == 0)
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
, {{isset($comment->user->location) ? $comment->user->location->provincia : ''}}, ({{isset($comment->user->location) ? $comment->user->location->city : ''}})</div>
                                                    <div class="feedback-text" style="overflow: auto;">{{$comment->message}}</div>
                                                    <div class="feedback-rating">
                                                        @for($i=1;$i<=10;$i=$i+2)
                                                        <span class="fa @if($comment->sumRating>=$i) fa-star @else fa-star-o @endif" aria-hidden="true"></span>
                                                        @endfor
                                                    </div>
                                                    @foreach(App\Models\AdsComments::where('parent_id', $comment->id)->active()->get() as $answers)
                                                        <div class="feedback-inner">
                                                            <div class="feedback-avatar">
                                                                <a href="{{route('profile', ['user_id' => $answers->user->id])}}" class="feedback-avatar-photo" style="background-image: url({{asset($answers->user->photo)}})"></a>
                                                            </div>
                                                            <div class="feedback-description">
                                                                <div class="feedback-name"><a href="{{route('profile', ['user_id' => $answers->user->id])}}">{{$answers->user->name}} {{$answers->user->surname}}</a></div>
                                                                <div class="feedback-time">{{$answers->created_at}}, {{$answers->user->location->provincia}}, ({{$answers->user->location->city}})</div>
                                                                <div class="feedback-text">{{$answers->message}}</div>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                            @if(isset(Auth::user()->id) && Auth::user()->id != $ad->user->id)
                            <!-- коли користувач зареєстровувати додати клас register -->
                            <div class="request @auth register @endauth">
                                <button>{{trans('main.enviar_consulta')}} <i class="fa fa-angle-down" aria-hidden="true"></i></button>
                                <!-- виводити коли користувач не зареєстрований -->
                                <div class="request-warning">{{trans('main.feedback_only_registered_user')}}</div>
                            </div> 
                            <div class="leave-feedback">
                                <div class="feedback-title">{{trans('main.deja_un_comentario')}}:</div>
                                <form method="post" action="{{route('advertise-commentary', ['id' => $ad->id])}}">
                                    {{ csrf_field() }}
                                    <div class="stars">
                                        <fieldset class="rating">
                                            <input type="radio" id="star5" name="rating" value="10" /><label class = "full" for="star5" title="Awesome - 5 stars"></label>
                                            {{--<input type="radio" id="star4half" name="rating" value="9" /><label class="half" for="star4half" title="Pretty good - 4.5 stars"></label>--}}
                                            <input type="radio" id="star4" name="rating" value="8" /><label class = "full" for="star4" title="Pretty good - 4 stars"></label>
                                            {{--<input type="radio" id="star3half" name="rating" value="7" /><label class="half" for="star3half" title="Meh - 3.5 stars"></label>--}}
                                            <input type="radio" id="star3" name="rating" value="6" /><label class = "full" for="star3" title="Meh - 3 stars"></label>
                                            {{--<input type="radio" id="star2half" name="rating" value="5" /><label class="half" for="star2half" title="Kinda bad - 2.5 stars"></label>--}}
                                            <input type="radio" id="star2" name="rating" value="4" /><label class = "full" for="star2" title="Kinda bad - 2 stars"></label>
                                            {{--<input type="radio" id="star1half" name="rating" value="3" /><label class="half" for="star1half" title="Meh - 1.5 stars"></label>--}}
                                            <input type="radio" id="star1" name="rating" value="2" /><label class = "full" for="star1" title="Sucks big time - 1 star"></label>
                                            {{--<input type="radio" id="starhalf" name="rating" value="1" /><label class="half" for="starhalf" title="Sucks big time - 0.5 stars"></label>--}}
                                        </fieldset>
                                    </div>
                                    <label>
                                        <span>{{trans('main.comentario')}}:</span>
                                        <textarea name="message_re">{{old('message_re')}}</textarea>
                                        {{--<button data-toggle="modal" data-target="#gless">Leave a review</button>--}}
                                        <button type="submit">{{trans('main.leave_a_review')}}</button>
                                    </label>
                                </form>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>     
@endsection

@push('scripts')
    <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyApmMWIVsXENuZA1DYjcDaE_-whpbPDasc&libraries=places"></script>
    <script type="text/javascript">

        $(document).ready(function() {
            $('[href="#likes"]').on('click', function () {
                var type = null;
                var obj = $(this);
                var numLikes = Number(obj.find('span').html());
                if (obj.hasClass("like")) {
                    obj.removeClass('like');
                    type = "del";
                    //obj.find('i').removeClass('fa-heart').addClass('fa-heart-o');
                    obj.find('span').html(numLikes - 1);
                } else {
                    $(this).clone().appendTo("body").addClass("cloned-like").css({
                        left: $(this).offset().left,
                        top: $(this).offset().top - $(".user .user-photo").offset().top
                    }).animate({
                        left: $(".user .user-photo").offset().left + 10,
                        top: $(".user .user-photo").offset().top + 10
                    }, function () {
                        $(this).fadeOut().remove();
                    });
                    obj.addClass('like');
                    //obj.find('i').removeClass('fa-heart-o').addClass('fa-heart');
                    obj.find('span').html(numLikes + 1);
                    type = "add";
                }

                $.ajax({
                    type: 'POST',
                    url: '{{route("advertise-like.store")}}',
                    headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                    data: {id: $(this).data('id'), type: type},

                    success: function (data) {

                    },
                    error: function (data) {
                        alert("Opps! Some thing went wrong.");
                    }
                });

                //$.post('{{route("advertise-like.store")}}', {id: $(this).data('id'), type: type});
                return false;
            });

        });

       var grey = '#d0d4d9';
        var pm = '#fd4b4b';
        var am = '#ff6d2e';
        var colors = [];
        var startWork = begining_work_day_clock;
        var endWork = end_working_day_clock; 
        var startDay = begining_work_day_ampm;
        var endDay = end_working_day_ampm;  

        clock();

        function clock(){
            for(var i = 0; i < 12; i++){
                        colors[i] = grey;
                }           
                if(startDay == endDay){     
                        if(startDay == "am"){
                                for(var i = startWork; i < endWork; i++){
                                        colors[i] = am;
                                }                       
                        }
                        if(startDay == "pm"){
                                for(var i = startWork; i < endWork; i++){
                                        colors[i] = pm;
                                }
                        }
                }
                if(startDay == "am" && endDay == "pm"){
                        for(var i = startWork; i < 12; i++){
                                colors[i] = am;
                        }   
                        for(var i = 0; i < endWork; i++){
                                colors[i] = pm;
                        }
                }
                if(startDay == "pm" && endDay == "am"){
                        for(var i = startWork; i < 12; i++){
                                colors[i] = pm;
                        }   
                        for(var i = 0; i < endWork; i++){
                                colors[i] = am;
                        }
                }
                var qouteD = document.getElementById("quote-diagram");   
                var qouteDiagram = new Chart(qouteD, {
                type: 'doughnut',
                data: {
                    labels: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12],
                    datasets: [{
                        data: [1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1 ],
                        backgroundColor: colors
                    }]
                },
                 options: {
                    legend: {
                        display: false
                    },
                    tooltips: {
                        enabled: false
                    },
                    responsiveAnimationDuration: 1000
                }
            });
        }  

            $(".from-clock").on('click', 'a', function(){
        startWork = $(this).attr("data-time");
        startDay = $(this).attr("data-clock");
                $(".to-clock").show();
        });     

    $('.to-clock').on('click', 'a', function(){
        endWork = $(this).attr("data-time");
        endDay = $(this).attr("data-clock");
        $(".user-clock").show();                
            });

            $('.to-clock, .from-clock').on('click', 'a', function(){

                clock();
                
        });
    </script>
    <script type="text/javascript">
        function initMap() {
          var myLatLng = {lat: 40.425133, lng: -3.704077};

          var map = new google.maps.Map(document.getElementById('map'), {
            zoom: 14,
            center: myLatLng
          });

          var marker = new google.maps.Marker({
            position: myLatLng,
            map: map
          });

          var mapSearch = $('#search-map');
        mapSearch.val(map_address.trim());

        var placs = new google.maps.places.SearchBox(document.getElementById('search-map'));
        placs.addListener('places_changed', function() {
            if (placs.getPlaces().length == 0) {
                    return;
            };
            var coordinate = placs.getPlaces()[0].geometry.location;
            map.setCenter(placs.getPlaces()[0].geometry.location);
            marker.setPosition(new google.maps.LatLng(coordinate.lat(),coordinate.lng()))

            var cityCircle = new google.maps.Circle({
                strokeColor: '#000000',
                strokeOpacity: 0.8,
                strokeWeight: 2,
                fillColor: '#000000',
                fillOpacity: 0.35,
                map: map,
                center: {lat: coordinate.lat(), lng: coordinate.lng()},
                radius: map_radius
              });
              map.fitBounds(cityCircle.getBounds());

        })


        setTimeout(function(){
            google.maps.event.trigger(document.getElementById('search-map'), 'focus', {});
            google.maps.event.trigger(document.getElementById('search-map'), 'keydown', {
                keyCode: 13
            });
        },2000)
        }


        initMap(); 

    </script>
    @auth
    @endauth
@endpush
