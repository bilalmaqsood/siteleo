<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\UsersLocations;
use MenaraSolutions\Geographer\Earth;
use MenaraSolutions\Geographer\Country;


class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //$categories = Category::where('parent_id', 0)->get();

        //$citys = UsersLocations::orderBy('created_at', 'DESC')->distinct()->get()->toArray();

        $poblacions  = UsersLocations::getPoblacion();
        $cities = [];
        $provinces = [];
        foreach (UsersLocations::orderBy('created_at', 'DESC')->get() as $c){
            $count = count($c->user->ads);

            if($count){ 
	     $cities[$c->city] = isset($cities[$c->city]) ? $cities[$c->city]+$count : $count;
            $provinces[$c->provincia] = isset($provinces[$c->provincia]) ? $provinces[$c->provincia]+$count : $count;
}
        }



        return view('home', [
            'category' => new Category(),
            'cities' => $cities,
            'provinces' => $provinces,
            'poblacions' => $poblacions,
            'category_count' => $this->calculateAdsCategory()
        ]);
    }

    public function liveUpdate(Request $request){
        $counts = \App\Models\CoutEvents::counts();
        return [
            'new_events' => $counts->chat_num+$counts->ad_num,
            'chat_num' => $counts->chat_num,
            'ad_num' => $counts->ad_num,
        ];
    }

    private function calculateAdsCategory(){
        $ads = \App\Models\Ads::active()->get();
        $return = [];
        foreach ($ads as $ad){
            foreach ($ad->category as $cat){
                is_numeric($cat) && !empty($cat) && !is_null($cat) && isset($return[$cat]) ? $return[$cat]++ : $return[$cat]=1;
            }
        }
        return $return;
    }
}
