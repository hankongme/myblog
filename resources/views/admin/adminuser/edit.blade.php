@extends('admin.common.common')
@section('stylecss')

@endsection
@section('content')

    <div class="ibox float-e-margins">

        <div class="ibox-content">

            {!!Form::open(['url'=>$data['submit_url'],'method'=>'post','id'=>'submitform','class'=>'form-horizontal m-t','role'=>'form'])!!}
            <div class="form-group">
                {!! Form::label('title','账号',['class'=>'col-sm-3 control-label']) !!}
                <div class="col-sm-8">
                    @if($data['submit_url'] == url(__ADMIN_PATH__.'/admin_user/store'))
                    {!! Form::text('account',$data['account'],['class'=>'form-control','required'=>'']) !!}
                        @else
                        {!! Form::text('account',$data['account'],['class'=>'form-control','required'=>'','readonly'=>'true']) !!}
                    @endif
                    <span class="help-block m-b-none"><i class="fa fa-info-circle"></i> 账号由 字母,数字加下划线组成</span>
                </div>
            </div>

            @if($data['submit_url'] == url(__ADMIN_PATH__.'/admin_user/store'))

                <div class="form-group">
                    {!! Form::label('title','密码',['class'=>'col-sm-3 control-label']) !!}
                    <div class="col-sm-8">
                        {!! Form::password('password',['class'=>'form-control','required'=>'']) !!}

                        <span class="help-block m-b-none"><i class="fa fa-info-circle"></i> 密码</span>
                    </div>
                </div>

                <div class="form-group">
                    {!! Form::label('title','确认密码',['class'=>'col-sm-3 control-label']) !!}

                    <div class="col-sm-8">

                        {!! Form::password('repass',['class'=>'form-control','required'=>'']) !!}

                        <span class="help-block m-b-none"><i class="fa fa-info-circle"></i>确认密码</span>
                    </div>
                </div>

            @endif

            <div class="form-group">
                {!! Form::label('title','管理员姓名',['class'=>'col-sm-3 control-label']) !!}
                <div class="col-sm-8">


                    {!! Form::text('truename',$data['truename'],['class'=>'form-control','required'=>'']) !!}

                    <span class="help-block m-b-none"><i class="fa fa-info-circle"></i> 管理员姓名</span>

                </div>
            </div>

            <div class="form-group">
                {!! Form::label('title','email',['class'=>'col-sm-3 control-label']) !!}
                <div class="col-sm-8">
                    {!! Form::text('email',$data['email'],['class'=>'form-control col-sm-8']) !!}

                    <span class="help-block m-b-none"><i class="fa fa-info-circle">邮箱</i> </span>
                </div>
            </div>

            <div class="form-group">
                {!! Form::label('title','描述',['class'=>'col-sm-3 control-label']) !!}
                <div class="col-sm-8">
                    {!! Form::text('description',$data['description'],['class'=>'form-control col-sm-8']) !!}

                    <span class="help-block m-b-none"><i class="fa fa-info-circle">描述</i> </span>
                </div>
            </div>

            <div class="form-group">
                {!! Form::label('title','状态',['class'=>'col-sm-3 control-label']) !!}
                <div class="col-sm-8">

                    <div class="radio-custom radio-primary">
                        <input name="status" checked="" type="radio" value="1">
                        <label>启用</label>
                    </div>

                    <div class="radio-custom radio-primary">
                        <input name="status" type="radio" value="0">
                        <label>禁用</label>
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
    <script>
        DCNET.setValue('status', '{{$data['status']==0?0:1}}');
    </script>
@endsection
