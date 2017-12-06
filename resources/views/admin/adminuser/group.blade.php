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
                    {!! Form::text('account',$data['account'],['class'=>'form-control','required'=>'','disabled'=>'true']) !!}

                    <span class="help-block m-b-none"><i class="fa fa-info-circle"></i> 账号</span>
                </div>
            </div>

            <div class="form-group">
                {!! Form::label('title','管理员姓名',['class'=>'col-sm-3 control-label']) !!}
                <div class="col-sm-8">


                    {!! Form::text('truename',$data['truename'],['class'=>'form-control','disabled'=>'true']) !!}

                    <span class="help-block m-b-none"><i class="fa fa-info-circle"></i> 管理员姓名</span>

                </div>
            </div>


            <div class="form-group">
                {!! Form::label('title','设置管理组',['class'=>'col-sm-3 control-label']) !!}
                <div class="col-sm-8">

                    {!! Form::select('role_id',$role,$data['role_id'],['class'=>'form-control m-b','required'=>'']) !!}


                    <span class="help-block m-b-none"><i class="fa fa-info-circle">管理员类型</i> </span>
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
    <script src="{{asset('styles/js/plugins/validate/jquery.validate.min.js')}}"></script>
    <script src="{{asset('styles/js/plugins/validate/messages_zh.min.js')}}"></script>
    <script>
        DCNET.setValue('status', '{{$data['status']===0?0:1}}');
        DCNET.setValue('role_id', '{{$data['role_id'] or 0}}');
    </script>
@endsection
