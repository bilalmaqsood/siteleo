<?php

namespace App\Models;

use Encore\Admin\Traits\AdminBuilder;
use Encore\Admin\Traits\ModelTree;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use ModelTree, AdminBuilder;
    
    public function getIconAttribute($value)
    {
        return !empty($value) ? '/uploads/'.$value : $value;
    }


    public function ads()
    {
        return $this->belongsToMany('App\Models\Ads', 'categories_ads', 'categorie_id', 'ads_id');
    }
}
