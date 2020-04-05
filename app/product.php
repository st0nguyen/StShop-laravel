<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class product extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];
    protected $table = 'tbl_product';
    protected $primaryKey = 'product_id';
    protected $fillable
        = [
            'product_name',
            'product_slug',
            'product_price',
            'product_desc',
            'product_content',
            'category_id',
            'brand_id',
            'product_status',
            'product_image'
        ];

    public function product()
    {
        return $this->hasOne('App\order');
    }
}
