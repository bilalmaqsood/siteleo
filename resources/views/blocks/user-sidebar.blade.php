@php
use App\Models\Ads;
use App\Models\AdsComments;
use App\Models\UsersChat;
$rout = Route::currentRouteName();

$counts = \App\Models\CoutEvents::counts();

$ad = $counts->ad_num;
$favorites = Auth::user()->favorites;
$chat = $counts->chat_num;
$ads = Ads::where('activ', 1)->where('user_id', Auth::user()->id)->get();
@endphp

<div class="mobile-sidebar">
	<div class="mobile-button">
		<i class="fa fa-list" aria-hidden="true"></i>
	</div>
</div>

<ul class="user-sidebar">
	@if(Auth::user()->role=='worker')
	<li @if($rout=='user-setting') class="active" @endif><a href="/user/setting"><i class="fa fa-cog" aria-hidden="true"></i>{{trans('main.ajustes_de_perfil')}}</a></li>
	<li @if($rout=='user-profile') class="active" @endif><a href="/user/profile"><i class="fa fa-user" aria-hidden="true"></i>{{trans('main.mi_perfil')}}</a></li>
	<li @if($rout=='user-messages') class="active" @endif><a href="/user/messages"><i class="fa fa-envelope" aria-hidden="true"></i>{{trans('main.tus_mensajes')}} @if($chat){{'('.$chat.')'}}@endif</a></li>

	<li @if($rout=='user-advertisement') class="active" @endif><a href="/user/advertisement"><i class="fa fa-th-list" aria-hidden="true"></i>{{trans('main.mis_anuncios')}} @if(count($ads)){{'('.count($ads).')'}}@endif</a></li>

	<li @if($rout=='user-new-advertisement') class="active" @endif><a href="/user/new-advertisement"><i class="fa fa-plus" aria-hidden="true"></i>{{trans('main.publica_un_nuevo_anuncio')}}</a></li>
	<li @if($rout=='user-feedback') class="active" @endif><a href="/user/feedback"><i class="fa fa-comment" aria-hidden="true"></i>{{trans('main.valoraciones')}} @if($ad){{'('.$ad.')'}}@endif</a> </li>
	<li @if($rout=='user-balance') class="active" @endif><a href="/user/balance"><i class="fa fa-money" aria-hidden="true"></i>{{trans('main.mi_saldo')}}</a></li>
	<li @if($rout=='user-favorite') class="active" @endif><a href="/user/favorite"><i class="fa fa-heart" aria-hidden="true"></i>{{trans('main.favoritos')}} @if(count($favorites)){{'('.count($favorites).')'}} @endif</a></li>
	@else
	<li @if($rout=='user-setting') class="active" @endif><a href="/user/setting"><i class="fa fa-cog" aria-hidden="true"></i>{{trans('main.ajustes_de_perfil')}}</a></li>
	<li @if($rout=='user-profile') class="active" @endif><a href="/user/profile"><i class="fa fa-user" aria-hidden="true"></i>{{trans('main.mi_perfil')}}</a></li>
	<li @if($rout=='user-messages') class="active" @endif><a href="/user/messages"><i class="fa fa-envelope" aria-hidden="true"></i>{{trans('main.tus_mensajes')}}</a></li>
	<li @if($rout=='user-favorite') class="active" @endif><a href="/user/favorite"><i class="fa fa-heart" aria-hidden="true"></i>{{trans('main.favoritos')}} @if(count($favorites)){{'('.count($favorites).')'}} @endif</a></li>
	@endif
</ul>
