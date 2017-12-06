<?php
namespace App\Http\Controllers\Tools;

use App\Http\Controllers\Codes\CodesStatus;
use App\Systemconfig;
use Germey\Geetest\Geetest;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Vinkla\Hashids\Facades\Hashids;

class BaseTools
{

    public function __construct()
    {
    }

    static public function log($msg)
    {
        $enableLog = true;
        if ($enableLog) {
            $timeStamp = time();
            $timeStr   = date('y-m-d G:i:s', $timeStamp);
            error_log("[$timeStr] $msg\n", '3', '../storage/logs/debug.log');
        }
    }

    static public function jsonMsg($data, $code = CodesStatus::Code_Succeed, $msg = CodesStatus::Msg_Succeed, $url = '')
    {

        $package = [
            'status' => $code,
            'return' => $msg,
            'data'   => $data
        ];

        if ($url) {
            $package['url'] = $url;
        }
        return $package;
    }

    static public function jsonReturn($data, $code = CodesStatus::Code_Succeed, $msg = CodesStatus::Msg_Succeed, $url = '')
    {
        return response()->json(BaseTools::jsonMsg($data, $code, $msg, $url));
    }

    static public function checkEmptyString($str)
    {
        return (!isset($str) || $str == '') ? true : false;
    }


    static public function isValidId($id)
    {
        return strlen($id) > 0?1:0;
    }


    static public function checkPhoneNumber($phone)
    {
        if (preg_match('/^(0|86|17951)?(13[0-9]|15[012356789]|17[013678]|18[0-9]|14[57])[0-9]{8}$/', $phone)) {
            return true;
        } else {
            return false;
        }
    }

