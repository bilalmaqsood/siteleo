@extends('layouts.user')
 
@section('body-class', 'user-office-page user-new-class1-page')

@section('user_content')
    <div class="user-title">{{trans('main.perfil')}}:</div>
    <div class="user-subtitle">{{trans('main.para_publicar_tu_anuncio_neces')}}</div>
    @if(!$profile_status)
    <div class="user-information">
        <!-- якщо заповнена інформація про користувача клас "check" якщо ні "uncheck" -->
        <div class="user-check @if($profile_status) check @else uncheck @endif"></div>
        <span>{{trans('main.informacion')}}</span>
        <a href="/user/profile"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
    </div>
    @endif
    <div class="user-title">{{trans('main.nuevo_anuncio')}}:</div>
    <form method="post" action="@if(isset($ad)){{route('user-new-advertisement-update', ['id' => $ad->id])}}@else{{route('user-new-advertisement-create')}}@endif" id="form">
        {{ csrf_field() }}
        <div class="item-filter">
            <span>{{trans('main.selecciona_una_categoria')}} *</span>
            <input type="hidden" class="for-search" id="cat_level1" name="category[]" value="@if(isset($ad) && isset($ad->category[0])){{$ad->category[0]}}@endif" required>
            <div class="dropdown-list">
                <button><span>@if(isset($ad) && isset($categories[$ad->category[0]])) {{$categories[$ad->category[0]]->title}} @endif</span></button>
                <ul class="add-scroll">
                    @foreach($category->where('parent_id', 0)->orderBy('order')->get() as $item)
                        <li onclick="$('#cat_level1').val({{$item->id}});$('.parent_').hide();$('[id=parent_{{$item->id}}]').show();"><a href="#">@if(!empty($item->icon))<img src="{{asset($item->icon)}}" alt="{{$item->title}}" width="15px"> @endif{{$item->title}}</a></li>
                    @endforeach
                </ul>
            </div>
        </div>
         <div class="item-filter">
            <span>{{trans('main.selecciona_una_subcategoria')}} *</span>
            <input type="hidden" class="for-search" id="cat_level2" name="category[]" value="@if(isset($ad) && isset($ad->category[1])){{$ad->category[1]}}@endif" required>
            <div class="dropdown-list">
                <button><span>@if(isset($ad) && isset($categories[$ad->category[1]])) {{$categories[$ad->category[1]]->title}} @endif</span></button>
                <ul class="add-scroll">
                    @foreach($category->where('parent_id', '!=', 0)->orderBy('order')->get() as $item)
                        <li onclick="$('#cat_level2').val({{$item->id}});$('.parent_parent_').hide();$('[id=parent_parent_{{$item->id}}]').show();" class="parent_" id="parent_{{$item->parent_id}}" style="display: none;"><a href="#">{{$item->title}}</a></li>
                    @endforeach
                </ul>
            </div>
        </div>
        {{--<div class="item-filter">
            <span>{{trans('main.selecciona_una_subcategoria')}} *</span>
            <input type="hidden" class="for-search" id="cat_level3" name="category[]" value="@if(isset($ad) && count($ad->category) && isset($ad->category[2])){{$ad->category[2]}}@endif" required>
            <div class="dropdown-list">
                <button><span>@if(isset($ad) && count($ad->category) && isset($categories[$ad->category[2]])) {{$categories[$ad->category[2]]->title}} @endif</span></button>
                <ul class="add-scroll">
                    @foreach($category->where('parent_id', '!=', 0)->orderBy('order')->get() as $item)
                        <li onclick="$('#cat_level3').val({{$item->id}});" class="parent_parent_" id="parent_parent_{{$item->parent_id}}" style="display: none;"><a href="#">{{$item->title}}</a></li>
                    @endforeach
                </ul>
            </div>
        </div>--}}
        <label class="profile-input">
            <span>{{trans('main.titulo_de_anuncio')}} *</span>
            <input type="text" name="name" value="@if(isset($ad->name)){{$ad->name}}@else{{old('name')}}@endif" required>
        </label>
        <div class="text-redactor">
            <label><div>{{trans('main.t_rminos_del_servicio')}} *</div><div><span class="chars_left">0</span> CARACTERES RESTANTES</div></label>
            <textarea class="redactor" name="terms_service">@if(isset($ad->terms_service)){{$ad->terms_service}}@else{{old('terms_service')}}@endif</textarea>
            <div class="mustText">SE REQUIEREN AL MENOS 150 CARACTERES</div>
        </div>
        <div class="text-redactor">
            <label><div>{{trans('main.descripci_n')}} *</div><div><span class="chars_left">0</span> CARACTERES RESTANTES</div></label>
            <textarea class="redactor" name="description">@if(isset($ad->description)){{$ad->description}}@else{{old('description')}}@endif</textarea>
            <div class="mustText">SE REQUIEREN AL MENOS 150 CARACTERES</div>
        </div>

        <div class="submit-button">
            <button class="@if(!isset($ad)) not-allowed @endif" title="acepta las reglas" @if(isset($ad)) loading @endif>{{trans('main.publica_un_anuncio')}}</button>
            <div class="checkbox">
                <input type="checkbox" name="conditions" value="1" id="user-contract" class="hidden" @if(isset($ad)) checked="" @endif />
                <label for="user-contract">{{trans('main.acepto_las_normas_de_la_plataf')}}</label>
            </div>
        </div>
    </form>
    
@endsection