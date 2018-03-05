<header class="fixed-top">
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container">
            <a class="navbar-brand" href="/">寒空的博客</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarResponsive">
                <ul class="navbar-nav ml-auto">
                    @if(!empty($head_nav))
                    @foreach($head_nav as $k=>$v)
                    <li class="nav-item {{$v['class_name']}}" data-url="{{$v['url']}}">
                        <a class="nav-link" href="{!! url($v['url']) !!}">{{$v['name']}}</a>
                    </li>
                        @endforeach
                    @endif

                </ul>
            </div>
        </div>
    </nav>
</header>