    static public function checkEmail($email)
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL) ? true : false;
    }


    static public function getOrderSn($prefix = 'SN', $suffixLength = 5)
    {
        return $prefix . date("YmdGi", time()) . BaseTools::getRandomCode($suffixLength, 'NUMBER');
    }


    /**
     * 生成验证码
     *
     * @param int    $len
     * @param string $format
     *
     * @return string
     */
    static public function getRandomCode($len = 6, $format = 'NUMBER')
    {
        switch ($format) {
            case 'ALL':
                $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
                break;
            case 'CHAR':
                $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
                break;
            case 'NUMBER':
                $chars = '0123456789';
                break;
            default :
                $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
                break;
        }
        mt_srand((double)microtime() * 1000000 * getmypid());
        $code = "";
        while (strlen($code) < $len)
            $code .= substr($chars, (mt_rand() % strlen($chars)), 1);
        return $code;
    }

    static public function getTime($addSeconds = 0)
    {
        $seconds = time() + $addSeconds;
        return date('Y-m-d H:i:s', $seconds);
    }


    static public function getTimeFromNow($timeStamp)
    {
        $timeDiff = time() - $timeStamp;
        if ($timeDiff < 60) {
            return '刚刚';
        } else {
            if ($timeDiff < 3600) {
                return floor($timeDiff / 60) . '分钟前';
            } else {
                if ($timeDiff < 3600 * 24) {
                    return floor($timeDiff / 3600) . '小时前';
                } else {
                    if ($timeDiff < 3600 * 24 * 30) {
                        return floor($timeDiff / 3600 / 24) . '天前';
                    } else {
                        return '1个月前';
                    }
                }
            }
        }
    }


    static public function getDistanceNowime($timeStamp)
    {
        $timeDiff = time() - $timeStamp;
        if ($timeDiff < 60) {
            return '刚刚';
        } else {
            if ($timeDiff < 3600) {
                return floor($timeDiff / 60) . '分钟前';
            } else {
                if ($timeDiff < 3600 * 24) {
                    return floor($timeDiff / 3600) . '小时前';
                } else {
                    if ($timeDiff < 3600 * 24 * 7) {
                        return floor($timeDiff / 3600 / 24) . '天前';
                    } else {
                        return date('Y-m-d', $timeStamp);
                    }
                }
            }
        }
    }


    static public function formatMoney($money, $decimals = 1)
    {
        if ($money < 100) {
            $temp      = $money / 100;
            $result[0] = $temp;
            $result[1] = '元';
        } else {
            if ($money < 10000 * 100) {
                $temp      = $money / 100;
                $result[0] = number_format($temp, $decimals);
                $result[1] = '元';
            } else {
                if ($money < 10000 * 10000 * 100) {
                    $temp      = $money / 100 / 10000;
                    $result[0] = number_format($temp, $decimals);
                    $result[1] = '万';
                } else {
                    if ($money < 10000 * 10000 * 10000 * 100) {
                        $temp      = $money / 100 / 10000 / 10000;
                        $result[0] = number_format($temp, $decimals);
                        $result[1] = '亿';
                    } else {
                        $temp      = $money / 100;
                        $result[0] = number_format($temp, $decimals);
                        $result[1] = '元';
                    }
                }
            }
        }
        return $result;
    }


    /**
     * 模拟get请求
     *
     * @param $url
     *
     * @return mixed
     */
    static function httpGet($url)
    {
        $ch          = curl_init();
        $this_header = [
            "content-type: application/x-www-form-urlencoded;charset=UTF-8"
        ];

        curl_setopt($ch, CURLOPT_HTTPHEADER, $this_header);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);//禁止直接显示获取的内容 重要
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        $rss_content = curl_exec($ch);//赋值内容'
        $error       = curl_errno($ch);
        return $rss_content;
        if (!$error) {
            curl_close($ch);
            return $rss_content;
//            $result = $rss_content;
//            $result = json_decode ($rss_content , true);
//            return $result?$result:$rss_content;
        } else {
            curl_close($ch);
            return self::ajaxError('curl_error-' . $error);
        }
    }

    /**
     * curl post 请求
     *
     * @param $url
     * @param $data
     *
     * @return mixed
     */
    static function httpPost($url, $data)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        $output = curl_exec($ch);

        if ($output) {
            curl_close($ch);
            $result = json_decode($output, true);
            return $result;
        } else {
            $error = curl_errno($ch);
            curl_close($ch);
            return self::ajaxError($error);
        }


    }

    /**
     * http请求
     *
     * @param        $URL
     * @param string $type
     * @param array  $params
     * @param string $headers
     *
     * @return mixed
     */
    static function httpRequest($URL, $type = 'GET', $params = [], $headers = '')
    {

        $ch      = curl_init($URL);
        $timeout = 5;
        if ($headers != "") {
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        } else {
            curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-type: application/json']);
        }
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        $params = http_build_query($params);
        switch ($type) {
            case "GET" :
                curl_setopt($ch, CURLOPT_HTTPGET, true);
                break;
            case "POST":
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
                break;
            case "PUT" :
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
                curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
                break;
            case "PATCH":
                curl_setopt($ch, CULROPT_CUSTOMREQUEST, 'PATCH');
                curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
                break;
            case "DELETE":
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
                curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
                break;
        }

        $file_contents = curl_exec($ch);//获得返回值

        if ($file_contents) {
            curl_close($ch);
            return $file_contents;
        } else {
            $error = curl_errno($ch);
            curl_close($ch);
            return self::ajaxError($error);
        }


    }


    /**
     * 获取文件大小格式化
     *
     * @param $size
     *
     * @return string
     */
    static function file_size_format($size)
    {
        $units = [' B', ' KB', ' MB', ' GB', ' TB'];
        for ($i = 0; $size >= 1024 && $i < 4; $i++)
            $size /= 1024;
        return round($size, 2) . $units[$i];
    }


    /**
     * 极验验证方法
     *
     * @param $data
     *
     * @return bool
     */
    static function geetestValidate($data)
    {
        $user_id           = session('user_id');
        $gtserver          = session('gtserver');
        $geetest_challenge = $data['geetest_challenge'];
        $geetest_validate  = $data['geetest_validate'];
        $geetest_seccode   = $data['geetest_seccode'];

        if (!Geetest::successValidate($geetest_challenge, $geetest_validate, $geetest_seccode, ['user_id' => $user_id])) {

            return false;

        } else {

            return true;
        }

    }


    /**
     * 获取系统配置(当前使用)
     */
    static function get_system_config()
    {

        $config = S('CONFIGLIST');
        if (!$config) {
            $data = Systemconfig::where('status', '=', 1)->get();
            if ($data) {
                $data = $data->toArray();
                foreach ($data as $k => $v) {
                    $config[$v['name']] = self::parse($v['type'], $v['value']);
                }
            }
            S('CONFIGLIST', $config);
        }
        return $config;
    }

    /**
     *  分析枚举类型配置值 格式 a:名称1,b:名称2
     *
     * @param $string
     *
     * @return array
     */

    static function parse_config_attr($string)
    {
        $array = preg_split('/[,;\r\n]+/', trim($string, ",;\r\n"));
        if (strpos($string, ':')) {
            $value = [];
            foreach ($array as $val) {
                list ($k, $v) = explode(':', $val);
                $value[$k] = $v;
            }
        } else {
            $value = $array;
        }
        return $value;
    }


    /**
     * 根据配置类型解析配置
     *
     * @param  integer $type  配置类型
     * @param  string  $value 配置值
     */
    static function parse($type, $value)
    {
        switch ($type) {
            case 3: //解析数组
                $array = preg_split('/[,;\r\n]+/', trim($value, ",;\r\n"));
                if (strpos($value, ':')) {
                    $value = [];
                    foreach ($array as $val) {
                        list($k, $v) = explode(':', $val);
                        $value[$k] = $v;
                    }
                } else {
                    $value = $array;
                }
                break;
        }
        return $value;
    }


    /**
     * 数据列表加上标识
     *
     * @param     $list
     * @param int $page
     * @param int $page_count
     *
     * @return mixed
     */
    static function get_key_list($list, $page = 1, $page_count = 10)
    {
        if (intval($page) == 0) {
            $page = 1;
        }

        if (is_array($list) && !empty($list)) {
            $i = $page_count * ($page - 1) + 1;
            foreach ($list as $k => $v) {
                $list[$k]['key_num'] = $i;
                $i++;
            }
        }
        return $list;
    }


    /**
     * select返回的数组进行整数映射转换
     *
     * @param array $map
     *            映射关系二维数组 array(
     *            '字段名1'=>array(映射关系数组),
     *            '字段名2'=>array(映射关系数组),
     *            ......
     *            )
     *
     * @return array array(
     *         array('id'=>1,'title'=>'标题','status'=>'1','status_text'=>'正常')
     *         ....
     *         )
     *
     */
    static function int_to_string(&$data, $map = [
        'status' => [
            1  => '正常',
            -1 => '删除',
            0  => '禁用',
            2  => '未审核',
            3  => '草稿'
        ]
    ], $check_null = [])
    {
        if ($data === false || $data === NULL) {
            return $data;
        }

        $data = (array)$data;
        foreach ($data as $key => $row) {
            foreach ($map as $col => $pair) {

                if (isset($row[$col]) && isset($pair[$row[$col]])) {
                    $data[$key][$col . '_text'] = $pair[$row[$col]];
                }

            }


        }
        return $data;
    }


    /**
     * ajax错误信息返回
     * @param        $msg
     * @param string $url
     *
     * @return mixed
     */
    static function ajaxError($msg, $url = '')
    {
        return BaseTools::jsonReturn($msg, CodesStatus::Code_Failed, $msg, $url);
    }

    /**
     * ajax成功信息返回
     * @param        $msg
     * @param string $url
     *
     * @return mixed
     */
    static function ajaxSuccess($msg, $url = '')
    {
        return BaseTools::jsonReturn($msg, CodesStatus::Code_Succeed, $msg, $url);
    }


    /**
     * 获取上传文件名称(去除字符串和时间戳)
     *
     * @param $filename
     *
     * @return mixed
     */
    static function getFilename($filename)
    {
        $data['time_name'] = substr($filename, 0, strrpos($filename, '_'));
        $data['true_name'] = substr($data['time_name'], 0, strrpos($data['time_name'], '_'));

        return $data;
    }


    /**
     * 字符过滤
     *
     * @param unknown $str
     */
    static function compile_str($str)
    {
        $arr = [
            '<' => '＜',
            '>' => '＞',
            '"' => '”',
            "'" => '’'
        ];

        return strtr($str, $arr);
    }

    /**
     * Get client ip.
     *
     * @return string
     */
    static function get_client_ip()
    {
        if (getenv("HTTP_CLIENT_IP")) {
            $ip = getenv("HTTP_CLIENT_IP");
        } else {
            if (getenv("HTTP_X_FORWARDED_FOR")) {
                $ip = getenv("HTTP_X_FORWARDED_FOR");
            } else {
                if (getenv("REMOTE_ADDR")) {
                    $ip = getenv("REMOTE_ADDR");
                } else $ip = "127.0.0.1";
            }
        }
        if ($ip == '::1') {
            $ip = "127.0.0.1";
        }

        return $ip;
    }

    /**
     * Get current server ip.
     *
     * @return string
     */
    static function get_server_ip()
    {
        // for php-cli(phpunit etc.)
        if (empty($_SERVER['SERVER_ADDR'])) {
            return gethostbyname(gethostname());
        }

        return $_SERVER['SERVER_ADDR'];
    }


    /**
     * 更新配置(仅限systemconfig修改)
     */
    static function update_config()
    {
        //清空原有配置
        S('CONFIGLIST', NULL);
        return true;
    }


    /**
     * 判断是否是http
     *
     * @param $url
     *
     * @return bool
     */
    static function is_http($url)
    {
        return preg_match('/(http:\/\/)|(https:\/\/)/i', $url) ? true : false;
    }

    /**
     * url转化为http
     *
     * @param string $url
     *            链接地址
     * @param number $server
     *            是否加上域名
     */
    static function url_to_http($url = '', $server = 1)
    {
        if (!BaseTools::is_http($url)) {
            $host = $server ? $_SERVER['HTTP_HOST'] : '';
            $url  = 'http://' . $host . $url;
        }

        return $url;
    }


    static function url_to_https($url = '', $server = 1)
    {
        if (!BaseTools::is_http($url)) {
            $host = $server ? $_SERVER['HTTP_HOST'] : '';
            $url  = 'https://' . $host . $url;
        }

        return $url;
    }


    /**
     * 存入时钱转化为分
     *
     * @param $money
     *
     * @return mixed
     */
    static function yuanToFen($money)
    {
        return intval($money * 100);
    }

    /**
     * 取出时分转化为元
     *
     * @param $money
     *
     * @return float
     */
    static function fenToYuan($money)
    {
        return floatval($money / 100);
    }

    static function phoneFormat($phone)
    {
        return $phone ? substr_replace($phone, '****', 4, 4) : '';
    }

    static function strFormat($str)
    {
        return $str ? substr_replace($str, '****', 4, 4) : '';
    }


    /**
     * 字符串替换
     *
     * @param 需要操作的字符串   $str
     * @param 字符串需要替换的部分 $find
     * @param 替换成        $replace_to
     */
    static function replace_str($str, $find, $replace_to = '')
    {
        if (is_array($find)) {
            foreach ($find as $k => $v) {
                $str = str_replace($k, $v, $str);
            }
        } else {
            $str = str_replace($find, $replace_to, $str);
        }

        return $str;
    }


    /**
     * 创建文件夹
     *
     * @param $path
     *
     * @return bool
     */
    static function mk_path($path)
    {
        if (is_dir($path)) {
            return true;
        } else {
            $res = mkdir(iconv("UTF-8", "GBK", $path), 0777, true);
            return $res;
        }
    }


    /**
     *创建zip打包
     */
    static function addFileToZip($path, $zip)
    {
        $handler = opendir($path); //打开当前文件夹由$path指定。
        while (($filename = readdir($handler)) !== false) {
            if ($filename != "." && $filename != "..") {//文件夹文件名字为'.'和‘..’，不要对他们进行操作
                if (is_dir($path . "/" . $filename)) {// 如果读取的某个对象是文件夹，则递归
                    return self::addFileToZip($path . "/" . $filename, $zip);
                } else { //将文件加入zip对象
                    $zip->addFile($path . "/" . $filename);
                }
            }
        }
        @closedir($path);
    }


    //循环删除目录和文件函数
    static function del_file_dir($dirName)
    {
        if (is_dir($dirName)) {
            if ($handle = opendir("$dirName")) {
                while (false !== ($item = readdir($handle))) {
                    if ($item != "." && $item != "..") {
                        if (is_dir("$dirName/$item")) {
                            return self::del_file_dir("$dirName/$item");
                        } else {
                            if (!unlink("$dirName/$item")) {
                                echo '删除文件' . "$dirName/$item" . '失败!';
                                return false;
                            }

                        }
                    }
                }
                closedir($handle);
                if (!rmdir($dirName)) {
                    echo '删除文件夹' . "$dirName" . '失败!';
                    return false;
                }

            }


        }

    }


    static function isImage($filename)
    {
        $types = '.gif|.jpeg|.png|.jpg|.JPG|.PNG'; //定义检查的图片类型
        $ext   = strrchr($filename, '.');
        return stripos($types, $ext);
    }


    /**
     * 获取分类树
     *
     * @param $arr
     * @param $pid
     * @param $step
     *
     * @return mixed
     */
    static function get_tree($arr, $pid, $step)
    {
        if (is_array($arr) && $arr) {
            global $tree;
            foreach ($arr as $key => $val) {
                if ($val['parent_id'] == $pid) {
                    $flg              = str_repeat('&nbsp;&nbsp;&nbsp;&nbsp;—', $step);
                    $val['name']      = $flg . $val['name'];
                    $tree[$val['id']] = $val['name'];
                    self::get_tree($arr, $val['id'], $step + 1);
                }
            }
            return $tree;
        }

        return [];

    }

    /**
     * 判断是否是手机
     *
     * @return bool
     */
    static function isMobile()
    {
        // 如果有HTTP_X_WAP_PROFILE则一定是移动设备
        if (isset ($_SERVER['HTTP_X_WAP_PROFILE'])) {
            return true;
        }
        // 如果via信息含有wap则一定是移动设备,部分服务商会屏蔽该信息
        if (isset ($_SERVER['HTTP_VIA'])) {
            // 找不到为flase,否则为true
            return stristr($_SERVER['HTTP_VIA'], "wap") ? true : false;
        }
        // 脑残法，判断手机发送的客户端标志,兼容性有待提高
        if (isset ($_SERVER['HTTP_USER_AGENT'])) {
            $clientkeywords = [
                'nokia',
                'sony',
                'ericsson',
                'mot',
                'samsung',
                'htc',
                'sgh',
                'lg',
                'sharp',
                'sie-',
                'philips',
                'panasonic',
                'alcatel',
                'lenovo',
                'iphone',
                'ipod',
                'blackberry',
                'meizu',
                'android',
                'netfront',
                'symbian',
                'ucweb',
                'windowsce',
                'palm',
                'operamini',
                'operamobi',
                'openwave',
                'nexusone',
                'cldc',
                'midp',
                'wap',
                'mobile'
            ];
            // 从HTTP_USER_AGENT中查找手机浏览器的关键字
            if (preg_match("/(" . implode('|', $clientkeywords) . ")/i", strtolower($_SERVER['HTTP_USER_AGENT']))) {
                return true;
            }
        }
        // 协议法，因为有可能不准确，放到最后判断
        if (isset ($_SERVER['HTTP_ACCEPT'])) {
            // 如果只支持wml并且不支持html那一定是移动设备
            // 如果支持wml和html但是wml在html之前则是移动设备
            if ((strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') !== false) && (strpos($_SERVER['HTTP_ACCEPT'], 'text/html') === false || (strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') < strpos($_SERVER['HTTP_ACCEPT'], 'text/html')))) {
                return true;
            }
        }
        return false;
    }


    /**
     * 生成验证码
     * @param $phone
     *
     * @return mixed
     */
    static public function makeCaptcha($phone)
    {
        $data['code']  = rand(100000, 999999);
        $data['phone'] = $phone;
        $data['time']  = time() + 5 * 60;
        session(['phoneCaptcha' => $data]);
        session(['phoneCaptcha_error_count' => 0]);
        return $data['code'];
    }

    /**
     * 检测短信验证码
     * @param $phone
     * @param $code
     *
     * @return bool
     */
    static public function checkCaptcha($phone, $code)
    {
        $data = session('phoneCaptcha');
        if (!$data) {
            return false;
        }
        if ($data['time'] < time() || $data['phone'] != $phone || $data['code'] != $code) {
            return false;
        }
        session(['phoneCaptcha' => NULL]);
        return true;
    }


    /**
     *
     * 检测银行卡格式
     * @param $bank
     *
     * @return bool
     */
    static public function checkBankCard($bank)
    {
        $chars = '/^(\d{16}|\d{19}|\d{17})$/';
        if (preg_match($chars, $bank)) {
            return true;
        } else {
            return false;
        }
    }


    static public function success($data, $url = '', $view = 'home.common.success')
    {
        return view($view)->with([
                                     'data' => $data,
                                     'url'  => $url
                                 ]);
        exit();
    }

    /**
     * 计算数组的笛卡尔积
     */
    static public function getCartesianProduct()
    {
        $result = [];
        $array  = func_get_args();
        //取出当前值
        $array = current($array);
        //取出第一个元素并删除
        $current_arr = array_shift($array);
        foreach ($current_arr as $k => $v) {
            $result[] = [$v];
        }

        //计算多个数组的笛卡尔积
        foreach ($array as $k => $v) {
            $result = BaseTools::CartesianProductTwoArray($result,$v);
        }
        return $result;
    }


    /**
     * 获取两个数组的笛卡尔积
     * @param $arr1
     * @param $arr2
     *
     * @return array
     */
    static public function CartesianProductTwoArray($arr1,$arr2)
    {
        $result = [];
        foreach ($arr1 as $k=>$v){
            foreach ($arr2 as $kk=>$vv){
                $temp = $v;
                $temp[] = $vv;
                $result[] = $temp;
            }
        }

        return $result;
    }

    /**
     *
     * 以数组的值数量排序(二维数组)
     *
     * @param $array
     *
     * @return array
     */
    static public function getSortByValCount($array)
    {
        $data = [];
        foreach ($array as $k => $v) {
            $arr_sort[$k] = count($v);
        }

        asort($arr_sort);
        foreach ($arr_sort as $k => $v) {
            $data[$k] = $array[$k];
        }

        return $data;
    }

    static function error($data, $url = '', $view = 'admin.common.error')
    {

        return view($view)->with([
                                     'data' => $data,
                                     'url'  => $url
                                 ]);
        exit();
    }

    static function arrayFormat($data,$id_name='id',$val_name=''){
            $result = [];
            if(empty($data)){
                return [];
            }
            if($val_name){
                foreach ($data as $k=>$v){
                    $result[$v[$id_name]] =$v[$val_name];
                }
            }else{
                foreach ($data as $k=>$v){
                    $result[$v[$id_name]] =$v;
                }
            }

            return $result;
    }


}
