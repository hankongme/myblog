@extends('admin.common.common')
@section('stylecss')

@endsection
@section('content')

    <div class="ibox float-e-margins">

        <div class="ibox-content">

            <div class="col-sm-12">

                {!!Form::open(['url'=>$data['submit_url'],'method'=>'post','id'=>'submitform','class'=>'form-horizontal m-t','role'=>'form'])!!}

                <div class="col-sm-12">
                    <div class="tabs-container">
                        <ul class="nav nav-tabs" id="tab_nav">
                            @if(!empty($data['data']))


                                @foreach($data['data'] as $k=>$v)

                                    <li class=""><a data-toggle="tab" href="#tab-{{$k}}"
                                                    aria-expanded="false">{{$v['name']}}</a>
                                    </li>
                                @endforeach

                            @endif


                        </ul>
                        <div class="tab-content" id="tab_content">

                            @if(!empty($data['data']))
                                @foreach($data['data'] as $item=>$list)
                                    <div id="tab-{{$item}}" class="tab-pane">
                                        <div class="panel-body">
                                            @if(!empty($list['list'])&&is_array($list['list']))
                                                @foreach($list['list'] as $k=>$v)
                                                    <div class="form-group">
                                                        {!! Form::label('title',$v['title'],['class'=>'col-sm-3 control-label']) !!}
                                                        <div class="col-sm-8">

                                                            @if($v['type'] == 0)
                                                            {!! Form::text('config['.$v['name'].']',$v['value'],['class'=>'form-control','required'=>'','placeholder'=>$v['remark']]) !!}
                                                            @elseif($v['type'] == 1)
                                                            {!! Form::text('config['.$v['name'].']',$v['value'],['class'=>'form-control','required'=>'','placeholder'=>$v['remark']]) !!}
                                                            @elseif($v['type'] == 2)
                                                            {!! Form::textarea('config['.$v['name'].']',$v['value'],['class'=>'form-control','required'=>'','placeholder'=>$v['remark'],'rows'=>5]) !!}
                                                            @elseif($v['type'] == 3)
                                                            {!! Form::textarea('config['.$v['name'].']',$v['value'],['class'=>'form-control','required'=>'','placeholder'=>$v['remark'],'rows'=>5]) !!}
                                                            @elseif($v['type'] == 4)
                                                            {!! Form::select('config['.$v['name'].']',$v['extra'],$v['value'],['class'=>'form-control','required'=>'','placeholder'=>$v['remark']]) !!}
                                                            @endif

                                                        </div>
                                                    </div>

                                                @endforeach




                                            @endif
                                        </div>
                                    </div>

                                @endforeach
                            @endif


                        </div>


                    </div>
                </div>
            </div>


            <div class="form-group">
                <div class="col-sm-8 col-sm-offset-3">
                    <button class="btn btn-primary" type="button" onclick="submit_form();">提交</button>
                </div>
            </div>

            <input type="hidden" name="not_parent" value="1">

            {!! Form::close() !!}
        </div>

    </div>

@endsection

@section('stylejs')
    <script>
        $('#tab_nav').children(":first").addClass('active');
        $('#tab_content').children(":first").addClass('active');

    </script>
    <script src="{{asset('styles/js/plugins/validate/jquery.validate.min.js')}}"></script>
    <script src="{{asset('styles/js/plugins/validate/messages_zh.min.js')}}"></script>

@endsection
