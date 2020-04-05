@extends('admin_layout')
@section('admin_content')
    <div class="table-agile-info">
        <div class="panel panel-default">
            <div class="panel-heading">
                Liệt kê thương hiệu sản phẩm
                <button type="button"  class="btn btn-success" onclick="brand.openAddEditBrand()"><i class="fa fa-plus"></i>
                </button>
            </div>
            <div class="row w3-res-tb">

                <div class="col-sm-4">
                </div>
                <div class="col-sm-3">

                </div>
            </div>
            <div class="table-responsive">
                <?php
                $message = Session::get('message');
                if ($message) {
                    echo '<span class="text-alert">' . $message . '</span>';
                    Session::put('message', null);
                }
                ?>
                <table class="table table-striped b-t b-light">
                    <thead>
                    <tr>
                        <th>
                            #
                        </th>
                        <th>Tên thương hiệu</th>
                        <th>Brand Slug</th>
                        <th>Hiển thị</th>

                        <th style="width:30px;">action</th>
                    </tr>
                    </thead>
                    <tbody id="tbBrand">

                    </tbody>
                </table>
            </div>
{{--            <footer class="panel-footer">--}}
{{--                <div class="row">--}}

{{--                    <div class="col-sm-5 text-center">--}}
{{--                        <small class="text-muted inline m-t-sm m-b-sm">showing 20-30 of 50 items</small>--}}
{{--                    </div>--}}
{{--                    <div class="col-sm-7 text-right text-center-xs">--}}
{{--                        <ul class="pagination pagination-sm m-t-none m-b-none">--}}
{{--                            <li><a href=""><i class="fa fa-chevron-left"></i></a></li>--}}
{{--                            <li><a href="">1</a></li>--}}
{{--                            <li><a href="">2</a></li>--}}
{{--                            <li><a href="">3</a></li>--}}
{{--                            <li><a href="">4</a></li>--}}
{{--                            <li><a href=""><i class="fa fa-chevron-right"></i></a></li>--}}
{{--                        </ul>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </footer>--}}
        </div>
    </div>
    <div id="addEditBrand" class="modal fade" role="dialog">
        <div class="modal-dialog modal-lg">
            <form id="frmAddEditBrand" enctype="multipart/form-data">
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Create New User</h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <input hidden id="Id" name="Id">
                    <div class="modal-body">
                        <div style="text-align:center">
                            <div class="form-group">
                                <label for="exampleInputEmail1">Tên thương hiệu</label>
                                <input type="text" id='brand_name' name="brand_name" class="form-control"
                                       id="exampleInputEmail1" placeholder="Tên danh mục">
                            </div>
                            <div class="form-group">
                                <label for="exampleInputEmail1">Slug</label>
                                <input type="text" id="brand_slug" name="brand_slug" class="form-control" id="exampleInputEmail1"
                                       placeholder="Slug">
                            </div>
                            <div class="form-group">
                                <label for="exampleInputPassword1">Mô tả thương hiệu</label>
                                <textarea style="resize: none" rows="8" class="form-control" name="brand_desc" id="brand_desc"
                                          id="exampleInputPassword1" placeholder="Mô tả danh mục"></textarea>
                            </div>
                            <div class="form-group">
                                <label for="exampleInputPassword1">Hiển thị</label>
                                <select name="brand_status"  id="brand_status" class="form-control input-sm m-bot15">
                                    <option value="0">Hiển thị</option>
                                    <option value="1">Ẩn</option>

                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-info" data-dismiss="modal">Close</button>
                        <a href="javascript:void(0);" class="btn btn-danger" onclick="brand.save()">Save</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <script src="{{ asset('js/brands.js') }}"></script>
@endsection



