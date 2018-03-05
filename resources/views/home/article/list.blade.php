@extends('home.common.common')

@section('main')
    <main>
        <section class="py-4 content">
            <div class="container">
                <div class="row">
                    <div class="col-md-9 col-xs-12">
                        @if(!empty($data))
                            @foreach($data as $k=>$v)
                        <div class="col-md-12">
                            <div class="jumbotron article-list">
                                <a class="h1 article-title" href="/a/{{$v['id']}}">{{$v['title']}}</a>
                                <p class="lead">

                                    {!! $v['brief_to_html'] !!}

                                </p>
                                <hr class="my-4">
                                <div class="article-bottom">
                                    <p class="float-md-left text-center">
                                        <a >{!! date('Y-m-d',strtotime($v['created_at'])) !!}</a>
                                    </p>

                                    <p class="float-md-left ml-1 text-center">
                                        @if(!empty($v['tags']))
                                            @foreach($v['tags'] as $k=>$tag)
                                        <a href="/t/{{$tag['tag_name']}}"><span class="badge badge-pill badge-primary">{{$tag['tag_name']}}</span></a>
                                            @endforeach
                                        @endif
                                    </p>

                                    <p class="float-md-right text-center">
                                        <a class="btn btn-primary" href="/a/{{$v['id']}}" role="button">查看全文</a>
                                    </p>

                                </div>
                            </div>
                        </div>
                            @endforeach
                        @endif

                        <div class="container-fluid">
                            {{$page}}
                        </div>

                    </div>
                    @include('home.common.right')
                </div>
            </div>
        </section>

    </main>
@endsection
