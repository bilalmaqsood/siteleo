<?php

namespace App\Http\Controllers\User;
use Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Category;
use Transliterate;
use App\Models\Ads;
use App\Models\AdsCategories;

class NewAdvertisementController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //dd(Auth::user()->graphics);
        
        $profile_status = $this->user_profile_status();        
        return view('user.new-advertisement', [
            'profile_status' => $profile_status,
            'category' => new Category(),
        ]);
    }

    
    public function create(Request $request)
    {
        $category = $request->category;

        foreach ($category as $key => $val){
            if(empty($val) || is_null($val)) unset($category[$key]);
        }

        //dd(count($category), $category);

        if(count($category)<2){
            return back()->withErrors(['You must select a category!']);
        }
        
        if(!$this->user_profile_status()){
            return redirect()->route('user-profile')->withErrors(['Your profile must be completely filled!']);
        }
        
        $this->validate($request, [
            'category' => 'required|array',
            'name' => 'required|string|min:2',
            'terms_service' => 'required|min:150|max:2000',
            'description' => 'required|min:150|max:2000',
            'conditions' => 'accepted',
        ]);
        
        $uri = preg_replace('/[^A-Za-z0-9_]/', ' ', $request->name);
        $uri = str_replace('__', '_', $uri);
        $uri = $this->strlen_simb(substr(Transliterate::make(date('d_m_Y').'_'.$uri, ['type' => 'filename', 'lowercase' => true]), 0, 90),'_');
   
	$col=Ads::where('user_id',Auth::user()->id)->get();
	$count=count($col);
	
        $ad = new Ads();
        $ad->user_id = Auth::user()->id;
        $ad->uri = $uri;
        $ad->name = $request->name;
        $ad->category = $request->category;
        $ad->terms_service = $request->terms_service;
        $ad->description = $request->description;

        $ad->seo_title = $request->name;
        $ad->seo_keywords = implode(', ', explode(' ', preg_replace('/[^A-Za-z0-9]/', ' ', strip_tags($request->description))));
        $ad->seo_description = strip_tags($request->description);
if($count>=2){
$balance=Auth::user()->balance;
$balance=$balance-5;
if($balance<0){
$status="There isn't enough money on your balance";
        return redirect()->route('user-balance')->with('status', $status);
}
else{
$ad->save();
$status='Your information has been saved! You can view them in the "My Ads" section but the publication will be posted after confirmation by the moderator. In the meantime, you can fund your account!';
}
}else
{
$ad->save();
$status='Your information has been saved! You can view them in the "My Ads" section but the publication will be posted after confirmation by the moderator. In the meantime, you can fund your account!';
}
        foreach ($request->category as $category){
            if(!empty($category)) {
                $ad_category = new AdsCategories();
                $ad_category->categorie_id = $category;
                $ad_category->ads_id = $ad->id;
                $ad_category->save();
            }
        }

        return redirect()->route('user-balance')->with('status', $status);
        dd($request->all(), $uri);
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
    public function edit(Request $request, $id)
    {
        $find = Ads::find($id);
        if(is_null($find)) return redirect()->route('user-new-advertisement')->withErrors(['Ad not found!']);
        $ad = $find->user()->where('id', '=', Auth::user()->id)->first();
        if(is_null($ad)) return redirect()->route('user-new-advertisement')->withErrors(['Ad not found!']);
        //dd($find->category);
        
        $categories = [];
        foreach (Category::all() as $cat){
            $categories[$cat->id] = $cat;
        }
        
        $profile_status = $this->user_profile_status();        
        return view('user.new-advertisement', [
            'profile_status' => $profile_status,
            'category' => new Category(),
            'categories' => $categories,
            'ad' => $find,
        ]);
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
        $find = Ads::find($id);
        if(is_null($find)) return redirect()->route('user-new-advertisement')->withErrors(['Ad not found!']);
        $ad = $find->user()->where('id', '=', Auth::user()->id)->first();
        if(is_null($ad)) return redirect()->route('user-new-advertisement')->withErrors(['Ad not found!']);
        
        if(count($request->category)<2){
            return back()->withErrors(['You must select a category!']);
        }
        
        if(!$this->user_profile_status()){
            return redirect()->route('user-profile')->withErrors(['Your profile must be completely filled!']);
        }
        
        $this->validate($request, [
            'category' => 'required|array',
            'name' => 'required|string|min:2',
            'terms_service' => 'required|min:150|max:2000',
            'description' => 'required|min:150|max:2000',
            'conditions' => 'accepted',
        ]);
        
        $find->user_id = Auth::user()->id;
        $find->name = $request->name;
        $find->category = $request->category;
        $find->terms_service = $request->terms_service;
        $find->description = $request->description;

        if(empty($find->seo_title)) $find->seo_title = $request->name;
        if(empty($find->seo_keywords)) $find->seo_keywords = str_replace([' ,'], '', implode(', ', explode(' ', preg_replace('/[^A-Za-z0-9]/', ' ', strip_tags($request->description)))));
        if(empty($find->seo_description)) $find->seo_description = strip_tags($request->description);

        $find->save();

        $cats = AdsCategories::where('ads_id', $id)->get();
        $i=0;
        foreach ($cats as $cat){
            $cat->categorie_id = $request->category[$i];
            $cat->ads_id = $id;
            $cat->save();
            $i++;
        }


//        foreach ($request->category as $category){
//            $ad_category = new AdsCategories();
//            $ad_category->categorie_id = $category;
//            $ad_category->ads_id = $id;
//            $ad_category->save();
//        }

        return redirect()->route('user-advertisement')->with('status', 'Your information has been updated!');
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
    
    private function user_profile_status(){
        return !empty(Auth::user()->birthday) && Auth::user()->sex && Auth::user()->location && Auth::user()->graphics ? 1:0;
    }
    
    private function strlen_simb($str, $simb){
        if ($str{strlen($str)-1} == $simb) {
            return substr($str,0,-1);
        }
        return $str;
    }
}
