@extends('admin.common.common')
@section('stylecss')
    <link rel="stylesheet" href="{{asset('styles/lib/dataTables/dataTables.bootstrap.css')}}" media="all">
    <link rel="stylesheet" href="{{asset('styles/lib/dataTables/dataTables.responsive.css')}}" media="all">



@endsection
@section('content')
    <main class="site-page">

        <div class="panel">
            <div class="panel-heading">
                <h3 class="panel-title">管理员列表</h3>
                <div class="panel-actions">
                    <input class="form-control round" id="inputRounded" type="text">

                    <input  class="format_checkbox" id="inputChecked" checked="" type="checkbox" data-name="哈哈">

                    <input class="format_radio" id="inputRadiosChecked" name="inputRadios" checked="" type="radio">

                    <div class="radio-custom radio-primary">
                        <input id="inputRadiosChecked" name="inputRadios" type="radio">
                        <label for="inputRadiosChecked">选中</label>
                    </div>

                    <button class="btn btn-primary">
                        新增1
                    </button>
                    <button class="btn btn-primary">
                        新增2
                    </button>

                    <button class="btn btn-primary">
                        新增3
                    </button>

                    <button class="btn btn-primary">
                        新增4
                    </button>

                    <button class="btn btn-primary">
                        新增5
                    </button>

                </div>
            </div>
            <div class="panel-body nav-tabs-animate">

                <div class="row">
                    <div class="col-sm-12">
                        <form autocomplete="off">
                            <div class="row">
                                <div class="form-group col-sm-6">
                                    <label class="control-label" for="inputBasicName">姓名</label>
                                    <input class="form-control" id="inputBasicName" name="inputName" placeholder="姓名" autocomplete="off" type="text">
                                </div>
                                <div class="form-group col-sm-6">
                                    <label class="control-label" for="inputBasicNickName">昵称</label>
                                    <input class="form-control" id="inputBasicNickName" name="inputNickName" placeholder="昵称" autocomplete="off" type="text">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label">性别</label>
                                <div>

                                        <input id="inputBasicMale" class="format_radio" name="inputGender" type="radio" data-name="男">

                                    <div class="radio-custom radio-default radio-inline">
                                        <input id="inputBasicFemale" name="inputGender" checked="" type="radio">
                                        <label for="inputBasicFemale">女</label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label" for="inputBasicEmail">E-Mail</label>
                                <input class="form-control" id="inputBasicEmail" name="inputEmail" placeholder="邮箱" autocomplete="off" type="email">
                            </div>
                            <div class="form-group">
                                <label class="control-label" for="inputBasicPassword">密码</label>
                                <input class="form-control" id="inputBasicPassword" name="inputPassword" placeholder="密码" autocomplete="off" type="password">
                            </div>
                            <div class="form-group">
                                <div class="checkbox-custom checkbox-default">
                                    <input id="inputBasicRemember" name="inputCheckbox" checked="" autocomplete="off" type="checkbox">
                                    <label for="inputBasicRemember">自动登录</label>
                                </div>
                            </div>
                            <div class="form-group">
                                <button type="button" class="btn btn-primary">登录</button>
                            </div>
                        </form>
                    </div>
                </div>


                <div class="row">
                    <div class="col-sm-12">
                        <form class="form-horizontal">
                            <div class="form-group">
                                <label class="col-sm-3 control-label">您的姓名：</label>
                                <div class="col-sm-9">
                                    <input class="form-control" name="name" placeholder="姓名" autocomplete="off" type="text">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">您的性别：</label>
                                <div class="col-sm-9">
                                    <div class="radio-custom radio-default radio-inline">
                                        <input id="inputHorizontalMale" name="inputRadiosMale2" type="radio">
                                        <label for="inputHorizontalMale">男</label>
                                    </div>
                                    <div class="radio-custom radio-default radio-inline">
                                        <input id="inputHorizontalFemale" name="inputRadiosMale2" checked="" type="radio">
                                        <label for="inputHorizontalFemale">女</label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">您的邮箱：</label>
                                <div class="col-sm-9">
                                    <input class="form-control" name="email" placeholder="@email.com" autocomplete="off" type="email">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">您的简介：</label>
                                <div class="col-sm-9">
                                    <textarea class="form-control" placeholder="简介"></textarea>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-9 col-sm-offset-3">
                                    <button type="button" class="btn btn-primary">提交</button>
                                    <button type="reset" class="btn btn-default btn-outline">重置</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="table-responsive">
                    <table  class="table table-bordered table-hover dataTable table-striped width-full text-nowrap" id="dataTable"  data-paging="false" data-ordering="false" data-info="false">
                        <thead>
                        <tr>
                            <th>
                                <input class="form-control" name="name" placeholder="姓名" autocomplete="off" type="text">
                            </th>
                            <th>
                                <input class="form-control" name="name" placeholder="姓名" autocomplete="off" type="text">
                            </th>
                            <th>
                                <input class="form-control" name="name" placeholder="姓名" autocomplete="off" type="text">
                            </th>
                            <th>
                                <input class="form-control" name="name" placeholder="姓名" autocomplete="off" type="text">
                            </th>
                            <th>
                                <input class="form-control" name="name" placeholder="姓名" autocomplete="off" type="text">
                            </th>
                            <th>
                                <input class="form-control date" name="name" placeholder="姓名" autocomplete="off" type="text">
                            </th>
                            <th>
                                <button type="button" class="btn btn-primary">查询</button>
                            </th>
                        </tr>

                        <tr>
                            <th>姓名</th>
                            <th>职位</th>
                            <th>工作地点</th>
                            <th>年龄</th>
                            <th>入职时间</th>
                            <th>年薪</th>
                            <th>年薪</th>
                        </tr>
                        </thead>

                        <tbody>
                        <tr>
                            <td>李霞</td>
                            <td>系统架构师</td>
                            <td>北京</td>
                            <td>61</td>
                            <td>2011/04/25</td>
                            <td>&yen;320,800</td>
                            <td class="text-nowrap">
                                <button type="button" class="btn btn-sm btn-icon btn-flat btn-default">
                                    <i class="icon wb-wrench" aria-hidden="true"></i>
                                </button>
                                <button type="button" class="btn btn-sm btn-icon btn-flat btn-default" data-toggle="tooltip" data-original-title="删除">
                                    <i class="icon wb-close" aria-hidden="true"></i>
                                </button>
                            </td>
                        </tr>
                        <tr>
                            <td>李霞</td>
                            <td>系统架构师</td>
                            <td>北京</td>
                            <td>61</td>
                            <td>2011/04/25</td>
                            <td>&yen;320,800</td>
                            <td class="text-nowrap">
                                <button type="button" class="btn btn-sm btn-icon btn-flat btn-default">
                                    <i class="icon wb-wrench" aria-hidden="true"></i>
                                </button>
                                <button type="button" class="btn btn-sm btn-icon btn-flat btn-default" data-toggle="tooltip" data-original-title="删除">
                                    <i class="icon wb-close" aria-hidden="true"></i>
                                </button>
                            </td>
                        </tr>
                        <tr>
                            <td>李霞</td>
                            <td>系统架构师</td>
                            <td>北京</td>
                            <td>61</td>
                            <td>2011/04/25</td>
                            <td>&yen;320,800</td>
                            <td class="text-nowrap">
                                <button type="button" class="btn btn-sm btn-icon btn-flat btn-default">
                                    <i class="icon wb-wrench" aria-hidden="true"></i>
                                </button>
                                <button type="button" class="btn btn-sm btn-icon btn-flat btn-default" data-toggle="tooltip" data-original-title="删除">
                                    <i class="icon wb-close" aria-hidden="true"></i>
                                </button>
                            </td>
                        </tr>
                        <tr>
                            <td>李霞</td>
                            <td>系统架构师</td>
                            <td>北京</td>
                            <td>61</td>
                            <td>2011/04/25</td>
                            <td>&yen;320,800</td>
                            <td class="text-nowrap">
                                <button type="button" class="btn btn-sm btn-icon btn-flat btn-default">
                                    <i class="icon wb-wrench" aria-hidden="true"></i>
                                </button>
                                <button type="button" class="btn btn-sm btn-icon btn-flat btn-default" data-toggle="tooltip" data-original-title="删除">
                                    <i class="icon wb-close" aria-hidden="true"></i>
                                </button>
                            </td>
                        </tr>
                        <tr>
                            <td>李霞</td>
                            <td>系统架构师</td>
                            <td>北京</td>
                            <td>61</td>
                            <td>2011/04/25</td>
                            <td>&yen;320,800</td>
                            <td class="text-nowrap">
                                <button type="button" class="btn btn-sm btn-icon btn-flat btn-default">
                                    <i class="icon wb-wrench" aria-hidden="true"></i>
                                </button>
                                <button type="button" class="btn btn-sm btn-icon btn-flat btn-default" data-toggle="tooltip" data-original-title="删除">
                                    <i class="icon wb-close" aria-hidden="true"></i>
                                </button>
                            </td>
                        </tr>   <tr>
                            <td>李霞</td>
                            <td>系统架构师</td>
                            <td>北京</td>
                            <td>61</td>
                            <td>2011/04/25</td>
                            <td>&yen;320,800</td>
                            <td class="text-nowrap">
                                <button type="button" class="btn btn-sm btn-icon btn-flat btn-default">
                                    <i class="icon wb-wrench" aria-hidden="true"></i>
                                </button>
                                <button type="button" class="btn btn-sm btn-icon btn-flat btn-default" data-toggle="tooltip" data-original-title="删除">
                                    <i class="icon wb-close" aria-hidden="true"></i>
                                </button>
                            </td>
                        </tr>
                        <tr>
                            <td>李霞</td>
                            <td>系统架构师</td>
                            <td>北京</td>
                            <td>61</td>
                            <td>2011/04/25</td>
                            <td>&yen;320,800</td>
                            <td class="text-nowrap">
                                <button type="button" class="btn btn-sm btn-icon btn-flat btn-default">
                                    <i class="icon wb-wrench" aria-hidden="true"></i>
                                </button>
                                <button type="button" class="btn btn-sm btn-icon btn-flat btn-default" data-toggle="tooltip" data-original-title="删除">
                                    <i class="icon wb-close" aria-hidden="true"></i>
                                </button>
                            </td>
                        </tr>
                        <tr>
                            <td>李霞</td>
                            <td>系统架构师</td>
                            <td>北京</td>
                            <td>61</td>
                            <td>2011/04/25</td>
                            <td>&yen;320,800</td>
                            <td class="text-nowrap">
                                <button type="button" class="btn btn-sm btn-icon btn-flat btn-default">
                                    <i class="icon wb-wrench" aria-hidden="true"></i>
                                </button>
                                <button type="button" class="btn btn-sm btn-icon btn-flat btn-default" data-toggle="tooltip" data-original-title="删除">
                                    <i class="icon wb-close" aria-hidden="true"></i>
                                </button>
                            </td>
                        </tr>
                        <tr>
                            <td>李霞</td>
                            <td>系统架构师</td>
                            <td>北京</td>
                            <td>61</td>
                            <td>2011/04/25</td>
                            <td>&yen;320,800</td>
                            <td class="text-nowrap">
                                <button type="button" class="btn btn-sm btn-icon btn-flat btn-default">
                                    <i class="icon wb-wrench" aria-hidden="true"></i>
                                </button>
                                <button type="button" class="btn btn-sm btn-icon btn-flat btn-default" data-toggle="tooltip" data-original-title="删除">
                                    <i class="icon wb-close" aria-hidden="true"></i>
                                </button>
                            </td>
                        </tr>
                        <tr>
                            <td>李霞</td>
                            <td>系统架构师</td>
                            <td>北京</td>
                            <td>61</td>
                            <td>2011/04/25</td>
                            <td>&yen;320,800</td>
                            <td class="text-nowrap">
                                <button type="button" class="btn btn-sm btn-icon btn-flat btn-default">
                                    <i class="icon wb-wrench" aria-hidden="true"></i>
                                </button>
                                <button type="button" class="btn btn-sm btn-icon btn-flat btn-default" data-toggle="tooltip" data-original-title="删除">
                                    <i class="icon wb-close" aria-hidden="true"></i>
                                </button>
                            </td>
                        </tr>
                        <tr>
                            <td>李霞</td>
                            <td>系统架构师</td>
                            <td>北京</td>
                            <td>61</td>
                            <td>2011/04/25</td>
                            <td>&yen;320,800</td>
                            <td class="text-nowrap">
                                <button type="button" class="btn btn-sm btn-icon btn-flat btn-default">
                                    <i class="icon wb-wrench" aria-hidden="true"></i>
                                </button>
                                <button type="button" class="btn btn-sm btn-icon btn-flat btn-default" data-toggle="tooltip" data-original-title="删除">
                                    <i class="icon wb-close" aria-hidden="true"></i>
                                </button>
                            </td>
                        </tr>
                        <tr>
                            <td>李霞</td>
                            <td>系统架构师</td>
                            <td>北京</td>
                            <td>61</td>
                            <td>2011/04/25</td>
                            <td>&yen;320,800</td>
                            <td class="text-nowrap">
                                <button type="button" class="btn btn-sm btn-icon btn-flat btn-default">
                                    <i class="icon wb-wrench" aria-hidden="true"></i>
                                </button>
                                <button type="button" class="btn btn-sm btn-icon btn-flat btn-default" data-toggle="tooltip" data-original-title="删除">
                                    <i class="icon wb-close" aria-hidden="true"></i>
                                </button>
                            </td>
                        </tr>   <tr>
                            <td>李霞</td>
                            <td>系统架构师</td>
                            <td>北京</td>
                            <td>61</td>
                            <td>2011/04/25</td>
                            <td>&yen;320,800</td>
                            <td class="text-nowrap">
                                <button type="button" class="btn btn-sm btn-icon btn-flat btn-default">
                                    <i class="icon wb-wrench" aria-hidden="true"></i>
                                </button>
                                <button type="button" class="btn btn-sm btn-icon btn-flat btn-default" data-toggle="tooltip" data-original-title="删除">
                                    <i class="icon wb-close" aria-hidden="true"></i>
                                </button>
                            </td>
                        </tr>   <tr>
                            <td>李霞</td>
                            <td>系统架构师</td>
                            <td>北京</td>
                            <td>61</td>
                            <td>2011/04/25</td>
                            <td>&yen;320,800</td>
                            <td class="text-nowrap">
                                <button type="button" class="btn btn-sm btn-icon btn-flat btn-default">
                                    <i class="icon wb-wrench" aria-hidden="true"></i>
                                </button>
                                <button type="button" class="btn btn-sm btn-icon btn-flat btn-default" data-toggle="tooltip" data-original-title="删除">
                                    <i class="icon wb-close" aria-hidden="true"></i>
                                </button>
                            </td>
                        </tr>   <tr>
                            <td>李霞</td>
                            <td>系统架构师</td>
                            <td>北京</td>
                            <td>61</td>
                            <td>2011/04/25</td>
                            <td>&yen;320,800</td>
                            <td class="text-nowrap">
                                <button type="button" class="btn btn-sm btn-icon btn-flat btn-default">
                                    <i class="icon wb-wrench" aria-hidden="true"></i>
                                </button>
                                <button type="button" class="btn btn-sm btn-icon btn-flat btn-default" data-toggle="tooltip" data-original-title="删除">
                                    <i class="icon wb-close" aria-hidden="true"></i>
                                </button>
                            </td>
                        </tr>   <tr>
                            <td>李霞</td>
                            <td>系统架构师</td>
                            <td>北京</td>
                            <td>61</td>
                            <td>2011/04/25</td>
                            <td>&yen;320,800</td>
                            <td class="text-nowrap">
                                <button type="button" class="btn btn-sm btn-icon btn-flat btn-default">
                                    <i class="icon wb-wrench" aria-hidden="true"></i>
                                </button>
                                <button type="button" class="btn btn-sm btn-icon btn-flat btn-default" data-toggle="tooltip" data-original-title="删除">
                                    <i class="icon wb-close" aria-hidden="true"></i>
                                </button>
                            </td>
                        </tr>   <tr>
                            <td>李霞</td>
                            <td>系统架构师</td>
                            <td>北京</td>
                            <td>61</td>
                            <td>2011/04/25</td>
                            <td>&yen;320,800</td>
                            <td class="text-nowrap">
                                <button type="button" class="btn btn-sm btn-icon btn-flat btn-default">
                                    <i class="icon wb-wrench" aria-hidden="true"></i>
                                </button>
                                <button type="button" class="btn btn-sm btn-icon btn-flat btn-default" data-toggle="tooltip" data-original-title="删除">
                                    <i class="icon wb-close" aria-hidden="true"></i>
                                </button>
                            </td>
                        </tr>   <tr>
                            <td>李霞</td>
                            <td>系统架构师</td>
                            <td>北京</td>
                            <td>61</td>
                            <td>2011/04/25</td>
                            <td>&yen;320,800</td>
                            <td class="text-nowrap">
                                <button type="button" class="btn btn-sm btn-icon btn-flat btn-default">
                                    <i class="icon wb-wrench" aria-hidden="true"></i>
                                </button>
                                <button type="button" class="btn btn-sm btn-icon btn-flat btn-default" data-toggle="tooltip" data-original-title="删除">
                                    <i class="icon wb-close" aria-hidden="true"></i>
                                </button>
                            </td>
                        </tr>   <tr>
                            <td>李霞</td>
                            <td>系统架构师</td>
                            <td>北京</td>
                            <td>61</td>
                            <td>2011/04/25</td>
                            <td>&yen;320,800</td>
                            <td class="text-nowrap">
                                <button type="button" class="btn btn-sm btn-icon btn-flat btn-default">
                                    <i class="icon wb-wrench" aria-hidden="true"></i>
                                </button>
                                <button type="button" class="btn btn-sm btn-icon btn-flat btn-default" data-toggle="tooltip" data-original-title="删除">
                                    <i class="icon wb-close" aria-hidden="true"></i>
                                </button>
                            </td>
                        </tr>   <tr>
                            <td>李霞</td>
                            <td>系统架构师</td>
                            <td>北京</td>
                            <td>61</td>
                            <td>2011/04/25</td>
                            <td>&yen;320,800</td>
                            <td class="text-nowrap">
                                <button type="button" class="btn btn-sm btn-icon btn-flat btn-default">
                                    <i class="icon wb-wrench" aria-hidden="true"></i>
                                </button>
                                <button type="button" class="btn btn-sm btn-icon btn-flat btn-default" data-toggle="tooltip" data-original-title="删除">
                                    <i class="icon wb-close" aria-hidden="true"></i>
                                </button>
                            </td>
                        </tr>   <tr>
                            <td>李霞</td>
                            <td>系统架构师</td>
                            <td>北京</td>
                            <td>61</td>
                            <td>2011/04/25</td>
                            <td>&yen;320,800</td>
                            <td class="text-nowrap">
                                <button type="button" class="btn btn-sm btn-icon btn-flat btn-default">
                                    <i class="icon wb-wrench" aria-hidden="true"></i>
                                </button>
                                <button type="button" class="btn btn-sm btn-icon btn-flat btn-default" data-toggle="tooltip" data-original-title="删除">
                                    <i class="icon wb-close" aria-hidden="true"></i>
                                </button>
                            </td>
                        </tr>   <tr>
                            <td>李霞</td>
                            <td>系统架构师</td>
                            <td>北京</td>
                            <td>61</td>
                            <td>2011/04/25</td>
                            <td>&yen;320,800</td>
                            <td class="text-nowrap">
                                <button type="button" class="btn btn-sm btn-icon btn-flat btn-default">
                                    <i class="icon wb-wrench" aria-hidden="true"></i>
                                </button>
                                <button type="button" class="btn btn-sm btn-icon btn-flat btn-default" data-toggle="tooltip" data-original-title="删除">
                                    <i class="icon wb-close" aria-hidden="true"></i>
                                </button>
                            </td>
                        </tr>   <tr>
                            <td>李霞</td>
                            <td>系统架构师</td>
                            <td>北京</td>
                            <td>61</td>
                            <td>2011/04/25</td>
                            <td>&yen;320,800</td>
                            <td class="text-nowrap">
                                <button type="button" class="btn btn-sm btn-icon btn-flat btn-default">
                                    <i class="icon wb-wrench" aria-hidden="true"></i>
                                </button>
                                <button type="button" class="btn btn-sm btn-icon btn-flat btn-default" data-toggle="tooltip" data-original-title="删除">
                                    <i class="icon wb-close" aria-hidden="true"></i>
                                </button>
                            </td>
                        </tr>   <tr>
                            <td>李霞</td>
                            <td>系统架构师</td>
                            <td>北京</td>
                            <td>61</td>
                            <td>2011/04/25</td>
                            <td>&yen;320,800</td>
                            <td class="text-nowrap">
                                <button type="button" class="btn btn-sm btn-icon btn-flat btn-default">
                                    <i class="icon wb-wrench" aria-hidden="true"></i>
                                </button>
                                <button type="button" class="btn btn-sm btn-icon btn-flat btn-default" data-toggle="tooltip" data-original-title="删除">
                                    <i class="icon wb-close" aria-hidden="true"></i>
                                </button>
                            </td>
                        </tr>   <tr>
                            <td>李霞</td>
                            <td>系统架构师</td>
                            <td>北京</td>
                            <td>61</td>
                            <td>2011/04/25</td>
                            <td>&yen;320,800</td>
                            <td class="text-nowrap">
                                <button type="button" class="btn btn-sm btn-icon btn-flat btn-default">
                                    <i class="icon wb-wrench" aria-hidden="true"></i>
                                </button>
                                <button type="button" class="btn btn-sm btn-icon btn-flat btn-default" data-toggle="tooltip" data-original-title="删除">
                                    <i class="icon wb-close" aria-hidden="true"></i>
                                </button>
                            </td>
                        </tr>   <tr>
                            <td>李霞</td>
                            <td>系统架构师</td>
                            <td>北京</td>
                            <td>61</td>
                            <td>2011/04/25</td>
                            <td>&yen;320,800</td>
                            <td class="text-nowrap">
                                <button type="button" class="btn btn-sm btn-icon btn-flat btn-default">
                                    <i class="icon wb-wrench" aria-hidden="true"></i>
                                </button>
                                <button type="button" class="btn btn-sm btn-icon btn-flat btn-default" data-toggle="tooltip" data-original-title="删除">
                                    <i class="icon wb-close" aria-hidden="true"></i>
                                </button>
                            </td>
                        </tr>   <tr>
                            <td>李霞</td>
                            <td>系统架构师</td>
                            <td>北京</td>
                            <td>61</td>
                            <td>2011/04/25</td>
                            <td>&yen;320,800</td>
                            <td class="text-nowrap">
                                <button type="button" class="btn btn-sm btn-icon btn-flat btn-default">
                                    <i class="icon wb-wrench" aria-hidden="true"></i>
                                </button>
                                <button type="button" class="btn btn-sm btn-icon btn-flat btn-default" data-toggle="tooltip" data-original-title="删除">
                                    <i class="icon wb-close" aria-hidden="true"></i>
                                </button>
                            </td>
                        </tr>   <tr>
                            <td>李霞</td>
                            <td>系统架构师</td>
                            <td>北京</td>
                            <td>61</td>
                            <td>2011/04/25</td>
                            <td>&yen;320,800</td>
                            <td class="text-nowrap">
                                <button type="button" class="btn btn-sm btn-icon btn-flat btn-default">
                                    <i class="icon wb-wrench" aria-hidden="true"></i>
                                </button>
                                <button type="button" class="btn btn-sm btn-icon btn-flat btn-default" data-toggle="tooltip" data-original-title="删除">
                                    <i class="icon wb-close" aria-hidden="true"></i>
                                </button>
                            </td>
                        </tr>

                        </tbody>
                    </table>

                    <div class="row">
                        <div class="col-sm-6">
                            <div class="dataTables_info" id="dataTable_info" role="alert" aria-live="polite" aria-relevant="all">显示 1 到 1 项，共 1 项</div></div><div class="col-sm-6"><div class="dataTables_paginate paging_simple_numbers" id="dataTable_paginate"><ul class="pagination"><li class="paginate_button previous disabled" aria-controls="dataTable" tabindex="0" id="dataTable_previous"><a href="#">上一页</a></li><li class="paginate_button active" aria-controls="dataTable" tabindex="0"><a href="#">1</a></li><li class="paginate_button next disabled" aria-controls="dataTable" tabindex="0" id="dataTable_next">
                                        <a href="#">下一页</a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-6">
                            <div class="dataTables_info" id="dataTable_info" role="alert" aria-live="polite" aria-relevant="all">显示 11 到 20 项，共 28 项</div></div>
                        <div class="col-sm-6"><div class="dataTables_paginate paging_simple_numbers" id="dataTable_paginate">
                                <ul class="pagination">
                                    <li class="paginate_button previous" aria-controls="dataTable" tabindex="0" id="dataTable_previous"><a href="#">上一页</a></li><li class="paginate_button " aria-controls="dataTable" tabindex="0"><a href="#">1</a></li>
                                    <li class="paginate_button active" aria-controls="dataTable" tabindex="0"><a href="#">2</a></li>
                                    <li class="paginate_button " aria-controls="dataTable" tabindex="0"><a href="#">3</a></li>
                                    <li class="paginate_button next" aria-controls="dataTable" tabindex="0" id="dataTable_next">
                                        <a href="#">下一页</a></li></ul></div>
                        </div>
                    </div>
                </div>
            </div>
            <div>123</div>
        </div>
    </main>



@endsection

@section('stylejs')

    <script src="{{asset('styles/lib/laydate/laydate.js')}}"></script>
    <script src="{{asset('styles/lib/dataTables/jquery.dataTables.js')}}"></script>
    <script src="{{asset('styles/lib/dataTables/dataTables.bootstrap.js')}}"></script>
    <script src="{{asset('styles/lib/dataTables/dataTables.responsive.min.js')}}"></script>

    <script>
        $(function () {
            $('#dataTable').DataTable({
                "paging":   false,
                "ordering": false,
                "searching": false,
                "info":     false
            });

            $("[data-toggle='tooltip']").tooltip();

        });

    </script>
@endsection
