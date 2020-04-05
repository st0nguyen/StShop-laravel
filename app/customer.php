<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class customer extends Model
{
    protected $table = 'tbl_customers';
    protected $primaryKey = 'customer_id';
    protected  $fillable = ['customer_name','customer_email','customer_password','customer_phone'];

    public function customer()
    {
        return $this->hasOne('App\order', 'order_id', 'customer_id');
    }
}
