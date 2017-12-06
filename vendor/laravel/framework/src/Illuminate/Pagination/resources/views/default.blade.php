    <div class="row">
        <div class="col-sm-6">
            <div class="dataTables_info" id="dataTable_info" role="alert" aria-live="polite" aria-relevant="all">当前第{{$paginator->currentPage()}}页，共 {{$paginator->total()}} 条记录</div>
        </div>
        <div class="col-sm-6">
            <div class="dataTables_paginate paging_simple_numbers" id="dataTable_paginate">

                <ul class="pagination">
                    @if ($paginator->onFirstPage())
                        <li class="paginate_button previous disabled">
                            <a href="#">上一页</a>
                        </li>
                    @else
                        <li class="paginate_button previous">
                            <a href="{{ $paginator->previousPageUrl() }}">上一页</a>
                        </li>
                    @endif

                    @foreach ($elements as $element)
                        {{-- "Three Dots" Separator --}}
                        @if (is_string($element))
                            <li class="paginate_button active"><a href="#">{{ $element }}</a></li>
                        @endif

                        {{-- Array Of Links --}}
                        @if (is_array($element))
                            @foreach ($element as $page => $url)
                                @if ($page == $paginator->currentPage())
                                    <li class="paginate_button active"> <a href="#">{{ $page }}</a></li>
                                @else
                                    <li class="paginate_button " ><a href="{{ $url }}">{{ $page }}</a></li>
                                @endif
                            @endforeach
                        @endif
                    @endforeach

                    {{-- Next Page Link --}}
                    @if ($paginator->hasMorePages())
                        <li class="paginate_button next"  id="dataTable_next">
                            <a href="{{ $paginator->nextPageUrl() }}">下一页</a></li>
                    @else
                        <li class="paginate_button next disabled"  id="dataTable_next">
                            <a href="#">下一页</a></li>
                    @endif

                </ul>
            </div>
        </div>
    </div>
