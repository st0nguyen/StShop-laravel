<?php

namespace App\Http\Controllers;

use App\brand;
use App\order;
use App\product;
use Illuminate\Http\Request;
use DB;
use Session;
use App\Http\Requests;
use Illuminate\Support\Facades\Redirect;
session_start();

class AdminController extends Controller
{
    public function AuthLogin(){
        $admin_id = Session::get('admin_id');
        if($admin_id){
            return Redirect::to('dashboard');
        }else{
            return Redirect::to('admin')->send();
        }
    }
    public function index(){
    	return view('admin_login');
    }
    public function show_dashboard(){
       $cate= \App\categoryProduct::all();
       $brand = brand::all();
       $pro = product::all();
       $or = order::all();
        $this->AuthLogin();
    	return view('admin.dashboard',compact('cate','brand', 'pro','or'));
    }
    public function dashboard(Request $request){
    	$admin_email = $request->admin_email;
    	$admin_password = ($request->admin_password);

    	$result = DB::table('tbl_admin')->where('admin_email',$admin_email)->where('admin_password',$admin_password)->first();
    	if($result){
            Session::put('admin_name',$result->admin_name);
            Session::put('admin_id',$result->admin_id);
            return Redirect::to('/dashboard');
        }else{
            Session::put('message','đẹp trai nhưng sai mật khẩu ><');
            return Redirect::to('/admin');
        }

    }
    public function logout(){
        $this->AuthLogin();
        Session::put('admin_name',null);
        Session::put('admin_id',null);
        return Redirect::to('/admin');
    }
}
