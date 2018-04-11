<footer>
   <div class="footer-top">
	   	<div class="container">
	   	<div class="col-xs-6 col-sm-4 col-md-3 footer-help">
	   		<h5>{{trans('main.help')}}</h5>
	   		<a href="/faq/">{{trans('main.kak_eto_rabotaet')}}</a>
	   		<a href="/contact/">{{trans('main.contactanos')}}</a>
	   	</div>
	   	<div class="col-xs-6 col-sm-4 col-md-3">
	   		<h5>{{trans('main.about_us')}}</h5>
	   		<a href="{{route('terms')}}">{{trans('main.condiciones_de_uso')}}</a>
	   		<a href="{{route('terms')}}">{{trans('main.politica_de_privacidad')}}</a>
	   	</div>
	   </div>
   </div>
   <div class="footer-bottom">© {{config('copy')}}  @if(date('Y')=='2017') 2017 @else 2017-{{date('Y')}} @endif</div>
</footer>

<div id="scroll-top"></div>

<div class="modal fade forgot-password" id="forgot-password" tabindex="-1">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
          <div class="modal-header">
            	<h5>{{trans('main.recuperaci_n_de_contrase_a')}}</h5>
              	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><i class="fa fa-times" aria-hidden="true"></i></button>
          </div>
          <div class="modal-body">
            <p>{{trans('main.despu_s_de_completar_el_pedido')}}</p>
            <form>
            	<div class="form-block">
            		<label>
	            		<span>{{trans('main.email')}}</span>
	            		<input type="email">
	            	</label>
	            	<button class="save">{{trans('main.enviar')}}</button>
            	</div>
            </form>
          </div>
      </div>
    </div>
</div>

<div class="modal fade gless" id="gless" tabindex="-1">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
          <div class="modal-header">
              <h5>{{trans('main.thank_you')}}</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><i class="fa fa-times" aria-hidden="true"></i></button>
          </div>
          <div class="modal-body">
{{--              @php $rout = Route::currentRouteName(); @endphp--}}
              {{--@if($rout=='contact')--}}
                <h5>{{trans('main.thank_you_for_your_feedback')}}</h5>
              {{--@endif--}}
          </div>
      </div>
    </div>
</div>



@stack('modal')
<script>jQuery.noConflict(true);</script>
<script type="text/javascript" src="{{ asset('js/libs.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/jquery.form.js') }}"></script>
<script type="text/javascript" src="{{ asset('packages/node_modules/tinymce/tinymce.min.js') }}"></script>
<!-- <script type="text/javascript" src="https://cloud.tinymce.com/stable/tinymce.min.js?apiKey=qc1dg3lej2r9qh3f8ikwpouamc27al0oc6hx23hehjq4uixq"></script> -->
<script type="text/javascript" src="{{ asset('js/script.js') }}"></script>
<script src="https://unpkg.com/masonry-layout@4/dist/masonry.pkgd.min.js"></script>
<script type="text/javascript" src="{{asset('js/jquery.cookie.js')}}"></script>




























