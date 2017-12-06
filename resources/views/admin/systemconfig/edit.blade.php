@extends('admin.common.common')
@section('stylecss')

@endsection
@section('content')

    <div class="ibox float-e-margins">

        <div class="ibox-content">

            {!!Form::open(['url'=>$data['submit_url'],'method'=>'post','id'=>'submitform','class'=>'form-horizontal m-t','role'=>'form'])!!}
            <div class="form-group">
                {!! Form::label('title','配置标识',['class'=>'col-sm-3 control-label']) !!}
                <div class="col-sm-8">

                    {!! Form::text('name',$data['name'],['class'=>'form-control','required'=>'']) !!}


                    <span class="help-block m-b-none"><i class="fa fa-info-circle"></i>字母,数字以及下划线组成</span>
                </div>
            </div>


            <div class="form-group">
                {!! Form::label('title','配置标题',['class'=>'col-sm-3 control-label']) !!}
                <div class="col-sm-8">

                    {!! Form::text('title',$data['title'],['class'=>'form-control','required'=>'']) !!}

                    <span class="help-block m-b-none"><i class="fa fa-info-circle"></i> 配置标题</span>

                </div>
            </div>

            <div class="form-group">
                {!! Form::label('title','排序',['class'=>'col-sm-3 control-label']) !!}
                <div class="col-sm-8">
                    {!! Form::text('sort',$data['sort'],['class'=>'form-control col-sm-8']) !!}

                </div>
            </div>

            <div class="form-group">
                {!! Form::label('title','配置分组',['class'=>'col-sm-3 control-label']) !!}
                <div class="col-sm-8">

                    {!! Form::select('group',$group,$data['group'],['class'=>'form-control m-b','required'=>'']) !!}


                    <span class="help-block m-b-none"><i class="fa fa-info-circle">配置分组</i> </span>
                </div>
            </div>

            <div class="form-group">
                {!! Form::label('title','配置类型',['class'=>'col-sm-3 control-label']) !!}
                <div class="col-sm-8">

                    {!! Form::select('type',$type,$data['type'],['class'=>'form-control m-b','required'=>'']) !!}


                    <span class="help-block m-b-none"><i class="fa fa-info-circle">配置类型</i> </span>
                </div>
            </div>


            <div class="form-group">
                {!! Form::label('title','配置值',['class'=>'col-sm-3 control-label']) !!}
                <div class="col-sm-8">

                    {!! Form::textarea('value',$data['value'],['class'=>'form-control m-b','required'=>'']) !!}


                    <span class="help-block m-b-none"><i class="fa fa-info-circle">配置值</i> </span>
                </div>
            </div>


            <div class="form-group">
                {!! Form::label('title','配置项',['class'=>'col-sm-3 control-label']) !!}
                <div class="col-sm-8">

                    {!! Form::textarea('extra',$data['extra'],['class'=>'form-control m-b','required'=>'']) !!}


                    <span class="help-block m-b-none"><i class="fa fa-info-circle">配置项</i> </span>
                </div>
            </div>


            <div class="form-group">
                {!! Form::label('title','说明',['class'=>'col-sm-3 control-label']) !!}
                <div class="col-sm-8">

                    {!! Form::textarea('remark',$data['remark'],['class'=>'form-control m-b','required'=>'']) !!}


                    <span class="help-block m-b-none"><i class="fa fa-info-circle">说明</i> </span>
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
        DCNET.setValue('status','{{$data['status'] or 1}}');

    </script>
@endsection
