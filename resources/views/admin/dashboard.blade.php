@extends('admin_layout')
@section('admin_content')
<h3>Chào mừng bạn đến với Admin</h3>
<br>
<div class="market-updates">
    <div class="col-md-3 market-update-gd">
        <div class="market-update-block clr-block-2">
            <div class="col-md-4 market-update-right">
                <i class="fa fa-eye"> </i>
            </div>
            <div class="col-md-8 market-update-left">
                <h4>Danh mục</h4>
                <h3>{{ $cate->count() }}</h3>
                <p>Other hand, we denounce</p>
            </div>
            <div class="clearfix"> </div>
        </div>
    </div>
    <div class="col-md-3 market-update-gd">
        <div class="market-update-block clr-block-1">
            <div class="col-md-4 market-update-right">
                <i class="fa fa-users" ></i>
            </div>
            <div class="col-md-8 market-update-left">
                <h4>Thương hiệu</h4>
                <h3>{{ $brand->count() }}</h3>
                <p>Other hand, we denounce</p>
            </div>
            <div class="clearfix"> </div>
        </div>
    </div>
    <div class="col-md-3 market-update-gd">
        <div class="market-update-block clr-block-3">
            <div class="col-md-4 market-update-right">
                <i class="fa fa-usd"></i>
            </div>
            <div class="col-md-8 market-update-left">
                <h4>Sản phẩm</h4>
                <h3>{{ $pro->count() }}</h3>
                <p>gogo</p>
            </div>
            <div class="clearfix"> </div>
        </div>
    </div>
    <div class="col-md-3 market-update-gd">
        <div class="market-update-block clr-block-4">
            <div class="col-md-4 market-update-right">
                <i class="fa fa-shopping-cart" aria-hidden="true"></i>
            </div>
            <div class="col-md-8 market-update-left">
                <h4>Đơn hàng</h4>
                <h3>{{ $or->count() }}</h3>
                <p>Other hand, we denounce</p>
            </div>
            <div class="clearfix"> </div>
        </div>
    </div>
    <div class="clearfix"> </div>
</div>
<div class="col-md-6 agile-calendar">
    <div class="calendar-widget">
        <div class="panel-heading ui-sortable-handle">
					<span class="panel-icon">
                      <i class="fa fa-calendar-o"></i>
                    </span>
            <span class="panel-title"> Calendar Widget</span>
        </div>
        <!-- grids -->
        <div class="agile-calendar-grid">
            <div class="page">

                <div class="w3l-calendar-left">
                    <div class="calendar-heading">

                    </div>
                    <div class="monthly" id="mycalendar"></div>
                </div>

                <div class="clearfix"> </div>
            </div>
        </div>
    </div>
</div>
@endsection
