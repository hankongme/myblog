@extends('admin.common.common')
@section('stylecss')
    <link rel="stylesheet" href="{{asset('styles/lib/dataTables/dataTables.bootstrap.css')}}" media="all">
    <link rel="stylesheet" href="{{asset('styles/lib/dataTables/dataTables.responsive.css')}}" media="all">
@endsection
@section('content')
    <main class="site-page">

        <div class="panel">
            <div class="panel-heading">
                <h3 class="panel-title">管理组列表</h3>
                <div class="panel-actions">

                    @can('admin.role.create')
                    <button type="button"  class="btn btn-primary" onclick="edit('添加管理员','{!! url(__ADMIN_PATH__.'/role/create') !!}')" >
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
                            <th data-field="id">ID</th>
                            <th data-field="name">管理组标识</th>
                            <th data-field="displayname">管理组名</th>
                            <th data-field="description">描述</th>
                            <th data-field="status">状态</th>
                            <th data-field="craeted_at">创建时间</th>
                            <th data-field="operate">操作</th>
                        </tr>

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
                                <td>{{$v['name']}}</td>
                                <td>{{$v['display_name']}}</td>
                                <td>{{$v['description']}}</td>
                                <td>{{$v['created_at']}}</td>
                                <td>{{$v['status_text']}}</td>
                                <td>
                                    @can('admin.role.edit')
                                    <a onclick="edit('编辑','{{url(__ADMIN_PATH__.'/role',['edit','id','id'=>$v['id']])}}')"
                                    >编辑</a>
                                    @endcan

                                    @can('admin.role.permission')
                                    <a onclick="edit('权限设置','{{url(__ADMIN_PATH__.'/role',['permission','id','id'=>$v['id']])}}')"
                                    >用户组权限</a>
                                    @endcan

                                    @can('admin.role.del')
                                    <a href="{{url(__ADMIN_PATH__.'/role',['del','id',$v['id']])}}"
                                       class="ajax-get confirm">删除</a>
                                    @endcan
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
                "info":  false,
                "bAutoWidth":true,
                scrollY:        "100%",
                scrollX:        true,
                scrollCollapse: true,
                paging:         false,
                fixedColumns:   {
                    leftColumns: 0,
                    rightColumns: 1
                }
            });

            $("[data-toggle='tooltip']").tooltip();

        });

    </script>
@endsection
