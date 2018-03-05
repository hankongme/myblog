@extends('home.common.common')

@section('main')
    <main>
        <section class="article-content">
            <div class="container">
                <div class="row">
                    <div class="col-md-9 col-xs-12">
                        <div class="col-md-12">
                            <div class="article">
                                <h1 class="display-5 text-center">{{$info['title']}}</h1>
                                <div class="justify-content-center text-center">
                                    <span style="padding-right: 1rem">发布:{!! date('Y-m-d',strtotime($info['created_at'])) !!}</span>
                                    <span style="padding-left: 1rem">BY:寒空</span>
                                </div>
                                <hr class="my-1">
                                <div id="content" class="article-bottom">
                                {!! $info['content_to_html'] !!}
                                </div>

                                <div class="content-footer">
                                    <div class="float-md-left">
                                        <p>上一篇:
                                            <a href="/a/{{$prev['id']}}">{{$prev['title']}}</a>
                                        </p>
                                    </div>

                                    <div class="float-md-right">
                                        <p>下一篇:
                                            <a href="/a/{{$next['id']}}">
                                                {{$next['title']}}
                                            </a>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <div class="container-fluid">

                        </div>
                    </div>
                    @include('home.common.right')
                </div>
            </div>
        </section>

    </main>
@endsection
