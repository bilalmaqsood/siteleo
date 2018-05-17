@extends('layouts.app')
  
@section('body-class', 'profile-page')
 
@section('content')
    <div id="content">
        	<div class="container"> 
                @include('blocks.breadcrumbs')
                <div class="profile-wrapper"> 
                    <div class="profile-header paid"></div>  
                    <div class="profile-info">
                        <div class="profile-info-left">
                            <div class="profile-name">{{$user->name}} {{$user->surname}}</div>
                            <div class="profile-city">{{$user->location->provincia}} ({{$user->location->city}})</div>
                            <div class="profile-rewievs">
                                <div class="rating">
                                    @for($i=1;$i<=10;$i=$i+2)
                                        <span class="fa @if($stars>$i) fa-star @else fa-star-o @endif" aria-hidden="true"></span>
                                    @endfor
                                </div>
                                <div class="profile-rewievs-count">{{trans('main.reviews')}}: <span>{{$coments}}</span></div>
                            </div>
                        </div>
                        <div class="profile-info-center">
                            <a href="{{asset($user->photo)}}" data-fancybox="profile" class="profile-avatar" style="background-image: url({{asset($user->photo)}})"></a>
                        </div>
                        <div class="profile-info-right">
                            <div class="profile-status">
                                <i class="fa fa-clock-o" aria-hidden="true"></i>
                                <span>@if($user->isOnline()) {{trans('main.on_line')}} @else {{trans('main.off_line')}} @endif</span>
                            </div>
                            @if($user->response_time['hours']) <div class="profile-response">{{trans('main.responde_en')}} {{$user->response_time['hours']/$user->response_time['num']}} {{trans('main.horas')}}</div> @endif
                            <div class="profile-experience"><i class="fa fa-bookmark-o" aria-hidden="true"></i>{{$user->experience}} of experience</div>
                            <div class="profile-salary"><i class="fa fa-money" aria-hidden="true"></i>{{$user->price_per_hour}}</div>
                        </div>
                        <div class="profile-buttons">
                            <a href="#" data-toggle="modal" data-target="#message"><i class="fa fa-envelope-o" aria-hidden="true"></i> {{trans('main.contactar')}}</a>
                            <!-- В атрибут data-phone передавати номер користувача -->
                           <!-- <a href="#" class="show-phone" data-phone="{{$user->phone}}"><i class="fa fa-phone" aria-hidden="true"></i> {{trans('main.tel_fono')}}</a> -->
                        </div>
                    </div>
                    <div class="profile-block">
                        <div class="profile-item">
                            <!-- <div class="profile-title">My services</div> -->
                            <div class="profile-title">Mis servicios</div>
                            @php $services = $user->services; @endphp
                            <ul>
                            @foreach($services as $service)
                                <li>{{ $service->title }}</li>
                            @endforeach
                            </ul>
                        </div>
                        <div class="profile-item">
                            <div class="profile-title">{{trans('main.about_me')}}</div>
                            {!! $user->personal_information !!}
                        </div>
                        <div class="profile-item">
                            <div class="profile-title">{{trans('main.training_degrees_courses_and_c')}}</div>
                            @foreach($user->certificates as $caertificate)
                            <p>{{$caertificate->title}}</p>
                            @endforeach
                        </div>
                        <div class="profile-item">
                            <div class="profile-title">{{trans('main.about_expirions')}}</div>
                            {!! $user->about_experience !!}
                        </div>
                        <div class="profile-item">
                            <div class="profile-title">{{trans('main.where_is')}} {{$user->name}}?</div>
                            {{--<p>Natalia imparte clases cerca de metro Méndez Álvaro, metro Puente de Vallecas<br>--}}
                            {{--Se desplaza a domicilio a Madrid Capital</p>--}}
                            <input type="hidden" id="search-map">
                            <div id="map"></div>
                        </div>
                        <div class="profile-item">
                            <div class="profile-title">{{trans('main.disponibilidad_horaria_de')}} {{$user->name}}</div>
                            <div class="user-time-block">
                                <div class="user-clock">
                                    <canvas id="quote-diagram" height="100px" width="100px"></canvas>
                                </div>

                                @php
                                    if(isset($user->graphics->begining_work_day)){
                                        $result_end = explode(' ', $user->graphics->begining_work_day);
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

                                    if(isset($user->graphics->end_working_day)){
                                        $result_end = explode(' ', $user->graphics->end_working_day);
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

                                    var map_address = "@if($user->location){{$user->location->provincia}} {{$user->location->city}} {{$user->location->postcode}}@endif";
                                    var map_radius = @if($user->location){{$user->location->radius}}@else 0 @endif;
                                </script>

                                <div class="user-clock-explanation">
                                    <h5>{{trans('main.disponibilidad_horaria')}}:</h5>
                                    <p>from {{$user->graphics->begining_work_day}} to {{$user->graphics->end_working_day}}</p>
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
                        <div class="profile-item">
                            <div class="profile-title">GALERIA</div>
                            <p>{{trans('main.aqui_puedes_ver_las_fotos_de_l')}}</p>
                            <div class="profile-gallery">
                                @foreach($user->gallery as $galery)
                                <a href="{{asset($galery->photo)}}" data-fancybox="gallery" style="background-image: url({{asset($galery->photo)}})"></a>
                                @endforeach
                            </div>
                        </div>
                        <div class="profile-item">
                            <div class="profile-title">{{trans('main.reviews')}}</div>
                            <div>

                                @foreach($user->ads as $ad)
                                    @foreach($ad->comments->where('active','1') as $comment)
                                        @if($comment->parent_id == 0)
                                            <div class="user-feedbacks-item">
                                                <div class="feedback-avatar">
                                                    <a href="{{route('profile', ['user_id' => $comment->user->id])}}" class="feedback-avatar-photo" style="background-image: url({{asset($comment->user->photo)}})"></a>
                                                </div>
                                                <div class="feedback-description">
                                                    <div class="feedback-name"><a href="{{route('profile', ['user_id' => $comment->user->id])}}">{{$comment->user->name}} {{$comment->user->surname}}</a></div>
                                                    <div class="feedback-time">{{$comment->created_at}}@if(isset($comment->user->location)), {{$comment->user->location->provincia}}, ({{$comment->user->location->city}})@endif</div>
                                                    <div class="feedback-text" style="overflow: auto;">{{$comment->message}}</div>
                                                    <div class="feedback-rating">
                                                        @for($i=1;$i<=10;$i=$i+2)
                                                            <span class="fa @if($comment->sumRating>=$i) fa-star @else fa-star-o @endif" aria-hidden="true"></span>
                                                        @endfor
                                                    </div>
                                                    @foreach(App\Models\AdsComments::where('parent_id', $comment->id)->get() as $answers)
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
                                @endforeach
                            </div>
                        </div>

                        <div class="profile-item">
                            <div class="profile-title">{{trans('main.advertisement')}} {{$user->name}}</div>
                            {{--<p>Natalia imparte clases cerca de metro Méndez Álvaro, metro Puente de Vallecas <br>--}}
                            {{--Se desplaza a domicilio a Madrid Capital</p>--}}
                            @foreach($user->ads as $ad)
                            <div class="class1-item paid">
                                <a href="{{route('advertise', ['uri' =>$ad->uri])}}">
                                    <div class="class1-header">
                                        <div class="class1-avatar"></div>
                                        <div class="class1-description">{{$ad->name}}</div>
                                        <div class="class1-rewievs">
                                        <div>
                                                <i class="fa fa-heart-o" aria-hidden="true"></i>
                                                <span>{{$ad->like}}</span>
					

                                            </div>
                                        </div>
                                    </div>
                                    <div class="class1-body">
                                        <div class="class1-avatar" data-mh="class1">
                                            <div class="class1-avatar-photo" style="background-image: url({{asset($user->photo)}})"></div>
                                        </div>
                                        <div class="class1-description" data-mh="class1">
                                            <div class="class1-description-title">
                                                <img src="{{asset('img/flag.png')}}" alt="">
                                                @php
                                                    $cats_name = [];
                                                    foreach ($ad->categories as $category) {
                                                        $cats_name[] = $category->title;
                                                    }
                                                @endphp

                                                <span>{{implode(" / ", $cats_name)}}</span>
                                            </div>
                                            @if($ad->payable) <div class="class1-description-status">{{trans('main.anuncio_destacado')}}</div> @endif
                                            <div class="class1-description-text">{!! $ad->description !!}</div>
                                        </div>
                                        <div class="class1-rewievs" data-mh="class1">
                                            @php
                                                $total_points = 0;
                                                $total_voices = 0;

                                                foreach ($ad->comments()->active()->get() as $comment) {
                                                    if(!$comment->parent_id){
                                                        $total_points = $total_points+$comment->sumRating;
                                                        $total_voices++;
                                                    }
                                                }

                                                $stars = $total_points && $total_voices ? round($total_points/$total_voices) : 0;
                                            @endphp

                                                @if($stars)
                                                    <div class="rating">
                                                        @for($i=1;$i<=10;$i=$i+2)
                                                            <span class="fa @if($stars>=$i) fa-star @else fa-star-o @endif" aria-hidden="true"></span>
                                                        @endfor
                                                    </div>
                                                    @else
                                                <div class="rating">
                                                    @for($i=1;$i<=10;$i=$i+2)
                                                    <span class="fa fa-star-o" aria-hidden="true"></span>
                                                     @endfor
                                                </div>
                                                @endif

                                        </div>
                                    </div> 
                                </a>                              
                            </div>
                            @endforeach

                        </div>
                    </div>
                </div>
            </div>
        </div>
@endsection

@push('modal')
@if($user->ads->first())
    <div class="modal fade message" id="message" tabindex="-1">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5>Escríbele un mensaje a {{$user->name}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><i class="fa fa-times" aria-hidden="true"></i></button>
                </div>
                <div class="modal-body">
                    <div class="quick-contact">
                        <form method="post" action="{{route('advertise-chat', ['id' => $user->ads->first()->id])}}">
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
                                <textarea name="message"></textarea>
                            </label>
                            {{--<button data-toggle="modal" data-target="#gless">Enviar consulta <i class="fa fa-angle-right" aria-hidden="true"></i></button>--}}
                            <button type="submit">{{trans('main.enviar_consulta')}} <i class="fa fa-angle-right" aria-hidden="true"></i></button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif
@endpush

@push('scripts')
   <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyB-sZgZdP3F8OQC994en-n6hkndb9CKKa4&libraries=places"></script>
    <script type="text/javascript">
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
            google.maps.event.trigger(document.getElementById('search-map'), 'focus');
            google.maps.event.trigger(document.getElementById('search-map'), 'keydown', {
                keyCode: 13
            });
        },2000)
        }


        initMap(); 

    </script>
@endpush
