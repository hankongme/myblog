<?php
namespace App\Http\Controllers\Tools;

use App\Http\Controllers\Codes\CodesStatus;
use App\Systemconfig;
use Germey\Geetest\Geetest;
use Illuminate\Support\Facades\Cache;
use Vinkla\Hashids\Facades\Hashids;

/**
 * Class AdminTools
 *
 * @package App\Http\Controllers\Tools
 */
class AdminTools
{
    /**
     * @param     $data
     * @param     $search_form
     * @param int $page_num
     * @param int $page_count
     *
     * @return array
     */
    static public function dataFormat($data, $search_form, $page_count=10){

        if ($data) {
            unset($search_form['page']);
            $data->path .= '?' . http_build_query($search_form);
            $page = $data->render();
            $data = $data->toArray();
        }
        $page_num   = intval(isset($search_form['page']) ? $search_form['page'] : 1);
        $page_num   = $page_num == 0 ? 1 : $page_num;
        if ($data['data']) {
            $data['data'] = BaseTools::get_key_list($data['data'], $page_num, $page_count);
        }
        return ['page'=>$page,'data'=>$data,'search_form'=>$search_form];
    }

    /**
     * @param int $num
     *
     * @return int
     */
    static public function getAdminPageNum($num=10)
    {
        $page = C('ADMIN_PAGE_COUNT');
        return $page?$page:$num;
    }


    static public function searchFromFormat(array $data){
        foreach ($data as $k => $v) {
            if ($v === '') {
                unset($data[$k]);
            } else {
                $data[$k] = trim($v);
            }
        }

        return $data;
    }
}
