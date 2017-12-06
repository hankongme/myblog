@extends('admin.common.common')
@section('stylecss')
    <link rel="stylesheet" href="{{asset('styles/lib/dataTables/dataTables.bootstrap.css')}}" media="all">
    <link rel="stylesheet" href="{{asset('styles/lib/dataTables/dataTables.responsive.css')}}" media="all">

@endsection
@section('content')


    <main class="site-page">

        <div class="panel">
            <div class="panel-heading">
                <h3 class="panel-title">权限列表</h3>


                <div class="panel-actions">
                    @if(isset($data['cid'])&&$data['cid']!=0)
                        <button type="button"  class="btn btn-primary" onclick="javascript:history.go(-1);">
                            <i class="fa fa-level-up" aria-hidden="true"></i>
                        </button>
                    @endif

                    @can('admin.adminuser.create')
                    <button type="button"  class="btn btn-primary" onclick="edit('添加','{{url(__ADMIN_PATH__.'/permission/create',['cid',$data['cid']])}}')" >
                        <i class="fa fa-plus" aria-hidden="true"></i>
                    </button>
                    @endcan


                </div>
            </div>
            <div class="panel-body nav-tabs-animate">
                    <table  class="table table-bordered table-hover dataTable table-striped width-full text-nowrap" id="dataTable"  data-paging="false" data-ordering="false" data-info="false">
                        <thead>

                        <tr>
                            <th data-field="id">ID</th>
                            <th data-field="name">权限规则</th>
                            <th data-field="price">权限名称</th>
                            <th data-field="created_at">创建时间</th>
                            <th data-field="operate">操作</th>
                        </tr>

                        </thead>


                        <tbody>
                        @foreach($data['data'] as $v)
                            <tr>

                                <td>{{$v['id']}}</td>
                                <td>{{$v['name']}}</td>
                                <td>{{$v['display_name']}}</td>
                                <td>{{$v['created_at']}}</td>

                                <td>
                                    @can('admin.permission.edit')

                                    <a onclick="edit('编辑','{{url(__ADMIN_PATH__.'/permission',['edit',$v['id']])}}')">编辑</a>

                                    @endcan


                                    <a href="{!! url(__ADMIN_PATH__.'/permission/index',['cid',$v['id']]) !!}">下级权限</a>

                                    @can('admin.permission.delete')
                                    <a href="{{url(__ADMIN_PATH__.'/permission',['destory',$v['id']])}}" class="ajax-get confirm">删除</a>
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
