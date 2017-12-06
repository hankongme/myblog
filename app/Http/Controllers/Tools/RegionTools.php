<?php
namespace App\Http\Controllers\Tools;

use App\Region;

/**
 * 地区操作类
 * Class RegionTools
 *
 * @package App\Http\Controllers\Tools
 */
class RegionTools
{

    //region 数据分类存储,id,级别,上级
    /**
     *
     */
    const REGION_ID = 1;

    /**
     *
     */
    const REGION_LEVEL = 2;

    /**
     *
     */
    const REGION_PARENT = 3;

    const REGION_NAME = 4;

    //region level 类型
    /**
     *
     */
    const REGION_TYPE_COUNTRY = 0;

    /**
     *
     */
    const REGION_TYPE_PROVINCE = 1;

    /**
     *
     */
    const REGION_TYPE_CITY = 2;

    /**
     *
     */
    const REGION_TYPE_DISTRICT = 3;

    /**
     * 获取地区信息
     *
     * @param int $type
     *
     * @return mixed
     */
    static public function getRegionData($type = 0)
    {
        $data = S('RegionData');
        if (!$data || (!isset($data[$type]) || !$data[$type])) {
            $region = Region::get()->toArray();
            foreach ($region as $k => $v) {
                $data[self::REGION_ID][$v['region_id']]                       = $v;
                $data[self::REGION_LEVEL][$v['region_type']][$v['region_id']] = $v;
                $data[self::REGION_PARENT][$v['parent_id']][$v['region_id']]  = $v;
                $data[self::REGION_NAME][$v['region_name']]                   = $v;
            }
            S('RegionData', $data);
        }
        return $type ? $data[$type] : $data;
    }

    static public function getRegionSort($char = '', $type = self::REGION_TYPE_CITY)
    {
        $data = S('RegionSortData');
        if (!$data || (!isset($data[$char]) || !$data[$char])) {
            $region = Region::get()->toArray();
            foreach ($region as $k => $v) {
                $data[$v['region_type']][$v['first_char']][$v['region_id']] = $v;
            }
            ksort($data[self::REGION_TYPE_CITY]);
            ksort($data[self::REGION_TYPE_PROVINCE]);
            ksort($data[self::REGION_TYPE_DISTRICT]);
            S('RegionSortData', $data);
        }

        return $char ? $data[$type][$char] : $data;
    }


    /**
     * 根据ID获取地区信息
     *
     * @param $id
     *
     * @return array
     */
    static public function getRegionById($id)
    {
        $data = self::getRegionData(self::REGION_ID);
        return isset($data[$id]) ? $data[$id] : [];
    }

    /**
     * 根据ID获取区域名称
     *
     * @param $id
     *
     * @return mixed|string
     */
    static public function getRegionNameById($id)
    {
        $data = self::getRegionById($id);
        return isset($data['region_name']) ? $data['region_name'] : '';
    }


    /**
     * 根据id数组获取地区信息
     *
     * @param array $ids
     *
     * @return array
     */
    static public function getRegionByIds(array $ids)
    {
        $data   = [];
        $region = self::getRegionData(self::REGION_ID);
        foreach ($ids as $k => $id) {
            $data[$k] = isset($region[$id]) ? $region[$id] : [];
        }
        return $data;
    }

    /**
     * 根据级别获取地区
     *
     * @param int $level
     * @param int $is_format
     *
     * @return array|mixed
     */
    static public function getRegionByLevel($level = 1, $is_format = 0)
    {
        $data = self::getRegionData(self::REGION_LEVEL);
        $data = isset($data[$level]) ? $data[$level] : [];
        return $is_format ? self::formatData($data) : $data;
    }


    /**
     * 根据上级ID获取地区信息
     *
     * @param int $parent_id
     * @param int $is_format
     *
     * @return array|mixed
     */
    static public function getRegionByParent($parent_id = 1, $is_format = 0)
    {
        $data = self::getRegionData(self::REGION_PARENT);
        $data = isset($data[$parent_id]) ? $data[$parent_id] : [];
        return $is_format ? self::formatData($data) : $data;
    }


    /**
     * 获取省信息
     *
     * @param int $is_format
     *
     * @return array
     */
    static public function getProvinceData($is_format = 0)
    {
        $data = self::getRegionData(self::REGION_LEVEL);
        return $is_format ? self::formatData($data[self::REGION_TYPE_PROVINCE]) : $data[self::REGION_TYPE_PROVINCE];
    }

