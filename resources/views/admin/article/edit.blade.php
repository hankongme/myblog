@extends('admin.common.common')
@section('stylecss')
    <link rel="stylesheet" type="text/css" href="{{asset('styles/lib/webuploader/webuploader-image.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('styles/lib/webuploader/webuploader.css')}}">
@endsection
@section('content')


    <main class="site-page">

        <div class="panel">
            <div class="panel-heading">
            </div>
            <div class="panel-body container-fluid">

                    {!!Form::open(['url'=>$data['submit_url'],'method'=>'post','id'=>'submitform','class'=>'form-horizontal m-t','role'=>'form'])!!}

                    <div class="form-group">
                        {!! Form::label('title','标题',['class'=>'col-sm-3 control-label']) !!}
                        <div class="col-sm-8">
                            {!! Form::text('title',$data['title'],['class'=>'form-control col-sm-6']) !!}
                        </div>
                    </div>






                    <div class="form-group">
                        {!! Form::label('title','文章分类',['class'=>'col-sm-3 control-label']) !!}
                        <div class="col-sm-8">
                            {!! Form::select('category_id',$category,$data['category_id'],['class'=>'form-control m-b','required'=>'']) !!}
                        </div>
                    </div>


                    <div class="form-group">
                        {!! Form::label('title','封面图片',['class'=>'col-sm-3 control-label']) !!}
                        <div class="col-sm-8">
                            @if($data['cover_img'])
                                <img src="{{$data['cover_img']}}" width="100">
                            @endif
                            <div id="uploader" class="uploader wu-example">
                                <input type="hidden" name="cover_img" value="{{$data['cover_img']}}" class="uploader_input">
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
                        <span class="help-block m-b-none"><i class="fa fa-info-circle"></i> 大小不超过1M</span>
                    </div>



                    <div class="form-group">
                        {!! Form::label('title','排序',['class'=>'col-sm-3 control-label']) !!}
                        <div class="col-sm-8">
                            {!! Form::text('sort',$data['sort'],['class'=>'form-control col-sm-6']) !!}
                            <span class="help-block m-b-none"><i class="fa fa-info-circle"></i>越大越靠前</span>
                        </div>

                    </div>


                    <div class="form-group">
                        {!! Form::label('title','精选文章(显示在首页)',['class'=>'col-sm-3 control-label']) !!}
                        <div class="col-sm-8">
                            是{!! Form::radio('is_best',1,false) !!}
                            否 {!! Form::radio('is_best',0,false) !!}

                        </div>
                    </div>

                    <div class="form-group">
                        {!! Form::label('title','是否置顶',['class'=>'col-sm-3 control-label']) !!}
                        <div class="col-sm-8">
                            是{!! Form::radio('is_top',1,false) !!}
                            否 {!! Form::radio('is_top',0,false) !!}
                        </div>
                    </div>
                    <div class="form-group">
                        {!! Form::label('title','文章内容',['class'=>'col-sm-3 control-label']) !!}
                        <div class="col-sm-8">
                            {!! Form::textarea('content',$data['content'],['id'=>'content']) !!}
                        </div>
                    </div>
                    <div class="form-group">
                        {!! Form::label('title','关键字',['class'=>'col-sm-3 control-label']) !!}
                        <div class="col-sm-8">
                            {!! Form::text('keywords',$data['keywords'],['class'=>'form-control col-sm-6']) !!}
                        </div>
                    </div>



                    <div class="form-group">
                        {!! Form::label('title','简介',['class'=>'col-sm-3 control-label']) !!}
                        <div class="col-sm-8">
                            {!! Form::textarea('brief',$data['brief'],['class'=>'form-control col-sm-6']) !!}
                        </div>
                    </div>

                    <div class="form-group">
                        {!! Form::label('title','描述',['class'=>'col-sm-3 control-label']) !!}
                        <div class="col-sm-8">
                            {!! Form::textarea('description',$data['description'],['class'=>'form-control col-sm-6']) !!}
                        </div>
                    </div>


                    {!! Form::hidden('id',$data['id'],['id'=>'id']) !!}

                    <div class="form-group">
                        <div class="col-sm-8 col-sm-offset-3">
                            <button class="btn btn-primary" type="button" onclick="submit_form();">提交</button>
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

    <script src="{{asset('styles/js/tools/ueditor/ueditor.config.js')}}"></script>
    <script src="{{asset('styles/js/tools/ueditor/ueditor.all.js')}}"></script>
    <script src="{{asset('styles/lib/webuploader/webuploader.min.js')}}"></script>
    <script src="{{asset('styles/lib/webuploader/webuploader.image.js')}}"></script>

    <script>

        webuploadinit('uploader', 'filePicker');
        var ue = UE.getEditor('content');
        DCNET.setValue('is_best', '{{$data['is_best'] or 1}}');
        DCNET.setValue('is_top', '{{$data['is_top'] or 0}}');




    </script>
@endsection
