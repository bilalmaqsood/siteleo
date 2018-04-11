@extends('layouts.user')
 
@section('body-class', 'user-office-page user-class1-page')
 
@section('user_content')
<div class="user-title">Mis anuncios:</div>
@if(!count($ads))
<div class="not-favorite">
    <span>{{trans('main.nothing_has_been_added_yet2')}}</span>
</div>
@endif
@foreach($ads as $ad)
<div class="class2-item">
    <div class="class2-item-left">
        <div class="no-name">
            <div class="class1-avatar">
                <div class="class1-avatar-photo" style="background-image: url({{asset(Auth::user()->photo)}})"></div>
            </div>
            <div class="class2-item-desc">
                <p>{{$ad->name}}</p>

                <span class="date">@php
$dates=$ad->created_at;
$dates1=explode(' ',$dates);
$dates2=explode('-',$dates1[0]);
$dates3=explode(':',$dates1[1]);
$test=$dates2[2].'-'.$dates2[1].'-'.$dates2[0].' '.$dates3[0].':'.$dates3[1];
echo $test;
@endphp
</span>
                <div class="status">
                    @if($ad->payable)
                        {{trans('main.ad_status')}}:
                    <div>
                        <i class="fa fa-info-circle" aria-hidden="true"></i>
                        {{trans('main.anuncio_destacado')}}
                        <span class="status-date">
                            ({{date('m.d.Y', $ad->last_pay)}})
                        </span>
                    </div>
                    @endif
                    <a href="{{route('user-new-advertisement-up', ['id' => $ad->id])}}" class="to-top">{{trans('main.up_ad')}}</a>
                </div>
            </div>
        </div>
        <div class="buttons">
            @if($ad->activ)
            <div class="public-status public">{{trans('main.published')}}</div>
            @else
            <div class="public-status wait">{{trans('main.waiting')}}</div>
            @endif
            <a href="{{route('advertise', ['uri' => $ad->uri])}}" target="_blanck"><i class="fa fa-eye" aria-hidden="true"></i>{{trans('main.view_add')}}</a>
            <a href="#" onclick="$('#delete_id').val({{$ad->id}});" data-toggle="modal" data-target="#delete-class2"><i class="fa fa-trash-o" aria-hidden="true"></i>{{trans('main.delete')}}</a>
            <a href="{{route('user-new-advertisement-edit', ['id' => $ad->id])}}"><i class="fa fa-pencil-square-o" aria-hidden="true"></i>{{trans('main.edit')}}</a>
        </div>
    </div>
    <div class="class2-item-right">
        <div><i class="fa fa-comment" aria-hidden="true"></i> {{trans('main.reviews')}}: {{count(\App\Models\AdsComments::where('ads_id', $ad->id)->where('parent_id', '!=', 0)->get())}}</div>
        <div><i class="fa fa-tags" aria-hidden="true"></i> {{trans('main.orders')}}: {{count(\App\Models\AdsComments::where('ads_id', $ad->id)->where('parent_id', 0)->get())}}</div>
        <div><i class="fa fa-eye" aria-hidden="true"></i> {{trans('main.view')}}: {{ $ad->views }}</div>
        <div><i class="fa fa-heart" aria-hidden="true"></i> {{trans('main.favourite')}}: {{ $ad->like }}</div>
      <!--  <div><i class="fa fa-money" aria-hidden="true"></i> {{trans('main.paid_for')}}: {{ $ad->payable }}</div>-->
    </div>
</div>
@endforeach
@endsection
@push('modal')
<div class="modal fade delete-class2" id="delete-class2" tabindex="-1">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
          <div class="modal-header">
              <h5>{{trans('main.delete_advertise')}}</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><i class="fa fa-times" aria-hidden="true"></i></button>
          </div>
          <div class="modal-body">
            <h5>{{trans('main.are_you_sure_you_want_to_delet')}}</h5>
            <div class="delete-button">
                <form method="post" action="{{route('user-advertisement-destroy')}}">
                    {{ csrf_field() }}
                    <input type="hidden" name="delete_id" value="" id="delete_id" />
                    <button class="yes" type="submit">{{trans('main.are_you_sure_you_want_to_delet1')}}</button>
                    <button class="no" data-dismiss="modal" aria-label="Close" onclick="$('#delete_id').val('');">{{trans('main.are_you_sure_you_want_to_delet2')}}</button>
                </form>
            </div>
          </div>
      </div>
    </div>
</div>
@endpush
