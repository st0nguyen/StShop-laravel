<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class orderDetail extends Model
{
    protected $table = 'tbl_order_details';
    protected $primaryKey = 'order_details_id';
    protected  $fillable = ['order_id','product_id','product_name','product_price','product_sales_quantity'];
    public function order()
{
    return $this->belongsTo('App\order','order_id','order_id');
}
    public function product()
    {
        return $this->hasOne('App\product','product_id');
    }

    public function comments()
    {
        return $this->hasMany('App\Comment');
    }

}
