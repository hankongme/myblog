@extends('admin.common.common')
@section('stylecss')

@endsection
@section('content')

    <div class="ibox float-e-margins">

        <div class="ibox-content">

            {!!Form::open(['url'=>$data['submit_url'],'method'=>'post','id'=>'submitform','class'=>'form-horizontal m-t','role'=>'form'])!!}
            <div class="form-group">
                {!! Form::label('title','菜单名称',['class'=>'col-sm-3 control-label']) !!}
                <div class="col-sm-8">

                    {!! Form::text('title',$data['title'],['class'=>'form-control','required'=>'']) !!}


                </div>
            </div>


            <div class="form-group">
                {!! Form::label('title','类型',['class'=>'col-sm-3 control-label']) !!}
                <div class="col-sm-8">

                    {!! Form::select('type',$menu_type,$data['type'],['class'=>'form-control','required'=>'']) !!}


                </div>
            </div>


            <div class="form-group">
                {!! Form::label('title','上级菜单',['class'=>'col-sm-3 control-label']) !!}
                <div class="col-sm-8">

                    {!! Form::select('pid',$menu_list,$data['pid'],['class'=>'form-control','required'=>'']) !!}


                </div>
            </div>


            <div class="form-group" id="url">
                {!! Form::label('title','链接',['class'=>'col-sm-3 control-label']) !!}
                <div class="col-sm-8">

                    {!! Form::text('url',$data['url'],['class'=>'form-control','required'=>'']) !!}


                </div>
            </div>

            <div class="form-group" id="keyword">
                {!! Form::label('title','关键字',['class'=>'col-sm-3 control-label']) !!}
                <div class="col-sm-8">

                    {!! Form::text('keyword',$data['keyword'],['class'=>'form-control','required'=>'']) !!}


                </div>
            </div>

            <div class="form-group">
                {!! Form::label('title','排序',['class'=>'col-sm-3 control-label']) !!}
                <div class="col-sm-8">

                    {!! Form::text('sort',$data['sort'],['class'=>'form-control','required'=>'']) !!}

                    <span class="help-block m-b-none"><i class="fa fa-info-circle"></i>越小越靠前</span>

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
        DCNET.setValue("type","{{$data['type']}}");
        DCNET.setValue("pid","{{$data['pid']}}");
        change_show();
        $("select[name='type']").change(function(){
            change_show();
        });

        function change_show(){
            var type = $("select[name='type']").val();
            if(type == 'click'){
                $('#keyword').show();
                $('#url').hide();
            }else if(type == 'view'){
                $('#keyword').hide();
                $('#url').show();
            }else{
                if(type=='parent'){
                    $('pid').hide();
                    DCNET.setValue("pid", 0);
                }
                $('#keyword').hide();
                $('#url').hide();
            }
        }
    </script>
@endsection
