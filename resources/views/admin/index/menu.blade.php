<div class="layui-side layui-bg-black x-side">
    <div class="layui-side-scroll">
        <ul class="layui-nav layui-nav-tree site-demo-nav" lay-filter="side">
            {{--<li class="layui-nav-user">--}}
            {{--<img src="images/a5.jpg"><br>--}}
            {{--<span>有格萨隆</span>--}}
            {{--</li> --}}

            <li class="layui-nav-item layui-nav-animation layui-nav-itemed">
                <a class="javascript:;" href="javascript:;" _href="{!! url(__ADMIN_PATH__.'/welcome') !!}">
                    <i class="fa fa-home"></i><cite>首页</cite>
                </a>
            </li>

            @if(!empty($MenuData))
                @foreach($MenuData as $k=>$menus)
            <li class="layui-nav-item layui-nav-animation">
                <a class="javascript:;" href="javascript:;">
                    <i class="fa fa-angle-right"></i>
                    <i class="fa {{$menus['icon']}}"></i><cite>{{$menus['display_name']}}</cite>
                </a>
                @if(isset($menus['child'])&&!empty($menus['child']))
                <dl class="layui-nav-child">
                    @foreach($menus['child'] as $k=>$child)
                    <dd class="count">
                        <a href="{!! URL::route($child['name']) !!}" _href="{!! URL::route($child['name']) !!}">
                            <cite>{{$child['display_name']}}</cite>
                        </a>
                    </dd>
                    @endforeach
                </dl>
                @endif
            </li>
                @endforeach
            @endif

        </ul>
    </div>

</div>
