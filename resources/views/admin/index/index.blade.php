<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>
        管理后台
    </title>
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon" />
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="format-detection" content="telephone=no">
    <link rel="stylesheet" href="{{asset('styles/lib/layui/css/layui.css')}}" media="all">
    <link rel="stylesheet" href="{{asset('styles/css/admin.css')}}" media="all">
    <link rel="stylesheet" href="{{asset('styles/css/font-awesome-4.7.0/css/font-awesome.css')}}" media="all">
</head>

<body>
<div class="layui-layout layui-layout-admin">
    <div class="layui-header header header-demo">
        <div class="layui-main">
            <a class="logo" href="#">
                <span>后台系统</span>
            </a>
            <div class="x-slide_left">
                <i class="fa fa-arrow-left"></i>
                <i class="fa fa-list"></i>
            </div>
            <ul class="layui-nav" lay-filter="">
                <li class="layui-nav-item layui-nav-item2"><i class="fa fa-bell"></i></li>
                <li class="layui-nav-item layui-nav-item2">
                    <i class="fa fa-user"></i>
                    <dl class="layui-nav-child">
                        <!-- 二级菜单 -->
                        <dd><a href="{!! url(__ADMIN_PATH__.'/logout') !!}">切换帐号</a></dd>
                        <dd><a href="{!! url(__ADMIN_PATH__.'/logout') !!}">退出</a></dd>
                    </dl>
                </li>
                <!-- <li class="layui-nav-item">
                  <a href="" title="消息">
                      <i class="layui-icon" style="top: 1px;">&#xe63a;</i>
                  </a>
                  </li> -->
                <li class="layui-nav-item  layui-nav-item2 x-index" onclick="signOut()"><i class="fa fa-sign-out"></i></li>
            </ul>
        </div>
    </div>

    <!--菜单s-->
    @include('admin.index.menu')
    <!--菜单e-->


    <div class="layui-tab layui-tab-card site-demo-title x-main" lay-filter="x-tab" lay-allowclose="true">
        <button class="roll-nav roll-left J_tabLeft"><i class="fa fa-backward"></i>
        </button>
        <ul class="layui-tab-title">
            <div class="dv1">
                <li class="layui-this">
                    首页
                    <i class="layui-icon layui-unselect layui-tab-close">x</i>
                </li>
            </div>
        </ul>
        <button class="roll-nav roll-right J_tabRight"><i class="fa fa-forward"></i>
        </button>
        <button class="dropdown J_tabClose">
            <span>关闭所有</span>
        </button>
        <div class="layui-tab-content site-demo site-demo-body">
            <div class="layui-tab-item layui-show">
                <iframe width="100%" height="100%" frameborder="0" src="{!! url(__ADMIN_PATH__.'/welcome') !!}" class="x-iframe">
                </iframe>
            </div>
        </div>
    </div>
    <div class="site-mobile-shade">
    </div>
</div>


<script src="{{asset('styles/lib/layui/layui.js')}}"></script>
<script src="{{asset('styles/js/admin-layui.js')}}"></script>
<script src="{{asset('styles/js/admin.js')}}"></script>
<script src="{{asset('styles/js/jquery.min.js')}}"></script>
<script src="{{asset('styles/js/contabs.min.js')}}"></script>

</body>
<script type="text/javascript">
    $(document).ready(function(){
        var countLength=$('.count').length;
        for(var i=0;i<countLength;i++){
            $($('.count')[i]).attr('idx',i);
        }
    });

    function signOut() {
        var url = "{!! url(__ADMIN_PATH__.'/logout') !!}";
        window.location.href=url;
    }
</script>
</html>
