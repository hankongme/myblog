@extends('admin.common.common')
@section('stylecss')

@endsection
@section('content')

    <div class="ibox float-e-margins">

        <div class="ibox-content">

            {!!Form::open(['url'=>$data['submit_url'],'method'=>'post','id'=>'submitform','class'=>'form-horizontal m-t','role'=>'form'])!!}
            <div class="form-group">
                {!! Form::label('title','管理组标识',['class'=>'col-sm-3 control-label']) !!}
                <div class="col-sm-8">

                    @if($data['submit_url'] == url(__ADMIN_PATH__.'/role/update'))
                        {!! Form::text('name',$data['name'],['class'=>'form-control','required'=>'','readonly'=>'readonly']) !!}
                    @else
                        {!! Form::text('name',$data['name'],['class'=>'form-control','required'=>'']) !!}
                    @endif

                    <span class="help-block m-b-none"><i class="fa fa-info-circle"></i> 管理组标识</span>
                </div>
            </div>


            <div class="form-group">
                {!! Form::label('title','管理组名称',['class'=>'col-sm-3 control-label']) !!}
                <div class="col-sm-8">


                    {!! Form::text('display_name',$data['display_name'],['class'=>'form-control','required'=>'']) !!}

                    <span class="help-block m-b-none"><i class="fa fa-info-circle"></i> 管理组名称</span>

                </div>
            </div>

            <div class="form-group">

                <div class="row cl">
                    {!! Form::label('title','管理组权限',['class'=>'col-sm-3 control-label']) !!}
                    <div class="col-sm-8">


                        @foreach($permission as $k=>$v)
                            <dl class="permission-list">
                                <dt>
                                    <label class="control-label">
                                        <input class="auth_rules rules_all format_checkbox"
                                               type="checkbox" name="rules[]"
                                               value="{{$v['id']}}" data-name="{{$v['display_name']}}">

                                    </label>
                                </dt>
                                <dd>

                                    @if(isset ($v['child'])&&!empty($v['child']))

                                        <?php $vvi = 0;?>
                                        @foreach($v['child'] as $kk=>$vv)

                                            <dl class="cl permission-list2">
                                                <dt>
                                                    <label class="control-label">
                                                        <input class="auth_rules format_checkbox"
                                                               type="checkbox"
                                                               name="rules[]"
                                                               value="{{$vv['id']}}"
                                                               id="user-Character-0-<?php echo $vvi;?>" data-name="{{$vv['display_name']}}">

                                                    </label>
                                                </dt>
                                                <dd>

                                                    @if(isset ($vv['child'])&&!empty($vv['child']))
                                                        <?php $vvvi = 0;?>
                                                        @foreach($vv['child'] as $kkk=>$vvv)



                                                            <label class="control-label">
                                                                <input type="checkbox" class="auth_rules format_checkbox"
                                                                       id="user-Character-0-<?php echo $vvi;?>-<?php echo $vvvi;?>"
                                                                       name="rules[]"
                                                                       value="{{$vvv['id']}}" data-name="{{$vvv['display_name']}}"/>

                                                            </label>
                                                            <?php $vvvi++;?>

                                                        @endforeach

                                                    @endif
                                                </dd>
                                            </dl>
                                            <?php $vvi++;?>
                                        @endforeach



                                    @endif


                                </dd>
                            </dl>

                        @endforeach


                    </div>
                </div>


            </div>


            {!! Form::hidden('id',$data['id']) !!}
            <div class="form-group">
                <div class="col-sm-8 col-sm-offset-3">
                    <button class="btn btn-primary" type="button" onclick="submit_form();">提交</button>
                </div>
            </div>

            {!! Form::close() !!}


        </div>
    </div>


@endsection

@section('stylejs')


    <script type="text/javascript">
        $(function () {
            var rules = [{{$role_permissions}}];
            $('.auth_rules').each(function () {
                if ($.inArray(parseInt(this.value, 10), rules) > -1) {
                    $(this).prop('checked', true);
                }
                if (this.value == '') {
                    $(this).closest('span').remove();
                }
            });

            $(".permission-list dt input:checkbox").click(
                    function () {

                        $(this).closest("dl").find("dd input:checkbox").prop(
                                "checked", $(this).prop("checked"));
                    });


            $(".permission-list2 dt input:checkbox").click(
                    function () {
                        $(this).parents(".permission-list").find(".rules_all").prop(
                                "checked", $(this).prop("checked"));

                    });


            $(".permission-list2 dd input:checkbox")
                    .click(
                            function () {
                                var l = $(this).parent().parent().find(
                                        "input:checked").length;

                                var l2 = $(this).parents(".permission-list")
                                        .find(".permission-list2 dd").find(
                                                "input:checked").length;
                                if ($(this).prop("checked")) {
                                    $(this).closest("dl").find(
                                            "dt input:checkbox").prop(
                                            "checked", true);
                                    $(this).parents(".permission-list").find(
                                            "dt").first()
                                            .find("input:checkbox").prop(
                                            "checked", true);
                                } else {
                                    if (l == 0) {
                                        $(this).closest("dl").find(
                                                "dt input:checkbox").prop(
                                                "checked", false);
                                    }
                                    if (l2 == 0) {
                                        $(this).parents(".permission-list")
                                                .find("dt").first().find(
                                                "input:checkbox").prop(
                                                "checked", false);
                                    }
                                }
                            });

            $("#form-admin-role-add").validate({
                rules: {
                    roleName: {
                        required: true,
                    },
                },
                onkeyup: false,
                focusCleanup: true,
                success: "valid",
                submitHandler: function (form) {
                    submit_form();
                    var index = parent.layer.getFrameIndex(window.name);
                    parent.layer.close(index);
                }
            });
        });


    </script>

@endsection
