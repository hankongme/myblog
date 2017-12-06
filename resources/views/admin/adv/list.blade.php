@extends('admin.common.common')
@section('stylecss')
    <link rel="stylesheet" href="{{asset('styles/lib/dataTables/dataTables.bootstrap.css')}}" media="all">
    <link rel="stylesheet" href="{{asset('styles/lib/dataTables/dataTables.responsive.css')}}" media="all">
@endsection
@section('content')

    <?php
    function getInArr($key, $arr){
        foreach($arr as $index => $item){
            if($key == $item){
                return 1;
            }
        }
        return 0;
    }

    ?>
    <main class="site-page">

        <div class="panel">
            <div class="panel-heading">
                <h3 class="panel-title">广告位列表</h3>
                <div class="panel-actions">

                        <button type="button"  class="btn btn-primary" onclick="javascript:history.go(-1);">
                            <i class="fa fa-level-up" aria-hidden="true"></i>
                        </button>


                    @can('admin.adv.createadv')
                    <button type="button" class="btn btn-primary"
                            onclick="edit('添加','{{url(__ADMIN_PATH__.'/adv/createadv',['id',$adv_position['id']])}}')">
                        <i class="fa fa-plus" aria-hidden="true"></i>
                    </button>
                    @endcan



                </div>
            </div>
            <div class="panel-body nav-tabs-animate">
                    <table  class="table table-bordered table-hover dataTable table-striped width-full text-nowrap" id="dataTable"  data-paging="false" data-ordering="false" data-info="false">
                        <thead>
                        <tr>

                            <th data-field="id">序号</th>
                            <th data-field="title1">名称</th>
                            <th data-field="picurl">图片</th>
                            <th data-field="picurl1">显示</th>
                            <th data-field="stime">开始时间</th>
                            <th data-field="etime">结束时间</th>
                            <th data-field="created_at">添加时间</th>
                            <th data-field="operate">操作</th>
                        </tr>
                        </thead>
                        <tbody>


                        <tr>


                            <td>

                                {{--序号--}}

                            </td>
                            <td>

                                {{--名称--}}
                                <input class="search_form" type="text" name="name" value="{{$search_form['pname'] or ''}}">

                            </td>



                            <td>
                                {{--图片--}}

                            </td>


                            <td>
                                {{--显示--}}

                            </td>



                            <td>

                                {{--添加时间--}}
                                <input type="text" name="s_time" placeholder="开始时间"
                                       class="date  search_form"
                                       value="{{$search_form['s_time'] or ''}}">
                                <br/>
                                <input type="text" name="e_time" placeholder="结束时间"
                                       class="date  search_form"
                                       value="{{$search_form['e_time'] or ''}}">

                            </td>

                            <td>

                                {{--添加时间--}}
                                <input type="text" name="ss_time" placeholder="开始时间"
                                       class="date  search_form"
                                       value="{{$search_form['ss_time'] or ''}}">
                                <br/>
                                <input type="text" name="ee_time" placeholder="结束时间"
                                       class="date  search_form"
                                       value="{{$search_form['ee_time'] or ''}}">

                            </td>



                            <td>

                                {{--添加时间--}}
                                <input type="text" name="start_time" placeholder="开始时间"
                                       class="date  search_form"
                                       value="{{$search_form['start_time'] or ''}}">
                                <br/>
                                <input type="text" name="end_time" placeholder="结束时间"
                                       class="date  search_form"
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

                                    <td>{{$v['title']}}</td>
                                    <td><img src="{{$v['image_url']}}" alt="" width="50px" height="80px"></td>
                                    <td>{{$v['status'] == 1 ? '显示' : '不显示'}}</td>
                                    <td>{{$v['start_time']}}</td>
                                    <td>{{$v['end_time']}}</td>
                                    <td>{{$v['created_at']}}</td>
                                    <td>

                                        @can('admin.adv.editadv')
                                        <a class="ajax-get btn btn-xs btn-white"
                                           onclick="edit('编辑广告-{{$v['title']}}','{!! url(__ADMIN_PATH__.'/adv/editadv',['id',$v['id']]) !!}')"
                                           data-toggle="tooltip" data-placement="left"
                                           title="编辑"><i class="fa fa-edit"></i></a>
                                        @endcan

                                        @can('admin.adv.deladv')
                                        <a class="confirm ajax-get btn btn-xs btn-white"
                                           href="{!! url(__ADMIN_PATH__.'/adv/deladv',['id',$v['id']]) !!}"
                                           data-toggle="tooltip" data-placement="left"
                                           title="删除"><i class="fa fa-minus-circle"></i></a>
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
