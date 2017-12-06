@extends('admin.common.common')
@section('stylecss')

    <link href="{{asset("styles/css/plugins/bootstrap-iconpicker/icon-fonts/font-awesome-4.2.0/css/font-awesome.min.css")}}"
          rel="stylesheet">

    <link href="{{asset("styles/css/plugins/bootstrap-iconpicker/bootstrap-iconpicker/css/bootstrap-iconpicker.min.css")}}"
          rel="stylesheet">


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
                {!! Form::label('title','分类',['class'=>'col-sm-3 control-label']) !!}
                <div class="col-sm-8">

                    {!! Form::select('parent_id',$category,$data['parent_id'],['class'=>'form-control m-b','required'=>'']) !!}


                </div>
            </div>

            <div class="form-group">
                {!! Form::label('title','排序',['class'=>'col-sm-3 control-label']) !!}
                <div class="col-sm-3">

                    {!! Form::text('sort',$data['sort'],['class'=>'form-control','required'=>'']) !!}

                </div>
            </div>



            <div class="form-group">
                {!! Form::label('title','状态',['class'=>'col-sm-3 control-label']) !!}
                <div class="col-sm-8">


                    显示{!! Form::radio('status',1,false) !!}

                    隐藏 {!! Form::radio('status',0,false) !!}


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
    <style>
        td {
            padding: 1px !important;
        }

    </style>
    <script src="{{asset('styles/js/plugins/validate/jquery.validate.min.js')}}"></script>
    <script src="{{asset('styles/js/plugins/validate/messages_zh.min.js')}}"></script>

    <script src="{{asset('styles/js/plugins/bootstrap-iconpicker/bootstrap-iconpicker/js/iconset/iconset-fontawesome-4.3.0.min.js')}}"></script>
    <script src="{{asset('styles/js/plugins/bootstrap-iconpicker/bootstrap-iconpicker/js/bootstrap-iconpicker.js')}}"></script>
    <script>

        DCNET.setValue('status', '{{$data['status'] or 1}}');

    </script>

@endsection