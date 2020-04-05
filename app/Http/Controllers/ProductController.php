<?php

namespace App\Http\Controllers;

use App\brand;
use Illuminate\Http\Request;
use DB;
use Session;
use App\Http\Requests;
use Illuminate\Support\Facades\Redirect;
use App\product;
use Illuminate\Database\Eloquent\SoftDeletes;

session_start();
class   ProductController extends Controller
{
    public function AuthLogin(){
        $admin_id = Session::get('admin_id');
        if($admin_id){
            return Redirect::to('dashboard');
        }else{
            return Redirect::to('admin')->send();
        }
    }
    public function add_product(){
        $this->AuthLogin();
//        $cate_product = DB::table('tbl_category_product')->orderby('category_id','desc')->get();
//        $brand_product = DB::table('tbl_brand')->orderby('brand_id','desc')->get();
        $cate_product = \App\categoryProduct::all();
        $brand_product = brand::all();
        return view('admin.add_product')->with('cate_product', $cate_product)->with('brand_product',$brand_product);


    }
    public function all_product(){
        $this->AuthLogin();
//    	$all_product = DB::table('tbl_product')
//        ->join('tbl_category_product','tbl_category_product.category_id','=','tbl_product.category_id')
//        ->join('tbl_brand','tbl_brand.brand_id','=','tbl_product.brand_id')
//        ->orderby('tbl_product.product_id','desc')->get();
        $all_product = product::all()->sortByDesc('product_id');


    	$manager_product  = view('admin.all_product')->with('all_product',$all_product);
    	return view('admin_layout')->with('admin.all_product', $manager_product);

    }
    public function save_product(Request $request){
         $this->AuthLogin();

        $product = product::create($request->all());
        if ($request->hasFile('product_image')) {
            $image = base64_encode(file_get_contents($request->file('product_image')));
            $product->product_image = $image;
        }
        $product->save();
    	Session::put('message','Thêm sản phẩm thành công');
    	return Redirect::to('add-product');
    }
    public function unactive_product($product_id){
         $this->AuthLogin();
         product::find($product_id)->update(['product_status'=>1]);
//        DB::table('tbl_product')->where('product_id',$product_id)->update(['product_status'=>1]);
        Session::put('message','Không kích hoạt sản phẩm thành công');
        return Redirect::to('all-product');

    }
    public function active_product($product_id){
         $this->AuthLogin();
        product::find($product_id)->update(['product_status'=>0]);
        Session::put('message','Không kích hoạt sản phẩm thành công');
        return Redirect::to('all-product');
    }
    public function edit_product($product_id){
         $this->AuthLogin();
//        $cate_product = DB::table('tbl_category_product')->orderby('category_id','desc')->get();
//        $brand_product = DB::table('tbl_brand')->orderby('brand_id','desc')->get();
        $edit_product = product::findOrFail($product_id);
        $cate_product = \App\categoryProduct::all();
        $brand_product = brand::all();
//dd($edit_product);
//        $edit_product = DB::table('tbl_product')->where('product_id',$product_id)->get();

        $manager_product  = view('admin.edit_product')->with('edit_product',$edit_product)->with('cate_product',$cate_product)->with('brand_product',$brand_product);

        return view('admin_layout')->with('admin.edit_product', $manager_product);
    }
    public function update_product(Request $request,$product_id){
         $this->AuthLogin();
        $data = array();
        $data['product_name'] = $request->product_name;

        $data['product_slug'] = $request->product_slug;
        $data['product_price'] = $request->product_price;
        $data['product_desc'] = $request->product_desc;
        $data['product_content'] = $request->product_content;
        $data['category_id'] = $request->product_cate;
        $data['brand_id'] = $request->product_brand;
        $data['product_status'] = $request->product_status;
        $get_image = $request->file('product_image');

        if($get_image){
                    $get_name_image = $get_image->getClientOriginalName();
                    $name_image = current(explode('.',$get_name_image));
                    $new_image =  $name_image.rand(0,99).'.'.$get_image->getClientOriginalExtension();
                    $get_image->move('public/uploads/product',$new_image);
                    $data['product_image'] = $new_image;
                    DB::table('tbl_product')->where('product_id',$product_id)->update($data);
                    Session::put('message','Cập nhật sản phẩm thành công');
                    return Redirect::to('all-product');
        }

       product::find($product_id)->update($data);
        Session::put('message','Cập nhật sản phẩm thành công');
        return Redirect::to('all-product');
    }
    public function delete_product($product_id){
        $this->AuthLogin();
        $pro = product::find($product_id)->delete();

        Session::put('message','Xóa sản phẩm thành công');
        return Redirect::to('all-product');
    }

    public function deleted_product(){
        $this->AuthLogin();
        $pro = product::onlyTrashed()->get();
//        dd($pro);

        $manager_product  = view('admin.deleted_product')->with('all_product',$pro);
        return view('admin_layout')->with('admin.all_product', $manager_product);
}

    public function destroy_product($id){
        $this->AuthLogin();
        product::where('product_id',$id)->forceDelete();
        Session::put('message','Xóa danh mục sản phẩm thành công');
        return Redirect::to('deleted-product');
    }

    public function restore_product($id){
        $this->AuthLogin();
        $pro = product::withTrashed()
            ->where('product_id', $id)
            ->restore();
        Session::put('message','Phục hồi sản phẩm thành công');
        return Redirect::to('deleted-product');
    }


    //End Admin Page
    public function details_product($product_slug){
        $cate_product = DB::table('tbl_category_product')->where('category_status','0')->orderby('category_id','desc')->get();
        $brand_product = DB::table('tbl_brand')->where('brand_status','0')->orderby('brand_id','desc')->get();

        $details_product = DB::table('tbl_product')
        ->join('tbl_category_product','tbl_category_product.category_id','=','tbl_product.category_id')
        ->join('tbl_brand','tbl_brand.brand_id','=','tbl_product.brand_id')
        ->where('tbl_product.product_slug',$product_slug)->get();

        foreach($details_product as $key => $value){
            $category_id = $value->category_id;
        }


//        $related_product = DB::table('tbl_product')
//        ->join('tbl_category_product','tbl_category_product.category_id','=','tbl_product.category_id')
//        ->join('tbl_brand','tbl_brand.brand_id','=','tbl_product.brand_id')
//        ->where('tbl_category_product.category_id',$category_id)->whereNotIn('tbl_product.product_slug',[$product_slug])->get();

        $related_product = product::where('category_id', $category_id)->whereNotIn('product_slug',[$product_slug])->get();

        return view('pages.sanpham.show_details')->with('category',$cate_product)->with('brand',$brand_product)->with('product_details',$details_product)->with('relate',$related_product);

    }
}
