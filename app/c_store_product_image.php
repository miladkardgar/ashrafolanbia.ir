<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class c_store_product_image extends Model
{
    protected $guarded=['id'];

    public function product()
    {
        return $this->belongsTo('App\c_store_product','CSP_id');
    }
}