    /**
     * ID转化为名称
     *
     * @param     $data
     * @param int $level
     *
     * @return mixed
     */
    static public function regionIdToName($data, $level = 3, $is_foreach = 1)
    {
        $region = self::getRegionData(self::REGION_LEVEL);
        if (1 == $level) {
            $province = $region[self::REGION_TYPE_PROVINCE];

            foreach ($data as $k => $v) {
                $data[$k]['province_name'] = isset($province[$v['province']]) ? $province[$v['province']]['region_name'] : '';
            }
        } elseif (2 == $level) {
            $province = $region[self::REGION_TYPE_PROVINCE];
            $city     = $region[self::REGION_TYPE_CITY];
            foreach ($data as $k => $v) {
                $data[$k]['province_name'] = isset($province[$v['province']]) ? $province[$v['province']]['region_name'] : '';
                $data[$k]['city_name']     = isset($city[$v['city']]) ? $city[$v['city']]['region_name'] : '';
            }
        } else {
            $province = $region[self::REGION_TYPE_PROVINCE];
            $city     = $region[self::REGION_TYPE_CITY];
            $district = $region[self::REGION_TYPE_CITY];
            foreach ($data as $k => $v) {
                $data[$k]['province_name'] = isset($province[$v['province']]) ? $province[$v['province']]['region_name'] : '';
                $data[$k]['city_name']     = isset($city[$v['city']]) ? $city[$v['city']]['region_name'] : '';
                $data[$k]['district_name'] = isset($district[$v['district']]) ? $district[$v['district']]['region_name'] : '';
            }
        }

        return $data;
    }


    /**
     * 通过名称获取ID
     *
     * @param        $province_name
     * @param        $city_name
     * @param string $district_name
     *
     * @return array
     */
    static public function regionNameToId($province_name, $city_name, $district_name = '')
    {
        $data     = self::getRegionData(self::REGION_NAME);
        $province = isset($data[mb_substr($province_name, 0, -1)]) ? $data[mb_substr($province_name, 0, -1)] : [];
        if (!$province) {
            return ['error' => 1];
        }
        $return['province'] = $province;
        $city               = isset($data[mb_substr($city_name, 0, -1)]) ? $data[mb_substr($city_name, 0, -1)] : [];
        if (!$city) {
            return ['error' => 2];
        }
        $return['city'] = $city;
        if ($district_name) {
            $district = isset($data[$district_name]) ? $data[$district_name] : [];
            if (!$district) {
                return ['error' => 3];
            }
            $return['district'] = $district;
        }

        $return['error'] = 0;
        return $return;
    }


    /**
     *
     * 检测省市县关联关系
     *
     * @param     $province
     * @param int $city
     * @param int $district
     *
     * @return bool
     */
    static public function checkRegion($province, $city = 0, $district = 0)
    {

        $city_data = self::getRegionByParent($province);
        if (!($city_data)) {
            return false;
        }
        if ($city && !isset($city_data[$city])) {
            return false;
        }
        if ($district) {
            if ($city) {
                $district_data = self::getRegionById($district);
                if (!$district_data || ($district_data['parent_id'] != $city)) {
                    return false;
                }
            } else {
                $district_data = self::getRegionById($district);
                if (!$district_data) {
                    return false;
                }

                $city_data = self::getRegionById($district_data['parent_id']);
                if (!$city_data || $city_data['parent_id'] != $province) {
                    return false;
                }
            }
        }
        return true;

    }


    /**
     * 格式化信息
     *
     * @param $data
     *
     * @return array
     */
    static public function formatData($data)
    {
        $region = [];
        foreach ($data as $k => $v) {
            $region[$k] = isset($v['region_name']) ? $v['region_name'] : $v;
        }
        return $region;
    }


    static public function getAddressBySession()
    {
        $region_info             = session('region_info');
        $region_info_expire_time = session('region_info_expire_time');
        $user_ip                 = session('user_ip');
        if (!$region_info || ($region_info_expire_time < time()) || $user_ip != BaseTools::get_client_ip()) {
            return false;
        } else {
            return $region_info;
        }
    }

}
