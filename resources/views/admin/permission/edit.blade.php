@extends('admin.common.common')
@section('stylecss')

    <link href="{{asset("styles/lib/bootstrap-iconpicker/icon-fonts/font-awesome-4.2.0/css/font-awesome.min.css")}}"
          rel="stylesheet">

    <link href="{{asset("styles/lib/bootstrap-iconpicker/css/bootstrap-iconpicker.min.css")}}"
          rel="stylesheet">


@endsection
@section('content')

    <div class="ibox float-e-margins">

        <div class="ibox-content">

            {!!Form::open(['url'=>$data['submit_url'],'method'=>'post','id'=>'submitform','class'=>'form-horizontal m-t','role'=>'form'])!!}
            <div class="form-group">
                {!! Form::label('title','规则',['class'=>'col-sm-3 control-label']) !!}
                <div class="col-sm-8">
                    {!! Form::text('name',$data['name'],['class'=>'form-control','required'=>'']) !!}

                    <span class="help-block m-b-none"><i class="fa fa-info-circle"></i> 规则</span>
                </div>
            </div>

            <div class="form-group">
                {!! Form::label('title','规则名称',['class'=>'col-sm-3 control-label']) !!}
                <div class="col-sm-8">
                    {!! Form::text('display_name',$data['display_name'],['class'=>'form-control','required'=>'']) !!}

                    <span class="help-block m-b-none"><i class="fa fa-info-circle"></i> 规则名称</span>
                </div>
            </div>


            <div class="form-group">
                {!! Form::label('title','上级规则',['class'=>'col-sm-3 control-label']) !!}
                <div class="col-sm-8">

                    {!! Form::select('cid',$permission_data,$data['cid'],['class'=>'form-control m-b','required'=>'']) !!}


                </div>
            </div>

            <div class="form-group">
                {!! Form::label('title','排序',['class'=>'col-sm-3 control-label']) !!}
                <div class="col-sm-3">

                    {!! Form::text('sort',$data['sort'],['class'=>'form-control','required'=>'']) !!}

                </div>
            </div>

            <div class="form-group">
                {!! Form::label('title','图标',['class'=>'col-sm-3 control-label']) !!}

                <div class="col-sm-3">
                    <button class="btn btn-default" name="icon" data-iconset="fontawesome"
                            data-icon="{{ $data['icon']?$data['icon']:'fa-sliders' }}" role="iconpicker"></button>

                </div>
            </div>


            <div class="form-group">
                {!! Form::label('title','规则状态',['class'=>'col-sm-3 control-label']) !!}
                <div class="col-sm-8">


                    启用{!! Form::radio('status',1,false) !!}

                    禁用 {!! Form::radio('status',0,false) !!}


                </div>
            </div>


            <div class="form-group">
                {!! Form::label('title','是否显示',['class'=>'col-sm-3 control-label']) !!}
                <div class="col-sm-8">


                    是{!! Form::radio('is_show',1,false) !!}

                    否 {!! Form::radio('is_show',0,false) !!}


                </div>
            </div>

            <div class="form-group">
                {!! Form::label('title','规则描述',['class'=>'col-sm-3 control-label']) !!}
                <div class="col-sm-8">
                    {!! Form::textarea('description',$data['description'],['class'=>'form-control col-sm-8']) !!}

                    <span class="help-block m-b-none"><i class="fa fa-info-circle"></i> 规则描述</span>
                </div>
            </div>
            {!! Form::hidden('cid',$data['cid']) !!}
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

    <script src="{{asset('styles/lib/bootstrap-iconpicker/js/iconset/iconset-fontawesome-4.3.0.min.js')}}"></script>
    <script src="{{asset('styles/lib/bootstrap-iconpicker/js/bootstrap-iconpicker.js')}}"></script>
    <script>

        DCNET.setValue('status', '{{$data['status'] or 1}}');
        DCNET.setValue('is_show', '{{$data['is_show'] or 1}}');

    </script>

@endsection
