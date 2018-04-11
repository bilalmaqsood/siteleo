<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reviews extends Model
{

    public function getUserPhotoAttribute($value)
    {
        return '/uploads/'.$value;
    }

}
