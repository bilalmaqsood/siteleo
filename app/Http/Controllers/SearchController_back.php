<?php

namespace App\Http\Controllers;

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

      

  if($request->category && is_numeric($request->category)) $ads->where('category', 'like', '%'.$request->category.'%');
        $ads = $ads->orderBy('last_pay', 'desc')->get();
        $collection = collect($ads);

        $collection = $collection->filter(function($item, $key) use ($request){
            $search_provincia = explode(' ', $request->provincia);
            if(
                str_contains($item->user->location->provincia, $search_provincia)
                || $request->provincia=='Todas'
                || !isset($request->provincia)
            )
            {
                return true;
            }
        });
        $collection = $collection->filter(function($item, $key) use ($request){
            $search_city = explode(' ', $request->city);
            if(
                str_contains($item->user->location->city, $search_city)
                || $request->city=='Todas' || !isset($request->city)){
                return true;
            }
        });

        $collection = $collection->filter(function($item, $key) use ($request){
            $search = explode(' ', $request->q);
            if(str_contains($item->name.' '.$item->uri.' '.$item->terms_service.' '.$item->description, $search) || empty($request->q) || !isset($request->q)){
                return true;
            }
        });

        $collection = $collection->groupBy('last_pay');
        $no_pay_ads = $collection->pop();
        if($no_pay_ads) $no_pay_ads = $no_pay_ads->sortByDesc('created_at');

        $cities = [];
        foreach (UsersLocations::orderBy('created_at', 'DESC')->get() as $c){
            $ads = [];
            foreach ($c->user->ads as $ad){ if($ad->activ) $ads[] = $ad; }
            $count = count($ads);
            if($count) $cities[$c->city] = isset($cities[$c->city]) ? $cities[$c->city]+$count : $count;
        }

        return view('search', [
            'category' => false,
            'uri_category' => false,
            'uri_subcategory' => false,
            'q' => $request->q,
            'city' => $request->city,
            'provincia' => $request->provincia,
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
        if(!$request->val) return response(['error'], 500);

        $result = [];

        $ads = Ads::all();

        $collection = collect($ads)->filter(function($item, $key) use ($request){
            $search = $request->val;//explode(' ', $request->val);
            $search_city = explode(' ', $request->city);
            if(str_contains($item->user->location->city, $search_city) || $request->city=='Todas'){
                if(str_contains($item->name.' '.$item->uri.' '.$item->terms_service.' '.$item->description, $search) || empty($request->val)){
                    return true;
                }
            }
        });

        foreach ($collection->toArray() as $item) {
            if(is_array($item['category']) && isset($item['category'][1])){
                $item['category'] = $item['category'][1];
                $result[] = $item;
            }
        }
        $categories = \App\ArrayClass::convert(\App\Models\Category::all());
        $result = \App\ArrayClass::group($result, 'category');
        $result = array_map(function($item, $key) use ($categories, $request){
            return [
                'url' => route('search-q', ['q' => $request->val, 'city' => $request->city, 'category' => $key]),
                'text' => $categories[$key]->title.' ('.count($item).')'
            ];
        }, $result, array_keys($result));

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
