<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="format-detection" content="telephone=no">
    <title>{{$web_title or ''}}</title>
    <link href="" rel="stylesheet">
    <link rel="stylesheet" href="{{asset('styles/lib/layui/css/layui.css')}}" media="all">
    <link rel="stylesheet" href="{{asset('styles/css/admin.css')}}" media="all">
    <link rel="stylesheet" href="{{asset('styles/css/font-awesome-4.7.0/css/font-awesome.css')}}" media="all">
    <link rel="stylesheet" href="{{asset('styles/lib/bootstrap/css/bootstrap.css')}}" media="all">
    <link rel="stylesheet" href="{{asset('styles/lib/webIcons/css/web-icons.css')}}" media="all">
    <link rel="stylesheet" href="{{asset('styles/css/site.css')}}" media="all">
    <style>
        .x-body{
            width: 100%;
            text-align: center;
        }
        .x-form{
            background-color: #fff;
            padding: 20px 10px;
            display: inline-block;
            margin:auto;
        }
        td{
            vertical-align: middle !important;
        }
    </style>
    @yield('stylecss')


</head>

<body id="body" >

<div id="app">
    @yield('content')
</div>


<script src="{{asset('styles/js/jquery.min.js')}}"></script>
<script src="{{asset('styles/js/contabs.min.js')}}"></script>
<script src="{{asset('styles/lib/bootstrap/js/bootstrap.min.js')}}"></script>
<script src="{{asset('styles/lib/layer/layer.js')}}"></script>

@include('admin.common.script')

@yield('stylejs')

@include('admin.common.extend')
</body>
</html>
