<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class shipping extends Model
{
    protected $table = 'tbl_shipping';
    protected $primaryKey = 'shipping_id';
    protected  $fillable = ['shipping_name','shipping_address','shipping_phone','shipping_email','shipping_notes'];

    public function order()
    {
        return $this->hasOne('App\order');
    }
}

