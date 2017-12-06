@extends('admin.common.common')
@section('stylecss')


@endsection
@section('content')

    <div class="ibox float-e-margins">

        <div class="ibox-content">

            {!!Form::open(['url'=>$data['submit_url'],'method'=>'post','id'=>'submitform','class'=>'form-horizontal m-t','role'=>'form'])!!}
            <div class="form-group">
                {!! Form::label('title','分类名称',['class'=>'col-sm-3 control-label']) !!}
                <div class="col-sm-8">
                    {!! Form::text('name',$data['name'],['class'=>'form-control','required'=>'']) !!}

                    <span class="help-block m-b-none"><i class="fa fa-info-circle"></i> 分类名称</span>
                </div>
            </div>



            <div class="form-group">
                {!! Form::label('title','上级分类',['class'=>'col-sm-3 control-label']) !!}
                <div class="col-sm-8">

                    {!! Form::select('parent_id',$category,$data['parent_id'],['class'=>'form-control m-b','required'=>'']) !!}

                </div>
            </div>



            <div class="form-group">
                {!! Form::label('title','是否显示',['class'=>'col-sm-3 control-label']) !!}
                <div class="col-sm-8">
                    <input type="checkbox" class="js-switch" name="status" value="1">
                </div>
            </div>


            <div class="form-group">
                {!! Form::label('title','排序',['class'=>'col-sm-3 control-label']) !!}
                <div class="col-sm-3">
                    {!! Form::text('sort',$data['sort'],['class'=>'form-control','required'=>'']) !!}
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
        DCNET.setValue('status', '{{$data['status'] or 1}}');
    </script>

    @include('admin.plugins.from')
@endsection
