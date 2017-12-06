@extends('admin.common.common')
@section('stylecss')
    <link rel="stylesheet" href="{{asset('styles/lib/dataTables/dataTables.bootstrap.css')}}" media="all">
    <link rel="stylesheet" href="{{asset('styles/lib/dataTables/dataTables.responsive.css')}}" media="all">
    <link rel="stylesheet" type="text/css" href="{{asset('styles/lib/dataTables/fixedColumns/fixedColumns.dataTables.min.css')}}">
@endsection
@section('content')
    <style type="text/css">
        th, td { white-space: nowrap; }
        .DTFC_RightBodyWrapper{
            background-color: #fff;
        }
    </style>

    <main class="site-page">

        <div class="panel">
            <div class="panel-heading">
                <h3 class="panel-title">操作日志列表</h3>


                <div class="panel-actions">

                </div>
            </div>
            <div class="panel-body nav-tabs-animate">

             
                    <table  class="table table-bordered table-hover dataTable table-striped  text-nowrap" id="dataTable"  data-paging="false" data-ordering="false" data-info="false" width="100%">
                        <thead>

                        <tr>
                            <th data-field="id">序号</th>
                            <th data-field="account">账号</th>
                            <th data-field="name">姓名</th>
                            <th data-field="phone">IP</th>
                            <th data-field="remark">日志</th>
                            <th data-field="ids">操作内容</th>
                            <th data-field="craeted_at">操作时间</th>
                            <th data-field="operate">操作</th>
                        </tr>


                        </thead>


                        <tbody>


                        <tr>


                            <td></td>

                            <td>
                                <input class="search_form form-control" type="text" name="account"
                                       value="{{$search_form['account'] or ''}}">

                            </td>
                            <td>
                                <input class="search_form form-control" type="text" name="name"
                                       value="{{$search_form['name'] or ''}}">

                            </td>

                            <td>


                                <input class="search_form form-control" type="text" name="ip"
                                       value="{{$search_form['ip'] or ''}}">


                            </td>
                            <td>


                                <input class="search_form form-control" type="text" name="remark"
                                       value="{{$search_form['remark'] or ''}}">


                            </td>

                            <td>


                                <input class="search_form form-control" type="text" name="ids"
                                       value="{{$search_form['ids'] or ''}}">


                            </td>


                            <td>
                                <input type="text" name="start_time" placeholder="开始时间"
                                       class="date search_form form-control"
                                       value="{{$search_form['start_time'] or ''}}">
                                <br/><input type="text" name="end_time" placeholder="结束时间"
                                            class="date search_form form-control"
                                            value="{{$search_form['end_time'] or ''}}">

                            </td>

                            <td>
                                <button class="btn btn-primary" type="button" onclick="search_form();">
                                    查询

                                </button>

                            </td>
                        </tr>
                        @if($data['data'])
                            @foreach($data['data'] as $v)
                                <tr>

                                    <td>{{$v['key_num']}}</td>


                                    <td>

                                        {{$v['account']}}


                                    </td>
                                    <td>{{$v['truename']}}</td>

                                    <td>{{$v['ip']}}</td>

                                    <td>{{$v['remark']}}</td>


                                    <td>{{$v['ids']}}</td>
                                    <td>{{$v['created_at']}}</td>

                                    <td>
                                        {{--@can('admin.actionlog.del')--}}

                                        {{--<a class="ajax-get" href="{!! url(__ADMIN_PATH__."/actionlog/{$v['id']}/del") !!}">删除日志</a>--}}

                                        {{--@endcan--}}

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
    <script src="{{asset('styles/lib/dataTables/fixedColumns/dataTables.fixedColumns.js')}}"></script>
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
