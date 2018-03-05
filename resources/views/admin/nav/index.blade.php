@extends('admin.common.common')
@section('stylecss')

    <link rel="stylesheet" href="{{asset('styles/lib/dataTables/dataTables.bootstrap.css')}}" media="all">
    <link rel="stylesheet" href="{{asset('styles/lib/dataTables/dataTables.responsive.css')}}" media="all">

@endsection
@section('content')


    <main class="site-page">
        <div class="panel">
            <div class="panel-heading">
                <h3 class="panel-title">导航列表</h3>
                <div class="panel-actions">
                    @can('admin.nav.create')
                    <button type="button" class="btn btn-primary"
                            onclick="edit('添加','{{url(__ADMIN_PATH__.'/nav')}}')">
                        <i class="fa fa-plus" aria-hidden="true"></i>
                    </button>
                    @endcan

                </div>
            </div>
            <div class="panel-body nav-tabs-animate">

                <table class="table table-bordered table-hover dataTable table-striped width-full text-nowrap"
                       id="dataTable" data-paging="false" data-ordering="false" data-info="false">
                    <thead>
                    <tr>
                        <th data-field="id">序号</th>
                        <th data-field="name">名称</th>
                        <th data-field="url">链接</th>
                        <th data-field="url_type">链接类型</th>
                        <th data-field="status">状态</th>
                        <th data-field="sort">排序</th>
                        <th data-field="created_at">添加时间</th>
                        <th data-field="operate">操作</th>
                    </tr>
                    </thead>


                    <tbody>
                    <tr>
                        <!--id-->
                        <td></td>

                        <!--名称-->
                        <td>
                            <input type="text" name="name" placeholder="名称"
                                   class="search_form form-control"
                                   value="{{$search_form['name'] or ''}}">
                        </td>

                        <!--链接-->
                        <td>
                            <input type="text" name="url" placeholder="链接"
                                   class="search_form form-control"
                                   value="{{$search_form['url'] or ''}}">
                        </td>


                        <!--链接类型-->
                        <td>
                            <select class="form-control search_form" name="url_type" id="url_type">
                                <option value="10">全部</option>
                                <option value="1">外部链接</option>
                                <option value="0">内部链接</option>
                            </select>

                        </td>


                        <!--状态-->
                        <td>
                            <select class="form-control search_form" name="status" id="status">
                                <option value="10">全部</option>
                                <option value="1">启用</option>
                                <option value="0">禁用</option>
                            </select>

                        </td>


                        <!--排序-->
                        <td>

                        </td>

                        <!--创建时间-->
                        <td>
                            <input type="text" name="start_time" placeholder="开始时间"
                                   class="date  search_form form-control"
                                   value="{{$search_form['start_time'] or ''}}">
                            <br/>
                            <input type="text" name="end_time" placeholder="结束时间"
                                   class="date  search_form form-control"
                                   value="{{$search_form['end_time'] or ''}}">

                        </td>


                        <td>
                            <button class="btn btn-primary" type="button" onclick="search_form();">
                                查询

                            </button>

                        </td>


                    </tr>
                    @if(!empty($data['data']))
                        @foreach($data['data'] as $v)
                            <tr>

                                <td>{{$v['key_num']}}</td>

                                <td>
                                    {{$v['name']}}
                                </td>


                                <td>
                                    {{$v['url']}}
                                </td>

                                <td>
                                    {{$v['url_type_text']}}
                                </td>

                                <td>
                                    {{$v['status_text']}}
                                </td>

                                <td>
                                    {{$v['sort']}}
                                </td>

                                <td>{{$v['created_at']}}</td>
                                <td>


                                    @can('admin.menus.edit')

                                    <a class="btn btn-xs btn-white"
                                       onclick="edit('编辑','{{url(__ADMIN_PATH__.'/nav',[$v['id']])}}')"
                                       data-toggle="tooltip" data-placement="left" title="编辑"><i
                                                class="fa fa-edit">
                                        </i>
                                    </a>
                                    @endcan



                                    @can('admin.menus.delete')
                                    <a class="confirm ajax-get btn btn-xs btn-white"
                                       href="{!! url(__ADMIN_PATH__."/nav/{$v['id']}/del") !!}"
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
        });        //图标提示
        $("[data-toggle='tooltip']").tooltip();

        DCNET.setValue('url_type','{{$search_form['url_type'] or 10}}');
        DCNET.setValue('status','{{$search_form['status'] or 10}}');

    </script>
@endsection
