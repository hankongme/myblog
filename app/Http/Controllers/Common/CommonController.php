<?php

namespace App\Http\Controllers\Common;

use App\Agency;
use App\Category;
use App\Http\Controllers\Tools\BaseTools;
use App\Http\Controllers\Tools\RegionTools;
use App\Industry;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class CommonController extends Controller
{
    public function getRegionByParent($id)
    {
        return RegionTools::getRegionByParent(intval($id));
    }

    public function getCategoryByParent($id)
    {
        $data     = [];
        $category = Category::where('parent_id', intval($id))->get()->toArray();
        if ($category) {
            foreach ($category as $k => $v) {
                $data[$v['id']] = $v;
            }
        }
        return $data;
    }

    public function getAddressByPoint(Request $request, $longitude, $latitude)
    {
        $region_info   = RegionTools::getAddressBySession();
        $longitude_old = session('longitude');
        if (!$region_info || $request->get('refresh') || $longitude_old != $longitude) {
            $url  = "https://apis.map.qq.com/jsapi?qt=rgeoc&lnglat={$longitude},{$latitude}";
            $data = BaseTools::httpGet($url);
            $data = mb_detect_encoding($data, ['UTF-8', 'GBK']) == 'UTF-8' ? $data : iconv('gbk', 'UTF-8', $data);
            $data = \GuzzleHttp\json_decode($data, true);

            if (isset($data['detail']) && !empty($data['detail'])) {
                if (isset($data['info']) && $data['info']['error']) {
                    return BaseTools::ajaxError('获取位置失败,请手动选择!');
                }
                if (isset($data['detail']['results']) && $data['detail']['results']) {
                    $region      = $data['detail']['results'][0];
                    $province    = isset($region['p']) ? $region['p'] : $data['detail']['poilist'][0]['addr_info']['p'];
                    $city        = isset($region['c']) ? $region['c'] : $data['detail']['poilist'][0]['addr_info']['c'];
                    $district    = isset($region['d']) ? $region['d'] : $data['detail']['poilist'][0]['addr_info']['d'];
                    $region_info = RegionTools::regionNameToId($province, $city, $district);
                    if (!$region_info['error']) {
                        //存储位置信息
                        $region_info['longitude'] = $longitude;
                        $region_info['latitude']  = $latitude;
                        session(['region_info' => $region_info]);
                        session(['region_info_expire_time' => time() + 60 * 5]);
                        session(['user_ip' => BaseTools::get_client_ip()]);
                        return BaseTools::ajaxSuccess($region_info);
                    }
                }
            }

            return BaseTools::ajaxError('获取位置失败,请手动选择!');
        } else {
            return BaseTools::ajaxSuccess($region_info);
        }
    }


}
