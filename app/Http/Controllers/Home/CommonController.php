<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Tools\BaseTools;
use App\Nav;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;

class CommonController extends Controller
{
    protected $config;

    public function __construct()
    {
        C(BaseTools::get_system_config());
        View::share('head_nav',$this->getNav());
        $this->config = $this->getConfig();
        View::share('CONFIG', $this->config);
        View::share('is_mobile', BaseTools::isMobile());
        View::share('web_title', $this->config['WEB_TITLE']);
        View::share('web_keywords', $this->config['WEB_KEYWORDS']);
        View::share('web_description', $this->config['WEB_DESCRIPTION']);
    }

    /**
     * 获取导航栏目
     */
    protected function getNav()
    {
        $current_url = URL::current();
        $data        = Nav::where('status', 1)->orderBy('sort','desc')->get()->toArray();
        foreach ($data as $k => $v) {
            if($data[$k]['url'] != '/'){
                $data[$k]['class_name'] = (strpos($current_url, $data[$k]['url']) !== false) ? $data[$k]['class_name'] . ' active' : $data[$k]['class_name'];
            }else{
                $data[$k]['class_name'] = ($current_url == url($data[$k]['url']))? $data[$k]['class_name'] . ' active' : $data[$k]['class_name'];
            }
        }
        return $data;
    }


    protected function getConfig()
    {
        $config                           = [];
        $config['WEB_NAME']               = C('WEB_NAME');
        $config['WEB_TITLE']              = C('WEB_TITLE');
        $config['WEB_KEYWORDS']           = C('WEB_KEYWORDS');
        $config['WEB_DESCRIPTION']        = C('WEB_DESCRIPTION');
        $config['BLOG_EMAIL']             = C('BLOG_EMAIL');
        $config['POST_NUM']               = C('POST_NUM');
        $config['WEB_QQ']                 = C('WEB_QQ');
        $config['WEB_NUMBER']             = C('WEB_NUMBER');
        $config['WEB_COMPANY_USER_PHONE'] = C('WEB_COMPANY_USER_PHONE');
        return $config;
    }


}
