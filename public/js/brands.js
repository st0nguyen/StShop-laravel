var brand = brand || {};
brand.drawTable = function(){
    $.ajax({
        url:'api/brand',
        method : 'GET',
        dataType : 'json',
        success : function(data){
            $('#tbBrand').empty();
            $.each(data, function(index, value){
                let i = parseInt(index) + 1;
                $('#tbBrand').append(
                    "<tr>"+
                    "<td>" + i++ + "</td>" +
                    "<td>" + value.brand_name + "</td>" +
                    "<td>" + value.brand_slug + "</td>" +
                    "<td>" + value.brand_status + "</td>" +
                    "<td>" +
                    "<a href='javascript:;' onclick=brand.getDetail(" + value.brand_id + ")><i class='fa fa-edit'></i></a> " +
                    "<a href='javascript:;' onclick=brand.delete(" + value.brand_id + ")><i class='fa fa-trash'></i></a>" +
                    "</td>" +
                    "</tr>"
                );
            });
        }
    });
};
brand.save = function(){
    if($('#frmAddEditBrand').valid()){
        var dataObj = {};
        if($('#Id').val() == 0){
            dataObj.brand_name = $('#brand_name').val();
            dataObj.brand_slug = $('#brand_slug').val();
            dataObj.brand_desc = $('#brand_desc').val();
            dataObj.brand_status = $('#brand_status').val();
            $.ajax({
                url: 'api/brand',
                method: 'POST',
                data: JSON.stringify(dataObj),
                dataType: 'json',
                contentType: 'application/json',
                success: function (data) {
                    $('#addEditBrand').modal('hide');
                    alert('xong cmmr')
                    brand.drawTable();
                }
            });
        }else{
            dataObj.brand_name = $('#brand_name').val();
            dataObj.brand_slug = $('#brand_slug').val();
            dataObj.brand_desc = $('#brand_desc').val();
            dataObj.brand_status = $('#brand_status').val();
            dataObj.id = $('#Id').val();
            $.ajax({
                url: '/api/admin/sanpham/' + dataObj.id,
                method: 'PUT',
                data: JSON.stringify(dataObj),
                dataType: 'json',
                contentType: 'application/json',
                success: function (data) {
                    $('#addEditModel').modal('hide');
                    brand.drawTable();
                }
            });
        }
    }
};

brand.resetForm = function () {
    $('#brand_name').val();
    $('#brand_slug').val();
    $('#brand_desc').val();
    $('#brand_status').val();
    $('#Id').val(0);
    $("#frmAddEditBrand").validate().resetForm();
};

brand.openAddEditBrand = function(){
    brand.resetForm();
    $('#addEditBrand').modal('show');
};

brand.getDetail = function (id) {
    brand.resetForm();
    $.ajax({
        url: '/api/admin/sanpham/' + id,
        method: 'GET',
        dataType: 'json',
        contentType: 'application/json',
        success: function (data) {
            $('#productName').val(data.productName);
            $('#productLineId').val(data.productLineId);
            $('#productScale').val(data.productScale);
            $('#productVendor').val(data.productVendor);
            $('#productDescription').val(data.productDescription);
            $('#quantityInStock').val(data.quantityInStock);
            $('#buyPrice').val(data.buyPrice);
            $('#MSRP').val(data.MSRP);
            $('#image').val(data.image);
            $('#Id').val(data.id);
            $('#addEditUser').find('.modal-title').text('Update User');
            $('#addEditUser').modal('show');
        }
    });
};

brand.delete = function (id) {
    bootbox.confirm({
        message: "Mày thật sự ko muốn bán nó nữa ?",
        buttons: {
            confirm: {
                label: 'Yessss',
                className: 'btn-success'
            },
            cancel: {
                label: 'No!!!',
                className: 'btn-danger'
            }
        },
        callback: function (result) {
            if (result) {
                $.ajax({
                    url: '/api/admin/sanpham/' + id,
                    method: 'DELETE',
                    dataType: 'json',
                    contentType: 'application/json',
                    success: function (data) {
                        product.drawTable();
                    }
                });
            }
        }
    });
};

brand.init =function () {
    brand.drawTable();
};

$(document).ready(function () {
    brand.init();
});
