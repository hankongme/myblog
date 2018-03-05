@extends('admin.common.common')
@section('stylecss')
    <link rel="stylesheet" href="{{asset('styles/lib/dataTables/dataTables.bootstrap.css')}}" media="all">
    <link rel="stylesheet" href="{{asset('styles/lib/dataTables/dataTables.responsive.css')}}" media="all">
@endsection
@section('content')

    <main class="site-page">

        <div class="panel">
            <div class="panel-heading">
                <h3 class="panel-title">标签列表</h3>
                <div class="panel-actions">
                    @can('admin.tag.create')
                    <button type="button" class="btn btn-primary"
                            onclick="edit('添加','{{url(__ADMIN_PATH__.'/tag/create')}}')">
                        添加标签
                    </button>
                    @endcan

                </div>
            </div>
            <div class="panel-body nav-tabs-animate">

                    <table  class="table table-bordered table-hover dataTable table-striped width-full text-nowrap" id="dataTable"  data-paging="false" data-ordering="false" data-info="false">
                        <thead>
                        <tr>
                            <th data-field="id">序号</th>
                            <th data-field="title">名称</th>
                            <th data-field="pub_time">发布时间</th>
                            <th data-field="is_show">是否显示</th>
                            <th>操作</th>

                        </tr>



                        </thead>


                        <tbody>


                        <tr>

                            <td></td>
                            <td>
                                <input class="search_form form-control" type="text" name="title" value="{{$search_form['title'] or ''}}">
                            </td>
                            <td>
                                <input type="text" name="start_time" placeholder="开始时间"
                                       class="date  search_form form-control"
                                       value="{{$search_form['start_time'] or ''}}">
                                <br/>
                                <input type="text" name="end_time" placeholder="结束时间"
                                       class="date search_form form-control"
                                       value="{{$search_form['end_time'] or ''}}">
                            </td>

                            <td>
                                <select autocomplete="off" class="search_form form-control" name="status" id="status">
                                    <option value="10">全部</option>
                                    <option value="1">是</option>
                                    <option value="0">否</option>
                                </select>
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
                                    <td>{{$v['name']}}</td>
                                    <td>{{$v['created_at']}}</td>
                                    <td>{{$v['status_text']}}</td>
                                    <td>
                                        @can('admin.tag.edit')
                                        <a class="btn btn-xs btn-white"
                                           onclick="edit('编辑','{{url(__ADMIN_PATH__.'/tag',[$v['id'],'edit'])}}')"
                                           data-toggle="tooltip" data-placement="left" title="编辑"><i class="fa fa-edit">
                                            </i>
                                        </a>
                                        @endcan

                                        @can('admin.tag.del')
                                        <a class="confirm ajax-get btn btn-xs btn-white"
                                           href="{!! url(__ADMIN_PATH__.'/tag',[$v['id'],'del']) !!}"
                                           data-toggle="tooltip" data-placement="left"
                                           title="删除"><i
                                                    class="fa fa-minus-circle"></i></a>
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
     DCNET.setValue('status', '{{$search_form['status'] or 10}}');

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
