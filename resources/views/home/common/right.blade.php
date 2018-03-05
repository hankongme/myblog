<div class="col-md-3 d-none d-md-block d-lg-block">
    <div>
        <div class="category bg-light">
            <p class="lead">分类目录</p>
            <hr class="mt-2">
            <div class="category-list">

                <div class="list-group">
                    @if(!empty($category))
                        @foreach($category as $k=>$v)
                        <a href="/c/{{$v['id']}}" class="list-group-item border-0  list-group-item-action">
                            {{$v['name']}}
                            <span class="badge badge-primary badge-pill">{{$v['article_num']}}</span>
                        </a>
                        @endforeach
                    @endif
                </div>

            </div>
        </div>
    </div>
    <div>
        <div class="category bg-light">
            <p class="lead">文章归档</p>
            <hr class="mt-2">
            <div class="category-list">
                <div class="list-group">
                    @if(!empty($article_date))
                        @foreach($article_date as $k=>$v)
                    <a href="/d/{{$v['date']}}" class="list-group-item border-0 list-group-item-action">
                        {{$v['date']}}
                        <span class="badge badge-primary badge-pill">{{$v['counts']}}</span>
                    </a>
                        @endforeach
                    @endif

                </div>

            </div>
        </div>
    </div>
</div>
