<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <title>{!! $web_title !!}</title>
    <meta name="description" content="{{$web_description}}"/>
    <meta name="keywords" content="{{$web_keywords}}" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="stylesheet" href="{{ mix('css/app.css') }}" media="screen">

</head>
<body>
@include('home.common.header')

@yield('main')

@include('home.common.footer')
</body>
<script src="{{ mix('js/app.js') }}"></script>
<!--[if lt IE 9]>
<script src="/js/html5shiv.js"></script>
<script src="/js/respond.min.js"></script>
<![endif]-->
</html>
