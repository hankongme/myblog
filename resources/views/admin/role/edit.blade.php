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

                    @if($data['submit_url'] == url('admin/role/update'))
                        {!! Form::text('name',$data['name'],['class'=>'form-control','required'=>'','readonly'=>'readonly']) !!}
                    @else
                        {!! Form::text('name',$data['name'],['class'=>'form-control','required'=>'']) !!}
                    @endif

                    <span class="help-block m-b-none"><i class="fa fa-info-circle"></i> 管理组标识 例如:CAIWUZU</span>
                </div>
            </div>


            <div class="form-group">
                {!! Form::label('title','管理组名称',['class'=>'col-sm-3 control-label']) !!}
                <div class="col-sm-8">


                    {!! Form::text('display_name',$data['display_name'],['class'=>'form-control','required'=>'']) !!}

                    <span class="help-block m-b-none"><i class="fa fa-info-circle"></i> 管理组名称 例如:财务组</span>

                </div>
            </div>

            <div class="form-group">
                {!! Form::label('title','详细说明',['class'=>'col-sm-3 control-label']) !!}
                <div class="col-sm-8">
                    {!! Form::text('description',$data['description'],['class'=>'form-control col-sm-8']) !!}

                    <span class="help-block m-b-none"><i class="fa fa-info-circle">详细说明</i> </span>
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
    </script>
@endsection
