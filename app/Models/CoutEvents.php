<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CoutEvents extends Model
{
    protected $table = 'count_events';

    public $work_user_id = 'self';

    public function scopeCounts(){
        if(!isset(\Auth::user()->id)) return 0;
        $user_id = $this->work_user_id=='self' ? \Auth::user()->id : $this->work_user_id;
        $return = self::where('user_id', $user_id)->first();
        if(!$return) {
            $this->user_id = $user_id;
            $this->chat_num = count(\App\Models\UsersChat::where('partner_id', $user_id)->where('new', 1)->get());
            $this->ad_num = count(\App\Models\AdsComments::where('ads_user', $user_id)->where('new', 1)->get());
            $this->save();
            $return = self::counts();
        }

        return $return;
    }

    public function scopeAdd($builder, $fields='chat_num', $num=1){
        $counts = self::counts();
        $counts->{$fields} += (int)$num;
        if($counts->{$fields}<0) $counts->{$fields} = 0;
        $counts->save();

        return $counts;
    }

    public function scopeSetUserId($builder, $user_id='self'){
        $this->work_user_id = $user_id;
        return $this;
    }
}
