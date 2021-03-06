var Script = function () {

    jQuery('.sidebar .item.vertical > a').click(function () {
        var ver = jQuery(this).next();
        if (ver.is(":visible")) {
            jQuery(this).parent().removeClass("open");
            ver.slideUp(200);
        } else {
            jQuery(this).parent().addClass("open");
            ver.slideDown(200);
        }
    });

    $(function () {
        function responsiveView() {
            var wSize = $(window).width();
            if (wSize <= 768) {
                $('.main').addClass('sidebar-close');
                $('.sidebar .sidebar-menu').hide();
            }

            if (wSize > 768) {
                $('.main').removeClass('sidebar-close');
                $('.sidebar .sidebar-menu').show();
            }
        }
        $(window).on('load', responsiveView);
        $(window).on('resize', responsiveView);
    });

    $('.sidebar-toggle').click(function () {
        if ($('.sidebar .sidebar-menu').is(":visible") === true) {
            $('.main-content').css({
                'margin-left': '0px'
            });
            $('.sidebar').css({
                'margin-left': '-180px'
            });
            $('.sidebar .sidebar-menu').hide();
            $(".main").addClass("sidebar-closed");
        } else {
            $('.main-content').css({
                'margin-left': '180px'
            });
            $('.sidebar .sidebar-menu').show();
            $('.sidebar').css({
                'margin-left': '0'
            });
            $(".main").removeClass("sidebar-closed");
        }
    });

    /**
     * 后台侧边菜单选中状态
     */
    //console.log(window.location.pathname + window.location.search);
    $('.item').find('a').removeClass('active');
    $('.sidebar-menu').find('a[href*="' + GlobalUrl.current_controller + '"]').addClass('active').parents('.item').addClass('open active');


    //统一添加数据
    $("#addSubmit").click(function () {
        formSend('/' + GlobalUrl.current_controller + 'save', $('#addForm').serialize());
    });

    //$("#btnEdit").click()

    //统一修改数据
    $("#editSubmit").click(function () {
        formSend('/' + GlobalUrl.current_controller + 'update', $('#editForm').serialize());
    });

    //统一删除
    $('.btn-delete').click(function () {
        var data = { id: $(this).attr('data-id') };
        //console.log(data);
        //询问框
        layer.confirm('确定要删除吗', {
            btn: ['确定', '取消'] //按钮
        }, function () {
            formSend('delete', data);
        }, function () {
            layer.close();
        });

    });

    //批量删除
    $(".btn-batch-remove").click(function () {
        var ids = new Array();
        //console.log(e);
        $("input[name='BatchRemove']:checked").each(function () {
            ids.push($(this).attr('data-id'));
        });
        layer.confirm('确定要删除吗', {
            btn: ['确定', '取消'] //按钮
        }, function () {
            var data = { id: ids };
            formSend('delete', data);
        }, function () {
            layer.close();
        });
    });

    //模态框内容重置    
    $('.form-modal').on('hide.zui.modal', function () {
        $(this).find('form')[0].reset();
    });


    //文件上传
    $('#uploaderThumb').uploader({
        autoUpload: true,            // 当选择文件后立即自动进行上传操作
        url: '/admin/article/uploaderThumb', // 文件上传提交地址
        responseHandler: function (responseObject, file) {
            var res = $.parseJSON(responseObject.response);
            $("#thumb").attr('value', res.url);

            // 当服务器返回的文本内容包含 `'error'` 文本时视为上传失败
            // if (responseObject.response.indexOf('error')) {
            //     return '上传失败。服务器返回了一个错误：' + responseObject.response;
            // }
        }
    });


    // 选择时间和日期
    $(".form-datetime").datetimepicker(
        {
            weekStart: 1,
            todayBtn: 1,
            autoclose: 1,
            todayHighlight: 1,
            startView: 2,
            forceParse: 0,
            showMeridian: 1,
            format: "yyyy-mm-dd hh:ii"
        });
}();