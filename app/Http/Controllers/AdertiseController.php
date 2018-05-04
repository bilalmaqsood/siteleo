<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ads;
use App\Models\Category;
use App\Classes\LangClass;
use App\Models\AdsComments;
use Auth;
use App\Models\UsersFavorites;
use App\Models\UsersChat;

class AdertiseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($uri)
    {
        $ad = Ads::where('uri', $uri)->first();
        if(is_null($ad)) return view('errors.404');
        $cats = Category::find($ad->category);
        $cats_name = []; foreach ($cats as $cat) $cats_name[] = $cat->title;

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

        $week_name = []; foreach ($ad->user->graphics->working_days as $day) $week_name[] = $week[$day];

        $upd_ad = Ads::find($ad->id);
        $upd_ad->views = $upd_ad->views+1;
        $upd_ad->save();

        $total_points = 0;
        $total_voices = 0;
        foreach (\App\Models\AdsComments::active()->where('ads_id', $ad->id)->get() as $comment) {
            if(!$comment->parent_id){
                $total_points = $total_points+$comment->sumRating;
                $total_voices++;
            }
        }
//        dd(\App\Models\AdsComments::active()->where('ads_id', $ad->id)->get());
        return view('advertise', [
            'ad' => $ad, 
            'cats_name' => $cats_name, 
            'week_name' => $week_name, 
            'stars' => $total_points && $total_voices ? round($total_points/$total_voices) : 0,
        ]);
    }

    public function llike(Request $request)
    {
        $ad = Ads::where('id', $request->id)->first();
        if(is_null($ad)) return redirect()->route('home');

        $favoriteTest = UsersFavorites::where('user_id', Auth::user()->id)->where('ads_id', $request->id)->first();
        $upd_ad = Ads::find($request->id);

        if(!$favoriteTest){
            $favorite = new UsersFavorites();
            $favorite->user_id = Auth::user()->id;
            $favorite->ads_id = $request->id;
            $favorite->save();
            $upd_ad->like = $upd_ad->like+1;
        }else{
            $favoriteTest->delete();
            $upd_ad->like = $upd_ad->like-1;
        }

        $upd_ad->save();
    }

    public function storeLike(Request $request)
    {
        $ad = Ads::where('id', $request->id)->first();



        $ad = Ads::where('id', $request->id)->first();
        if(is_null($ad)) return redirect()->route('home');

        $favoriteTest = UsersFavorites::where('user_id', Auth::user()->id)->where('ads_id', $request->id)->first();
        $upd_ad = Ads::find($request->id);

        if($request->type=='add'){
            $favorite = new UsersFavorites();
            $favorite->user_id = Auth::user()->id;
            $favorite->ads_id = $request->id;
            $favorite->save();
            $upd_ad->like = $upd_ad->like+1;
        }

        if($request->type=='del'){
            $favoriteTest->delete();
            $upd_ad->like = $upd_ad->like-1;
        }

//        if(!$favoriteTest){
//            $favorite = new UsersFavorites();
//            $favorite->user_id = Auth::user()->id;
//            $favorite->ads_id = $request->id;
//            $favorite->save();
//            $upd_ad->like = $upd_ad->like+1;
//        }else{
//            $favoriteTest->delete();
//            $upd_ad->like = $upd_ad->like-1;
//        }

        $upd_ad->save();
    }

    public function chat(Request $request, $id){
        $ad = Ads::where('id', $id)->first();
        if(is_null($ad)) return view('errors.404');
        $this->validate($request, [
            'message' => 'required|string|min:2'
        ]);

        $chat = new UsersChat();
        $chat->user_id = $ad->user->id;
        $chat->partner_id = Auth::user()->id;
        $chat->message = 'New Chat width '.(\App\User::find($ad->user->id)->name);
        $chat->save();

        $chat = new UsersChat();
        $chat->user_id = Auth::user()->id;
        $chat->partner_id = $ad->user->id;
        $chat->message = $request->message;
        $chat->save();

        \App\Models\CoutEvents::setUserId($ad->user->id)->add('chat_num', 1);

        return redirect()->route('user-messages');
        dd($request->all());
    }

    public function commentary(Request $request, $id)
    {
        $ad = Ads::find($id);
        if(is_null($ad)) return back()->withErrors(['Ad not found!']);
        $this->validate($request, [
            'rating' => 'required',
            'message_re' => 'required|string|min:2'
        ]);

        $comment = new AdsComments();
        $comment->user_id = Auth::user()->id;
        $comment->ads_id = $id;
        $comment->ads_user = $ad->user_id;
        $comment->message = $request->message_re;
        $comment->save();

        \App\Models\CoutEvents::setUserId($ad->user_id)->add('ad_num', 1);

        $commentary = AdsComments::find($comment->id);
        $user = Auth::user();

        $rating = $commentary->ratingUnique([
            'rating' => $request->rating
        ], $user);

        return back()->with('status', 'Your comment has been added! Thank you for your opinion!');
        dd($rating);
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
