@extends('admin.common.common')
@section('stylecss')
    <link rel="stylesheet" type="text/css" href="{{asset('styles/lib/webuploader/webuploader-image.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('styles/lib/webuploader/webuploader.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('styles/lib/editormd/editormd.css')}}">

@endsection
@section('content')


    <main class="site-page">

        <div class="panel">
            <div class="panel-heading">
            </div>
            <div class="panel-body container-fluid">

                    {!!Form::open(['url'=>$data['submit_url'],'method'=>'post','id'=>'submitform','class'=>'form-horizontal m-t','role'=>'form'])!!}

                    <div class="form-group">
                        {!! Form::label('title','名称',['class'=>'col-sm-2 control-label']) !!}
                        <div class="col-sm-10">
                            {!! Form::text('name',$data['name'],['class'=>'form-control col-sm-6']) !!}
                        </div>
                    </div>


                    {!! Form::hidden('id',$data['id'],['id'=>'id']) !!}

                    <div class="form-group">
                        <div class="col-sm-10 col-sm-offset-3">
                            <button class="btn btn-primary" type="button" onclick="submit_form()">提交</button>
                        </div>
                    </div>

                    {!! Form::close() !!}
            </div>
        </div>
    </main>


@endsection

@section('stylejs')
    <style>
        td {
            padding: 1px !important;
        }

    </style>


    <script>
        DCNET.setValue('status', '{{$data['status'] or 1}}');
    </script>

    @include('admin.plugins.from')
@endsection
