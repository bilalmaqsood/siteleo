@php
    $rout = Route::currentRouteName();
    $step1 = !empty(Auth::user()->birthday) && Auth::user()->sex && Auth::user()->location && Auth::user()->graphics ? 'full':false;
    $step2 = count(Auth::user()->ads) ? 'full':false;
    $step3 = Auth::user()->balance ? 'full':false;
    $step4 = $step1 && $step2 && $step3 ? 'full':false;
@endphp
<div class="book-list">
@if(Auth::user()->role=='worker')  
<ul>
        <!-- додати класи:
             full - коли заповнено,
             active - коли вкладка активна,
             disabled - для наступних вкладок -->
        <li class="{{$rout=='user-profile' ? 'active':''}} {{$step1}}"><a href="{{route('user-profile')}}"><span>{{trans('main.mi_perfil')}}</span></a></li>
        <li class="{{$rout=='user-advertisement' ? 'active':''}} {{$step2}}"><a href="{{route('user-advertisement')}}"><span>{{trans('main.mis_anuncios')}}</span></a></li>
        <li class="{{$rout=='user-balance' ? 'active':''}} {{$step3}}"><a href="{{route('user-balance')}}"><span>{{trans('main.mi_saldo')}}</span></a></li>
        <li class="{{$step4}}"><a><span>Resultado</span></a></li>
    </ul>
@endif
</div>
