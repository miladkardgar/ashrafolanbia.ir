<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class c_store_product extends Model
{

    public function images()
    {
        return $this->hasMany('App\c_store_product_image','CSP_id','id');
    }

    public function main_image()
    {

      return $this->hasOne('App\c_store_product_image','CSP_id')->where('main_img',1);

    }

}
