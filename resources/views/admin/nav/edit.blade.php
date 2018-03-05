@extends('admin.common.common')
@section('stylecss')


@endsection
@section('content')

    <div class="ibox float-e-margins">

        <div class="ibox-content">
            {!!Form::open(['url'=>$data['submit_url'],'method'=>'post','id'=>'submitform','class'=>'form-horizontal m-t','role'=>'form'])!!}

            <div class="form-group">
                {!! Form::label('title','导航名称',['class'=>'col-sm-3 control-label']) !!}
                <div class="col-sm-8">
                    {!! Form::text('name',$data['name'],['class'=>'form-control col-sm-6']) !!}
                </div>
            </div>


            <div class="form-group">
                {!! Form::label('url','跳转链接',['class'=>'col-sm-3 control-label']) !!}
                <div class="col-sm-8">
                    {!! Form::text('url',$data['url'],['class'=>'form-control col-sm-6','id'=>'url']) !!}
                </div>
            </div>



            <div class="form-group">
                {!! Form::label('status','是否有效',['class'=>'col-sm-3 control-label']) !!}
                <div class="col-sm-8">
                    {!! Form::checkbox('status',1,true,['class'=>'js-switch']) !!}
                </div>
            </div>



            <div class="form-group">
                {!! Form::label('url_type','链接类型',['class'=>'col-sm-3 control-label']) !!}
                <div class="col-sm-8 i-checks">
                    <input class="format_radio" id="inputRadiosChecked" name="url_type" value="0" data-name="内部链接" type="radio">
                    <input class="format_radio" id="inputRadiosChecked" name="url_type" value="1" data-name="外部链接" type="radio">
                </div>
            </div>



            <div class="form-group">
                {!! Form::label('address','排序(越大越靠前)',['class'=>'col-sm-3 control-label']) !!}
                <div class="col-sm-8">
                    {!! Form::email('sort',$data['sort'],['class'=>'form-control col-sm-6']) !!}
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


@endsection

@section('stylejs')

    <script>
        DCNET.setValue('status', '{{$data['status'] or 1}}');
        DCNET.setValue('url_type', '{{$data['url_type'] or 0}}');

        $("input[name='url_type']").on('ifChecked', function(event){
            var url_type = $(this).val();
            if(url_type == 0){
                $('#select_industry').show();
            }else{
                $('#select_industry').hide();
            }
            msg_success('链接类型已改变,请重新填写地址!');
            $('#url').val('');
        });
    </script>

    @include('admin.plugins.from')
@endsection
