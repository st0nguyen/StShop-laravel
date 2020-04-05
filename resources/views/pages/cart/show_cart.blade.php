@extends('layout')
@section('content')

    <section id="cart_items">
        <div class="container">
            <div class="breadcrumbs">
                <ol class="breadcrumb">
                    <li><a href="{{URL::to('/')}}">Trang chủ</a></li>
                    <li class="active">Giỏ hàng của bạn</li>
                </ol>
            </div>
            <div class="table-responsive cart_info" style="width: 900px;">
                <?php
                $content = Cart::content();

                ?>
                <table class="table table-condensed">
                    <thead>
                    <tr class="cart_menu">
                        <td class="image">Hình ảnh</td>
                        <td class="description">Tên sp</td>
                        <td class="price">Giá</td>
                        <td class="quantity">Số lượng</td>
                        <td class="total">Tổng</td>
                        <td></td>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($content as $v_content)
                        <tr>
                            <td class="cart_product">
                                <a href="">
                                    <img src="{{ 'data:image/jpeg;base64,'.$v_content->options->image }}" width="90"
                                         alt=""/>
                                </a>
                            </td>
                            <td class="cart_description">
                                <h4><a href="">{{$v_content->name}}</a></h4>
                                <p>Web ID: {{$v_content->id}}</p>
                            </td>
                            <td class="cart_price">
                                <p>{{number_format($v_content->price).' '.'vnđ'}}</p>
                            </td>
                            <td class="cart_quantity">
                                <div class="cart_quantity_button">
                                    <form action="{{URL::to('/update-cart-quantity')}}" method="POST">
                                        {{ csrf_field() }}
                                        <input class="cart_quantity_input" type="number" min="1" name="cart_quantity"
                                               value="{{$v_content->qty}}" style="width: 50px">
                                        <input type="hidden" value="{{$v_content->rowId}}" name="rowId_cart"
                                               class="form-control">
                                        <input type="submit" value="Cập nhật" name="update_qty"
                                               class="btn btn-default btn-sm">
                                    </form>
                                </div>
                            </td>
                            <td class="cart_total">
                                <p class="cart_total_price">

                                    <?php
                                    $subtotal = $v_content->price * $v_content->qty;
                                    echo number_format($subtotal) . ' ' . 'vnđ';
                                    ?>
                                </p>
                            </td>
                            <td class="cart_delete">
                                <a class="cart_quantity_delete"
                                   href="{{URL::to('/delete-to-cart/'.$v_content->rowId)}}"><i class="fa fa-times"></i></a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </section> <!--/#cart_items-->

    <section id="do_action">

        <div class="container">

            <div class="row">
                <div class="col-sm-3">
                    <?php
                    $customer_id = Session::get('customer_id');
                    if($customer_id != NULL){
                    ?>

                    <a class="btn btn-default check_out" href="{{URL::to('/checkout')}}">Thanh toán</a>
                    <?php
                    }else{
                    ?>

                    <a class="btn btn-default check_out" href="{{URL::to('/login-checkout')}}">Thanh toán</a>
                    <?php
                    }
                    ?>

                    <div class="total_area">
                        <ul>
                            <li>Tổng <span>{{Cart::total().' '.'vnđ'}}</span></li>
                            <li>Thuế <span>{{Cart::tax().' '.'vnđ'}}</span></li>
                            <li>Phí vận chuyển <span>Free</span></li>
                            <li>Thành tiền <span>{{Cart::total().' '.'vnđ'}}</span></li>
                        </ul>

                    </div>
                </div>

                <div class="col-sm-9 ">
                    <p class="btn btn-default check_out">Điền thông tin gửi hàng</p>
                    <div class="bill-to">

                        <div class="form-one">
                            <form action="{{URL::to('/checkout-all')}}" method="POST">
                                {{csrf_field()}}
                                <div >
                                    <input id="ca" type="text" name="shipping_email" placeholder="Email" data-validation="email" data-validation-error-msg="định dạng mail không chính xác !">
                                </div>
                                <div>
                                    <input id="ca" type="text" name="shipping_name" placeholder="Họ và tên" data-validation="required" data-validation-error-msg="vui lòng không để trống trường này">
                                </div>
                                <div>
                                    <input id="ca" type="text" name="shipping_address" placeholder="Địa chỉ" data-validation="required" data-validation-error-msg="vui lòng không để trống trường này">
                                </div>
                                <div>
                                    <input id="ca" type="text" name="shipping_phone" placeholder="Phone" data-validation="required" data-validation-error-msg="vui lòng không để trống trường này">
                                </div>
                                <div>
                                      <textarea name="shipping_notes" placeholder="Ghi chú đơn hàng của bạn"
                                                rows="16" data-validation="required" data-validation-error-msg="vui lòng không để trống trường này"></textarea>
                                </div>

                                <br>
                                <div class="payment-options" style="height: 0px;width: 200px;border-left-width: 10px;padding-top: 30px;">
                                    <span>
						<label><input name="payment_option" value="1" type="radio"> Trả bằng thẻ ATM</label>
					</span>
                                    <span>
						<label><input name="payment_option" value="2" type="radio" checked> Nhận tiền mặt</label>
					</span>
                                    <span>
						<label><input name="payment_option" value="3" type="radio"> Thanh toán thẻ ghi nợ</label>
					</span>

                                </div>

                                <div class="form-group row">
                                    <div class="col-md-6 offset-md-4" style="">
                                        <div class="g-recaptcha" data-sitekey="6LcO0OEUAAAAAPnuhclJi3IkCZDPGO7j8FQRU3KP">


                                            @if($errors->has('g-recaptcha-response'))
                                                <span class="invalid-feedback" style="display:block">
                                        <strong>{{$errors->first('g-recaptcha-response')}}</strong>
                                    </span>
                                            @endif
                                        </div>
                                    </div>

                                </div>

                                <input type="submit" value="Đặt hàng" name="send_order_place"
                                       class="btn btn-primary btn-sm" style="width: 350px;height: 35px;">
                            </form>
                        </div>

                    </div>
                </div>
            </div>

        </div>

    </section><!--/#do_action-->






@endsection
<script src='https://www.google.com/recaptcha/api.js'></script>
