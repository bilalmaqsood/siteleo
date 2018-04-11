<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Ads;
use Illuminate\Support\Facades\Auth;

class AdvertisementController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
        return view('user.advertisement', ['ads'=>Auth::user()->ads]);
    }
    
    public function up($id, Request $request){
        $find = Ads::find($id);
        if(is_null($find)) return redirect()->route('user-new-advertisement')->withErrors(['Ad not found!']);
        $ad = $find->user()->where('id', '=', Auth::user()->id)->first();
        if(is_null($ad)) return redirect()->route('user-new-advertisement')->withErrors(['Ad not found!']);
        if(!$find->activ) return back()->withErrors(['Your ad has not been moderated yet!']);
        if(($find->last_pay+86400) > time()) return back()->withErrors(['To avoid re-writing, you can raise this ad again after 24 hours.']);
        
        $balance = Auth::user()->balance;
        $ad_cost = config('ad_cost');
        if(($last_balance = $balance-$ad_cost)<0) return redirect()->route('user-balance')->withErrors(['There are not enough funds on your account, please refill your balance!']);
        
        $request->user()->fill([
            'balance' => $last_balance,
        ])->save();
        
        $find->payable = $find->payable+1;
        $find->last_pay = time();
        $find->save();
        
        return back()->with('status', 'Thank you for your payment, your ad is raised in the top!');
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
    public function destroy(Request $request)
    {
        $find = Ads::find($request->delete_id);
        if(is_null($find)) return redirect()->route('user-new-advertisement')->withErrors(['Ad not found!']);

        \App\Models\AdsCategories::where('ads_id', $request->delete_id)->delete();
        \App\Models\AdsComments::where('ads_id', $request->delete_id)->delete();

        $ad = $find->user()->where('id', '=', Auth::user()->id)->first();
        if(is_null($ad)) return redirect()->route('user-new-advertisement')->withErrors(['Ad not found!']);
        $find->delete();
        return back()->with('status', 'Your ad has been successfully deleted!');
        dd();
    }
}
