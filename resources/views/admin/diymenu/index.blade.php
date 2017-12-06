@extends('admin.common.common')
@section('stylecss')
    <link rel="stylesheet" href="{{asset('styles/lib/dataTables/dataTables.bootstrap.css')}}" media="all">
    <link rel="stylesheet" href="{{asset('styles/lib/dataTables/dataTables.responsive.css')}}" media="all">
@endsection
@section('content')


    <main class="site-page">

        <div class="panel">
            <div class="panel-heading">
                <h3 class="panel-title">菜单列表</h3>


                <div class="panel-actions">

                    @can('admin.diymenu.create')
                    <button type="button" class="btn btn-primary"
                            onclick="edit('添加','{{url(__ADMIN_PATH__.'/diymenu/create')}}')">
                        <i class="fa fa-plus" aria-hidden="true"></i>
                    </button>
                    @endcan

                    @can('admin.diymenu.publish')
                    <button type="button" class="confirm ajax-get btn btn-primary" href="{{url(__ADMIN_PATH__.'/diymenu/publish')}}">
                        发布菜单
                    </button>
                    @endcan

                </div>
            </div>
            <div class="panel-body nav-tabs-animate">

                    <table  class="table table-bordered table-hover dataTable table-striped width-full text-nowrap" id="dataTable"  data-paging="false" data-ordering="false" data-info="false">
                        <thead>

                        <tr>
                            <th data-field="id">序号</th>
                            <th data-field="title">菜单名称</th>
                            <th data-field="pname">菜单类型</th>
                            <th data-field="created_at">类型值</th>
                            <th data-field="operate">操作</th>
                        </tr>

                        </thead>


                        <tbody>
                        @if($data['data'])
                            @foreach($data['data'] as $v)
                                <tr>

                                    <td>{{$v['id']}}</td>
                                    <td>{{$v['title']}}</td>
                                    <td>{{$v['type']}}</td>
                                    <td>{{$v['value']}}</td>

                                    <td>

                                        @can('admin.diymenu.edit')
                                        <a class="ajax-get btn btn-xs btn-white"
                                           onclick="edit('编辑菜单-{{$v['title']}}','{!! url(__ADMIN_PATH__.'/diymenu/edit',['id',$v['id']]) !!}',500,400)"
                                           data-toggle="tooltip" data-placement="left"
                                           title="编辑"><i class="fa fa-edit"></i></a>
                                        @endcan

                                        @can('admin.diymenu.del')
                                        <a class="confirm ajax-get btn btn-xs btn-white"
                                           href="{!! url(__ADMIN_PATH__.'/diymenu/del',['id',$v['id']]) !!}"
                                           data-toggle="tooltip" data-placement="left"
                                           title="删除"><i class="fa fa-minus-circle"></i></a>
                                        @endcan


                                    </td>

                                </tr>

                                @if(!empty($v['child']))
                                    @foreach($v['child'] as $vv)
                                        <tr>

                                            <td>{{$vv['id']}}</td>
                                            <td>——{{$vv['title']}}</td>
                                            <td>{{$vv['type']}}</td>
                                            <td>{{$vv['value']}}</td>

                                            <td>

                                                @can('admin.diymenu.edit')
                                                <a class="ajax-get btn btn-xs btn-white"
                                                   onclick="edit('编辑菜单-{{$vv['title']}}','{!! url(__ADMIN_PATH__.'/diymenu/edit',['id',$vv['id']]) !!}',500,400)"
                                                   data-toggle="tooltip" data-placement="left"
                                                   title="编辑"><i class="fa fa-edit"></i></a>
                                                @endcan

                                                @can('admin.diymenu.del')
                                                <a class="confirm ajax-get btn btn-xs btn-white"
                                                   href="{!! url(__ADMIN_PATH__.'/diymenu/del',['id',$vv['id']]) !!}"
                                                   data-toggle="tooltip" data-placement="left"
                                                   title="删除"><i class="fa fa-minus-circle"></i></a>
                                                @endcan


                                            </td>

                                        </tr>

                                    @endforeach
                                @endif
                            @endforeach
                        @endif
                        </tbody>
                    </table>
                    {{$page}}
            </div>
        </div>
    </main>


@endsection

@section('stylejs')

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
