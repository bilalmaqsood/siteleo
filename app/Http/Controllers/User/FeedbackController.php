<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use App\Models\Ads;
use App\Models\AdsComments;
use App\User;
use Carbon\Carbon;

class FeedbackController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        foreach (\App\Models\AdsComments::where('ads_user', \Auth::user()->id)->where('new',1)->get() as $item) {
            $item->new = 0;
            $item->save();
        }
        \App\Models\CoutEvents::setUserId(Auth::user()->id)->add('ad_num', -9999);
        return view('user.feedback');
    }

    public function answer(Request $request, $id)
    {
        $commetary = AdsComments::find($id);
        if(is_null($commetary)) return back()->withErrors(['Commentary not found!']);
        $this->validate($request, [
            'message' => 'required|string|min:2'
        ]);
        //dd($commetary->ad);

        $comment = new AdsComments();
        $comment->parent_id = $commetary->id;
        $comment->user_id = Auth::user()->id;
        $comment->ads_id = $commetary->ads_id;
        $comment->ads_user = Auth::user()->id;
        $comment->message = $request->message;
        $comment->new = 0;
        $comment->save();

        $commetary->new=0;
        $commetary->save();

        $response_time = round(((time() - $commetary->created_at->timestamp)/60)/60);

        $update = [
            'response_time' => [
                'hours' => Auth::user()->response_time['hours']+$response_time,
                'num' => Auth::user()->response_time['num']+1,
            ]
        ];

        $request->user()->fill($update)->save();

        //dd($response_time);

        return back()->with('status', 'Your answer is saved!');
        dd($request->message);
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
