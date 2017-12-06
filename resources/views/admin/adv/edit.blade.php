@extends('admin.common.common')
@section('stylecss')

@endsection
@section('content')

    <div class="ibox float-e-margins">

        <div class="ibox-content">

            {!!Form::open(['url'=>$data['submit_url'],'method'=>'post','id'=>'submitform','class'=>'form-horizontal m-t','role'=>'form'])!!}
            <div class="form-group">
                {!! Form::label('title','广告位标识',['class'=>'col-sm-3 control-label']) !!}
                <div class="col-sm-8">

                    {!! Form::text('varname',$data['varname'],['class'=>'form-control','required'=>'']) !!}


                    <span class="help-block m-b-none"><i class="fa fa-info-circle"></i>字母,数字以及下划线组成</span>
                </div>
            </div>


            <div class="form-group">
                {!! Form::label('title','名称',['class'=>'col-sm-3 control-label']) !!}
                <div class="col-sm-8">

                    {!! Form::text('pname',$data['pname'],['class'=>'form-control','required'=>'']) !!}

                    <span class="help-block m-b-none"><i class="fa fa-info-circle"></i> 名称</span>

                </div>
            </div>





            <div class="form-group">
                {!! Form::label('title','说明',['class'=>'col-sm-3 control-label']) !!}
                <div class="col-sm-8">

                    {!! Form::textarea('info',$data['info'],['class'=>'form-control m-b','required'=>'']) !!}


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
    <script>
        DCNET.setValue('status', '{{$data['status']==0?0:1}}');
    </script>
@endsection
