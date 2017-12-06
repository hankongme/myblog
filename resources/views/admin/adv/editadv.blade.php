@extends('admin.common.common')
@section('stylecss')
    <link rel="stylesheet" type="text/css" href="{{asset('styles/lib/webuploader/webuploader-image.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('styles/lib/webuploader/webuploader.css')}}">
@endsection
@section('content')

    <div class="ibox float-e-margins">

        {!!Form::open(['url'=>$data['submit_url'],'method'=>'post','id'=>'submitform','class'=>'form-horizontal m-t','role'=>'form'])!!}
        <div class="form-group">
            {!! Form::label('title','广告名称',['class'=>'col-sm-3 control-label']) !!}
            <div class="col-sm-8">
                {!! Form::text('title',$data['title'],['class'=>'form-control','required'=>'']) !!}

            </div>
        </div>


        <div class="form-group">
            {!! Form::label('title','所属广告位',['class'=>'col-sm-3 control-label']) !!}
            <div class="col-sm-8">
                {!! Form::select('positionId',$position_id,$data['position_id'],['class'=>'form-control m-b','id'=>'FPostType', 'disabled']) !!}

            </div>
        </div>


        <div class="form-group">
            {!! Form::label('title','开始日期',['class'=>'col-sm-3 control-label']) !!}
            <div class="col-sm-8">

                {!! Form::text('start_time',$data['start_time'],['class'=>'date form-control col-sm-6 ']) !!}

            </div>
        </div>

        <div class="form-group">
            {!! Form::label('title','结束日期',['class'=>'col-sm-3 control-label']) !!}
            <div class="col-sm-8">

                {!! Form::text('end_time',$data['end_time'],['class'=>'date form-control col-sm-6 date']) !!}

            </div>
        </div>


            <div class="form-group">
                {!! Form::label('title','链接',['class'=>'col-sm-3 control-label']) !!}
                <div class="col-sm-8">
                    {!! Form::text('url',$data['url'],['class'=>'form-control','required'=>'']) !!}

                </div>
            </div>


        <div class="form-group" >
            {!! Form::label('title','选项图片',['class'=>'col-sm-3 control-label']) !!}
            <div class="col-sm-8">

                @if($data['image_url'])
                    <img src="{{$data['image_url']}}" alt="" width="500px">
                @endif


                <div id="uploader" class="uploader wu-example">

                    <input type="hidden" name="image_url" value="{{$data['image_url']}}" class="uploader_input">


                    <div class="queueList" style="border: none">
                        <div id="dndArea">
                            <div id="filePicker"></div>
                            <p>请上传图片文件</p>
                        </div>
                    </div>
                    <div class="statusBar" style="display:none;">
                        <div class="progress">
                            <span class="text">0%</span>
                            <span class="percentage"></span>
                        </div>
                        <div class="info"></div>
                        <div class="btns">
                            <div class="filePicker2"></div>
                            <div class="uploadBtn">开始上传</div>
                        </div>
                    </div>
                </div>


            </div>
        </div>


        <div class="form-group">
            {!! Form::label('title','排序',['class'=>'col-sm-3 control-label']) !!}
            <div class="col-sm-8">
                {!! Form::text('sort',$data['sort'],['class'=>'form-control','required'=>'']) !!}

            </div>
        </div>


        <div class="form-group">
            {!! Form::label('title','状态',['class'=>'col-sm-3 control-label']) !!}
            <div class="col-sm-8">

                启用{!! Form::radio('status',1,false) !!}

                禁用 {!! Form::radio('status',0,false) !!}

            </div>
        </div>


        <div class="form-group">
            {!! Form::label('title','说明',['class'=>'col-sm-3 control-label']) !!}
            <div class="col-sm-8">

                {!! Form::textarea('content',$data['content'],['class'=>'form-control m-b','required'=>'']) !!}

                <span class="help-block m-b-none"><i class="fa fa-info-circle">说明</i> </span>
            </div>
        </div>


        {!! Form::hidden('id',$data['id']) !!}
        {!! Form::hidden('position_id',$data['position_id']) !!}
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
    <script src="{{asset('styles/lib/laydate/laydate.js')}}"></script>
    <script src="{{asset('styles/lib/webuploader/webuploader.min.js')}}"></script>
    <script src="{{asset('styles/lib/webuploader/webuploader.image.js')}}"></script>
    <script>
        DCNET.setValue('status', '{{$data['status']==0?0:1}}');
        webuploadinit('uploader', 'filePicker');

    </script>
@endsection
