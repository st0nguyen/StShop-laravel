<?php

namespace App\Http\Controllers;

use App\brand;
use App\Mail\ContactMail;
use App\product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\Session\Session;
use Illuminate\Support\Facades\Input;
use App\Http\Requests;
use Illuminate\Support\Facades\Redirect;
use App\User;
use Illuminate\Validation\ValidationException;
use Mail;
use Validator;
use App\Rules\Captcha;


session_start();

class HomeController extends Controller
{
    public function index()
    {

//        $cate_product = DB::table('tbl_category_product')
//            ->where('category_status', '0')->orderby('category_id', 'desc')
//            ->get();
//
//        $brand_product = DB::table('tbl_brand')->where('brand_status', '0')
//            ->orderby('brand_id', 'desc')->get();

        $cate_product = \App\categoryProduct::where('category_status', 0)->get();
        $brand_product = brand::where('brand_status', 0)->get();


        $all_product = DB::table('tbl_product')->where('product_status', '0')
            ->orderby('product_id', 'desc')->paginate(6);

        return view('pages.home')->with('category', $cate_product)
            ->with('brand', $brand_product)->with('all_product', $all_product);
    }

    public function search(Request $request)
    {

        $keywords = $request->keywords_submit;

        $cate_product = DB::table('tbl_category_product')
            ->where('category_status', '0')->orderby('category_id', 'desc')
            ->get();
        $brand_product = DB::table('tbl_brand')->where('brand_status', '0')
            ->orderby('brand_id', 'desc')->get();

//        $search_product = DB::table('tbl_product')
//            ->where('product_name', 'like', '%' . $keywords . '%')->get();
        $search_product = product::where('product_name', 'like', '%' . $keywords . '%')->get();

        return view('pages.sanpham.search')->with('category', $cate_product)
            ->with('brand', $brand_product)
            ->with('search_product', $search_product);

    }

    public function allProduct(){

        $cate_product = \App\categoryProduct::where('category_status', 0)->get();
        $brand_product = brand::where('brand_status', 0)->get();
        $all_product = DB::table('tbl_product')->where('product_status', '0')
            ->orderby('product_id', 'desc')->paginate(9);

        return view('pages.sanpham.all_product')->with('category', $cate_product)
            ->with('brand', $brand_product)->with('all_product', $all_product);

}

    public function getContact() {
        return view('pages.contact_us');
    }

    public function contactus(Request $request){
        try {
            $this->validate($request, [
                'name'                 => 'required',
                'email'                => 'required|email',
                'subject'              => 'required',
                'message'              => 'required',
                'g-recaptcha-response' => 'required|captcha',
            ]);
        } catch (ValidationException $e) {
        }

        $info=[];
        $info['name'] = $request->name;
        $info['email'] = $request->email;
        $info['subject'] = $request->subject;
        $info['message'] = $request->message;

        Mail::to('eshopper.infor@gmail.com')->send(new ContactMail($info));
        Session()->put('mess','Chúng tôi sẽ liện hệ lại sớm nhất, cảm ơn bạn đã sử dụng dịch vụ của E-Shopper! ');

        return redirect()->back();
    }
}
