<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UsersServices extends Model
{
    protected $table = 'users_services';

    protected $fillable = ['title', 'updated_at'];

    public function user(){
        return $this->belongsTo('App\User');
    }
}
