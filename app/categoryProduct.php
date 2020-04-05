<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class categoryProduct extends Model
{
//    public $timestamps = false;

    protected $table = 'tbl_category_product';
    protected $primaryKey = 'category_id';
    protected  $fillable = ['category_name', 'category_desc','category_status','category_slug'];
}
