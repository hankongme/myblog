<?php

namespace App\Http\Controllers\Admin;


use App\Adv;
use App\AdvPosition;
use App\BaseModel;
use App\Http\Controllers\Tools\ActionLog;
use App\Http\Controllers\Tools\BaseTools;
use App\Http\Controllers\Controller;
use App\Http\Requests\AdvPositionRequest;
use App\Http\Requests\AdvRequest;


use Illuminate\Support\Facades\Input;



class AdvController extends Controller
{

    public function __construct() {


    }


    public function index() {

        $map[ 0 ] = [ 'id' , '!=' , 'NULL' ];
        $map[ 1 ] = [ 'id' , '!=' , '' ];

        $search_form = Input::get ();
        foreach ($search_form as $k => $v) {
            if ($v == ''){
                unset($search_form[ $k ]);
            } else{
                $search_form[ $k ] = trim ($v);
            }
        }

        if (isset($search_form[ 'id' ]) && $search_form[ 'id' ]){
            $map[] = [ 'id' , 'like' , '%' . $search_form[ 'id' ] . '%' ];
        }
        if (isset($search_form[ 'pname' ]) && $search_form[ 'pname' ]){
            $map[] = [ 'pname' , 'like' , '%' . $search_form[ 'pname' ] . '%' ];
        }

        if (isset($search_form[ 'varname' ]) && $search_form[ 'varname' ]){
            $map[] = [ 'varname' , 'like' , '%' . $search_form[ 'varname' ] . '%' ];
        }

        if (isset($search_form[ 'start_time' ]) && $search_form[ 'start_time' ]){
            $map[] = [ 'created_at' , '>' , $search_form[ 'start_time' ] ];
        }
        if (isset($search_form[ 'end_time' ]) && $search_form[ 'end_time' ]){
            $map[] = [ 'created_at' , '<' , $search_form[ 'end_time' ] ];
        }

        $page_num   = intval (isset($_GET[ 'page' ])?$_GET[ 'page' ]:1);
        $page_num   = $page_num == 0?1:$page_num;
        $page_count = C ('ADMIN_PAGE_COUNT');

        $model = new AdvPosition();

        $data = $model->where ($map)->paginate ($page_count);

        if ($data){

            unset($search_form[ 'page' ]);
            $data->path .= '?' . http_build_query ($search_form);
            $page = $data->render ();
            $data = $data->toArray ();
        }


        if (isset($data[ 'data' ]) && $data[ 'data' ]){
            $data[ 'data' ] = BaseTools::get_key_list ($data[ 'data' ] , $page_num , $page_count);
        }


        return view ('admin.adv.index')->with (
            [
                'data'        => $data ,
                'page'        => $page ,
                'page_count'  => $page_count ,
                'page_num'    => $page_num ,
                'search_form' => $search_form
            ]
        );

    }


    public function advlist($id = 0) {


        $adv_position = AdvPosition::where ('id' , $id)->first ();
        if(!$adv_position){
            return BaseTools::error('未找到该数据!');
        }
        $adv_position = $adv_position->toArray ();

        if (!$adv_position){
            return BaseTools::error ('未找到广告位');
        }

        $map[ 0 ] = [ 'status' , '>=' , 0 ];
        $map[ 1 ] = [ 'position_id' , '=' , $id ];

        $search_form = Input::get ();
        foreach ($search_form as $k => $v) {
            if ($v == ''){
                unset($search_form[ $k ]);
            } else{
                $search_form[ $k ] = trim ($v);
            }
        }

        if (isset($search_form[ 'id' ]) && $search_form[ 'id' ]){
            $map[] = [ 'id' , '=' , $search_form[ 'id' ] ];
        }
        if (isset($search_form[ 'name' ]) && $search_form[ 'name' ]){
            $map[] = [ 'name' , 'like' , '%' . $search_form[ 'name' ] . '%' ];
        }

        if (isset($search_form[ 'status' ]) && $search_form[ 'status' ] != 10){
            $map[] = [ 'status' , '=' , $search_form[ 'status' ] ];
        }


        if (isset($search_form[ 'start_time' ]) && $search_form[ 'start_time' ]){
            $map[] = [ 'created_at' , '>' , $search_form[ 'start_time' ] ];
        }
        if (isset($search_form[ 'end_time' ]) && $search_form[ 'end_time' ]){
            $map[] = [ 'created_at' , '<' , $search_form[ 'end_time' ] ];
        }


        if (isset($search_form[ 's_time' ]) && $search_form[ 's_time' ]){
            $map[] = [ 'start_time' , '>' , $search_form[ 's_time' ] ];
        }
        if (isset($search_form[ 'e_time' ]) && $search_form[ 'e_time' ]){
            $map[] = [ 'start_time' , '<' , $search_form[ 'e_time' ] ];
        }

        if (isset($search_form[ 'ss_time' ]) && $search_form[ 'ss_time' ]){
            $map[] = [ 'end_time' , '>' , $search_form[ 'ss_time' ] ];
        }
        if (isset($search_form[ 'ee_time' ]) && $search_form[ 'ee_time' ]){
            $map[] = [ 'end_time' , '<' , $search_form[ 'ee_time' ] ];
        }

        $page_num   = intval (isset($_GET[ 'page' ])?$_GET[ 'page' ]:1);
        $page_num   = $page_num == 0?1:$page_num;
        $page_count = C ('ADMIN_PAGE_COUNT');


        $data = Adv::where ($map)->orderBy('sort')->paginate ($page_count);

        if ($data){
            unset($search_form[ 'page' ]);
            $data->path .= '?' . http_build_query ($search_form);
            $page = $data->render ();
            $data = $data->toArray ();
        }

        if (isset($data[ 'data' ]) && $data[ 'data' ]){

            $string_map[ 'status' ] = [ 0 => '不显示' , 1 => '显示' ];

            $data[ 'data' ] = BaseTools::get_key_list ($data[ 'data' ] , $page_num , $page_count);
            $data[ 'data' ] = BaseTools::int_to_string ($data[ 'data' ] , $string_map);

        }
        return view ('admin.adv.list')->with (
            [
                'data'        => $data ,
                'page'        => $page ,
                'page_count'  => $page_count ,
                'page_num'    => $page_num ,
                'search_form' => $search_form ,
                'category_id' => $id ,
                'adv_position'  => $adv_position
            ]
        );

    }


