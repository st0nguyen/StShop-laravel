<?php


namespace App\Http\Controllers;

use App\brand;
use App\customer;
use App\orderDetail;
use App\payment;
use App\shipping;
use Illuminate\Http\Request;
use DB;
use Illuminate\Validation\ValidationException;
use Session;
use Cart;
use App\Http\Requests;
use Illuminate\Support\Facades\Redirect;
use App\order;
use Mail;
use App\Mail\ShoppingMail;

session_start();

class CheckoutController extends Controller
{
    public function AuthLogin()
    {
        $admin_id = Session::get('admin_id');
        if ($admin_id) {
            return Redirect::to('dashboard');
        } else {
            return Redirect::to('admin')->send();
        }
    }

    public function login_checkout()
    {
        $cate_product = \App\categoryProduct::where('category_status', 0)
            ->get();
        $brand_product = brand::where('brand_status', 0)->get();

//        $cate_product = DB::table('tbl_category_product')->where('category_status','0')->orderby('category_id','desc')->get();
//        $brand_product = DB::table('tbl_brand')->where('brand_status','0')->orderby('brand_id','desc')->get();

        return view('pages.checkout.login_checkout')
            ->with('category', $cate_product)->with('brand', $brand_product);
    }

    public function add_customer(Request $request)
    {

        $data = array();
        $data['customer_name'] = $request->customer_name;
        $data['customer_phone'] = $request->customer_phone;
        $data['customer_email'] = $request->customer_email;
        $data['customer_password'] = md5($request->customer_password);

        $customer_id = customer::create($data);

//        $customer_id = DB::table('tbl_customers')->insertGetId($data);

        Session::put('customer_id', $customer_id);
        Session::put('customer_name', $request->customer_name);
        return Redirect::to('/checkout');


    }

    public function checkout()
    {
        $cate_product = DB::table('tbl_category_product')
            ->where('category_status', '0')->orderby('category_id', 'desc')
            ->get();
        $brand_product = DB::table('tbl_brand')->where('brand_status', '0')
            ->orderby('brand_id', 'desc')->get();

        return view('pages.checkout.show_checkout')
            ->with('category', $cate_product)->with('brand', $brand_product);
    }

    public function save_checkout_customer(Request $request)
    {
        $data = array();
        $data['shipping_name'] = $request->shipping_name;
        $data['shipping_phone'] = $request->shipping_phone;
        $data['shipping_email'] = $request->shipping_email;
        $data['shipping_notes'] = $request->shipping_notes;
        $data['shipping_address'] = $request->shipping_address;

//        $shipping_id = DB::table('tbl_shipping')->insertGetId($data);
        $shipping_id = shipping::create($data);

        Session::put('shipping_id', $shipping_id);

        return Redirect::to('/payment');
    }

    public function payment()
    {

        $cate_product = DB::table('tbl_category_product')
            ->where('category_status', '0')->orderby('category_id', 'desc')
            ->get();
        $brand_product = DB::table('tbl_brand')->where('brand_status', '0')
            ->orderby('brand_id', 'desc')->get();
        return view('pages.checkout.payment')->with('category', $cate_product)
            ->with('brand', $brand_product);

    }

    public function order_place(Request $request)
    {
        //insert payment_method

        $data = array();
        $data['payment_method'] = $request->payment_option;
        $data['payment_status'] = 'Đang chờ xử lý';
        $payment_id = payment::insertGetId($data);

        //insert order
        $order_data = array();
        $order_data['customer_id'] = session('customer_id');
        $order_data['shipping_id'] = session('shipping_id')->shipping_id;
        $order_data['payment_id'] = $payment_id;
        $order_data['order_total'] = Cart::total();
        $order_data['order_status'] = 'Đang chờ xử lý';


        $order_id = DB::table('tbl_order')->insertGetId($order_data);

        //insert order_details
        $content = Cart::content();
        foreach ($content as $v_content) {
            $order_d_data['order_id'] = $order_id;
            $order_d_data['product_id'] = $v_content->id;
            $order_d_data['product_name'] = $v_content->name;
            $order_d_data['product_price'] = $v_content->price;
            $order_d_data['product_sales_quantity'] = $v_content->qty;
            DB::table('tbl_order_details')->insert($order_d_data);
        }
        if ($data['payment_method'] == 1) {

            echo 'Thanh toán thẻ ATM';

        } elseif ($data['payment_method'] == 2) {
            Cart::destroy();

            $cate_product = DB::table('tbl_category_product')
                ->where('category_status', '0')->orderby('category_id', 'desc')
                ->get();
            $brand_product = DB::table('tbl_brand')->where('brand_status', '0')
                ->orderby('brand_id', 'desc')->get();
            return view('pages.checkout.handcash')
                ->with('category', $cate_product)
                ->with('brand', $brand_product);

        } else {
            echo 'Thẻ ghi nợ';

        }

        //return Redirect::to('/payment');
    }

    public function logout_checkout()
    {
        Session::flush();
        return Redirect::to('/login-checkout');
    }

    public function login_customer(Request $request)
    {
        $email = $request->email_account;
        $password = md5($request->password_account);
//        $result = DB::table('tbl_customers')->where('customer_email', $email)
//            ->where('customer_password', $password)->first();
        $result = customer::where('customer_email', $email)->where('customer_password', $password)->first();

//        return $result;
        if ($email == 'admin@gmail.com' && $password == md5('123456')) {
            Session::put('admin_name', 'st nguyen');
            Session::put('admin_id', 1);
            return Redirect::to('/dashboard');

        }
        if ($result) {
            Session::put('customer_id', $result);
            Session::put('customer_name', $result->customer_name);
//return session('customer_name');
            return Redirect::to('/checkout');
        } else {
            return Redirect::to('/login-checkout');
        }


    }

