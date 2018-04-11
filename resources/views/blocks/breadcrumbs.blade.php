@php $rout = Route::currentRouteName(); @endphp

@if($rout == 'advertise')
<div class="breadcrumbs">
	<ul>
		@foreach($ad->categories as $category)
			@if(!$category->parent_id)
				<li><a href="/categories">{{$category->title}}</a></li>
			@else
				@if(isset($save))
					<li><a href="{{route('search', ['uri_category' => $save, 'uri_subcategory' => $category->uri])}}">{{$category->title}}</a></li>
				@else
					<li><a href="{{route('search', ['uri_category' => $category->uri])}}">{{$category->title}}</a></li>
				@endif
				@php $save = $category->uri; @endphp
			@endif
		@endforeach
		<li><a href="{{route('advertise', ['uri' => $ad->uri])}}">{{$ad->name}}</a></li>
	</ul>
</div>
@endif

@include('blocks.messages')