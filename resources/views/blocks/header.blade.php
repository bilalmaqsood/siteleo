<header>
	<div class="container">
		<div class="header">
			<div class="logo">
				<a href="/">
					<img src="{{ asset('img/logo.png') }}" alt="EL Profesional">
				</a>
			</div>
			<div class="how-it-works">
				<a href="/faq/">{{trans('main.kak_eto_rabotaet')}}</a>
			</div>
                        @auth
			<!-- Коли зареєстрований вивести наступне -->
			<div class="dollar"><i class="fa fa-money" aria-hidden="true"></i>
				<span>{{Auth::user()->balance }}</span>
			</div>
			<div class="notification">
				<i class="fa fa-bell" aria-hidden="true"></i>

				@php
					$ad_num = \App\Models\AdsComments::where('ads_user', Auth::user()->id)->where('new', 1)->get();
					$chat_num = \App\Models\UsersChat::where('partner_id', Auth::user()->id)->where('new', 1)->get();
				@endphp

				<script>
					function livaeUpdate(){
					    $.get('/live-upd', {}, function(data){
//					        $('#new_events').html(data.new_events);
//					        $('#chat_num').html(data.chat_num);
//					        $('#ad_num').html(data.ad_num);
						});
					}
					$(function(){ setInterval(livaeUpdate, 200000); });
                    livaeUpdate();
				</script>

				<span id="new_events">{{count($chat_num)+count($ad_num)}}</span>

			
				<div class="notification-hover">
@if(count($chat_num)!=0)<div><a href="{{route('user-messages')}}"><i class="fa fa-envelope" aria-hidden="true"></i><span id="chat_num">{{count($chat_num)}}</span>
@if(count($chat_num)==1)
mensaje nuevo
@else
mensajes nuevos
@endif
 </a></div>
@else
<div><a href="{{route('user-messages')}}"><i class="fa fa-envelope" aria-hidden="true"></i><span id="chat_num" style='display:none'></span> No tienes mensajes nuevos</a></div>

@endif



					@if(Auth::user()->role=='worker')
					
@if(count($chat_num)!=0)
<div><a href="{{route('user-feedback')}}"><i class="fa fa-comment" aria-hidden="true" style="margin-right: 10px;"></i><span id="ad_num" class="{{count($ad_num)==0?'hide hidden':''}}">{{count($ad_num)}}</span>
@if(count($ad_num)==1)
valoración nueva
@else
No tienes valoraciones
@endif

</a></div>
@else
<div><a href="{{route('user-feedback')}}"><i class="fa fa-comment" aria-hidden="true" style="margin-right: 10px;"></i><span id="ad_num" style='display:none'></span>No tienes valoraciones</a></div>
@endif
					@endif
                </div>
			</div>
			<div class="user">
				{{ Auth::user()->id }}
                            <div class="user-photo" style="background-image: url({{asset(Auth::user()->photo)}});"></div>
                            <div class="user-name"><a href="{{route('user')}}"> {{ Auth::user()->name }} </a></div>
                            <div class="user-hover">
                                    <div><a href="/user/favorite"><i class="fa fa-heart" aria-hidden="true"></i>{{trans('main.favoritos')}}</a></div>
                                    <div><a href="/user/messages"><i class="fa fa-envelope" aria-hidden="true"></i>{{trans('main.tus_mensajes')}}</a></div>
                                    <div><a href="/user/profile"><i class="fa fa-cog"></i>CONFIGURACION</a></div>
			@if(!empty(Auth::user()->birthday) && Auth::user()->sex && Auth::user()->location && Auth::user()->graphics)		<div><a href="/profile/id{{ Auth::user()->id }}"><i class="fa fa-user" aria-hidden="true"></i>{{trans('main.mi_perfil')}}</a></div>@endif
                            </div>
			</div>
			<div class="class2">
				<a @if(Auth::user()->role=='worker') href="{{route('user-new-advertisement')}}" @else href="#" onclick="$('#gless').find('.modal-header h5').html('{{trans('main.attention')}}'); $('#gless').find('.modal-body h5').html('{{trans('main.please_switch_to_the_worker_ac')}}');" data-toggle="modal" data-target="#gless" @endif>{{trans('main.annciate')}}</a>
			</div>
                        @endauth
			<div class="choose-your-way">
				@guest
					<a class="login-button" href="{{ route('login') }}">
						<img src="{{ asset('img/login.png') }}" alt="login">
						<span>{{trans('main.entrar')}}</span>
					</a>
					<a class="register-button" href="{{ route('register') }}">
						<span>{{trans('main.register')}}</span>
					</a>
				@else
					<a class="register-button" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
						{{trans('main.logout')}}
					</a>

					<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
						{{ csrf_field() }}
					</form>
				@endguest
			</div>

		</div>
	</div>
</header>