    public function storeadv(AdvRequest $request) {
        $data = $request->all ();
        unset($data[ '_token' ]);
        $model = new Adv();
        $model->save_data ($data);
        if ($model->error){
            return BaseTools::ajaxError($model->error);
        }

        return BaseTools::ajaxSuccess('添加成功!');

    }

    public function editadv($id) {
        if (!$id){
            return BaseTools::error ('请选择需要修改的信息!');
        }

        $map[ 0 ] = [ 'id' , '=' , $id ];
        $map[ 1 ] = [ 'status' , '>=' , 0 ];
        $data     = Adv::where ($map)->first ();

        if (!$data){
            return BaseTools::error ('未找到该信息!');
        }

        $data = $data->toArray ();

        $data[ 'submit_url' ] = url (__ADMIN_PATH__.'/adv/updateadv');
        $with_data['position_id'] = $this->get_adv_position();
        $with_data[ 'data' ]  = $data;
        return view ('admin.adv.editadv')->with ($with_data);

    }

    public function updateadv(AdvRequest $request) {
        $data = $request->all ();
        unset($data[ '_token' ]);
        $model = new Adv();
        $model->save_data ($data);
        if ($model->error){
            return BaseTools::ajaxError($model->error);
        }

        return BaseTools::ajaxSuccess('修改成功!');

    }

    /**
     * 删除广告位
     * @param $id
     * @return mixed
     */
    public function deladv($id) {
        if (!$id){
            return BaseTools::ajaxError('请选择需要删除的信息!');
        }

        Adv::where ('id' , '=' , $id)->update(['status'=>'-1']);
        return BaseTools::ajaxSuccess('删除成功!');
    }


    /**
     * 创建广告
     * @param int $id
     * @return mixed
     */
    public function createadv($id = 0) {
        $data                       = BaseModel::getColumnTable ('dcnet_adv');
        $data[ 'submit_url' ]       = url (__ADMIN_PATH__.'/adv/storeadv');
        $data[ 'position_id' ]      = $id;
        $data[ 'sort' ]             = 50;
        $data['start_time'] =  BaseTools::getTime();
        $data['end_time'] =  BaseTools::getTime(3600*24*30);
        $with_data[ 'data' ]        = $data;
        $with_data[ 'position_id' ] = $this->get_adv_position ();
        return view ('admin.adv.editadv')->with ($with_data);

    }

    private function get_adv_position() {
        $data     = [ ];
        $position = AdvPosition::where ('id' , '>' , 0)->get ();
        foreach ($position as $k => $v) {
            $data[ $v[ 'id' ] ] = $v[ 'pname' ];
        }
        return $data;
    }


    /**
     * 存储广告位
     * @param LabelRequest $request
     * @return mixed
     */
    public function store(AdvPositionRequest $request) {
        $data = $request->all ();
        unset($data[ '_token' ]);
        $model = new AdvPosition();
        $model->save_data ($data);
        if ($model->error){
            return BaseTools::ajaxError($model->error);
        }

        return BaseTools::ajaxSuccess('添加成功!');

    }


    /**
     * 添加广告位
     * @return mixed
     */

    public function create() {
        $data                 = BaseModel::getColumnTable ('dcnet_adposition');
        $data[ 'submit_url' ] = url (__ADMIN_PATH__.'/adv/store');
        $with_data[ 'data' ]  = $data;
        return view ('admin.adv.edit')->with ($with_data);

    }


    /**
     * 编辑广告位
     * @param $id
     * @return mixed
     */
    public function edit($id) {
        if (!$id){
            return BaseTools::error ('请选择需要修改的信息!');
        }

        $map[ 0 ] = [ 'id' , '=' , $id ];
        $data     = AdvPosition::where ($map)->first ();
        if (!$data){
            return BaseTools::error ('未找到该信息!');
        }

        $data                 = $data->toArray ();
        $data[ 'submit_url' ] = url (__ADMIN_PATH__.'/adv/update');

        $with_data[ 'data' ] = $data;
        return view ('admin.adv.edit')->with ($with_data);
    }


    public function update(AdvPositionRequest $request) {
        $data = $request->all ();
        unset($data[ '_token' ]);
        $model = new AdvPosition();
        $model->save_data ($data);
        if ($model->error){
            return BaseTools::ajaxError($model->error);
        }

        return BaseTools::ajaxSuccess('修改成功!');
    }


    public function del($id) {
        if (!$id){
            return BaseTools::ajaxError('请选择需要删除的信息!');
        }
        $adv = Adv::where('position_id',$id)->count();
        if($adv){
            return BaseTools::ajaxError('该广告位下还有广告,请先处理!');
        }
        $map[ 0 ] = [ 'id' , '=' , $id ];
        AdvPosition::where ($map)->delete();
        return BaseTools::ajaxSuccess('删除成功!');

    }


}
