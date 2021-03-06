@extends('admin_layout')
@section('admin_content')
    <script>
        $(function () {
            $('#brand').validate({
                rules:
                    {
                        brand_product_name:
                            {
                                required: true
                            },
                        brand_slug:
                            {
                                required: true
                            },
                        brand_product_desc:
                            {
                                required: true
                            }
                    },
                messages:
                    {
                        brand_product_name:
                            {
                                required: 'Không thể để trống trường này !!'

                            },
                        brand_slug:
                            {
                                required: 'Không thể để trống trường này !!'
                            },
                        brand_product_desc:
                            {
                                required: 'Không thể để trống trường này !!'
                            }
                    }

            })
        })
    </script>
    <div class="row">
        <div class="col-lg-12">
            <section class="panel">
                <header class="panel-heading">
                    Thêm thương hiệu sản phẩm
                </header>
                <?php
                $message = Session::get('message');
                if ($message) {
                    echo '<span class="text-alert">' . $message . '</span>';
                    Session::put('message', null);
                }
                ?>
                <div class="panel-body">

                    <div class="position-center">
                        <form id="brand" role="form" action="{{URL::to('/save-brand-product')}}" method="post">
                            {{ csrf_field() }}
                            <div class="form-group">
                                <label for="exampleInputEmail1">Tên thương hiệu</label>
                                <input type="text" name="brand_name" class="form-control"
                                       id="exampleInputEmail1" placeholder="Tên danh mục">
                            </div>
                            <div class="form-group">
                                <label for="exampleInputEmail1">Slug</label>
                                <input type="text" name="brand_slug" class="form-control" id="exampleInputEmail1"
                                       placeholder="Slug">
                            </div>
                            <div class="form-group">
                                <label for="exampleInputPassword1">Mô tả thương hiệu</label>
                                <textarea style="resize: none" rows="8" class="form-control" name="brand_desc"
                                          id="exampleInputPassword1" placeholder="Mô tả danh mục"></textarea>
                            </div>
                            <div class="form-group">
                                <label for="exampleInputPassword1">Hiển thị</label>
                                <select name="brand_status" class="form-control input-sm m-bot15">
                                    <option value="0">Hiển thị</option>
                                    <option value="1">Ẩn</option>

                                </select>
                            </div>

                            <button type="submit" name="add_category_product" class="btn btn-info">Thêm thương hiệu
                            </button>
                        </form>
                    </div>

                </div>
            </section>

        </div>
    </div>
@endsection
