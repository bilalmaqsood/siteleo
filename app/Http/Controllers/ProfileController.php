<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Classes\LangClass;

class ProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        $user = User::find($id);


        if(!$this->user_profile_status($user)) return view('errors.404');
        $total_points = 0;
        $total_voices = 0;
        $coments = 0;
        //dd($user->ads);
        $abc = array();
        foreach ($user->ads as $ad) {
            foreach ($ad->comments as $comment) {
                if(!$comment->parent_id){
                    $total_points = $total_points+$comment->sumRating;
                    $total_voices++;
                }
            }
//            array_push($abc,$ad->comments->toArray());
//            dd($ad->comments->where("ads_user","!=",auth()->user()->id));
            $coments += count($ad->comments->where("parent_id","<=",0));
        }
//        dd($abc);
        $lang = new LangClass();

        $week = [
            trans('main.monday'),
            trans('main.tuesday'),
            trans('main.wednesday'),
            trans('main.thursday'),
            trans('main.friday'),
            trans('main.saturday'),
            trans('main.sunday'),
        ];

        $week_name = []; if(!is_null($user->graphics)) foreach ($user->graphics->working_days as $day) $week_name[] = $week[$day];

        /*$cats = Category::find($ad->category);
        $cats_name = []; foreach ($cats as $cat) $cats_name[] = $cat->title;*/

        return view('profile', [
            'week_name' => $week_name,
            'user' => $user,
            'stars' => $total_points && $total_voices ? round($total_points/$total_voices) : 0,
            'coments' => $coments,
        ]);
    }

    function user_profile_status($user){
  return !empty($user->birthday) && $user->sex && $user->location && $user->graphics ? 1:0;

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
