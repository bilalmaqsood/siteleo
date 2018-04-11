@php
    $title = false;
    $keywords = false;
    $description = false;
    $rout = Route::currentRouteName();
    if($rout=='advertise' && isset($ad)){
        $title = $ad->seo_title;
        $keywords = $ad->seo_keywords;
        $description = $ad->seo_description;
    }elseif($rout=='search'){
        if(isset($uri_category)){
            $category_seo = \App\Models\Category::where('uri', $uri_subcategory ? $uri_subcategory : $uri_category)->first();
            $title = $category_seo->seo_title;
            $keywords = $category_seo->seo_keywords;
            $description = $category_seo->seo_description;
        }
    }elseif($rout=='404'){
        $title = 404;
    }
@endphp

<title>{{ config('app.name', 'EL Profesional') }}@if($title) - {{$title}}@endif</title>
<meta name="keywords" content="@if($keywords){{$keywords}}@endif">
<meta name="description" content="@if($description){{$description}}@endif">