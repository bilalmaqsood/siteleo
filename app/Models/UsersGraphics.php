<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UsersGraphics extends Model
{
    protected $table = 'users_graphics';
    
    protected $casts = [
        'working_days' => 'array',
    ];
}
