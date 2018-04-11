<?php

namespace App\Models;

use Ghanem\Rating\Traits\Ratingable as Rating;
use Illuminate\Database\Eloquent\Model;

class AdsComments extends Model
{
    use Rating;

    protected $table = 'ads_commentaries';

    protected $fillable = ['parent_id', 'ads_user', 'message', 'new', 'active', 'updated_at'];

    public function scopeActive($query)
    {
        return $query->where('active', 1);
    }

    public function user()
    {
        return $this->belongsTo('App\User' , 'user_id');
    }

    public function autohor()
    {
        return $this->hasOne('App\User' , 'user_id');
    }

    public function ad()
    {
        return $this->belongsTo('App\Models\Ads', 'ads_id');
    }
}
