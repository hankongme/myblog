<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Tools\ActionLog;
use App\Http\Controllers\Tools\StringConstants;
use App\Http\Controllers\Tools\BaseTools;
use App\BaseModel;
use App\Http\Controllers\Controller;
use App\Repositries\TagRespository;
use App\Tags;
use Illuminate\Http\Request;


class TagController extends Controller
{
    public $tag;
    public function __construct(TagRespository $tagRespository)
    {

        $this->tag = $tagRespository;

    }

    public function index(Request $request)
    {
        $data = $this->tag->getListForSearch($request);
        return view('admin.tags.index')->with($data);
    }


    public function destory($id)
    {
        return $this->tag->destory($id)?BaseTools::ajaxSuccess('删除成功!'):BaseTools::ajaxError('删除失败!');
    }

    /**
     * 添加标签
     * @return mixed
     */
    public function create()
    {
        $data            = BaseModel::getColumnTable($this->tag->table);
        $data['submit_url'] = url(__ADMIN_PATH__.'/tag/store');
        return view('admin.tags.edit')->with([ 'data' => $data]);
    }


    /**
     * 保存标签
     * @param \Illuminate\Http\Request $request
     *
     * @return mixed
     */


    public function store(Request $request)
    {
        $data = $request->all();
        unset($data['_token']);

        $tag = $this->tag->save_data($data);
        if ($error = $this->tag->error()) {
            return BaseTools::ajaxError($error);
        }
        //操作日志记录
        ActionLog::actionLog($tag->id,'添加标签'.$data['name']);
        return BaseTools::ajaxSuccess('添加成功!');
    }


    /**
     * 修改标签
     * @param int $id
     * @return mixed
     */
    public function edit($id = 0)
    {
        $id   = intval($id);
        $data = $this->tag->find($id);
        if (!$data) {
            return BaseTools::error('未找到该信息!');
        }
        $data               = $data->toArray();
        $data['submit_url'] = url(__ADMIN_PATH__."/tag/{$id}/update");
        return view('admin.tags.edit')->with([ 'data' => $data]);
    }


    public function update(Request $request,$id=0)
    {
        $data = $request->all();
        unset($data['_token']);
        $tag = $this->tag->save_data($data,$id);
        if ($error = $this->tag->error()) {
            return BaseTools::ajaxError($error);
        }
        //操作日志记录
        ActionLog::actionLog($data['id']);
        return BaseTools::ajaxSuccess('修改成功!');

    }


    public function getTagByKeyWords(Request $request)
    {
        $key = $request->get('q');
        $data = Tags::where('name','like','%'.$key.'%')->select('id','name')->get()->toArray();
        if(!$data){
            $data[0]['id'] = $key;
            $data[0]['tag'] = $key;
        }
        return $data;
    }



}
