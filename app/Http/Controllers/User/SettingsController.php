<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Auth;
use Hash;

class SettingsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('user.setting');
    }
    
    public function update(Request $request)
    {
        $this->validate($request, [
            'old_password' => 'required|string|min:6',
            'new_password' => 'required|string|min:6|confirmed'
        ]);
                
        if (!Hash::check($request->old_password, Auth::user()->password))  {
            return back()->withErrors(['Invalid old password entered!']);
        }
        
        $request->user()->fill([
            'password' => Hash::make($request->new_password)
        ])->save();
        
        return back()->with('status', 'Your password has been changed!');
    }
}
