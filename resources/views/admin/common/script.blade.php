<script type="text/javascript">
    (function () {
        var DCNETPHP = window.DCNET = {
            "ROOT": "{{url('')}}", //当前网站地址
            "APP": "/", //当前项目地址
            "PUBLIC": "/", //项目公共目录地址
            "DEEP": "/", //PATHINFO分割符
            "MODEL": ["1", "false", "html"],
            "VAR": ["m", "c", "a"]
        };
        $('#table_checked').click(function () {
            $("#dataTable").find("input[name='id[]']").prop(
                    "checked", $(this).prop("checked"));
        });

        $(".format_radio").each(function(){
            var wrap_html = '<div class="radio-custom radio-primary"></div>';
            var radio_id = $(this).attr('id');
            var data_name = $(this).attr('data-name')?$(this).attr('data-name'):'选中';
            var after_html = radio_id?'<label for="'+radio_id+'">'+data_name+'</label>':'<label for="'+radio_id+'">'+data_name+'</label>';
            $(this).wrap(wrap_html);
            $(this).after(after_html);
        })

        $(".format_checkbox").each(function(){
            var wrap_html = '<div class="checkbox-custom checkbox-primary"></div>';
            var check_box_id = $(this).attr('id');
            var data_name = $(this).attr('data-name')?$(this).attr('data-name'):'选中';
            var after_html = check_box_id?'<label for="'+check_box_id+'">'+data_name+'</label>':'<label for="'+check_box_id+'">'+data_name+'</label>';
            $(this).wrap(wrap_html);
            $(this).after(after_html);
        })

    })();

    //图片上传 初始化
    var BASE_URL = '{{asset('styles/lib/webuploader')}}';
    var SERVER_URL = '{!! url(__ADMIN_PATH__.'/tool/upload_image') !!}';

    function msg_success(data, time) {

        if (!time) {
            time = 1000;
        } else {
            time = time * 1000;
        }

        layer.msg(data, {icon: 6, time: time});
    }

    function msg_error(data, time) {
        if (!time) {
            time = 1000;
        } else {
            time = time * 1000;
        }
        layer.msg(data, {icon: 5, time: time});
    }

    /**
     * 消息停留关闭
     * */
    function msg_close(waittime) {
        if (!waittime) {
            waittime = 2;
        }
        var interval = setInterval(function () {
            var time = --waittime;
            if (time <= 0) {
                layer_close();
                clearInterval(interval);
            }
            ;
        }, 1000);
    }


    //消息确认
    function msg_confirm(msg,success) {
        if (!msg) {
            msg = '确认执行操作么?';
        }
        layer.confirm(msg, {
            btn: ['确认', '取消'] //按钮
        }, function () {
            return success();
        }, function () {
        });
    }


    function ajaxload(url, waittime) {
        if (!waittime) {
            waittime = 2;
        }

        if (!url) {
            url = "{{url('')}}";
        }

        var interval = setInterval(function () {
            var time = --waittime;
            if (time <= 0) {
                location.href = url;
                clearInterval(interval);
            }
            ;
        }, 1000);
    }

    function edit(title, url, w, h) {
        layer_show(title, url, w, h);
    }

    //不能用submit，有冲突
    function submit_form(url) {

        if (!url) {
            url = $("#submitform").attr('action');
        }

        $.ajax({
            cache: true,
            type: "POST",
            url: url,
            data: $('#submitform').serialize(),
            async: false,
            error: function (request) {
                msg_error('网络错误,请稍后重试!');

            },
            success: function (data) {
                if (data.status == 0) {

                    var not_parent = $("input[name='not_parent']").val();
                    if (not_parent) {
                        msg_success(data.return);
                        if(data.url){
                            ajaxload(data.url);
                        }else{
                            ajaxload(window.location.href);
                        }
                    } else {
                        msg_success(data.return);
                        if(data.url){
                            ajaxload(data.url);
                        }else{
                            parent.ajaxload(parent.window.location.href);

                            parent.layer.close();
                        }
                    }
                } else {
                    msg_error(data.return);
                    if(data.url){
                        ajaxload(data.url);
                    }
                }
            }
        });

    }


    function datadel(url) {

        if (!confirm('确定要全部删除吗？')) {
            return false;
        }
        var ids = '';
        if (!url) {
            var url = "{{url('delall')}}";
        }
        $("input[name='id[]']:checked").each(function () {

            if ($(this).attr('data-id')) {
                // alert($(this).attr('data-id'));
                ids = ids + $(this).attr('data-id') + ',';
            } else {
                var temp_id = $(this).parent().parent().attr('data-id');
                ids = ids + temp_id + ',';
            }

        });


        if (!ids) {
            alert('请选择需要删除的信息!');
            return;
        }

        $.ajax({
            cache: true,
            type: "POST",
            url: url,
            data: {id: ids, _token: '{!! csrf_token() !!}'},// 你的formid
            async: false,
            error: function (request) {
                msg_error('网络错误,请稍后重试!');
            },
            success: function (data) {
                if (data.status == 0) {
                    msg_success(data.return);
                    ajaxload("");
                    layer.close(index);
                } else {
                    msg_error(data.return);
                }
            }
        });

    }


    /**
     * 搜索字段
     */
    function search_form() {
        var search_input = $('.search_form');
        var search_form = $("<form></form>");
        search_form.attr('action', window.location.pathname);
        search_form.attr('method', 'get');
        search_form.append(search_input);
        search_form.appendTo("body");
        search_form.css("display", "none");
        search_form.submit();
    }


</script>


<script src="{{asset('styles/js/common/dcnet.js')}}"></script>
<script src="{{asset('styles/js/common/common.js')}}"></script>
<script src="{{asset('styles/js/common/dcnetui.js')}}"></script>

