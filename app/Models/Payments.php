<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payments extends Model
{
    protected $table = "payments";

    protected $casts = [
        'payment_system_response' => 'array',
        'payment_system_response_data' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function cost()
    {
        return $this->belongsTo('App\Models\ListCost');
    }
}
