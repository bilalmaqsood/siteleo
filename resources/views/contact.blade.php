@extends('layouts.app')
 
@section('body-class', 'contact-page')
 
@section('content') 
        <div class="container">
	        	@include('blocks.breadcrumbs')
                <div class="block-wrap">
                    <div class="contact-left">
                        <div class="contact-title">{{trans('main.contacta_con_nosotros')}}</div>
                        <div class="contact-subtitle"><span>{{trans('main.para_poder_garantizar_una_aten')}}</span></div>
                        <form method="post" action="{{route('contact-send')}}" class="ajaxForm">
                            {{csrf_field()}}
                            <label>
                                <span>{{trans('main.name')}}</span>
                                <input type="text" name="name" value="{{old('name')}}" required>
                            </label>
                            <label>
                                <span>{{trans('main.email')}}</span>
                                <input type="email" name="email" value="{{old('email')}}" required>
                            </label>
                            <label>
                                <span>{{trans('main.phone')}}</span>
                                <input class="phone" type="text" name="phone" value="{{old('phone')}}" required>
                            </label>
                            <label>
                                <span>{{trans('main.comments')}}</span>
                                <textarea name="comment" required>{{old('comment')}}</textarea>
                            </label>
                            <button type="submit">{{trans('main.enviar_consulta')}} <i class="fa fa-angle-right" aria-hidden="true"></i></button>
                        </form>
                    </div>
                    <div class="contact-right">
                        <div class="contact-title">{{trans('main.consultas_m_s_frecuentes')}}</div>
                        {!! \App\Models\Conditions::find(1)->consultas_frecuentes !!}
                        <a href="/conditions/">{{trans('main.ir_a_la_secci_n_de_ayuda_y_con')}}</a>
                    </div>
                </div>
	        </div>
@endsection

@push('scripts')
    <script>
        $('.ajaxForm').submit(function() {
            var form = $(this);
            var modal = form.data('modal');
            var event_name = form.attr('id');
            var action = form.attr('action');
            if(modal!=undefined) modal = $(this).closest("#"+modal);

            $(this).ajaxSubmit({
                beforeSubmit: function(arr, $form, options) {
                    form.find('[type="submit"]').prop('disabled', true);
                },
                success: function(responseText, statusText, xhr, $form){
                    form.find('[type="submit"]').prop('disabled', false);
                    if (responseText != 'error') {

                        if(modal!=undefined){
                            $(modal).modal('hide');
                        }

                        var redirect = form.data('redirect');
                        if(redirect != undefined){
                            location.assign(redirect);
                        }else {
                            $('#gless').modal('show');
                            form[0].reset();
                        }
                    }
                    else {
                        console.log("#"+event_name+": Error");
                    }
                }
            });
            return false;
        });
    </script>
@endpush