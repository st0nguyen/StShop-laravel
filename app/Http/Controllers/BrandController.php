<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\brand;

class BrandController extends Controller
{
    public function index(){
        $brand = brand::all();
        return $brand;
    }

    public function store(Request $request){
        $brand = brand::create($request->all());
        return $brand;
    }
}
