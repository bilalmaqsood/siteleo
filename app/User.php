<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;
    use \HighIdeas\UsersOnline\Traits\UsersOnlineTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'photo', 'surname', 'phone', 'role', 'birthday', 'sex',
        'price_per_hour', 'contract_price', 'experience', 'personal_information', 'balance',
        'response_time', 'about_experience'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
    
    protected $casts = [
        'response_time' => 'array',
    ];
    
    public function getPhotoAttribute($value)
    {
        return '/uploads/'.$value;
    }

    public function chat()
    {
        return $this->hasMany('App\Models\UsersChat');
    }
    public function favorites()
    {
        return $this->hasMany('App\Models\UsersFavorites');
    }
    public function ads()
    {
        return $this->hasMany('App\Models\Ads');
    }
    public function gallery()
    {
        return $this->hasMany('App\Models\UsersGallery');
    }
    public function services()
    {
        return $this->hasMany('App\Models\UsersServices');
    }
    public function certificates()
    {
        return $this->hasMany('App\Models\UsersCertificate');
    }
    public function location()
    {
        return $this->hasOne('App\Models\UsersLocations');
    }
    public function graphics()
    {
        return $this->hasOne('App\Models\UsersGraphics');
    }
    public function comments()
    {
        return $this->hasOne('App\Models\AdsComments');
    }
}