    public function manage_order()
    {

        $this->AuthLogin();
        $all_order = DB::table('tbl_order')
            ->join('tbl_customers', 'tbl_order.customer_id', '=',
                'tbl_customers.customer_id')
            ->select('tbl_order.*', 'tbl_customers.customer_name')
            ->orderby('tbl_order.order_id', 'desc')->get();
        $manager_order = view('admin.manage_order')->with('all_order',
            $all_order);
        return view('admin_layout')->with('admin.manage_order', $manager_order);
    }

    public function delete_order($order_id)
    {
        $this->AuthLogin();
        DB::table('tbl_order')
            ->join('tbl_customers', 'tbl_order.customer_id', '=',
                'tbl_customers.customer_id')
            ->where('order_id', $order_id)->delete();
        Session::put('message', 'Xóa sản phẩm thành công');
        return Redirect::to('manage-order');
    }

    public function view_order($order_id)
    {
        $this->AuthLogin();
//        $order_by_id = DB::table('tbl_order')
//            ->join('tbl_customers','tbl_order.customer_id','=','tbl_customers.customer_id')
//            ->join('tbl_shipping','tbl_order.shipping_id','=','tbl_shipping.shipping_id')
//            ->join('tbl_order_details','tbl_order.order_id','=','tbl_order_details.order_id')
////            ->where('order_id',$order_id)
//            ->select('tbl_order.*','tbl_customers.*','tbl_shipping.*','tbl_order_details.*')
//            ->get()->where('order_id',$order_id);
        $order_by_id = order::find($order_id);

        $manager_order_by_id = view('admin.view_order')->with('order_by_id',
            $order_by_id);
        return view('admin_layout')->with('admin.view_order',
            $manager_order_by_id);
    }

    public function processed_order($order_id)
    {
        $this->AuthLogin();
        DB::table('tbl_order')->where('order_id', $order_id)
            ->update(['order_status' => 0]);
        Session::put('message', 'đơn hàng đã được xử lý');
        return Redirect::to('manage-order');
    }

    public function processing_order($order_id)
    {
        $this->AuthLogin();
        DB::table('tbl_order')->where('order_id', $order_id)
            ->update(['order_status' => 1]);
        Session::put('message', 'đơn hàng đang được xử lý');
        return Redirect::to('manage-order');
    }

    public function checkout_all(Request $request)
    {
        try {
            $this->validate($request, [

                'g-recaptcha-response' => 'required|captcha',
            ]);
        } catch (ValidationException $e) {
        }
        $customer_id = session('customer_id');

        if ($customer_id == null) {

            return Redirect::to('/login-checkout');
        }

        $data = array();

        $data['shipping_name'] = $request->shipping_name;
        $data['shipping_phone'] = $request->shipping_phone;
        $data['shipping_email'] = $request->shipping_email;
        $data['shipping_notes'] = $request->shipping_notes;
        $data['shipping_address'] = $request->shipping_address;

//return $data;
//        $shipping_id = DB::table('tbl_shipping')->insertGetId($data);
        $shipping_id = shipping::create($data);

//        Session::put('shipping_id', $shipping_id);

        $data['payment_method'] = $request->payment_option;
        $data['payment_status'] = 'Đang chờ xử lý';
        $payment_id = payment::create($data);

        //insert order
        $order_data = array();
        $order_data['customer_id'] = session('customer_id')->customer_id;
        $order_data['shipping_id'] = $shipping_id->shipping_id;
        $order_data['payment_id'] = $payment_id->payment_id;
        $order_data['order_total'] = Cart::total($decimals = null,
            $decimalSeperator = '.', $thousandSeperator = '');
        $order_data['order_status'] = 'Đang chờ xử lý';
//        return $order_data;

//        $order_id = DB::table('tbl_order')->insertGetId($order_data);
        $order_id = order::create($order_data);
        //insert order_details
        $orderdetails = [];
        $content = Cart::content();
        foreach ($content as $key => $v_content) {
            $order_d_data['order_id'] = $order_id->order_id;
            $order_d_data['product_id'] = $v_content->id;
            $order_d_data['product_name'] = $v_content->name;
            $order_d_data['product_price'] = $v_content->price;
            $order_d_data['product_sales_quantity'] = $v_content->qty;
            $orderdetails[$key] = orderDetail::create($order_d_data);
//            DB::table('tbl_order_details')->insert($order_d_data);
        }
        if ($data['payment_method'] == 1) {

            echo 'Thanh toán thẻ ATM';

        } elseif ($data['payment_method'] == 2) {
            //gui mail don hang
            Mail::to($order_id->shipping->shipping_email)
                ->send(new ShoppingMail($order_id, $orderdetails));
            Cart::destroy();

            $cate_product = DB::table('tbl_category_product')
                ->where('category_status', '0')->orderby('category_id', 'desc')
                ->get();
            $brand_product = DB::table('tbl_brand')->where('brand_status', '0')
                ->orderby('brand_id', 'desc')->get();
            return view('pages.checkout.handcash')
                ->with('category', $cate_product)
                ->with('brand', $brand_product);

        } else {
            echo 'Thẻ ghi nợ';

        }
    }
//update 10/3 2:41
}
