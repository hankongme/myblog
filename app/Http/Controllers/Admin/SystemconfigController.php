<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Tools\ActionLog;
use App\Http\Controllers\Codes\CodesStatus;
use App\Http\Controllers\Tools\BaseTools;

use App\Http\Requests\SystemconfigStoreRequest;
use App\Http\Requests\SystemconfigUpdateRequest;
use App\BaseModel;
use App\Http\Controllers\Controller;

use App\Systemconfig;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Inpurt;
use Illuminate\Support\Facades\Input;


class SystemconfigController extends Controller
{

    public function index()
    {

        $map[] = [ 'status', '>=', '0' ];

        $page_count = 10;
        $config     = Systemconfig::where($map)->paginate($page_count);
        $data       = [ ];
        $page       = $config->render();
        if ($config) {
            $data                = $config->toArray();
            $string_map['group'] = C('CONFIG_GROUP_LIST');
            $string_map['type']  = C('CONFIG_TYPE_LIST');

            $data['data'] = BaseTools::int_to_string($data['data'], $string_map);

        }

        return view('admin.systemconfig.index')->with([ 'data' => $data, 'page' => $page ]);

    }


    public function destory($id)
    {
        $id = intval($id);
        if (!$id) {
            return BaseTools::ajaxError('未找到该配置信息');
        }

        Systemconfig::where('id', '=', $id)->delete();

        BaseTools::update_config();
        //操作日志记录
        ActionLog::actionLog($id);
        return BaseTools::ajaxSuccess('删除成功!');

    }


    /**
     * 系统配置参数
     * @return mixed
     */

    public function group()
    {
        $map[0] = [ 'status', '=', 1 ];
        $config = Systemconfig::where($map)->get();

        $type = C('CONFIG_GROUP_LIST');

        $conflist = [ ];
        $list     = [ ];
        $data     = [ ];
        if ($config) {

            $list = $config->toArray();


            foreach ($list as $k => $v) {

                if ($v['type'] == 4) {
                    $v['extra'] = BaseTools::parse_config_attr($v['extra']);
                }
                $conflist[$v['group']][$k] = $v;

            }

            foreach ($type as $k => $v) {
                $data[$k]['name'] = $v;
                $data[$k]['list'] = isset($conflist[$k]) ? $conflist[$k] : [ ];

            }
        }

//        dd($data);
        $data['data']       = $data;
        $data['submit_url'] = url(__ADMIN_PATH__.'/systemconfig/savegroup');
        return view('admin.systemconfig.group')->with([ 'data' => $data ]);

    }


    /**
     * 保存配置值
     * @return mixed
     */
    public function save_group()
    {
        $data = Input::all();
        unset($data['_token']);
        $systemconfig = new Systemconfig();
        $systemconfig->save_group($data);
        if ($systemconfig->error) {

            return BaseTools::ajaxError('修改失败!');
        }

        BaseTools::update_config();
        //操作日志记录
        ActionLog::actionLog();
        return BaseTools::ajaxSuccess('修改成功!');


    }

    /**
     * 增加配置项
     * @return mixed
     */
    public function create()
    {
        $data = BaseModel::getColumnTable('system_config');

        $data['submit_url'] = url(__ADMIN_PATH__.'/systemconfig/store');
        $data['status']     = 1;

        $group = C('CONFIG_GROUP_LIST');
        $type  = C('CONFIG_TYPE_LIST');

        return view('admin.systemconfig.edit')->with([ 'data' => $data, 'group' => $group, 'type' => $type ]);
    }


    /**
     * 配置添加,获取并保存
     * @param SystemconfigStoreRequest $request
     * @return mixed
     */
    public function store(SystemconfigStoreRequest $request)
    {
        $data = $request->all();
        unset($data['_token']);
        $systemconfig = new Systemconfig();
        $systemconfig->save_data($data);
        if ($systemconfig->error) {
            return BaseTools::jsonReturn($systemconfig->error, CodesStatus::Code_Failed, $systemconfig->error);
        }

        BaseTools::update_config();
        //操作日志记录
        ActionLog::actionLog($data['name']);
        return BaseTools::ajaxSuccess('添加成功!');
    }


    /**
     * 修改配置项
     * @param int $id
     * @return mixed
     */
    public function edit($id = 0)
    {
        $id   = intval($id);
        $data = Systemconfig::where('id', '=', $id)->first();
        if (!$data) {
            return BaseTools::error('未找到该配置!');
        }
        $data               = $data->toArray();
        $data['submit_url'] = url(__ADMIN_PATH__.'/systemconfig/update');
        $group              = C('CONFIG_GROUP_LIST');
        $type               = C('CONFIG_TYPE_LIST');
        return view('admin.systemconfig.edit')->with([ 'data' => $data, 'group' => $group, 'type' => $type ]);
    }

    public function update(SystemconfigUpdateRequest $request)
    {
        $data = $request->all();
        unset($data['_token']);
        $systemconfig = new Systemconfig();
        $systemconfig->save_data($data);
        if ($systemconfig->error) {
            return BaseTools::jsonReturn($systemconfig->error, CodesStatus::Code_Failed, $systemconfig->error);
        }


        BaseTools::update_config();
        //操作日志记录
        ActionLog::actionLog($data['name']);
        return BaseTools::ajaxSuccess('修改成功!');

    }


}
