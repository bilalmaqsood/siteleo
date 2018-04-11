<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UsersGallery extends Model
{
    protected $table = 'users_gallery';

    protected $fillable = ['photo', 'updated_at'];

    public function user(){
        return $this->belongsTo('App\User', 'user_id');
    }
    
    public function getPhotoAttribute($value)
    {
        return '/uploads/'.$value;
    }
}
