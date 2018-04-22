@extends('layouts.app')

@section('body-class', 'home-page')

@section('content')
<div id="cloud">
		@foreach(\App\Models\BannerWords::all() as $item)
        	<span href="/search/" class="word-{{$loop->iteration}}" @if($item->depth) data-depth="0.{{$item->depth}}" @endif>{!! $item->word !!}</span>
		@endforeach
        	<div class="search-block">
        		<h2>El portal dedicado a los profesionales</h2>
        		<h3>que se desplazan a domicilio para ofrecer sus servicios</h3>
        		<form method="get" action="{{route('search-q')}}">
        			<div class="search-form">
        				<div class="item-filter suggestContainer" style="position:relative;">
        					<input type="text" placeholder="ejemplo: abogado" name="q" id="q" autocomplete="off">
							<div class="add-scroll" style="position:absolute; display: none;"></div>

							{{--<div class="smart-search" style="display: none;">--}}
        						{{--<ul class="list-search">--}}

        						{{--</ul>--}}
        					{{--</div>--}}
							@push('scripts')
							<script>
                                $(function(){$(".list-search").mCustomScrollbar();});
                                $("#q").on('keyup', function(){
                                    var qObj = $(this);
                                    var qText = qObj.val();
                                    var lObj = qObj.parent().find('div');
//									    console.log(lObj);
                                    var uObj = lObj.find('#mCSB_1_container');
                                    if(qText.length){
                                        $.get('{{route('search-s')}}', {val:qText, city:$('#search_city').val()}, function(data){
                                            uObj.html('');


                                            $.each(data,function(index,currentValue) {
                                                var regex = new RegExp(qText, 'gi')
                                                response = currentValue.text.replace(regex, function(str) {
                                                    return "<b>" + str + "</b>"
                                                })

                                                uObj.append('<li><a href="'+currentValue.url+'">'+response+'</a></li>');
                                            });



//                                            data.forEach(function callback(currentValue, index, array) {
//                                                var regex = new RegExp(qText, 'gi')
//                                                 response = currentValue.text.replace(regex, function(str) {
//                                                    return "<b>" + str + "</b>"
//                                                })
//
//                                                uObj.append('<li><a href="'+currentValue.url+'">'+response+'</a></li>');
//                                            });

                                            lObj.slideDown();
                                        }).fail(function(){
                                            lObj.slideUp();
                                            uObj.html('');
                                        });
                                    }else{
                                        lObj.slideUp();
                                        uObj.html('');
                                    }
                                });
							</script>
								<script>
									$(function(){$(".list-search").mCustomScrollbar();});
									$("#search_city").on('keyup', function(){
									    var qObj = $(this);
									    var qText = qObj.val();
									    var lObj = qObj.parent().find('div');
//									    console.log(lObj);
									    var uObj = lObj.find('#mCSB_2_container');
									    if(qText.length){
									        $.get('{{route('search-c')}}',
												{val: $("#q").val(), city:$('#search_city').val()},
												function(data){

                                                uObj.html('');
                                                    $.each(data,function(index,val) {
                                                        var regex = new RegExp($('#search_city').val(), 'gi');
                                                        response = val.text.replace(regex, function(str) {
                                                            return "<b>" + str + "</b>"
                                                        })
                                                        uObj.append('<li><a href="'+val.url+'">'+response+'</a></li>');
                                                    });

//                                                data.forEach(function callback(currentValue, index, array) {
//                                                    uObj.append('<li><a href="'+currentValue.url+'">'+currentValue.text+'</a></li>');
//                                                });
                                                lObj.slideDown();
											}).fail(function(){
                                                lObj.slideUp();
                                                uObj.html('');
											});
										}else{
                                            lObj.slideUp();
                                            uObj.html('');
										}
									});
								</script>
							@endpush
        				</div>
        				<div class="item-filter suggestContainer" style="position: relative">
                            <input type="text" value="" id="search_city" name="city" placeholder="ejemplo: Madrid">
                           {{--<div class="dropdown-list">--}}
                                {{--<ul class="">--}}
									<div class="add-scroll" style="position:absolute; display: none;"></div>
                                {{--</ul>--}}
                            {{--</div>--}}
                        </div>
                        <button class="send">{{trans('main.buscar')}}</button>
        			</div>
        		</form>
        	</div>
        </div>
        <div id="contact-blok">
        	<div class="container">
        		<div class="row">
	        		<div class="col-sm-4" style='display:none'>
	        			<div class="mail-contact">
	        				<img src="{{asset('img/mail.png')}}" alt="mail">
	        				<a href="mailto:">{{config('admin_email')}}</a>
	        			</div>
	        		</div>
	        		<div class="col-sm-4" style='display:none'> 
	        			<div class="phone-contact">
	        				<img src="{{asset('img/phone.png')}}" alt="phone">
	        				<span>{{trans('main.contact_support')}}</span>
	        				<a href="tel:{{str_replace(['-',' ','(',')'],'',config('support_number'))}}">{{config('support_number')}}</a>
	        			</div>
	        		</div>
	        		<div class="col-sm-4">
	        			<div class="social-contact">
	        				<i class="pe-7s-like2"></i>
	        				<span>{{trans('main.we_are')}}:</span>
	        				<a target="_blank" href="{{config('facebook_link')}}"><i class="fa fa-facebook" aria-hidden="true"></i></a>
	        				<a target="_blank" href="{{config('twitter_link')}}"><i class="fa fa-twitter" aria-hidden="true"></i></a>
	        				<a target="_blank" href="{{config('google_plus_link')}}"><i class="fa fa-google-plus" aria-hidden="true"></i></a>
	        				<a target="_blank" href="{{config('instagram_link')}}"><i class="fa fa-instagram" aria-hidden="true"></i></a>
	        			</div>
	        		</div>
	        	</div>
        	</div>
        </div>
        <div id="categories-block">
        	<div class="container">
        		<div class="row grid">
					@foreach(\App\Models\Category::where('parent_id', 0)->where('home', 1)->orderBy('order')->limit(6)->get() as $item)
						<div class="col-sm-4 grid-item">
							<div class="category-item">
								<div class="img-block">
									@if(!empty($item->icon))<img src="{{asset($item->icon)}}" alt="{{$item->title}}">@endif
								</div>
								<div class="description-block">
									<h6>{{$item->title}}</h6>
									<div class="category-list">
										@foreach(\App\Models\Category::where('parent_id', $item->id)->orderBy('order')->get() as $item)
											<p>

												@if($count = (isset($category_count[$item->id]) ? $category_count[$item->id] : 0))
													<a href="{{route('search', ['uri_category' => $item->uri])}}">{{$item->title}} ({{$count}})</a>
												@else
													{{$item->title}}
												@endif

											</p>
										@endforeach
									</div>
									<div class="show-more">{{trans('main.show_more')}} <i class="fa fa-angle-right" aria-hidden="true"></i></div>
								</div>
							</div>
						</div>
                    @endforeach
        		</div>
        		<a href="/categories/" class="more-button">{{trans('main.all_categories')}} <i class="fa fa-angle-right" aria-hidden="true"></i></a>
        	</div>
        </div>
        <div id="map">
        	<div class="container">
        		<div class="row">
        			<div class="col-sm-4 col-md-offset-1">
        				<h6>{{trans('main.search_map')}}</h6>
        				<h5>{{trans('main.find_professionals_near_you')}}</h5>
        				<div class="map-item-block">
							@foreach($poblacions as $states)
								<div class="map-item" state="{{preg_replace('/[^A-Za-z0-9_]/', '', $states)}}">
									@if(isset($provinces[$states]) && $provinces[$states])
										<a href="{{route('search-q', ['provincia' => $states])}}"> @endif
											{{$states}}
										@if(isset($provinces[$states]) && $provinces[$states])
												({{$provinces[$states]}})
										@endif
									@if(isset($provinces[$states]) && $provinces[$states])</a> @endif
								</div>
							@endforeach
        				</div>
        			</div>
        			<div class="col-sm-8 col-md-7">
        				<div class="poligon-block">
					        <img src="img/map.png" usemap="#spain-poligon" class="map-poligon">
					        <map id="spain-poligon" name="spain-poligon">
					        	<area href="madrid" title="Madrid" alt="Madrid" shape="poly" data-provincia="madrid" coords="146,133,150,131,153,128,156,129,160,127,157,122,154,118,152,113,150,108,150,103,150,98,149,94,145,95,144,98,141,102,138,104,135,107,134,111,132,115,129,118,126,121,132,122,141,123,144,125,147,127,147,130,146,133">
								<area href="barcelona" title="Barcelona" alt="Barcelona" shape="poly" data-provincia="barcelona" coords="309,75,304,79,301,82,296,87,293,88,284,90,283,88,283,85,282,82,280,80,279,78,278,75,278,72,280,72,284,68,284,66,284,62,284,58,285,56,287,54,291,54,291,57,293,59,297,59,300,61,302,62,302,63,301,65,299,68,299,69,301,72,304,74,305,74,309,75">
								<area href="valencia" title="Valencia" alt="Valencia" shape="poly" data-provincia="valencia" coords="235,167,232,160,231,156,230,151,231,148,232,144,234,141,232,140,228,141,223,141,221,139,218,139,214,136,212,135,210,140,209,142,206,145,204,146,203,148,204,151,209,152,213,154,212,157,211,158,210,156,210,161,211,164,213,166,216,166,216,167,217,169,217,172,221,172,224,172,225,174,226,170,228,168,232,167,235,167">
								<area href="sevilla" title="Sevilla" alt="Sevilla" shape="poly" data-provincia="sevilla" coords="77,233,86,234,92,230,108,230,112,226,112,223,117,222,118,221,109,211,101,212,101,207,95,198,90,196,84,201,84,206,78,206,75,209,79,212,78,218,77,233">
								<area href="alicante" title="Alicante" alt="Alicante" shape="poly" data-provincia="alicante" coords="220,202,221,198,222,194,223,191,225,191,227,186,227,182,231,182,234,180,238,178,242,174,243,174,242,171,238,171,230,170,228,174,227,176,226,177,221,176,218,175,218,178,216,179,215,184,215,186,216,189,215,192,214,195,217,199,220,202">
								<area href="tenerife" title="Tenerife" alt="Tenerife" shape="poly" data-provincia="tenerife" coords="268,270,271,269,275,266,278,265,282,264,288,264,289,267,287,271,284,275,281,277,279,281,277,283,276,285,272,286,268,286,266,282,264,279,260,272,262,268,268,270">
								<area href="las-palmas" title="La Palma" alt="La Palma" shape="poly" data-provincia="la-palma" coords="233,255,237,255,245,256,245,262,243,270,240,271,235,270,233,264,232,259,233,255">
								<area href="tarragona" title="Tarragona" alt="Tarragona" shape="poly" data-provincia="tarragona" coords="274,80,276,82,278,85,279,86,280,88,280,91,273,94,270,95,267,96,261,99,260,102,259,106,261,107,260,108,254,111,253,113,252,111,248,108,247,105,248,103,249,100,249,97,248,95,246,95,249,91,251,87,257,87,263,87,267,87,269,85,272,82,274,80">
								<area href="girona" title="Girona" alt="Girona" shape="poly" data-provincia="gerona" coords="316,47,319,50,323,50,321,53,321,58,322,62,322,66,312,75,311,72,307,72,305,69,302,69,307,66,307,61,304,60,303,57,297,57,294,57,294,54,294,52,290,51,288,49,287,47,292,49,296,50,300,51,305,52,312,52,313,50,316,47">
								<area href="lleida" title="Lleida" alt="Lleida" shape="poly" data-provincia="lerida" coords="251,86,256,85,260,85,264,83,266,81,269,79,271,77,272,76,275,76,276,75,275,71,275,68,276,68,278,68,280,68,280,63,280,62,282,59,282,56,282,54,282,51,284,51,283,49,279,49,277,49,276,47,275,44,274,42,274,41,272,40,269,40,265,38,262,37,259,35,259,39,261,44,262,47,260,53,259,59,258,65,257,70,254,72,251,74,252,78,252,80,251,83,251,86">
								<area href="huesca" title="Huesca" alt="Huesca" shape="poly" data-provincia="huesca" coords="255,41,257,44,258,47,257,56,256,62,254,66,250,70,247,73,247,77,250,78,248,80,246,82,244,82,240,83,239,81,238,78,237,76,235,74,233,74,230,72,230,70,228,68,227,66,225,65,222,64,222,62,222,60,222,57,222,54,222,52,221,48,220,46,219,42,219,38,219,36,221,33,223,36,226,38,228,37,233,36,237,39,240,42,244,42,249,41,253,40,255,41">
								<area href="zaragoza" title="Zaragoza" alt="Zaragoza" shape="poly" data-provincia="zaragoza" coords="216,39,216,42,216,46,216,50,218,52,219,54,220,56,219,59,218,61,216,63,219,66,221,66,224,68,224,69,225,73,228,75,232,78,233,80,235,80,237,83,238,86,242,88,246,87,247,86,247,89,245,92,242,94,235,88,228,85,225,86,222,89,220,91,218,92,215,92,209,91,207,93,204,95,200,96,197,96,192,94,192,90,188,91,186,91,185,88,187,86,191,86,192,83,191,78,194,75,195,74,195,70,194,69,198,66,203,66,207,66,208,65,209,62,208,60,206,58,206,54,207,50,209,47,209,45,213,41,216,39">
								<area href="cuenca" title="Cuenca" alt="Cuenca" shape="poly" data-provincia="cuenca" coords="191,154,194,152,198,150,199,149,200,146,202,143,205,141,206,141,208,134,209,131,205,128,200,127,199,126,198,123,195,122,193,120,191,118,190,116,189,114,187,112,184,112,181,112,179,113,177,115,177,117,175,118,171,118,170,119,169,122,167,125,166,127,162,128,161,130,160,132,161,136,163,137,165,141,166,144,167,147,167,150,167,152,168,153,175,153,179,154,181,155,183,156,187,153,189,154,191,154">
								<area href="guadalajara" title="Guadalajara" alt="Guadalajara" shape="poly" data-provincia="guadalajara" coords="165,124,165,121,166,118,166,115,169,115,171,115,174,113,179,109,183,108,188,108,190,110,192,112,192,114,193,116,195,113,196,111,199,111,199,105,196,100,193,97,191,96,188,95,183,96,177,97,174,96,174,93,170,91,165,90,160,90,155,90,153,90,151,93,154,97,154,101,152,105,153,109,156,113,158,115,159,117,160,121,161,124,165,124">
								<area href="teruel" title="Teruel" alt="Teruel" shape="poly" data-provincia="teruel" coords="238,105,243,105,243,105,244,103,246,99,243,97,228,89,225,92,223,95,219,97,215,95,210,95,205,98,201,100,200,102,202,106,201,110,200,114,197,116,198,117,200,122,204,124,208,126,213,129,212,130,212,132,217,132,217,134,219,131,223,128,227,123,230,120,230,111,230,108,233,103,236,104,238,105">
								<area href="castellon" title="Castellón" alt="Castellón" shape="poly" data-provincia="castellon" coords="236,138,238,134,251,114,249,112,246,109,243,109,238,109,236,108,233,110,234,116,234,119,233,122,230,124,228,126,225,129,225,132,221,133,219,135,224,135,226,138,231,136,233,137,236,138">
								<area href="albacete" title="Albacete" alt="Albacete" shape="poly" data-provincia="albacete" coords="170,156,169,160,169,162,169,167,170,170,172,172,173,175,173,178,170,180,175,181,176,183,178,185,178,188,179,191,177,195,179,197,179,195,184,190,185,189,188,185,188,186,190,186,193,184,197,184,199,184,199,180,201,176,203,173,207,173,210,172,213,172,215,175,213,169,210,168,206,165,206,162,206,157,206,155,200,153,196,155,194,157,191,157,188,156,185,158,184,160,181,159,177,158,175,157,170,156">
								<area href="murcia" title="Murcia" alt="Murcia" shape="poly" data-provincia="murcia" coords="182,198,183,196,186,192,188,191,190,191,192,190,196,188,202,188,203,186,203,182,205,177,208,176,209,176,212,177,213,179,211,183,211,187,213,190,211,195,211,200,214,202,218,204,218,206,215,208,219,210,218,212,207,211,201,213,199,215,198,218,195,215,191,210,189,202,186,199,182,198">
								<area href="almeria" title="Almería" alt="Almería" shape="poly" data-provincia="almeria" coords="164,238,167,240,170,240,174,235,177,233,182,234,183,237,186,236,186,233,189,231,191,226,195,222,196,219,193,218,191,214,188,211,187,207,186,204,186,203,183,203,182,208,181,213,180,216,177,217,174,220,173,225,170,225,167,224,165,232,164,238">
								<area href="navarra" title="Navarra" alt="Navarra" shape="poly" data-provincia="navarra" coords="204,64,204,62,203,59,203,52,203,48,205,45,207,42,208,41,210,38,213,37,215,36,217,35,218,32,214,31,208,31,207,29,203,29,199,27,199,26,200,24,200,22,200,20,197,19,195,20,193,23,191,23,190,26,189,28,187,30,186,33,185,36,183,38,182,40,180,42,180,45,182,46,186,48,191,49,193,51,194,53,198,54,198,57,197,58,193,59,194,62,197,63,201,63,204,64">
								<area href="guipuzcoa" title="Gipúzcoa" alt="Gipúzcoa" shape="poly" data-provincia="gipuzcoa" coords="175,27,177,24,179,22,178,19,182,19,187,19,191,17,192,16,193,17,189,20,187,24,185,27,184,30,181,29,177,28,175,27">
								<area href="alava" title="Álava" alt="Álava" shape="poly" data-provincia="alava" coords="175,45,175,42,175,40,178,39,181,37,181,33,178,31,174,33,171,31,171,28,166,28,164,27,162,24,160,25,163,28,164,32,160,32,159,33,164,37,167,41,171,44,175,45">
								<area href="vizcaya" title="Vizcaya" alt="Vizcaya" shape="poly" data-provincia="vizcaya" coords="165,17,166,14,169,16,171,18,173,16,175,17,175,20,174,22,173,25,171,26,168,25,167,25,164,22,163,21,160,21,156,21,154,22,157,20,158,19,163,17,165,17">
								<area href="la-rioja" title="La Rioja" alt="La Rioja" shape="poly" data-provincia="la-rioja" coords="165,60,169,56,171,56,171,59,173,61,176,60,177,57,180,57,184,57,185,57,186,60,187,63,188,65,190,65,189,60,190,57,192,56,189,53,181,49,174,49,172,48,169,47,167,44,163,43,162,44,162,48,162,55,165,60">
								<area href="soria" title="Soria" alt="Soria" shape="poly" data-provincia="soria" coords="152,77,155,75,156,71,160,70,165,66,167,63,173,65,177,65,179,61,181,61,183,61,184,63,185,66,186,67,189,68,190,68,191,71,192,73,188,77,187,80,189,82,185,82,183,85,180,89,182,92,182,93,178,94,173,90,167,86,160,87,157,84,156,81,152,77">
								<area href="segovia" title="Segovia" alt="Segovia" shape="poly" data-provincia="segovia" coords="128,108,132,106,135,102,137,99,139,94,143,93,147,91,149,91,151,88,155,86,152,83,148,82,144,81,142,83,139,84,138,82,137,82,136,84,130,83,126,83,125,85,124,88,123,92,122,92,121,95,123,101,128,108">
								<area href="burgos" title="Burgos" alt="Burgos" shape="poly" data-provincia="burgos" coords="143,36,143,32,143,30,140,30,143,27,146,25,147,25,154,24,157,24,157,27,158,28,161,29,154,30,155,32,156,36,159,39,161,39,160,43,159,47,159,51,159,56,160,59,161,62,163,63,160,65,159,67,159,67,156,66,152,65,152,69,152,72,151,75,148,75,144,78,141,78,138,73,138,69,141,66,141,63,139,61,137,60,135,56,132,56,132,51,131,48,131,45,132,39,137,38,143,36">
								<area href="palencia" title="Palencia" alt="Palencia" shape="poly" data-provincia="palencia" coords="112,54,113,49,116,45,116,42,116,37,116,34,119,31,123,30,127,31,129,33,130,35,131,37,129,41,128,48,128,53,129,57,132,60,134,61,138,62,138,64,135,65,134,68,133,69,130,70,125,69,121,68,118,65,117,67,116,64,112,64,113,59,112,54">
								<area href="valladolid" title="Valladolid" alt="Valladolid" shape="poly" data-provincia="valladolid" coords="105,89,108,89,112,90,117,90,119,91,120,87,122,84,122,80,124,79,129,80,133,79,135,78,135,75,134,73,127,73,124,73,121,73,119,70,119,69,118,70,116,70,114,68,113,68,111,67,110,67,110,64,110,61,110,59,108,55,106,55,104,58,106,63,107,68,105,73,105,76,108,79,107,83,105,86,105,89">
								<area href="avila" title="Ávila" alt="Ávila" shape="poly" data-provincia="avila" coords="110,93,110,99,110,103,108,106,104,111,103,116,100,118,97,118,93,120,98,123,101,122,104,122,104,126,106,127,112,124,114,121,116,121,117,123,121,123,124,118,126,115,130,112,126,111,123,106,121,100,119,96,116,94,110,93">
								<area href="toledo" title="Toledo" alt="Toledo" shape="poly" data-provincia="toledo" coords="103,129,103,136,104,137,106,137,108,138,108,144,110,148,113,150,116,150,120,148,122,144,124,146,127,147,129,147,132,146,134,145,136,150,136,153,137,154,142,154,147,152,152,149,156,148,160,149,164,146,162,142,159,137,157,133,154,132,149,135,142,137,138,137,137,134,140,132,143,130,138,128,127,125,124,125,120,127,117,125,112,128,110,130,108,131,103,129">
								<area href="ciudad-real" title="Ciudad Real" alt="Ciudad Real" shape="poly" data-provincia="ciudad_real" coords="122,149,120,153,119,160,115,163,113,174,129,185,131,182,138,183,149,181,158,181,163,180,168,178,169,176,168,171,165,166,165,162,167,156,163,155,163,150,156,152,150,155,144,157,136,158,133,155,132,153,131,150,126,151,122,149">
								<area href="jaen" title="Jaén" alt="Jaén" shape="poly" data-provincia="jaen" coords="136,216,140,216,148,210,153,210,159,207,162,209,174,192,174,189,169,184,164,184,149,186,132,187,133,195,131,200,131,205,135,212,136,216">
								<area href="cordoba" title="Córdoba" alt="Córdoba" shape="poly" data-provincia="cordoba" coords="99,193,97,185,107,179,111,177,118,182,122,185,127,188,130,189,130,193,128,201,128,207,131,210,133,215,131,219,129,222,121,221,117,215,111,207,104,209,98,195,99,193">
								<area href="granada" title="Granada" alt="Granada" shape="poly" data-provincia="granada" coords="145,239,151,239,156,238,161,238,166,221,168,219,171,222,173,215,178,211,178,205,181,202,175,198,167,209,163,213,159,212,156,214,151,213,146,216,144,219,135,220,133,224,131,226,135,230,141,232,145,234,145,239">
								<area href="malaga" title="Málaga" alt="Málaga" shape="poly" data-provincia="malaga" coords="110,236,111,231,115,227,121,224,126,224,130,231,141,239,127,240,121,246,110,247,105,252,100,246,104,241,105,238,110,236">
								<area href="cadiz" title="Cádiz" alt="Cádiz" shape="poly" data-provincia="cadiz" coords="96,261,80,251,81,246,78,241,78,236,86,238,94,234,101,234,103,233,107,232,108,237,104,235,102,240,100,244,95,247,98,249,102,253,96,261">
								<area href="huelva" title="Huelva" alt="Huelva" shape="poly" data-provincia="huelva" coords="61,194,60,198,55,201,53,208,48,213,49,224,63,224,75,235,76,213,67,211,75,203,81,203,81,201,75,198,69,198,61,194">
								<area href="badajoz" title="Badajoz" alt="Badajoz" shape="poly" data-provincia="badajoz" coords="60,190,70,194,75,195,79,197,88,192,93,192,95,184,108,174,111,169,110,163,116,153,105,156,82,165,76,161,66,159,59,154,55,156,55,175,52,184,60,190">
								<area href="caceres" title="Cáceres" alt="Cáceres" shape="poly" data-provincia="caceres" coords="53,155,51,149,47,143,59,143,64,132,60,128,62,124,67,124,79,120,83,122,90,123,96,126,100,126,100,139,104,140,107,151,108,153,100,155,100,159,82,164,77,157,66,156,61,151,53,155">
								<area href="salamanca" title="Salamanca" alt="Salamanca" shape="poly" data-provincia="salamanca" coords="64,97,67,102,64,121,80,115,84,118,90,120,95,115,101,114,107,100,107,93,102,93,91,90,86,91,75,87,64,97">
								<area href="zamora" title="Zamora" alt="Zamora" shape="poly" data-provincia="zamora" coords="60,64,64,57,88,61,97,62,102,65,102,71,102,78,101,90,85,88,77,84,80,77,72,74,71,65,60,64">
								<area href="cantabria" title="Cantabria" alt="Cantabria" shape="poly" data-provincia="cantabria" coords="131,31,133,36,140,35,141,32,137,30,143,23,146,22,151,22,158,16,149,12,141,12,124,15,117,22,119,27,131,31">
								<area href="leon" title="León" alt="León" shape="poly" data-provincia="leon" coords="67,54,97,60,102,61,101,55,107,51,110,51,113,36,114,31,118,28,114,22,103,26,98,30,93,28,90,30,85,28,76,29,73,32,65,34,59,43,64,46,67,52,67,54">
								<area href="asturias" title="Asturias" alt="Asturias" shape="poly" data-provincia="asturias" coords="66,31,72,30,75,26,85,26,91,26,104,25,114,20,123,15,105,10,90,7,83,9,61,8,57,14,66,31">
								<area href="orense" title="Orense" alt="Orense" shape="poly" data-provincia="orense" coords="53,52,38,46,32,45,28,48,29,55,28,59,32,69,43,66,51,67,58,64,58,58,64,53,58,47,64,48,64,52,58,48,53,52">
								<area href="lugo" title="Lugo" alt="Lugo" shape="poly" data-provincia="lugo" coords="42,3,58,8,55,14,59,22,63,31,58,39,54,51,44,47,38,44,38,37,37,34,36,21,42,3">
								<area href="pontevedra" title="Pontevedra" alt="Pontevedra" shape="poly" data-provincia="pontevedra" coords="33,34,37,38,36,44,30,44,25,47,25,53,25,59,12,64,16,51,14,47,15,40,33,34">
								<area href="a-coruna" title="A Coruña" alt="A Coruña" shape="poly" data-provincia="corunia" coords="7,42,13,33,7,34,3,28,11,18,23,15,30,19,30,11,24,10,33,3,36,6,40,2,36,17,34,31,34,33,7,42">
								<area href="buscar" title="Lanzarote" alt="Lanzarote" shape="poly" data-provincia="lanzarote" coords="353,244,357,243,360,244,361,247,361,253,357,257,352,258,349,259,344,258,343,254,344,249,353,244">
								<area href="buscar" title="Gran Canaria" alt="Gran Canaria" shape="poly" data-provincia="gran-canaria" coords="298,278,304,278,309,281,308,288,307,294,300,296,294,295,291,290,289,284,294,279,298,278">
								<area href="buscar" title="Menorca" alt="Menorca" shape="poly" data-provincia="menorca" coords="344,123,353,122,357,123,359,125,361,128,362,134,358,137,355,136,351,135,347,133,345,133,342,130,342,127,344,123">
								<area href="buscar" title="Mallorca" alt="Mallorca" shape="poly" data-provincia="mallorca" coords="319,128,322,127,326,129,327,132,330,132,333,135,335,138,335,143,332,152,328,156,324,158,315,158,308,156,307,152,307,150,303,150,299,150,295,147,296,141,306,131,319,128">
								<area href="buscar" title="Ibiza" alt="Ibiza" shape="poly" data-provincia="ibiza" coords="268,162,272,161,276,162,277,164,277,166,275,169,274,171,273,172,272,174,270,175,267,176,263,175,261,174,260,173,260,170,261,166,268,162">
								<area href="buscar" title="Formentera" alt="Formentera" shape="poly" data-provincia="formentera" coords="266,178,269,178,271,178,274,180,278,184,274,186,271,185,267,184,266,182,265,179,266,178">
					        </map>
					    </div>
        			</div>
        		</div>
        	</div>
        </div>

            <div id="cities-block">
			<div class="container">
			<div class="title">{{trans('main.ciudades_ms_buscadas')}}</div>
			<div class="subtitle">{{trans('main.estas_son_las_localidades_dond')}}</div>
				<div class="row">
					<div class="col-xs-6 col-sm-3">
						<a href="{{route('search-q', ['city' => 'Madrid'])}}">
							<div class="city-img">
								<img src="{{asset('img/city-1.jpg')}}" alt="Madrid">
							</div>
							<span>{{trans('main.madrid')}}</span>
						</a>
					</div>
					<div class="col-xs-6 col-sm-3">
						<a href="{{route('search-q', ['city' => 'Barcelona'])}}">
							<div class="city-img">
								<img src="{{asset('img/city-2.jpg')}}" alt="Barcelona">
							</div>
							<span>{{trans('main.barcelona')}}</span>
						</a>
					</div>
					<div class="col-xs-6 col-sm-3">
						<a href="{{route('search-q', ['city' => 'Valencia'])}}">
							<div class="city-img">
								<img src="{{asset('img/city-3.jpg')}}" alt="Valencia">
							</div>
							<span>{{trans('main.valencia')}}</span>
						</a>
					</div>
					<div class="col-xs-6 col-sm-3">
						<a href="{{route('search-q', ['city' => 'Sevilla'])}}">
							<div class="city-img">
								<img src="{{asset('img/city-4.jpg')}}" alt="Sevilla">
							</div>
							<span>{{trans('main.sevilla')}}</span>
						</a>
					</div>
				</div>
			</div>
		</div>

		<div id="step-block">
			<div class="step-instruction">
				<div class="container">
					<div class="col-sm-7 col-sm-offset-5 col-md-5 col-md-offset-7">
						<ul>
							@foreach(\App\Models\HomeInfo::all() as $item)
							<li class="fadeInUp animated">
								<h5>{{$item->title}}</h5>
								<p>{!! $item->description !!}</p>
							</li>
							@endforeach
						</ul>
					</div>
				</div>
			</div>
			<div class="step-bottom">
				<div class="container">
					{{trans('main.create_a_task_right_now_and_fi')}}
				</div>
			</div>
		</div>
		<div id="feedback-block">
			<div class="container">
				<div class="title">{{trans('main.what_our_customers_are_saying')}}</div>
				{{--<div class="subtitle">{{trans('main.everyone_s_reasons_for_learnin')}}</div>--}}
				<div class="feedback-slider">
					@foreach(\App\Models\Reviews::all() as $review)
					<div class="feedback-item">
						<div class="feedback-wrap">
							<div class="feedback-img" style="background-image: url({{asset($review->user_photo)}})"></div>
							<div class="feedback-name">{{$review->user_name}}</div>
							<p>{!! $review->commetary !!}</p>
							<div class="feedback-proffesion">{{$review->user_proffesion}}</div>
						</div>
					</div>
					@endforeach
				</div>
			</div>
		</div>

@endsection

@push('scripts')
<script>

    $(window).on('load', function () {
        $("#cloud span").animate({
            opacity: 1
        }, 1000);
    });

    var scene = $('#cloud').get(0);
    var parallaxInstance = new Parallax(scene);
</script>
@endpush
