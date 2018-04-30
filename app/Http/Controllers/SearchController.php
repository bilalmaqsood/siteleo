<?php

namespace App\Http\Controllers;

use function foo\func;
use Illuminate\Http\Request;
use App\Models\Ads;
use App\Models\Category;
use App\Models\AdsCategories;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\Paginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Models\UsersLocations;

class SearchController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($uri_category=false, $uri_subcategory=false, Request $request)
    {
        $ads = Ads::all();
        $collection = collect($ads);


        $collection = $collection->filter(function($val, $key){
            return $val['activ'];
        });

        $category = Category::where('uri', $uri_category)->first();
        $collection = $collection->filter(function($val, $key) use ($category){
            $return = false;
            foreach ($val['category'] as $cat) { if($cat==$category->id) $return=true; }
            return $return;
        });

        if($uri_subcategory){
            $subcategory = Category::where('uri', $uri_subcategory)->first();
            $collection = $collection->filter(function($val, $key) use ($subcategory){
                $return = false;
                foreach ($val['category'] as $cat) { if($cat==$subcategory->id) $return=true; }
                return $return;
            });
        }



        return view('search', [
            'category' => $category,
            'uri_category' => $uri_category,
            'uri_subcategory' => $uri_subcategory,
            'ads' => $this->paginate(
                $collection->all(),
                15,
                $request->page ? $request->page : 1,
                [
                    'path' => route('search', [
                        'uri_category' => $uri_category,
                        'uri_subcategory' => $uri_subcategory
                    ])
                ]
            ),
        ]);
    }

    public function indexQ(Request $request){

        $ads = Ads::where('activ', 1);

      
    if($request->has("add_id")){
        $collection = Ads::where("id",$request->add_id)->get();
    } else {

        if ($request->category && is_numeric($request->category))
            $ads->whereHas('category', function ($q) use ($request) {
                $q->where("categories.id", $request->category);
            });
        $ads = $ads->orderBy('last_pay', 'desc')->get();
        $collection = collect($ads);

//        dd($collection);
        if (!empty($request->provincia))
            $collection = $collection->filter(function ($item, $key) use ($request) {
                $search_provincia = explode(' ', $request->provincia);
                if (isset($item->user->location->provincia)) {
                    if (str_contains($item->user->location->provincia, $search_provincia)
                        || $request->provincia == 'Todas' || $request->provincia == 'all'
                        || !isset($request->provincia)
                    ) {
                        return true;
                    }
                }
            });


        if (!empty($request->city))
            $collection = $collection->filter(function ($item, $key) use ($request) {
                $search_city = explode(' ', $request->city);
                if (isset($item->user->location->city)) {
                    if (str_contains($item->user->location->city, $search_city)
                        || $request->city == 'all' || $request->city == 'Todas'
                        || !isset($request->city)
                    ) {
                        return true;
                    }
                }
            });

        if (!empty($request->q))
            $collection = $collection->filter(function ($item, $key) use ($request) {

                $searchString = $item->name . ' ' . $item->uri . ' ' . $item->terms_service . ' ' . $item->description;
                if (!empty($item->user->location))
                    $searchString = $searchString . " " . $item->user->location->city . ' ' . $item->user->location->provincia;

                $search = strtolower($request->q);
//                 $str='';
//                if(isset($item->user->location->city)) {
//                   $str=$item->user->location->city;
//                }
//
//                if(isset($item->user->location->provincia)) {
//                    $str=$str.' '.$item->user->location->provincia;
//                }

                $categories = "";
                if ($item->categories->count()) $categories = implode(" ", $item->categories->pluck("title")->toArray());
                if (str_contains(strtolower($searchString), $search) ||
                    str_contains(strtolower($categories), $search)
                ) {
                    return true;
                }

//               if(str_contains($searchString, $search) || empty($request->q) || !isset($request->q)){
//                     return true;
//                }
            });

    }
//    dd($collection);
        $collection = $collection->groupBy('last_pay');
        $no_pay_ads = $collection->pop();
        if($no_pay_ads) $no_pay_ads = $no_pay_ads->sortByDesc('created_at');

        $cities = [];
        foreach (UsersLocations::orderBy('created_at', 'DESC')->get() as $c){
            $ads = [];
            if(isset($c->user->ads)) {
                foreach ($c->user->ads as $ad) {
                    if ($ad->activ) $ads[] = $ad;
                }
            }
            $count = count($ads);
            if($count) $cities[$c->city] = isset($cities[$c->city]) ? $cities[$c->city]+$count : $count;

        }

        return view('search', [
            'category' => false,
            'uri_category' => false,
            'uri_subcategory' => false,
            'q' => $request->q,
            'city' => $request->city,
            'provincia' =>$request->provincia ? $request->provincia : '',
            'cities' => $cities,
            'ads' => $this->paginate(
                $collection->push($no_pay_ads)->collapse(),
                15,
                $request->page ? $request->page : 1,
                [
                    'path' => route('search-q', [
                        'q' => $request->q,
                        'city' => $request->city
                    ])
                ]
            ),
        ]);
    }

    public function paginate($items, $perPage = 15, $page = null, $options = [])
    {
        $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
        $items = $items instanceof Collection ? $items : Collection::make($items);
        return new LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $perPage, $page, $options);
    }


    public function sResult(Request $request)
    {
//        if(!$request->val) return response([/'error'], 500);

        $result = [];

        $ads = Ads::all();
        $search_cat = explode(' ', strtolower($request->val));
        $collection = collect($ads)->filter(function($item, $key) use ($request,$search_cat){
            $search = $request->val;//explode(' ', $request->val);
            $categories = "";
            if($item->categories->count()) $categories = implode(" ",$item->categories->pluck("title")->toArray());
                if(str_contains(strtolower($item->name.' '.$item->uri.' '.$item->terms_service.' '.$item->description), $search_cat) ||
                    str_contains(strtolower($categories), $search_cat))
                {
                    return true;
                }
        });

//    dd($collection);
////        foreach ($collection as $item) {
//
//            if(str_contains(strtolower($item->user->location->provincia), $search_cat)) {
//                $result[$item->user->location->provincia] = [
//                    'url' => route('search-q', ['provincia' => $item->user->location->provincia]),
//                    'text' => $item->user->location->provincia
//                ];
//            } else if(str_contains(strtolower($item->user->location->city), $search_cat) || str_contains(strtolower($item->user->location->provincia), $search_cat)) {
//                $result[$item->user->location->city]=[
//                    'url' => route('search-q', ['city' => $item->user->location->city]),
//                    'text' => $item->user->location->city
//                ];
//            }
//        }

        foreach ($collection->toArray() as $item) {
            if(is_array($item['category']) && isset($item['category'][1])){
                $item['category'] = $item['category'][1];
                $result[] = $item;
            }
        }
        $categories = \App\ArrayClass::convert(\App\Models\Category::all());
        $result = \App\ArrayClass::group($result, 'category');
        $search = [];
        $result = array_map(function($item, $key) use ($categories, $request,&$search){
            $found = 0;
            $data = [];
            foreach ($item[0]['categories'] as $cat){
                if(str_contains(strtolower($cat['title']), $request->val) && array_search($cat['title'], array_column($search, 'text'))===false){
                    array_push($search,[
                        'url' => route('search', $cat['uri']),
                        'text' => $cat['title']
                    ]);
                }
            }

            if(str_contains(strtolower($item[0]['name']),$request->val) && array_search($item[0]['name'], array_column($search, 'text'))===false){
                array_push($search,[
                    'url' => route('search-q',["add_id" => $item[0]['id']]),
                    'text' => $item[0]['name']
                ]);
            }

            return $data;
//            return [
//                'url' => route('search', [$categories[$key]->uri]),
//                'text' => $categories[$key]->title
//            ];
        }, $result, array_keys($result));

        return response($search, (count($search) ? 200 : 404));
    }

    public function cResult(Request $request)
    {
        $result = [];

        $ads = Ads::all();
        $search_city = explode(' ', strtolower($request->city));
//        dd($search_city);
//        $ads = Ads::whereHas("user.location", function($q) use($request){
//                  $q->where('name','LIKE',"% {$request->city} %")
//                    ->orWhere("provincia",'LIKE',"% {$request->city} %");
//        })->with("user.location")->get();

        $result = [];

        $collection = collect($ads)->filter(function($item, $key) use ($request,$search_city,$result){
            if(!$item->user->location)
                return false;
            if(str_contains(strtolower($item->user->location->city), $search_city) || str_contains(strtolower($item->user->location->provincia), $search_city)){
                return true;
//                if(str_contains($item->name.' '.$item->uri.' '.$item->terms_service.' '.$item->description, $search_city)){
//                    return true;
//                }
            }
        });

//        usort($collection, function ($a, $b) use ($request) {
//
//            return $pos_a - $pos_b;
//        });
//        stripos()
//        dd($result);
//        dd($collection);
//        $result = [];
        foreach ($collection as $item) {

            if(str_contains(strtolower($item->user->location->provincia), $search_city)) {
                $result[$item->user->location->provincia] = [
                    'url' => route('search-q', ['provincia' => $item->user->location->provincia]),
                    'text' => $item->user->location->provincia
                ];
            }
            if(str_contains(strtolower($item->user->location->city), $search_city)) {
                $result[$item->user->location->city]=[
                    'url' => route('search-q', ['city' => $item->user->location->city]),
                    'text' => $item->user->location->city
                ];
            }
        }
//        $categories = \App\ArrayClass::convert(\App\Models\Category::all());
//        $result = \App\ArrayClass::group($result, 'category');
//        $result = array_map(function($item, $key) use ($categories, $request){
//            return [
//                'url' => route('search-q', ['q' => $request->val, 'city' => $request->city, 'category' => $key]),
//                'text' => $categories[$key]->title.' ('.count($item).')'
//            ];
//        }, $result, array_keys($result));

        //dd($result);

        return response($result, (count($result) ? 200 : 404));
    }

    public function ajax(Request $request){

        $result = [];

        $ads = Ads::all();
        $collection = collect($ads)->filter(function($item, $key) use ($request){
            $search = explode(' ', $request->val);
            $search_city = explode(' ', $request->city);
            if(str_contains($item->user->location->city, $search_city) || $request->city=='Todas'){
                if(str_contains($item->name.' '.$item->uri.' '.$item->terms_service.' '.$item->description, $search) || empty($request->val)){
                    return true;
                }
            }
        });

        foreach ($collection->all() as $item) {
            $result[] = $item->name;
        }

        //dd($result);

        return response()->json($result);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
