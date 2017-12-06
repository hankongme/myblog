<?php

namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Tools\Labeltools;
use App\Http\Controllers\Tools\BaseTools;
use App\Http\Controllers\Controller;
use App\Http\Requests\AdpositionRequest;
use App\Http\Requests\AdvRequest;
use App\Http\Requests\DiyMenuRequest;
use App\Http\Requests\LabelCategoryRequest;
use App\Http\Requests\LabelRequest;
use App\Adposition;
use App\Adv;
use App\BaseModel;

use App\DiyMenu;
use App\LabelCategory;
use App\Labels;
use App\Users;
use EasyWeChat\Foundation\Application;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Session;


class DiyMenuController extends Controller
{
    public $wechat;



    protected $menu_type = [ 'parent' => '父级菜单' , 'view' => '自定义链接' , 'click' => '关键字回复' ];

    public function __construct(Application $application) {
            $this->wechat = $application;
    }


    public function index() {

        $map[ 0 ] = [ 'id' , '!=' , 'NULL' ];
        $map[ 1 ] = [ 'id' , '!=' , '' ];


        $page_num   = intval (isset($_GET[ 'page' ])?$_GET[ 'page' ]:1);
        $page_num   = $page_num == 0?1:$page_num;
        $page_count = C ('ADMIN_PAGE_COUNT');
        $menu_type  = C ('WX_DIYMEN_EVENT');
        $model      = new DiyMenu();

        $data = $model->where ($map)->orderBy('sort','asc')->paginate (100);

        if ($data){
            $page = $data->render ();
            $data = $data->toArray ();
        }

        $list = [ ];
        $list_data = [];
        if (isset($data[ 'data' ]) && $data[ 'data' ]){

            foreach ($data[ 'data' ] as $k => $v) {
                if ($v[ 'type' ] == 'view'){
                    $v[ 'value' ] = $v[ 'url' ];
                } else{
                    if ($v[ 'type' ] == 'click'){
                        $v[ 'value' ] = $v[ 'keyword' ];
                    } else{
                        if ($v[ 'type' ] == 'parent'){
                            $v[ 'value' ] = '父级菜单';
                        } else{
                            $v[ 'value' ] = isset($menu_type[ $v[ 'type' ] ])?$menu_type[ $v[ 'type' ] ]:'无';
                        }
                    }
                }
                $list_data[ $v[ 'pid' ] ][$k] = $v;
            }

            foreach ($list_data[0] as $k => $v) {
                $list[ $k ]            = $v;
                if($v['type'] == 'parent'){
                    $list[ $k ][ 'child' ] =isset($list_data[ $v[ 'id' ] ])?$list_data[ $v[ 'id' ] ]:[];
                }
            }

            $data[ 'data' ] = BaseTools::get_key_list($list , $page_num , $page_count);
        }


        return view ('admin.diymenu.index')->with (
            [
                'data'       => $data ,
                'page'       => $page ,
                'page_count' => $page_count ,
                'page_num'   => $page_num ,
            ]
        );

    }


    public function publish() {
        $menu = DiyMenu::get ();
        if (!$menu){
            return BaseTools::error ('请先填写自定义菜单!');
        }
        $menu     = $menu->toArray ();
        $menulist = [ ];
        $button   = [ ];
        foreach ($menu as $k => $v) {

            if ($v[ 'url' ]){

                if (!BaseTools::is_http ($v[ 'url' ])){
                    $v[ 'url' ] = url ($v[ 'url' ]);
                }

            }

            $menulist[ $v[ 'pid' ] ][ $k ] = $v;
        }

        foreach ($menulist[ 0 ] as $k => $v) {
            $button_temp_f           = [ ];
            $button_temp_f[ 'name' ] = $v[ 'title' ];
            if (isset($menulist[ $v[ 'id' ] ])&&$menulist[ $v[ 'id' ] ]){

                foreach ($menulist[ $v[ 'id' ] ] as $key => $value) {
                    $button_temp_c           = [ ];
                    $button_temp_c[ 'name' ] = $value[ 'title' ];
                    $button_temp_c[ 'type' ] = $value[ 'type' ];
                    if ($value[ 'type' ] == 'view'){
                        $button_temp_c[ 'url' ] = $value[ 'url' ];
                    } else{
                        if ($value[ 'type' ] == 'click'){
                            $button_temp_c[ 'key' ] = $value[ 'keyword' ];
                        }
                    }
                    $button_temp_f[ 'sub_button' ][] = $button_temp_c;
                }

            } else{
                $button_temp_f[ 'name' ] = $v[ 'title' ];
                $button_temp_f[ 'type' ] = $v[ 'type' ];
                if ($v[ 'type' ] == 'view'){
                    $button_temp_f[ 'url' ] = $v[ 'url' ];
                } else{
                    if ($v[ 'type' ] == 'click'){
                        $button_temp_f[ 'key' ] = $v[ 'keyword' ];
                    }
                }
            }

            $button[] = $button_temp_f;
        }

        $menu = $this->wechat->menu;

        $return = $menu->add($button);

        if (!$return->errcode){
            return BaseTools::ajaxSuccess('生成自定义菜单成功!');
        } else{
            return BaseTools::ajaxError('错误:' . $return->errcode . ':' . $return->errmsg);
        }
    }


