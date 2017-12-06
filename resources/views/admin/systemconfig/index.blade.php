@extends('admin.common.common')
@section('stylecss')

    <link rel="stylesheet" href="{{asset('styles/lib/dataTables/dataTables.bootstrap.css')}}" media="all">
    <link rel="stylesheet" href="{{asset('styles/lib/dataTables/dataTables.responsive.css')}}" media="all">

@endsection
@section('content')


    <main class="site-page">

        <div class="panel">
            <div class="panel-heading">
                <h3 class="panel-title">系统配置列表</h3>


                <div class="panel-actions">
                    @can('admin.systemconfig.create')
                    <button type="button" class="btn btn-primary"
                            onclick="edit('添加系统配置','{!! url(__ADMIN_PATH__.'/systemconfig/create') !!}')">
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
                            <th data-field="name">名称</th>
                            <th data-field="title">标题</th>
                            <th data-field="group">分组</th>
                            <th data-field="type">类型</th>
                            <th data-field="operate">操作</th>
                        </tr>

                        </thead>


                        <tbody>
                        @if(!empty($data['data']))
                            @foreach($data['data'] as $v)
                                <tr>
                                    <td>{{$v['id']}}</td>
                                    <td>{{$v['name']}}</td>

                                    <td>{{$v['title']}}</td>
                                    <td>{{$v['group_text']}}</td>
                                    <td>{{$v['type_text']}}</td>
                                    <td>
                                        @can('admin.systemconfig.edit')
                                        <a onclick="edit('编辑','{{url(__ADMIN_PATH__.'/systemconfig',['edit','id','id'=>$v['id']])}}')"
                                        >编辑</a>
                                        @endcan


                                        @can('admin.systemconfig.del')

                                        <a href="{{url(__ADMIN_PATH__.'/systemconfig',['del','id',$v['id']])}}"
                                           class="ajax-get confirm">删除</a>

                                        @endcan
                                    </td>

                                </tr>
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
