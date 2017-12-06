@extends('admin.common.common')
@section('stylecss')
    <link rel="stylesheet" href="{{asset('styles/lib/dataTables/dataTables.bootstrap.css')}}" media="all">
    <link rel="stylesheet" href="{{asset('styles/lib/dataTables/dataTables.responsive.css')}}" media="all">
@endsection
@section('content')


    <main class="site-page">

        <div class="panel">
            <div class="panel-heading">
                <h3 class="panel-title">管理员列表</h3>
                <div class="panel-actions">

                    @can('admin.adminuser.create')
                    <button type="button"  class="btn btn-primary" onclick="edit('添加管理员','{!! url(__ADMIN_PATH__.'/admin_user/create') !!}')" >
                        <i class="fa fa-plus" aria-hidden="true"></i>
                    </button>
                    @endcan

                </div>
            </div>
            <div class="panel-body nav-tabs-animate">
                    <table  class="table table-bordered table-hover dataTable table-striped width-full text-nowrap" id="dataTable"  data-paging="false" data-ordering="false" data-info="false">
                        <thead>

                        <tr>
                            <th>
                                <div class="checkbox-custom checkbox-primary">
                                    <input type="checkbox" id="table_checked">
                                    <label for="inputChecked"></label>
                                </div>
                            </th>
                            <th>ID</th>
                            <th>用户名</th>
                            <th>角色</th>
                            <th>创建时间</th>
                            <th>状态</th>
                            <th>操作</th>
                        </tr>


                        {{--<tr>--}}
                            {{--<td>--}}
                            {{--</td>--}}
                            {{--<td>--}}

                            {{--</td>--}}
                            {{--<td>--}}
                                {{--<input class="form-control search_form" name="name"  value="{{$search_form['name'] or ''}}" placeholder="姓名" autocomplete="off" type="text">--}}

                            {{--</td>--}}
                            {{--<td>--}}

                            {{--</td>--}}
                            {{--<td>--}}
                                {{--<input class="form-control date search_form" name="start_time" value="{{$search_form['start_time'] or ''}}" placeholder="开始时间" autocomplete="off" type="text">--}}
                                {{--<br/>--}}
                                {{--<input class="form-control date search_form" name="end_time"  value="{{$search_form['end_time'] or ''}}" placeholder="结束时间" autocomplete="off" type="text">--}}
                            {{--</td>--}}
                            {{--<td>--}}
                            {{--</td>--}}
                            {{--<td>--}}
                                {{--<button type="button" class="btn btn-primary" onclick="search_form();">查询</button>--}}
                            {{--</td>--}}
                        {{--</tr>--}}
                        </thead>


                        <tbody>
                        @foreach($data['data'] as $v)
                            <tr>
                                <td>
                                    <div class="checkbox-custom checkbox-primary">
                                        <input  type="checkbox" name="id[]">
                                        <label for="inputChecked"></label>
                                    </div>
                                </td>
                                <td>{{$v['id']}}</td>
                                <td>{{$v['account']}}</td>
                                <td>{{$v['truename']}}</td>
                                <td>{{$v['created_at']}}</td>
                                <td>{{$v['status_text']}}</td>
                                <td>
                                    @can('admin.adminuser.edit')
                                    <a onclick="edit('编辑','{{url(__ADMIN_PATH__.'/admin_user',['edit','id','id'=>$v['id']])}}')"
                                    >编辑</a>
                                    @endcan
                                    @can('admin.adminuser.repass')
                                    <a onclick="edit('修改密码','{{url(__ADMIN_PATH__.'/admin_user',['repass','id','id'=>$v['id']])}}')"
                                    >修改密码</a>
                                    @endcan


                                    @if($v['id']!==1)
                                        @can('admin.adminuser.group')
                                        <a onclick="edit('所属组','{{url(__ADMIN_PATH__.'/admin_user',['group','id','id'=>$v['id']])}}')"
                                        >所属组</a>
                                        @endcan
                                        @can('admin.adminuser.del')
                                        <a href="{{url(__ADMIN_PATH__.'/admin_user',['del','id',$v['id']])}}"
                                           class="ajax-get confirm">删除</a>
                                        @endcan
                                    @endif
                                </td>

                            </tr>
                        @endforeach

                        </tbody>
                    </table>

                    {{$page}}

            </div>
        </div>
    </main>


@endsection

@section('stylejs')

    <script src="{{asset('styles/lib/laydate/laydate.js')}}"></script>
    <script src="{{asset('styles/lib/dataTables/jquery.dataTables.js')}}"></script>
    <script src="{{asset('styles/lib/dataTables/dataTables.bootstrap.js')}}"></script>
    <script src="{{asset('styles/lib/dataTables/dataTables.responsive.min.js')}}"></script>

    <script>
        $(function () {
            $('#dataTable').DataTable({
                "paging":   false,
                "ordering": false,
                "searching": false,
                "info":     false,
                "bAutoWidth":true,
            });

            $("[data-toggle='tooltip']").tooltip();

        });

    </script>
@endsection
