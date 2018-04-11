<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdsCategories extends Model
{
    protected $table = 'categories_ads';

    public function category()
    {
        return $this->belongsToMany('App\Models\Category', 'categories_ads', 'id', 'categorie_id');
    }

    public function ads()
    {
        return $this->belongsToMany('App\Models\Category', 'categories_ads', 'id', 'categorie_id');
    }


}
