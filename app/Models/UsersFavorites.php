<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UsersFavorites extends Model
{
    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function ads()
    {
        return $this->belongsTo('App\Models\Ads');
    }
}
