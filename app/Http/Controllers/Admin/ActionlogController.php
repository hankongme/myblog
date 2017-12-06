<?php

namespace App\Http\Controllers\Admin;



use App\Http\Controllers\Tools\BaseTools;
use App\Http\Controllers\Controller;
use App\AdminActionLog;
use App\Users;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

class ActionlogController extends Controller
{

    public function index(Request $request)
    {

        $map[0] = [ 'admin_action_log.id', '!=', 'NULL' ];
        $map[1] = [ 'admin_action_log.id', '!=', '' ];

        $search_form = $request->all();
        foreach ($search_form as $k => $v) {
            if ($v == '') {
                unset($search_form[$k]);
            } else {
                $search_form[$k] = trim($v);
            }
        }

        if (isset($search_form['id']) && $search_form['id']) {
            $map[] = [ 'admin_action_log.id', 'like', '%' . $search_form['id'] . '%' ];
        }
        if (isset($search_form['ip']) && $search_form['ip']) {
            $map[] = [ 'admin_action_log.ip', 'like', '%' . $search_form['ip'] . '%' ];
        }
        if (isset($search_form['name']) && $search_form['name']) {
            $map[] = [ 'adminuser.truename', 'like', '%' . $search_form['name'] . '%' ];

        }
        if (isset($search_form['remark']) && $search_form['remark']) {
            $map[] = [ 'admin_action_log.remark', 'like', '%' . $search_form['remark'] . '%' ];

        }
        if (isset($search_form['ids']) && $search_form['ids']) {
            $map[] = [ 'admin_action_log.ids', 'like', '%' . $search_form['ids'] . '%' ];

        }

        if (isset($search_form['account']) && $search_form['account']) {
            $map[] = [ 'adminuser.account', '=', $search_form['account'] ];
        }

        if (isset($search_form['start_time']) && $search_form['start_time']) {
            $map[] = [ 'admin_action_log.created_at', '>', $search_form['start_time'] ];
        }
        if (isset($search_form['end_time']) && $search_form['end_time']) {
            $map[] = [ 'admin_action_log.created_at', '<', $search_form['end_time'] ];
        }

        $page_num   = intval(isset($_GET['page']) ? $_GET['page'] : 1);
        $page_num   = $page_num == 0 ? 1 : $page_num;
        $page_count = C('ADMIN_PAGE_COUNT');

        $actionlog = new AdminActionLog();

        $data = $actionlog->leftJoin('adminuser', function ($join) {
            $join->on('admin_action_log.adminuser_id', '=', 'adminuser.id');
        }
        )->select('admin_action_log.*', 'adminuser.account', 'adminuser.truename')->where($map)
                          ->orderby('admin_action_log.created_at', 'desc')->paginate($page_count);


        if ($data) {
            unset($search_form['page']);
            $data->path .= '?' . http_build_query($search_form);
            $page = $data->render();
            $data = $data->toArray();
        }


        if ($data['data']) {

            $data['data'] = BaseTools::get_key_list($data['data'], $page_num, $page_count);

        }

        return view('admin.adminuser.loglist')->with([ 'data' => $data, 'page' => $page, 'page_count' => $page_count, 'page_num' => $page_num, 'search_form' => $search_form ]);

    }


    /**
     * 删除日志
     * @param $id
     * @return mixed
     */
    public function destory($id)
    {
        $id = intval($id);
        if (!$id) {
            return BaseTools::ajaxError('请选择日志ID');
        }

        AdminActionLog::where('id', '=', $id)->delete();
        return BaseTools::ajaxSuccess('删除成功!');
    }


}
