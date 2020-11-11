<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class charity_payment_patern extends Model
{
    //

    public function fields()
    {
        return $this->hasMany('App\charity_payment_field','ch_pay_pattern_id');
    }
    public function titlesDEPRICATED()
    {
        return $this->hasMany('App\charity_payment_title','ch_pay_pattern_id');
    }
    public function titles(){
        return $this->belongsToMany('App\charity_payment_title', 'charity_payment_p_t_s','pattern_id','title_id');
    }
}
