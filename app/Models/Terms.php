<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Terms extends Model
{

    protected $table = 'terms';
    protected $fillable = ['for_professionals','for_clients'];
}
