<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request; 
use App\Http\Requests;
use Illuminate\Support\Facades\Storage;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/user/profile';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        $messages = array(
            'conditions'      => 'You must agree to the terms of use!'
        );
        
        return Validator::make($data, [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'conditions' => 'accepted',
            //'photo' => 'required'
        ], $messages);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {

        \Mail::to(config('admin_email'))->send(new \App\Mail\Support([
            'message' => 'New user on the site.',
        ]));

		$email=$data['email'];
		$name=$data['name'].' '.$data['surname'];
		$pass=$data['password'];


		\Mail::send('emails.new_register', ['data'=>$data], function ($message) use ($email, $name ,$pass,$data)
		{

	$message->to($email, $name)->subject('Ваші дані для авторизації на сайті isp');
     

	});
     


	
        //dd($data['registrate'] == 'worker' ? $data['registrate'] : 'visitor');
        return User::create([
            'name' => $data['name'],
            'surname' => $data['surname'],
            'photo' => isset($data['photo']) ? $data['photo']->store('images') : 'user-login.png',
            'role' => $data['registrate'] == 'worker' ? $data['registrate'] : 'visitor',
            'email' => $data['email'],
            'phone' => $data['phone'],
            'password' => bcrypt($data['password']),
        ]);
    }
}