    /**
     * 存储自定义菜单
     * @param LabelRequest $request
     * @return mixed
     */
    public function store(DiyMenuRequest $request) {
        $data = $request->all ();
        unset($data[ '_token' ]);
        $model = new DiyMenu();
        $model->save_data ($data);
        if ($model->error){
            return BaseTools::ajaxError ($model->error);
        }

        return BaseTools::ajaxSuccess ('添加成功!');

    }


    /**
     * 添加自定义菜单
     * @return mixed
     */

    public function create() {
        $data                     = BaseModel::getColumnTable ('diymenu');
        $data[ 'submit_url' ]     = url (__ADMIN_PATH__.'/diymenu/store');
        $menu_type                = array_merge ($this->menu_type , C ('WX_DIYMEN_EVENT'));
        $menu_list                = $this->get_parent_menu ();
        $menu_list[ 0 ]           = '顶级菜单';
        $with_data[ 'data' ]      = $data;
        $with_data[ 'menu_type' ] = $menu_type;
        $with_data[ 'menu_list' ] = $menu_list;
        return view ('admin.diymenu.edit')->with ($with_data);
    }


    /**
     * 获取父级菜单
     */

    protected function get_parent_menu() {
        $data = DiyMenu::where ('type' , 'parent')->where ('pid' , 0)->get ();
        $list = [ ];

        if ($data){
            $data = $data->toArray ();
            foreach ($data as $k => $v) {
                $list[ $v[ 'id' ] ] = $v[ 'title' ];
            }
        }
        return $list;
    }

    /**
     * 编辑自定义菜单
     * @param $id
     * @return mixed
     */
    public function edit($id) {
        if (!$id){
            return BaseTools::error ('请选择需要修改的信息!');
        }

        $map[ 0 ] = [ 'id' , '=' , $id ];
        $data     = DiyMenu::where ($map)->first ();
        if (!$data){
            return BaseTools::error ('未找到该信息!');
        }


        $menu_type            = array_merge ($this->menu_type , C ('WX_DIYMEN_EVENT'));
        $menu_list            = $this->get_parent_menu ();
        $menu_list[ 0 ]       = '顶级菜单';
        $data                 = $data->toArray ();
        $data[ 'submit_url' ] = url (__ADMIN_PATH__.'/diymenu/update');

        $with_data[ 'data' ]      = $data;
        $with_data[ 'menu_list' ] = $menu_list;
        $with_data[ 'menu_type' ] = $menu_type;
        return view ('admin.diymenu.edit')->with ($with_data);
    }


    public function update(DiyMenuRequest $request) {
        $data = $request->all ();
        unset($data[ '_token' ]);
        $model = new DiyMenu();
        $model->save_data ($data);
        if ($model->error){
            return BaseTools::ajaxError ($model->error);
        }

        return BaseTools::ajaxSuccess ('修改成功!');
    }


    public function del($id) {
        if (!$id){
            return BaseTools::ajaxError ('请选择需要删除的信息!');
        }

        $map[ 0 ] = [ 'id' , '=' , $id ];
        DiyMenu::where ($map)->delete();
        return BaseTools::ajaxSuccess ('删除成功!');

    }


}
