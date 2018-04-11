<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UsersLocations extends Model
{
    protected $table = 'users_locations';

    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
