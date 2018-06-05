/** 
 * javascript comment 
 * @Author: flydreame 
 * @Date: 2018-05-25 16:44:06 
 * @Desc:  
 */
function formSend(url, data) {
    //发送ajax请求
    $.ajax({
        url: url,
        async: false,//同步，会阻塞操作
        type: 'post',//PUT DELETE POST
        data: data,
        complete: function (msg) {
            //console.log('完成了');
        },
        success: function (result) {
            //console.log(result);
            if (result.code == 200) {
                $('.modal').modal('hide');
                layer.msg(result.msg, { icon: 1 });
                //location.reload();
                setTimeout(function () {//两秒后刷新  
                    location.reload();
                }, 2000);
            } else if (result.code == 201) {
                layer.msg(result.msg, { icon: 5 });
            } else {
                layer.msg(result.msg, { icon: 2 });
            }
        }, error: function () {
            layer.msg('网络错误', { icon: 5 });
        }
    })
}

// function getTree(id) {
//     $.ajax({
//         url: "/admin/auth_group/getAuth",
//         async: false,//同步，会阻塞操作
//         type: 'get',//PUT DELETE POST
//         data:{id:id},
//         success: function (result) {
//             if (result.code ==200) {
//                 return result.data;
//             }else{
//                 layer.msg(result.msg,{icon:5});
//             }
//         }
//     });
// }
//文件上传
function uploadImg(url){
    $("input[name='link']").attr('value','');
    $('.uploaderImg').uploader({
        autoUpload: true,            // 当选择文件后立即自动进行上传操作
        url: url, // 文件上传提交地址
        responseHandler: function (responseObject, file) {
            var res = $.parseJSON(responseObject.response);
            $("input[name='link']").attr('value', res.url);
            // 当服务器返回的文本内容包含 `'error'` 文本时视为上传失败
            // if (responseObject.response.indexOf('error')) {
            //     return '上传失败。服务器返回了一个错误：' + responseObject.response;
            // }
        }
    });
}
