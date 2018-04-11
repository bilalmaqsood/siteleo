@extends('layouts.user')
 
@section('body-class', 'user-office-page user-balance-page')
 
@section('user_content')
    <!-- <div class="user-title">{{trans('main.mi_saldo')}}:</div> -->
    
<!--     <p>{{trans('main.ahora_tienes_la_opcion_de_reca')}}</p>
    <p>{{trans('main.el_saldo_no_caduca_y_seg_n_el')}}</p> -->

    <div class="new-text">
        <h3>Sube tu anuncio a las primeras posiciones</h3>
   
        <p>Tu anuncio aparecerá de nuevo en las posiciones más visibles, asegurándote así que los clientes puedan ver tu anuncio.</p>

        <ul>
            <li><strong>Aparece en los primeros resultados de búsqueda de profesionales</strong></li>
        </ul>

        <p>Los clientes pueden ponerse en contacto más fácilmente con los primeros profesionales de la lista.</p>
        
        <p>Los profesionales mejor posicionados reciben un promedio de 10 veces más solicitudes de los clientes.</p>

        <p>Recargando <strong>SALDO</strong> a tu cuenta puedes subir tus anuncios cuando quieras. El <strong>SALDO</strong> no caduca y según el importe ingresado te ofrecemos un <strong>BONUS</strong> DE 3€ o 10€ para gestionar tus anuncios y obtener más clientes Con esta opción <strong>CADA SUBIDA</strong> tiene un coste de <strong>1.45€</strong></p>

        <p><span></span> Si elijes una <strong>SUBIDA INDIVIDUAL</strong> el coste es de 1.90€ por subida.</p>

        <p><span></span> Si <strong>SALTAS ESTE PASO</strong> tu anuncio se publicará igualmente.</p>
    </div>

    <div class="user-bill">{{trans('main.saldo_actual')}}: <span>{{number_format(Auth::user()->balance, 2, ',', ' ')}}€</span></div>
    
    <div class="price-title">{{trans('main.quiero_recargar_saldo')}}:</div>
    <form method="post" action="{{route('user-balance-pay')}}">
        {{ csrf_field() }}
        <div class="cost">
            @foreach(App\Models\ListCost::all() as $cost)
            <div class="cost-item">
                <input type="radio" name="cost" value="{{$cost->id}}">
                <label>{{$cost->cost}} € @if($cost->cost_bonus)+ <span>{{$cost->cost_bonus}} € {{trans('main.gratis')}}</span>@endif</label>
            </div>
            @endforeach
        </div>
        <button class="save">{{trans('main.recarga_saldo')}}</button>
    </form>
    <form>
        <div class="cost">
        @foreach($ads as $ad)
                    <div class="cost-item">
                        <input type="radio" value='{{ $ad->id }}' name="adver-item">
                        <label>{{ $ad->name }}</label>
                    </div>
        @endforeach
            <input type='hidden' value='1.9' name='cost'>
        </div>
        <button class="save">SUBIDA INDIVIDUAL </button> <span class="fa fa-info-circle" data-toggle="tooltip" data-placement="right" title="Coste 1.90 euro"></span> <br>
        <div><a class="save skip" style='display: inline-block;' href='{{ route("user-new-advertisement") }}'>SALTAR ESTE PASO</a></div>
    </form>

    <!-- <div class="user-bill">SALDO ACTUAL: <span>0,00€</span></div> -->
@endsection


