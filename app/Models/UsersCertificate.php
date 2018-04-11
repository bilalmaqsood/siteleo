<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UsersCertificate extends Model
{
    protected $fillable = ['title', 'updated_at'];

    public function user(){
        return $this->belongsTo('App\User');
    }
}
