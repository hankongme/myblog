@extends('admin.common.common')
@section('stylecss')
    <link rel="stylesheet" type="text/css" href="{{asset('styles/lib/webuploader/webuploader-image.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('styles/lib/webuploader/webuploader.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('styles/lib/editormd/editormd.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('styles/lib/select2/css/select2.css')}}">
@endsection
@section('content')


    <main class="site-page">

        <div class="panel">
            <div class="panel-heading">
            </div>
            <div class="panel-body container-fluid">

                    {!!Form::open(['url'=>$data['submit_url'],'method'=>'post','id'=>'submitform','class'=>'form-horizontal m-t','role'=>'form'])!!}

                    <div class="form-group">
                        {!! Form::label('title','标题',['class'=>'col-sm-2 control-label']) !!}
                        <div class="col-sm-10">
                            {!! Form::text('title',$data['title'],['class'=>'form-control col-sm-6']) !!}
                        </div>
                    </div>



                    <div class="form-group">
                        {!! Form::label('title','文章分类',['class'=>'col-sm-2 control-label']) !!}
                        <div class="col-sm-10">
                            {!! Form::select('category_id',$category,$data['category_id'],['class'=>'form-control m-b','required'=>'']) !!}
                        </div>
                    </div>


                    <div class="form-group">
                        {!! Form::label('title','封面图片',['class'=>'col-sm-2 control-label']) !!}
                        <div class="col-sm-10">
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
                    {!! Form::label('title','标签',['class'=>'col-sm-2 control-label']) !!}
                    <div class="col-sm-5">

                        <select id="mytags" name="tags[]" class="form-control" multiple="multiple">
                            @if(!empty($tags))
                                @foreach($tags as $item)
                                    <option value="{{$item->id}}" selected="selected">{{$item->name}}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>

                </div>

                    <div class="form-group">
                        {!! Form::label('title','排序',['class'=>'col-sm-2 control-label']) !!}
                        <div class="col-sm-5">
                            {!! Form::text('sort',$data['sort'],['class'=>'form-control col-sm-6']) !!}
                            <span class="help-block m-b-none"><i class="fa fa-info-circle"></i>越大越靠前</span>
                        </div>

                    </div>


                <div class="form-group">
                    {!! Form::label('title','是否显示',['class'=>'col-sm-2 control-label']) !!}
                    <div class="col-sm-10">

                        <input type="checkbox" class="js-switch" name="status" value="1">

                    </div>
                </div>
                <div class="form-group">
                    {!! Form::label('title','精选文章(显示在首页)',['class'=>'col-sm-2 control-label']) !!}
                    <div class="col-sm-10">

                        <input type="checkbox" class="js-switch" name="is_best" value="1">

                    </div>
                </div>

                <div class="form-group">
                    {!! Form::label('title','是否置顶',['class'=>'col-sm-2 control-label']) !!}
                    <div class="col-sm-10">

                        <input type="checkbox" class="js-switch" name="is_top" value="1">

                    </div>
                </div>

                <div class="form-group">
                    {!! Form::label('title','是否MD',['class'=>'col-sm-2 control-label']) !!}
                    <div class="col-sm-10">

                        <input type="checkbox" class="js-switch" name="is_md" value="1">

                    </div>
                </div>

                <div class="form-group">
                    {!! Form::label('title','简介',['class'=>'col-sm-2 control-label hide']) !!}
                    <div class="col-sm-10" id="brief">
                        {!! Form::textarea('brief',$data['brief'],['id'=>'brief','style'=>'display:none','class'=>"layui-textarea"]) !!}
                    </div>
                </div>

                    <div class="form-group" id="content_md">
                        {!! Form::label('title','文章内容',['class'=>'col-sm-2 control-label hide']) !!}
                        <div class="col-sm-10" id="content">
                            {!! Form::textarea('content',$data['content'],['style'=>'display:none']) !!}
                        </div>
                    </div>



                    <div class="form-group">
                        {!! Form::label('title','关键字',['class'=>'col-sm-2 control-label']) !!}
                        <div class="col-sm-10">
                            {!! Form::text('keywords',$data['keywords'],['class'=>'form-control col-sm-6']) !!}
                        </div>
                    </div>




                    <div class="form-group">
                        {!! Form::label('title','描述',['class'=>'col-sm-2 control-label']) !!}
                        <div class="col-sm-10">
                            {!! Form::textarea('description',$data['description'],['class'=>'form-control col-sm-6']) !!}
                        </div>
                    </div>


                    {!! Form::hidden('id',$data['id'],['id'=>'id']) !!}

                    <div class="form-group">
                        <div class="col-sm-10 col-sm-offset-3">
                            <button class="btn btn-primary" type="button" id="submit_article">提交</button>
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


    <script src="{{asset('styles/lib/webuploader/webuploader.min.js')}}"></script>
    <script src="{{asset('styles/lib/editormd/editormd.js')}}"></script>
    <script src="{{asset('styles/lib/webuploader/webuploader.image.js')}}"></script>
    <script src="{{asset('styles/lib/select2/js/select2.min.js')}}"></script>

    <script>
        webuploadinit('uploader', 'filePicker');

        var editor2 = editormd('brief',{
            width   : "100%",
            height  : 180,
            syncScrolling : "single",
            toolbarAutoFixed: true,
            gotoLine:false,
            emoji:false,
            autoFocus:false,
            saveHTMLToTextarea:true,
            path    : "/styles/lib/editormd/lib/",
            imageUpload : true,
            imageUploadURL : '{!! url(__ADMIN_PATH__.'/tool/upload_image') !!}'
        });
        //

        var editor = editormd('content',{
            width   : "100%",
            height  : 640,
            syncScrolling : "single",
            toolbarAutoFixed: true,
            gotoLine:false,
            emoji:false,
            autoFocus:false,
            saveHTMLToTextarea:true,
            path    : "/styles/lib/editormd/lib/",
            imageUpload : true,
            imageUploadURL : '{!! url(__ADMIN_PATH__.'/tool/upload_image') !!}'
        });


        function formatTags (tags) {
            if(tags.name||tags.tag){
                var this_tag = tags.name?tags.name:tags.tag;
                return "<div class='select2-result-repository clearfix'>" +
                        "<div class='select2-result-repository__meta'>" +
                        "<div class='select2-result-repository__title'>" +
                        this_tag +
                        "</div></div></div>";

            }
        }


        function formatTagsSelection (tags) {
            return tags.name ? tags.name : tags.text;
        }


        $("#mytags").select2({
            tags: true,
            placeholder: '选择相关标签',
            minimumInputLength: 1,
            ajax: {
                url: '/{{__ADMIN_PATH__.'/tag/key'}}',
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        q: params.term
                    };
                },
                processResults: function (data, params) {
                    return {
                        results: data
                    };
                },
                cache: false
            },
            templateResult: formatTags,
            templateSelection: formatTagsSelection,
            escapeMarkup: function (markup) { return markup; }
        });

        $("#submit_article").click(function(){
            submit_form();
        })

        DCNET.setValue('status', '{{$data['status'] or 1}}');
        DCNET.setValue('is_best', '{{$data['is_best'] or 1}}');
        DCNET.setValue('is_top', '{{$data['is_top'] or 0}}');
        DCNET.setValue('is_md', '{{$data['is_md'] or 1}}');


    </script>

    @include('admin.plugins.from')
@endsection
