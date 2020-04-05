<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class brand extends Model
{
    protected $table = 'tbl_brand';
    protected $primaryKey = 'brand_id';
    protected  $fillable = ['brand_name','brand_slug','brand_desc','brand_status'];
}
