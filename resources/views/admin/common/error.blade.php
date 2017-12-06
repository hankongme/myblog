@extends('admin.common.common')
@section('content')
    <div class="middle-box text-center animated fadeInDown">
        <h1>404</h1>
        <h3 class="font-bold">{{$data or '未找到该页面'}}</h3>

        <div class="error-desc">

            <br/><a onclick="layer_close()" class="btn btn-primary m-t">返回</a>

        </div>
    </div>

@stop
