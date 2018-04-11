@extends('layouts.user')
 
@section('body-class', 'user-office-page user-profile-page')
 
@section('user_content')
    <div class="tab-block">
        <ul class="nav nav-tabs">
            <li class="active"><a href="#profile-tab" data-toggle="tab"><i class="fa fa-user" aria-hidden="true"></i> {{trans('main.perfil')}}</a></li>
            <li><a href="#map-tab" data-toggle="tab"><i class="fa fa-globe" aria-hidden="true"></i>  {{trans('main.ubicacion')}}</a></li>
            @if (Auth::user()->role=='worker')
                <li><a href="#time-tab" data-toggle="tab"><i class="fa fa-clock-o" aria-hidden="true"></i> {{trans('main.horario')}}</a></li>
            @else
                <li><a href="{{route('user-worker')}}"><i class="fa fa-briefcase" aria-hidden="true"></i> Worker Account</a></li>
            @endif
        </ul>
        <div class="tab-content">
            <div class="tab-pane fade in active" id="profile-tab">
                
                <form method="post" action="{{route('user-profile-update')}}" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <div class="avatar">
                        <!--<input type="file" accept="image/*" class="input-photo" id="inputPhoto" name="photo">-->
                        {{Form::file('photo', ['accept'=>"image/*", 'class'=>"input-photo", 'id'=>"inputPhoto", 'value'=>old('photo')]) }}
                        <div class="user-img" style="background-image: url({{asset(Auth::user()->photo)}})"></div>
                        <label for="inputPhoto">{{trans('main.subir_foto')}}<i class="remove"></i></label>
                        <div class="hover-block-hint">{{trans('main.upload_file_info')}}</div>
                    </div>
                    <div class="user-title">{{trans('main.datos_pernates')}}</div>
                    <label class="profile-input">
                                <span>{{trans('main.nombre')}} *</span>
                            <input type="text" value="{{ !is_null(old('name')) ? old('name') : Auth::user()->name }}" name="name" required>
                    </label>
                    <label class="profile-input">
                            <span>{{trans('main.apellidos')}} *</span>
                            <input type="text" value="{{ !is_null(old('surname')) ? old('surname') : Auth::user()->surname }}" name="surname" required>
                    </label>
                    <div class="item-filter">
                        <span>{{trans('main.sexo')}} *</span>
                        <input type="hidden" value="{{ !is_null(old('sex')) ? old('sex') : Auth::user()->sex }}" name="sex" required>
                        <div class="dropdown-list">
                            <button><span>{{!is_null(old('sex')) ? (old('sex')==1 ? trans('main.un_hombre') : trans('main.una_mujer')):""}} @if(Auth::user()->sex==1) {{trans('main.un_hombre')}} @elseif(Auth::user()->sex==2) {{trans('main.una_mujer')}} @endif </span></button>
                            <ul>
                                <li onclick="$('[name=sex]').val('1');return false;"><a href="#">{{trans('main.un_hombre')}}</a></li>
                                <li onclick="$('[name=sex]').val('2');return false;"><a href="#">{{trans('main.una_mujer')}}</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="profile-input">
                            <?php
                                $b_date = explode('.', Auth::user()->birthday);
                                $day = count($b_date)==3 ? $b_date[0] : '';
                                $month = count($b_date)==3 ? $b_date[1] : '';
                                $year = count($b_date)==3 ? $b_date[2] : '';
                            ?>
                            <span>{{trans('main.fecha_nacimiento')}} *</span>
                            <div class="birthday-inputs">                                     
                                    <div class="item-filter">
                                        <input type="hidden" value="{{!is_null(old('day')) ? old('day') : $day}}" name="day" required>
                                        <div class="dropdown-list">
                                            <button><span>@if(isset($day) && !empty($day)) {{$day}} @else {{!is_null(old('day')) ? old('day') : trans('main.day')}} @endif</span></button>
                                            <ul class="add-scroll">
                                                @for ($i = 1; $i <= 31; $i++)
                                                    <li onclick="$('[name=day]').val('{{ $i }}'); return false;"><a href="#">{{ $i }}</a></li>
                                                @endfor
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="item-filter">
                                        <input type="hidden" value="{{!is_null(old('month')) ? old('month') : $month}}" name="month" required>
                                        <div class="dropdown-list">
                                            <button><span>@if(isset($month) && !empty($month)) @php echo trans('main.month_'.($month*1)); @endphp  @else {{!is_null(old('month')) ? old('month') : trans('main.month')}} @endif</span></button>
                                            <ul class="add-scroll">
                                                @for ($i = 1; $i <= 12; $i++)
                                                <li onclick="$('[name=month]').val('{{ $i }}'); return false;"><a href="#"> @php echo trans('main.month_'.$i); @endphp </a></li>
                                                @endfor
                                                
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="item-filter">
                                        <input type="hidden" value="{{!is_null(old('year')) ? old('year') : $year}}" name="year" required>
                                        <div class="dropdown-list">
                                            <button><span>@if(isset($year) && !empty($year)) {{$year}} @else {{!is_null(old('year')) ? old('year') : trans('main.year')}} @endif</span></button>
                                            <ul class="add-scroll">
                                                @for ($i = (date('Y')-14); $i >= 1940; $i--)
                                                    <li onclick="$('[name=year]').val('{{ $i }}'); return false;"><a href="#">{{ $i }}</a></li>
                                                @endfor
                                            </ul>
                                        </div>
                                    </div>
                            </div>
                    </div>
                    <label class="profile-input">
                    <span>{{trans('main.email')}} *</span>
                    <input type="email" disabled="" value="{{Auth::user()->email}}" required>
            </label>
            <label class="profile-input">
                    <span>{{trans('main.phone')}} *</span>
                    <input class="phone" type="text" name="phone" value="{{!is_null(old('phone')) ? old('phone') : Auth::user()->phone}}" required>
            </label>
                    
            @if (Auth::user()->role=='worker')
            <div class="profile-input">
                    <span>{{trans('main.precio_hora')}}:</span>
                    <input type="text" class="perhour" name="price_per_hour" required value="{{ !is_null(old('price_per_hour')) ? old('price_per_hour') : Auth::user()->price_per_hour}}">
                    @php $services = Auth::user()->services; @endphp
                    <div class="hidden-block" @if(count($services)) style="display: block" @endif>
                        @foreach($services as $service)
                        <!-- <input type="text" name="services_update[{{ $service->id }}]" value="{{ $service->title }}" class="fixed" placeholder="Ejemplo: Estética facial y corporal - 40€{{trans('main.por_servicio_completo_escribe')}}"> -->

                        <input type="text" name="services_update[{{ $service->id }}]" value="{{ $service->title }}" class="fixed" placeholder="Ejemplo: Estética facial y corporal - 40€">
                        @endforeach
                        <input type="text" name="services[]" value="" class="fixed" placeholder="Ejemplo: Estética facial y corporal - 40€">
                        
                        <div class="add-input"><i class="fa fa-plus-square" aria-hidden="true"></i> <span>{{trans('main.dobavte_eshhe_odin_polniy_serv')}}</span></div>
                    </div>
                    <div class="checkbox">
                        <input type="checkbox" @if(count($services) || !is_null(old('contract_price'))) checked="" @endif name="contract_price" value="1" id="user-contract" class="hidden">
                        <label for="user-contract">{{trans('main.precio_de_contrato')}}</label>
                    </div>
            </div>
            <label class="profile-input">
                    <span>{{trans('main.experiencia_en_el_sector')}}:</span>
                    <input type="text" class="peryears" name="experience" value="{{!is_null(old('experience')) ? old('experience') : Auth::user()->experience}}" placeholder="5 años" required>
            </label>
            <div class="profile-input">
                    <span>{{trans('main.tomar_foto')}}:</span>
                        <div class="photos">
                        @foreach(Auth::user()->gallery as $gallery)
                            <label>
                                <input name="photo_update[{{$gallery->id}}]" type="file" accept="image/*" class="input-photo">
                                <div class="user-img" style="background-image: url({{asset($gallery->photo)}})"></div>
                            </label>
                        @endforeach
                        @if(count(Auth::user()->gallery) < 5)
                        <label>
                            <input name="photo_added[]" type="file" accept="image/*" class="input-photo">
                            <div class="user-img" style="background-image: url({{asset('img/upload.png')}})"></div>
                        </label>
                        <div class="add-photo"><i class="fa fa-plus-square" aria-hidden="true"></i>{{trans('main.dobavit_eshhe_foto')}}</div>
                        <div class="hover-block-hint">{{trans('main.upload_file_info')}}</div>
                        @endif
                    </div>
            </div>
            <div class="profile-input">
                    <span>{{trans('main.estudios_y_certificaciones')}} *</span>
                    @foreach(Auth::user()->certificates as $certificate)                   
                        <input type="text" name="setificate_update[{{ $certificate->id }}]" value="{{ $certificate->title }}" class="setificate">
                    @endforeach
                    <input type="text" name="setificate[]" class="setificate">
                    <div class="add-input"><i class="fa fa-plus-square" aria-hidden="true"></i> <span>{{trans('main.dobavit_drugoe_issledovanie_se')}}</span></div>
            </div>
            <div class="profile-input text-redactor">
                    <span>{{trans('main.soprovoditelnoe_pismo')}}:</span>
                    <textarea class="redactor" name="personal_information">{{!is_null(old('personal_information')) ? old('personal_information') : Auth::user()->personal_information}}</textarea>
            </div>

            <div class="profile-input text-redactor">
                    <span>{{trans('main.about_expirions')}}:</span>
                    <textarea class="redactor" name="about_experience">{{!is_null(old('about_experience')) ? old('about_experience') : Auth::user()->about_experience}}</textarea>
            </div>
            @endif
            
            
            <button class="save" type="submit" loading>{{trans('main.save')}}</button>
            </form>
            </div>
            <div class="tab-pane fade" id="map-tab">
                <form action="{{route('user-location-update')}}" method="post">
                        {{ csrf_field() }}
                     <div class="item-filter province-filter"@if(Auth::user()->location) style="display:block;"@endif>
                        <span>{{trans('main.poblacion')}}*</span>
                         <input type="hidden" name="provincia" class="for-search" value="@if(Auth::user()->location){{Auth::user()->location->provincia}}@else{{old('provincia')}}@endif" required>
                         <div class="dropdown-list">
                             <button><span>@if(Auth::user()->location){{!is_null(old('provincia')) ? old('provincia') : Auth::user()->location->provincia}}@else{{old('provincia')}}@endif</span></button>
                             <ul class="add-scroll">
                                 @foreach($poblacions as $states)
                                 <li onclick="alert('here');$('[state={{preg_replace('/[^A-Za-z0-9_]/', '', $states)}}]').show()"><a href="#"> {{$states}} </a></li>
                                 @endforeach                                 
                             </ul>
                         </div>
                     </div>
                     <div class="item-filter city-filter"@if(Auth::user()->location) style="display:block;"@endif>
                     <span>{{trans('main.provincia')}}*</span>
                         <input type="hidden" name="city" class="for-search" value="@if(Auth::user()->location){{!is_null(old('city')) ? old('city') : Auth::user()->location->city}}@else{{old('city')}}@endif" required>
                         <div class="dropdown-list">
                             <button><span>@if(Auth::user()->location){{Auth::user()->location->city}}@else{{old('city')}}@endif</span></button>
                             <ul class="add-scroll">
                                 @foreach($provinces as $city)
                                    <li class="profile_cites" state=""><a href="#"> {{$city}} </a></li>
                                 @endforeach
                             </ul>
                         </div>
                     </div>
                     <div class="postal-filter"@if(Auth::user()->location) style="display:block;"@endif>
                             <label class="profile-input postal-input">
                             <span>{{trans('main.codigo_postal')}}*</span>
                             <input class="for-search" name="postcode" type="text" value="@if(Auth::user()->location){{!is_null(old('postcode')) ? old('postcode') : Auth::user()->location->postcode}}@else{{old('postcode')}}@endif" required>
                            </label>
                         @if(Auth::user()->role=='worker')
                            <div class="item-filter radius-filter" @if(Auth::user()->location) style="display:inline-block;"@endif>
                             <span>{{trans('main.me_desplazo_a')}}*</span>
                                 <input type="hidden" name="radius" value="@if(Auth::user()->location){{!is_null(old('radius')) ? old('radius') : Auth::user()->location->radius}}@endif" required>
                                 <div class="dropdown-list">
                                     <button><span>@if(Auth::user()->location){{Auth::user()->location->radius/1000}} km @endif</span></button>
                                     <ul class="add-scroll">
                                         <li onclick="$('[name=radius]').val('1000')"><a href="#" data-radius="1000">1 km</a></li>
                                         <li onclick="$('[name=radius]').val('2000')"><a href="#" data-radius="2000">2 km</a></li>
                                         <li onclick="$('[name=radius]').val('3000')"><a href="#" data-radius="3000">3 km</a></li>
                                         <li onclick="$('[name=radius]').val('4000')"><a href="#" data-radius="4000">4 km</a></li>
                                         <li onclick="$('[name=radius]').val('5000')"><a href="#" data-radius="5000">5 km</a></li>
                                         <li onclick="$('[name=radius]').val('6000')"><a href="#" data-radius="6000">6 km</a></li>
                                         <li onclick="$('[name=radius]').val('7000')"><a href="#" data-radius="7000">7 km</a></li>
                                         <li onclick="$('[name=radius]').val('8000')"><a href="#" data-radius="8000">8 km</a></li>
                                         <li onclick="$('[name=radius]').val('9000')"><a href="#" data-radius="9000">9 km</a></li>
                                         <li onclick="$('[name=radius]').val('10000')"><a href="#" data-radius="10000">10 km</a></li>
                                     </ul>
                                 </div>
                            </div>
                         @else
                             <input type="hidden" name="radius" value="0" required>
                         @endif
                     </div>
                     <div class="map-description">{{trans('main.selecciona_las_localidades_a_l')}}</div>
                     <input type="hidden" id="map-search" value="@if(Auth::user()->location){{Auth::user()->location->provincia}} {{Auth::user()->location->city}} {{Auth::user()->location->postcode}}@endif">
                     <div id="map" @if(Auth::user()->location) style="display:block;"@endif></div>
                     <button class="save" type="submit" loading>{{trans('main.guard_los_cambios')}}</button>
                </form>
            </div>
            @if (Auth::user()->role=='worker')
            <div class="tab-pane fade" id="time-tab">
                <form action="{{route('user-graphics-update')}}" method="post">
                {{ csrf_field() }}
                <div class="user-title">{{trans('main.disponibilidad_horaria')}}:</div>
                
                    
                    <div class="hour-inputs">
                    <div class="item-filter from-clock">
                    <span>{{trans('main.from')}}:</span>

                        @php
                            if(isset(Auth::user()->graphics->begining_work_day)){
                                $result_end = explode(' ', Auth::user()->graphics->begining_work_day);
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

                        <input type="hidden" class="for-search" value="@if(Auth::user()->graphics) {{!is_null(old('begining_work_day')) ? old('begining_work_day') : Auth::user()->graphics->begining_work_day}} @else {{old('begining_work_day')}} @endif" name="begining_work_day" required>
                        <div class="dropdown-list">
                            <button><span>@if(Auth::user()->graphics) {{!is_null(old('begining_work_day')) ? old('begining_work_day') : Auth::user()->graphics->begining_work_day}} @else {{old('begining_work_day')}} @endif</span></button>
                            <ul class="add-scroll">
                                <li><a href="#" data-time="0" data-clock="am">12:00 am</a></li>
                                <li><a href="#" data-time="1" data-clock="am">1:00 am</a></li>
                                <li><a href="#" data-time="2" data-clock="am">2:00 am</a></li>
                                <li><a href="#" data-time="3" data-clock="am">3:00 am</a></li>
                                <li><a href="#" data-time="4" data-clock="am">4:00 am</a></li>
                                <li><a href="#" data-time="5" data-clock="am">5:00 am</a></li>
                                <li><a href="#" data-time="6" data-clock="am">6:00 am</a></li>
                                <li><a href="#" data-time="7" data-clock="am">7:00 am</a></li>
                                <li><a href="#" data-time="8" data-clock="am">8:00 am</a></li>
                                <li><a href="#" data-time="9" data-clock="am">9:00 am</a></li>
                                <li><a href="#" data-time="10" data-clock="am">10:00 am</a></li>
                                <li><a href="#" data-time="11" data-clock="am">11:00 am</a></li>
                                <li><a href="#" data-time="0" data-clock="pm">12:00 pm</a></li>
                                <li><a href="#" data-time="1" data-clock="pm">1:00 pm</a></li>
                                <li><a href="#" data-time="2" data-clock="pm">2:00 pm</a></li>
                                <li><a href="#" data-time="3" data-clock="pm">3:00 pm</a></li>
                                <li><a href="#" data-time="4" data-clock="pm">4:00 pm</a></li>
                                <li><a href="#" data-time="5" data-clock="pm">5:00 pm</a></li>
                                <li><a href="#" data-time="6" data-clock="pm">6:00 pm</a></li>
                                <li><a href="#" data-time="7" data-clock="pm">7:00 pm</a></li>
                                <li><a href="#" data-time="8" data-clock="pm">8:00 pm</a></li>
                                <li><a href="#" data-time="9" data-clock="pm">9:00 pm</a></li>
                                <li><a href="#" data-time="10" data-clock="pm">10:00 pm</a></li>
                                <li><a href="#" data-time="11" data-clock="pm">11:00 pm</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="item-filter to-clock" @if(Auth::user()->graphics) style="display: inline-block" @endif>
                    <span>{{trans('main.to')}}:</span>

                        @php

                            if(isset(Auth::user()->graphics->end_working_day)){
                                $result_end = explode(' ', Auth::user()->graphics->end_working_day);
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
                        </script>

                        <input type="hidden" class="for-search" value="@if(Auth::user()->graphics) {{!is_null(old('end_working_day')) ? old('end_working_day') : Auth::user()->graphics->end_working_day}} @else {{old('end_working_day')}} @endif" name="end_working_day" required>
                        <div class="dropdown-list">
                            <button><span>@if(Auth::user()->graphics) {{!is_null(old('end_working_day')) ? old('end_working_day') : Auth::user()->graphics->end_working_day}} @else {{old('end_working_day')}} @endif</span></button>
                            <ul class="add-scroll">
                                 <li><a href="#" data-time="0" data-clock="am">12:00 am</a></li>
                                <li><a href="#" data-time="1" data-clock="am">1:00 am</a></li>
                                <li><a href="#" data-time="2" data-clock="am">2:00 am</a></li>
                                <li><a href="#" data-time="3" data-clock="am">3:00 am</a></li>
                                <li><a href="#" data-time="4" data-clock="am">4:00 am</a></li>
                                <li><a href="#" data-time="5" data-clock="am">5:00 am</a></li>
                                <li><a href="#" data-time="6" data-clock="am">6:00 am</a></li>
                                <li><a href="#" data-time="7" data-clock="am">7:00 am</a></li>
                                <li><a href="#" data-time="8" data-clock="am">8:00 am</a></li>
                                <li><a href="#" data-time="9" data-clock="am">9:00 am</a></li>
                                <li><a href="#" data-time="10" data-clock="am">10:00 am</a></li>
                                <li><a href="#" data-time="11" data-clock="am">11:00 am</a></li>
                                <li><a href="#" data-time="0" data-clock="pm">12:00 pm</a></li>
                                <li><a href="#" data-time="1" data-clock="pm">1:00 pm</a></li>
                                <li><a href="#" data-time="2" data-clock="pm">2:00 pm</a></li>
                                <li><a href="#" data-time="3" data-clock="pm">3:00 pm</a></li>
                                <li><a href="#" data-time="4" data-clock="pm">4:00 pm</a></li>
                                <li><a href="#" data-time="5" data-clock="pm">5:00 pm</a></li>
                                <li><a href="#" data-time="6" data-clock="pm">6:00 pm</a></li>
                                <li><a href="#" data-time="7" data-clock="pm">7:00 pm</a></li>
                                <li><a href="#" data-time="8" data-clock="pm">8:00 pm</a></li>
                                <li><a href="#" data-time="9" data-clock="pm">9:00 pm</a></li>
                                <li><a href="#" data-time="10" data-clock="pm">10:00 pm</a></li>
                                <li><a href="#" data-time="11" data-clock="pm">11:00 pm</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                @php $working_days = Auth::user()->graphics ? array_flip(Auth::user()->graphics->working_days) : []; @endphp
                <div class="user-title">{{trans('main.dias_disponibles')}}:</div>
                <div class="days">
                    <div class="left-days">
                        <div class="checkbox">
                            <input type="checkbox" name="working_days[]" value="0" id="monday" class="hidden" @if(isset($working_days[0])) checked="" @endif />
                            <label for="monday">{{trans('main.monday')}}</label>
                        </div>
                        <div class="checkbox">
                            <input type="checkbox" name="working_days[]" value="1" id="tuesday" class="hidden" @if(isset($working_days[1])) checked="" @endif />
                            <label for="tuesday">{{trans('main.tuesday')}}</label>
                        </div>
                        <div class="checkbox">
                            <input type="checkbox" name="working_days[]" value="2" id="wednesday" class="hidden" @if(isset($working_days[2])) checked="" @endif>
                            <label for="wednesday">{{trans('main.wednesday')}}</label>
                        </div>
                        <div class="checkbox">
                            <input type="checkbox" name="working_days[]" value="3" id="thursday" class="hidden" @if(isset($working_days[3])) checked="" @endif>
                            <label for="thursday">{{trans('main.thursday')}}</label>
                        </div>
                    </div>
                    <div class="right-days">
                        <div class="checkbox">
                            <input type="checkbox" name="working_days[]" value="4" id="friday" class="hidden" @if(isset($working_days[4])) checked="" @endif>
                            <label for="friday">{{trans('main.friday')}}</label>
                        </div>
                        <div class="checkbox">
                            <input type="checkbox" name="working_days[]" value="5" id="saturday" class="hidden" @if(isset($working_days[5])) checked="" @endif>
                            <label for="saturday">{{trans('main.saturday')}}</label>
                        </div>
                        <div class="checkbox">
                            <input type="checkbox" name="working_days[]" value="6" id="sunday" class="hidden" @if(isset($working_days[6])) checked="" @endif>
                            <label for="sunday">{{trans('main.sunday')}}</label>
                        </div>
                    </div>
                </div>
                <div class="user-clock" @if(Auth::user()->graphics) style="display: block" @endif>
                    <canvas id="quote-diagram" height="100px" width="100px"></canvas>
                </div>
                <button class="save" type="submit" loading>{{trans('main.guardar')}}</button>
                </form>
            </div>
            @endif
        </div>
    </div>

@endsection                   
        
@push('scripts')      
<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyApmMWIVsXENuZA1DYjcDaE_-whpbPDasc&libraries=places"></script>
<script type="text/javascript">
    
      
    
        $('.postal-input input').mask('0#')

        var map;
        var marker;
        var cityCircle;
        var trueMap = true;
        var radius = parseFloat($("input[name='radius']").val());

        function setMyMap(){
            var checkInput = 1;
            var search = "";
            var mapSearch = $('#map-search');
            $("#map-tab input:not(#map-search)").each(function(){  
                if($(this).val().length == 0){
                    checkInput *= checkInput * 0;
                }else{
                    checkInput *= checkInput * 1;
                }
            });
            $("#map-tab input.for-search").each(function(){
                search += " " + $(this).val();
            });
            if(checkInput){
                $("#map").show();
                $(".map-description").hide();
                mapSearch.val(search.trim());
                            setTimeout(function(){
                                if (trueMap) {
                                initMap();
                                trueMap = false;
                                }
                                google.maps.event.trigger(document.getElementById('map-search'), 'focus', {});
                                google.maps.event.trigger(document.getElementById('map-search'), 'keydown', {
                                  keyCode: 13
                                });
                            },300)
            }
        }


        $(".radius-filter").on('click', 'a', function(){
                radius = parseFloat($(this).attr("data-radius"));
        });

        function initMap() {
      var myLatLng = {lat: 40.425133, lng: -3.704077};

      map = new google.maps.Map(document.getElementById('map'), {
        zoom: 14,
        center: myLatLng
      });

      placeMarker(myLatLng);
    }

    function placeMarker(location) {
              if (marker == null) {
                marker = new google.maps.Marker({
                    position: location,
                    map: map
                });
              } else {  
                marker.setPosition(location); 
              } 
            }

        var placs = new google.maps.places.SearchBox(document.getElementById('map-search'));
        placs.addListener('places_changed', function() {
                if (placs.getPlaces().length == 0) {
                        return;
                };
                map.setCenter(placs.getPlaces()[0].geometry.location);
                placeMarker(placs.getPlaces()[0].geometry.location);
                if(cityCircle != undefined){
                        cityCircle.setMap(null);
                }
                cityCircle = new google.maps.Circle({
                strokeColor: '#000000',
                strokeOpacity: 0.8,
                strokeWeight: 2,
                fillColor: '#000000',
                fillOpacity: 0.35,
                map: map,
                center: { 'lat': marker.getPosition(event).lat(), 'lng': marker.getPosition(event).lng()},
                radius: radius
            });
            map.fitBounds(cityCircle.getBounds());
        });
       

    $(".province-filter").on('click', 'a', function(){
            if($(this).parents(".item-filter").find("input").val().length > 0){
                $(".city-filter").show();
            }
        });

        $(".city-filter").on('click', 'a', function(){
             if($(this).parents(".item-filter").find("input").val().length > 0){
                $(".postal-filter").show();
            }
        });

        $(".postal-input").on('change', 'input', function(){
             if($(this).val().length > 0){
                $(".radius-filter").css("display", "inline-block");
            }
        });

        $("#map-tab .item-filter").on('click', 'a',function(){          
            setMyMap();
        });  

        $("a[href='#map-tab']").on("click", function(){
            setMyMap();
        })      
</script>
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
@endpush    