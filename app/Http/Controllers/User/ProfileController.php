<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Classes\LangClass;

use Auth;
use App\Models\UsersServices;
use App\Models\UsersGallery;
use App\Models\UsersCertificate;
use App\Models\UsersLocations;
use App\Models\UsersGraphics;

use MenaraSolutions\Geographer\Earth;
use MenaraSolutions\Geographer\Country;

class ProfileController extends Controller
{
    public function index()
    {
     $attr=new Earth();

     $provinces = UsersLocations::getProvinces(); sort($provinces);
     $poblacions  = UsersLocations::getPoblacion(); sort($poblacions);

        return view('user.profile', compact("provinces","poblacions"),['lang' => new LangClass(), 'earth' => (new Earth())->findOneByCode('ES')]);
    }
    
    public function worker(Request $request){
        $request->user()->fill([
            'role' => Auth::user()->role=='worker' ? 'visitor' : 'worker'
        ])->save();
        $status = Auth::user()->role=='worker' ? 'Worker!':'Visitor!';
        return back()->with('status', 'Congratulations, your account has changed status to '. $status);
    }

    public function graphics_update(Request $request){
        $this->validate($request, [
            'begining_work_day' => 'required|regex:/1?([0-9]:[0-9])0 ([a-p]m)/',
            'end_working_day' => 'required|regex:/1?([0-9]:[0-9])0 ([a-p]m)/',
            'working_days' => 'required|array', 
        ]);
        
        $test = UsersGraphics::where('user_id','=',Auth::user()->id)->first();
        if(is_null($test)) $graphics = new UsersGraphics();
        else $graphics = UsersGraphics::find($test->id);
        $graphics->user_id = Auth::user()->id;
        $graphics->begining_work_day = $request->begining_work_day;
        $graphics->end_working_day = $request->end_working_day;
        $graphics->working_days = $request->working_days;
        $graphics->save();
        
        return back()->with('status', 'Your data has been updated!');
        dd($request->all());
    }
    
    public function location_update(Request $request){
        $this->validate($request, [
            'provincia' => 'required|string|min:2',
            'city' => 'required|string|min:2',
            'postcode' => 'required|min:2',
            'radius' => 'required',
        ]);
        
        $test = UsersLocations::where('user_id','=',Auth::user()->id)->first();
        if(is_null($test)) $location = new UsersLocations();
        else $location = UsersLocations::find($test->id);
        $location->user_id = Auth::user()->id;
        $location->provincia = $request->provincia;
        $location->city = $request->city;
        $location->postcode = $request->postcode;
        $location->radius = $request->radius;
        $location->save();
        
        return back()->with('status', 'Your data has been updated!');
        dd();
    }
    
    public function update(Request $request)
    {        
        $this->validate($request, [
            'name' => 'required|string|min:2',
            'surname' => 'required|string|min:2',
            'sex' => 'required|max:1',
            'day' => 'required|digits_between:01,31',
            'month' => 'required|digits_between:01,12',
            'year' => 'required|min:4|max:4',
            'phone' => 'required|min:8|max:18',
            'photo' => 'image|dimensions:min_width=360,min_height=360,max_width=1920,max_height=1080',
        ]);
        
        if(Auth::user()->role=='worker'){
            $this->validate($request, [
                'price_per_hour' => 'required',
                'experience' => 'required'
            ]);
        }
        
        $update = [
            'name' => $request->name,
            'surname' => $request->surname,
            'sex' => $request->sex,
            'birthday' => $request->day.'.'.$request->month.'.'.$request->year,
            'phone' => $request->phone,
            'photo' => !is_null($request->photo) ? $request->photo->store('images') : str_replace('/uploads/', '', Auth::user()->photo)
        ];
                        
        if(Auth::user()->role=='worker'){
            $this->validate($request, [
                'price_per_hour' => 'required',
                'contract_price' => 'required',
                'experience' => 'required',
                'personal_information' => 'required',
                'about_experience' => 'required',
            ]);

            $update = array_merge($update, [
                'price_per_hour' => $request->price_per_hour,
                'contract_price' => isset($request->contract_price) ? 1:0,
                'experience' => $request->experience,
                'personal_information' => $request->personal_information,
                'about_experience' => $request->about_experience,
            ]);
            
            if(!is_null($request->photo_update)){
                foreach ($request->file('photo_update') as $id => $f) {
                    $gallery = UsersGallery::find($id);
                    $gallery->photo = $f->store('images');
                    $gallery->save();
                }
            }
            
            if(!is_null($request->photo_added)){
                foreach ($request->file('photo_added') as $f) {
                    $gallery = new UsersGallery();
                    $gallery->user_id = Auth::user()->id;
                    $gallery->photo = $f->store('images');
                    $gallery->save();
                }
            }
            
            foreach ($request->services as $serv){
                if(!empty($serv)){
                    $service = new UsersServices();
                    $service->user_id = Auth::user()->id;
                    $service->title = $serv;
                    $service->save();
                }
            }
            
            if(isset($request->services_update)){
                foreach ($request->services_update as $key => $upd){
                    $service = UsersServices::find($key);
                    if(!empty($upd)){
                        $service->title = $upd;
                        $service->save();
                    }else{
                        $service->delete();
                    }
                }
            }            
            
            foreach ($request->setificate as $sert){
                if(!empty($sert)){
                    $certificate = new UsersCertificate();
                    $certificate->user_id = Auth::user()->id;
                    $certificate->title = $sert;
                    $certificate->save();
                }
            }
            
            if(isset($request->setificate_update)){
                foreach ($request->setificate_update as $key => $upd){
                    $certificate = UsersCertificate::find($key);
                    if(!empty($upd)){
                        $certificate->title = $upd;
                        $certificate->save();
                    }else{
                        $certificate->delete();
                    }
                }
            }
        }
        $request->user()->fill($update)->save();
        return back()->with('status', 'Your data has been updated!');
    }
}
//$user = App\User::find(Auth::user()->id);
//        
//        $user->name = $request->name;
//        $user->surname = $request->surname;
//        $user->sex = $request->sex;
//        $user->birthday = $request->day.'.'.$request->month.'.'.$request->year;
//        $user->phone = $request->phone;
//        $user->photo = !is_null($request->photo) ? $request->photo->store('images') : str_replace('/uploads/', '', Auth::user()->photo);
//                    
//        $user->save();
