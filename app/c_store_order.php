<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class c_store_order extends Model
{
    public function items()
    {
        return $this->hasMany('App\c_store_order_item','CSO_id');
    }

    public function transactions()
    {
        return $this->morphMany('App\gateway_transaction', 'modulable');
    }
}
