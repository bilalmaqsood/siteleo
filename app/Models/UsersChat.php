<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UsersChat extends Model
{
    protected $table = 'users_chat';

    public function user()
    {
        return $this->hasOne('App\Users', 'user_id');
    }

    public function partner()
    {
        return $this->hasOne('App\Users', 'partner_id');
    }

    public function setMessage(){
        $uId = \App::user()->id;
    }

    public function getMessage(){
        $uId = \App::user()->id;
    }
}
