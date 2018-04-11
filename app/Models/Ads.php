<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ads extends Model
{
    protected $table = 'ads';
    
    protected $casts = [
        'category' => 'array',
    ];
    
    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function comments()
    {
        return $this->hasMany('App\Models\AdsComments');
    }

    public function favorites()
    {
        return $this->hasMany('App\Models\UsersFavorites');
    }

    public function categories()
    {
        return $this->belongsToMany('App\Models\Category', 'categories_ads', 'ads_id', 'categorie_id');
    }

    public function category()
    {
        return $this->belongsToMany('App\Models\Category', 'categories_ads', 'ads_id', 'categorie_id');
    }

    public function scopeActiveCount($query)
    {
        return count($query->where('activ', 1)->get());
    }

    public function scopeActive($query)
    {
        return $query->where('activ', 1);
    }
}
